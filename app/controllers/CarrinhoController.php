<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'remover' && isset($_POST['produto_id'])) {
            $produtoId = $_POST['produto_id'];
            unset($_SESSION['carrinho'][$produtoId]);
        }

        if ($_POST['action'] === 'limpar') {
            unset($_SESSION['carrinho']);
        }
    }
}

header("Location: /artezzana/app/views/usuario/carrinho.php");
exit;
