<?php

declare(strict_types=1);

require_once __DIR__ . '/guard.php';
require_once __DIR__ . '/ex03_rbac_guard.php';

exigirLogin();
verificarPermissao('ADMIN');

echo '<h1>Painel administrativo</h1>';
echo '<p>Bem-vindo, ' . htmlspecialchars($_SESSION['nome']) . '.</p>';
echo '<a href="logout.php">Sair</a>';