<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../../config/database.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pdo = conectar(); 
    $controller = new AuthController($pdo);
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $controller->login($email, $senha);
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action']) && $_GET['action'] === 'logout') {
    $pdo = conectar(); 
    $controller = new AuthController($pdo);
    $controller->logout();
}

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        session_start();
    }

    public function login($email, $senha) {
        if (empty($email) || empty($senha)) {
            $_SESSION["msg"] = "Por favor, preencha todos os campos.";
            header("Location: ../views/auth/login.php");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["msg"] = "Formato de e-mail inválido.";
            header("Location: ../views/auth/login.php");
            exit();
        }

        try {
            $usuarioModel = new Usuario($this->pdo);
            $usuario = $usuarioModel->buscarPorEmail($email);

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION["logado"] = true;
                $_SESSION["usuario_id"] = $usuario['id'];
                $_SESSION["usuario_email"] = $usuario['email'];
                $_SESSION["usuario_nome"] = $usuario['nome'];
                $_SESSION["tipo_usuario"] = $usuario['tipo_usuario'];

                if ($_SESSION["tipo_usuario"] == "comprador") {
                    header("Location: ../views/usuario/dashboard_comprador.php");
                } else {
                    header("Location: ../views/usuario/dashboard_produtor.php");
                }
                exit();
            } else {
                $_SESSION["msg"] = "E-mail ou senha inválidos.";
                $_SESSION['login_form_data'] = ['email' => $email];
                header("Location: ../views/auth/login.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION["msg"] = "Erro ao tentar fazer login.";
            header("Location: ../views/auth/login.php");
            exit();
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: ../views/auth/login.php");
        exit();
    }
}