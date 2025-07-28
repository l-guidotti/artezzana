<?php
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../config/database.php';

$auth = new AuthController($pdo);

if ($url === '/login' && $method === 'POST') {
    $auth->login($_POST['email'], $_POST['senha']);
}
elseif ($url === '/logout') {
    $auth->logout();
}
?>