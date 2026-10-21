<?php

declare(strict_types=1);

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
]);

function sessaoExpirada(): bool
{
    $ultimoAcesso = $_SESSION['ultimo_acesso'] ?? 0;

    return time() - $ultimoAcesso > 900;
}

function exigirLogin(): void
{
    if (!isset($_SESSION['usuario_id']) || sessaoExpirada()) {
        session_unset();
        session_destroy();

        header('Location: login.php');
        exit;
    }

    $_SESSION['ultimo_acesso'] = time();
}