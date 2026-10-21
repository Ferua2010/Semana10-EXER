<?php

declare(strict_types=1);

require_once __DIR__ . '/ex03_rbac_guard.php';

verificarPermissao('ADMIN');

echo '<h1>Relatórios gerenciais</h1>';
echo '<p>Página restrita para administradores.</p>';