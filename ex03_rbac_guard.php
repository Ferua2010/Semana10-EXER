<?php

declare(strict_types=1);

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
]);

function verificarPermissao(string $perfilMinimo): void
{
    $perfis = [
        'OPERADOR' => 1,
        'ADMIN' => 2
    ];

    $perfilUsuario = $_SESSION['perfil'] ?? '';

    if (!isset($perfis[$perfilUsuario]) || $perfis[$perfilUsuario] < $perfis[$perfilMinimo]) {
        http_response_code(403);

        echo '<!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <title>Acesso negado</title>
        </head>
        <body>
            <h1>Permissão negada</h1>
            <p>Você não possui autorização para acessar esta página.</p>
        </body>
        </html>';

        exit;
    }
}