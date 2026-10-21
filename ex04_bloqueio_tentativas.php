<?php

declare(strict_types=1);

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
]);

function estaBloqueado(): bool
{
    $bloqueioAte = $_SESSION['bloqueio_ate'] ?? 0;

    return time() < $bloqueioAte;
}

function exibirBloqueio(): void
{
    $segundosRestantes = ($_SESSION['bloqueio_ate'] ?? 0) - time();

    echo "<h1>Acesso temporariamente bloqueado</h1>";
    echo "<p>Tente novamente em {$segundosRestantes} segundos.</p>";
}

function registrarFalha(): void
{
    $_SESSION['tentativas_login'] = ($_SESSION['tentativas_login'] ?? 0) + 1;

    if ($_SESSION['tentativas_login'] >= 3) {
        $_SESSION['bloqueio_ate'] = time() + 60;
        $_SESSION['tentativas_login'] = 0;
    }
}

function validarLogin(string $email, string $senha): bool
{
    if ($email === 'admin@sistema.com' && $senha === 'Senha@123') {
        $_SESSION['tentativas_login'] = 0;
        unset($_SESSION['bloqueio_ate']);

        session_regenerate_id(true);

        return true;
    }

    registrarFalha();

    return false;
}

if (estaBloqueado()) {
    exibirBloqueio();
    exit;
}

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validarLogin($email, $senha)) {
    echo '<h1>Login realizado com sucesso</h1>';
    exit;
}

echo '<h1>Login</h1>';
echo '<form method="post">
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit">Entrar</button>
</form>';