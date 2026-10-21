<?php

declare(strict_types=1);

namespace Dao;

use Config\Database;
use PDO;

class UsuarioDAO
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = Database::conectar();
    }

    public function cadastrar(string $nome, string $email, string $senhaHash): bool
    {
        $sql = 'INSERT INTO usuarios (nome, email, senha, perfil) VALUES (:nome, :email, :senha, :perfil)';

        $consulta = $this->conexao->prepare($sql);
        $consulta->bindValue(':nome', $nome);
        $consulta->bindValue(':email', $email);
        $consulta->bindValue(':senha', $senhaHash);
        $consulta->bindValue(':perfil', 'OPERADOR');

        return $consulta->execute();
    }

    public function buscarPorEmail(string $email): ?array
    {
        $sql = 'SELECT id, nome, email, senha, perfil FROM usuarios WHERE email = :email';

        $consulta = $this->conexao->prepare($sql);
        $consulta->bindValue(':email', $email);
        $consulta->execute();

        $usuario = $consulta->fetch();

        return $usuario === false ? null : $usuario;
    }
}