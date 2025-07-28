<?php
require_once __DIR__ . '/../../../app/helpers/auth.php';

verificarLogin();
protegerRotaProdutor();

$dadosUsuario = pegarDadosUsuario();
$usuario_nome = $dadosUsuario['nome'];
$tipo_usuario_logado = $dadosUsuario['tipo'];
$iniciais_usuario = $dadosUsuario['iniciais'];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos - Artezzana</title>
    <link rel="stylesheet" href="../../../public/css/global.css">
    <link rel="stylesheet" href="../../../public/css/dashboard-styles.css">
    <style>
        /* Seus estilos CSS para a tabela de produtos */
        .products-management { max-width: 1000px; margin: 2rem auto; padding: 2rem; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        .products-management h2 { font-size: 2rem; color: #1f2937; margin-bottom: 1.5rem; text-align: center; }
        .products-management .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .products-management .section-header h3 { font-size: 1.5rem; margin: 0; }
        .products-management .btn-add-product { background-color: #22c55e; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: background-color 0.3s; }
        .products-management .btn-add-product:hover { background-color: #16a34a; }
        .product-table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        .product-table th, .product-table td { text-align: left; padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
        .product-table th { background-color: #f9fafb; color: #374151; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; }
        .product-table tbody tr:hover { background-color: #f3f4f6; }
        .product-table td img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
        .product-table .actions a { color: #007bff; text-decoration: none; margin-right: 10px; }
        .product-table .actions a:hover { text-decoration: underline; }
        .product-table .actions .delete-btn { color: #dc3545; }
        .product-status .active { color: #22c55e; font-weight: 600; }
        .product-status .inactive { color: #9ca3af; }
        .no-products-message { text-align: center; padding: 2rem; color: #6b7280; font-size: 1.1rem; }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; font-weight: bold; }
        .error-message { background-color: #fdd; color: #d00; border: 1px solid #d00; }
        .success-message-php { background-color: #d1fae5; color: #065f46; border: 1px solid #065f46; }
    </style>
</head>
<body>
    <?php 
        include 'header_produtor.php'; 
    ?>

    <main class="main-content">
        <div class="products-management container">
            <div class="section-header">
                <h2>Meus Produtos</h2>
                <a href="./cadastrar_produto.php" class="btn btn-add-product">
                    + Adicionar Novo Produto
                </a>
            </div>

            <?php if ($mensagemErro): ?>
                <div class="message error-message">
                    <p><?= $mensagemErro ?></p>
                </div>
            <?php endif; ?>

            <?php if ($mensagemSucesso): ?>
                <div class="message success-message-php">
                    <p><?= $mensagemSucesso ?></p>
                </div>
            <?php endif; ?>

            <?php if (empty($produtos)): ?>
                <div class="no-products-message">
                    <p>Você ainda não cadastrou nenhum produto. <a href="./cadastrar_produto.php">Comece agora!</a></p>
                </div>
            <?php else: ?>
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Nome do Produto</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Categoria</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto): ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($produto['imagem_url'] ?? '../assets/images/placeholder.svg') ?>" alt="<?= htmlspecialchars($produto['nome']) ?>"></td>
                                <td><?= htmlspecialchars($produto['nome']) ?></td>
                                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?> / <?= htmlspecialchars($produto['unidade_medida']) ?></td>
                                <td><?= htmlspecialchars($produto['quantidade_estoque']) ?></td>
                                <td><?= htmlspecialchars($produto['categoria']) ?></td>
                                <td class="product-status">
                                    <span class="<?= $produto['ativo'] ? 'active' : 'inactive' ?>">
                                        <?= $produto['ativo'] ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="./editar_produto.php?id=<?= $produto['id'] ?>">Editar</a>
                                    <a href="?action=excluir&id=<?= $produto['id'] ?>" 
                                       onclick="return confirm('Tem certeza que deseja excluir este produto?');" class="delete-btn">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'footer_produtor.php'; ?> 

    <script src="../js/dashboard-script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Adicionar lógica JS específica para esta página, se houver
        });
    </script>
</body>
</html>