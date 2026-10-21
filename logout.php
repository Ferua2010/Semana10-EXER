<?php

declare(strict_types=1);

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax'
]);

session_unset();

setcookie(session_name(), '', time() - 3600, '/');

session_destroy();

header('Location: login.php');
exit;