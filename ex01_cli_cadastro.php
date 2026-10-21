<?php

declare(strict_types=1);

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/dao/UsuarioDAO.php';

use Dao\UsuarioDAO;

function validarSenha(string $senha): bool
{
    if (strlen($senha) < 8) {
        return false;
    }

    $temMaiuscula = preg_match('/[A-Z]/', $senha) === 1;
    $temMinuscula = preg_match('/[a-z]/', $senha) === 1;
    $temNumero = preg_match('/[0-9]/', $senha) === 1;
    $temEspecial = preg_match('/[^A-Za-z0-9]/', $senha) === 1;

    return $temMaiuscula && $temMinuscula && $temNumero && $temEspecial;
}

function lerDados(): array
{
    $nome = readline('Nome: ');
    $email = readline('E-mail: ');
    $senha = readline('Senha: ');

    return [$nome, $email, $senha];
}

function cadastrarUsuario(): void
{
    [$nome, $email, $senha] = lerDados();

    if (!validarSenha($senha)) {
        echo "Senha inválida. Use 8 caracteres, maiúscula, minúscula, número e caractere especial.\n";
        return;
    }

    $dao = new UsuarioDAO();
    $hash = password_hash($senha, PASSWORD_ARGON2ID);

    if ($dao->cadastrar($nome, $email, $hash)) {
        echo "Colaborador cadastrado com sucesso.\n";
        return;
    }

    echo "Erro ao cadastrar colaborador.\n";
}

cadastrarUsuario();