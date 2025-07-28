<?php
require_once __DIR__ . '/../../../app/helpers/auth.php';
require_once __DIR__ . '/../../../app/models/Produto.php';
require_once __DIR__ . '/../../../config/database.php';

$pdo = Database::conectar();

verificarLogin();
protegerRotaProdutor();

$produtorId = $_SESSION['usuario_id'];
$produtoModel = new Produto($pdo);
$produtos = $produtoModel->listarPorProdutor($produtorId);


$dadosUsuario = pegarDadosUsuario();
$usuario_nome = $dadosUsuario['nome'];
$tipo_usuario_logado = $dadosUsuario['tipo'];
$iniciais_usuario = $dadosUsuario['iniciais'];

$mensagemErro = $_SESSION['erro_produto'] ?? '';
$mensagemSucesso = $_SESSION['msg'] ?? '';

unset($_SESSION['erro_produto'], $_SESSION['msg']);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos - Artezzana</title>
    <link rel="stylesheet" href="../../../public/css/global.css">
    <link rel="stylesheet" href="../../../public/css/dashboard-styles.css">
    <link rel="stylesheet" href="../../../public/css/produto-styles.css">
</head>
<body>
    <?php 
        include 'header_produtor.php'; 
    ?>

    <main class="main-content">
        <div class="products-management container">
            <div class="section-header">
                <h2>Meus Produtos</h2>
                <a href="./cadastra_produto.php" class="btn btn-add-product">
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
                    <p>Você ainda não cadastrou nenhum produto. <a href="./cadastra_produto.php">Comece agora!</a></p>
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
</body>
</html>