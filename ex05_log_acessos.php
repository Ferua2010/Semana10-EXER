<?php

declare(strict_types=1);

require_once __DIR__ . '/config/Database.php';

use Config\Database;
use PDO;

function criarTabelaAuditoria(PDO $conexao): void
{
    $sql = 'CREATE TABLE IF NOT EXISTS auditoria_logins (
        id SERIAL PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        ip VARCHAR(45) NOT NULL,
        user_agent TEXT NOT NULL,
        sucesso BOOLEAN NOT NULL,
        data_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )';

    $conexao->exec($sql);
}

function registrarTentativa(PDO $conexao, string $email, bool $sucesso): void
{
    $sql = 'INSERT INTO auditoria_logins (email, ip, user_agent, sucesso)
            VALUES (:email, :ip, :user_agent, :sucesso)';

    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(':email', $email);
    $consulta->bindValue(':ip', $_SERVER['REMOTE_ADDR'] ?? 'desconhecido');
    $consulta->bindValue(':user_agent', $_SERVER['HTTP_USER_AGENT'] ?? 'desconhecido');
    $consulta->bindValue(':sucesso', $sucesso, PDO::PARAM_BOOL);

    $consulta->execute();
}

function listarTentativas(PDO $conexao): array
{
    $sql = 'SELECT email, ip, user_agent, sucesso, data_hora
            FROM auditoria_logins
            ORDER BY data_hora DESC
            LIMIT 20';

    $consulta = $conexao->query($sql);

    return $consulta->fetchAll();
}

$conexao = Database::conectar();
criarTabelaAuditoria($conexao);

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$sucesso = $email === 'admin@sistema.com' && $senha === 'Senha@123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    registrarTentativa($conexao, $email, $sucesso);
}

echo '<h1>Painel do administrador</h1>';
echo '<table border="1">
    <tr>
        <th>E-mail</th>
        <th>IP</th>
        <th>Navegador</th>
        <th>Sucesso</th>
        <th>Data e hora</th>
    </tr>';

foreach (listarTentativas($conexao) as $registro) {
    $resultado = $registro['sucesso'] ? 'Sim' : 'Não';

    echo "<tr>
        <td>{$registro['email']}</td>
        <td>{$registro['ip']}</td>
        <td>" . htmlspecialchars($registro['user_agent']) . "</td>
        <td>{$resultado}</td>
        <td>{$registro['data_hora']}</td>
    </tr>";
}

echo '</table>';