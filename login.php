<?php

declare(strict_types=1);

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
]);

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/dao/UsuarioDAO.php';

use Dao\UsuarioDAO;

function autenticar(string $email, string $senha): bool
{
    $dao = new UsuarioDAO();
    $usuario = $dao->buscarPorEmail($email);

    if ($usuario === null || !password_verify($senha, $usuario['senha'])) {
        return false;
    }

    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['perfil'] = $usuario['perfil'];
    $_SESSION['ultimo_acesso'] = time();

    session_regenerate_id(true);

    return true;
}

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && autenticar($email, $senha)) {
    header('Location: painel_admin.php');
    exit;
}

echo '<h1>Login</h1>
<form method="post">
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit">Entrar</button>
</form>';