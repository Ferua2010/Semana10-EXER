<?php

declare(strict_types=1);

namespace Config;

use PDO;

class Database
{
    public static function conectar(): PDO
    {
        $host = 'localhost';
        $dbname = 'sistema';
        $user = 'postgres';
        $password = 'sua_senha';

        return new PDO(
            "pgsql:host={$host};dbname={$dbname}",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
}