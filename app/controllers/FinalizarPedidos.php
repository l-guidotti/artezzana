<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../models/Usuario.php';


$pdo = conectar();
$produtoModel = new Produto($pdo);

$carrinho = $_SESSION['carrinho'] ?? [];

foreach ($carrinho as $produto_id => $item) {
    $quantidade_comprada = (int)$item['quantidade'];

    $produto = $produtoModel->buscarPorId($produto_id);

    if (!$produto) {
        error_log("Produto ID $produto_id não encontrado.");
        continue;
    }

    $estoque_atual = (int)$produto['quantidade_estoque'];
    $novo_estoque = max(0, $estoque_atual - $quantidade_comprada);

    $stmt = $pdo->prepare("UPDATE produtos SET quantidade_estoque = :novo_estoque WHERE id = :id");
    $stmt->execute([
        ':novo_estoque' => $novo_estoque,
        ':id' => $produto_id
    ]);

    error_log("Estoque atualizado: Produto $produto_id, de $estoque_atual para $novo_estoque");
}

unset($_SESSION['carrinho']);
session_write_close();

header("Location: /artezzana/app/views/usuario/carrinho.php");
exit;
