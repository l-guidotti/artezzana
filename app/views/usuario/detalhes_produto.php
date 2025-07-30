<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../app/models/Produto.php';
require_once __DIR__ . '/../../../app/models/Usuario.php';
require_once __DIR__ . '/../../../app/helpers/auth.php';

// Checar se tem produto_id na URL
$id_produto = $_GET['id'] ?? null;
$mensagemErro = '';
$mensagemSucesso = '';
$produto = null;

try {
    $pdo = conectar();

    if ($id_produto) {
        $stmt = $pdo->prepare("SELECT p.*, u.nome AS nome_produtor, u.id AS id_produtor, u.cidade, u.estado 
                               FROM produtos p
                               JOIN usuarios u ON p.produtor_id = u.id
                               WHERE p.id = :id");
        $stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);
        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lógica de adicionar ao carrinho (caso seja POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add_to_cart') {
        $quantidade = (int) ($_POST['quantidade'] ?? 1);

        if ($produto && $quantidade > 0 && $quantidade <= $produto['quantidade_estoque']) {
            // Inicializa o carrinho se ainda não existir
            if (!isset($_SESSION['carrinho'])) {
                $_SESSION['carrinho'] = [];
            }
            // Adiciona ou atualiza a quantidade do produto no carrinho
            $_SESSION['carrinho'][$id_produto] = [
                'produto_id' => $id_produto,
                'quantidade' => $quantidade,
                'nome' => $produto['nome'], // Adicione informações úteis para o carrinho
                'preco' => $produto['preco'],
                'imagem_url' => $produto['imagem_url']
            ];
            $mensagemSucesso = 'Produto adicionado ao carrinho com sucesso!';
            // Redireciona para evitar reenvio do formulário ao atualizar a página
            header("Location: carrinho.php");
            exit();
        } else {
            $mensagemErro = 'Quantidade inválida ou produto sem estoque.';
        }
    }


    $dadosUsuario = pegarDadosUsuario();
    $usuario_nome = $dadosUsuario['nome'];
    $tipo_usuario_logado = $dadosUsuario['tipo'];
    $iniciais_usuario = $dadosUsuario['iniciais'];

} catch (Exception $e) {
    $mensagemErro = 'Erro ao carregar os dados do produto: ' . $e->getMessage();
}

// Lógica para exibir mensagem de sucesso após redirecionamento
if (isset($_GET['success']) && $_GET['success'] === 'added') {
    $mensagemSucesso = 'Produto adicionado ao carrinho com sucesso!';
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produto['nome'] ?? 'Detalhes do Produto') ?> - Artezzana</title>
    <link rel="stylesheet" href="../css/detalhes-produto-styles.css">
    <link rel="stylesheet" href="../../../public/css/carrinho-styles.css">
    <link rel="stylesheet" href="../../../public/css/global.css">
    <link rel="stylesheet" href="../../../public/css/dashboard-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    
    <?php include 'header_comprador.php'; ?>

    <main class="main-content">
        <div class="container">
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

            <?php if ($produto): // Só exibe o HTML se o produto foi encontrado ?>
                <div class="product-detail-container">
                    <div class="product-detail-image">
                        <img src="<?= htmlspecialchars($produto['imagem_url'] ?? '../assets/images/placeholder_produto.png') ?>" alt="<?= htmlspecialchars($produto['nome'] ?? '') ?>" class="main-product-image">
                    </div>
                    <div class="product-detail-info">
                        <h1 class="product-name"><?= htmlspecialchars($produto['nome'] ?? '') ?></h1>
                        <p class="product-category">Categoria: <?= htmlspecialchars($produto['categoria'] ?? '') ?></p>
                        <p class="product-price">R$ <?= number_format($produto['preco'] ?? 0, 2, ',', '.') ?> / <?= htmlspecialchars($produto['unidade_medida'] ?? '') ?></p>
                        
                        <div class="product-stock-status">
                            <?php if (($produto['quantidade_estoque'] ?? 0) > 0): ?>
                                <span class="in-stock">Em estoque (<?= htmlspecialchars($produto['quantidade_estoque'] ?? 0) ?> unidades)</span>
                            <?php else: ?>
                                <span class="out-of-stock">Produto esgotado</span>
                            <?php endif; ?>
                        </div>

                        <div class="product-actions">
                            <form method="POST" action="detalhes_produto.php?id=<?= $id_produto ?>">
                                <input type="hidden" name="action" value="add_to_cart">
                                <label for="quantidade">Qtd:</label>
                                <input type="number" id="quantidade" name="quantidade" value="1" min="1" max="<?= htmlspecialchars($produto['quantidade_estoque'] ?? 0) ?>" required>
                                <button type="submit" class="btn btn-primary" <?= (($produto['quantidade_estoque'] ?? 0) <= 0 ? 'disabled' : '') ?>>
                                    <?= (($produto['quantidade_estoque'] ?? 0) <= 0 ? 'Sem Estoque' : 'Adicionar ao Carrinho') ?>
                                </button>
                            </form>
                        </div>

                        <div class="product-description">
                            <h3>Detalhes do Produto</h3>
                            <p><?= nl2br(htmlspecialchars($produto['descricao'] ?? '')) ?></p>
                        </div>

                        <div class="producer-info">
                            <h3>Sobre o Produtor</h3>
                            <p>Vendido por: <a href="perfil_produtor.php?id=<?= $produto['id_produtor'] ?>"><?= $produto['nome_produtor'] ?></a></p>
                            <p>Localização: <?= $produto['cidade'] ?>, <?= $produto['estado'] ?></p>

                        </div>

                        <div class="related-products">
                            <h3>Você também pode gostar</h3>
                            <div class="products-carousel">
                                <!-- Produtos relacionados serão carregados aqui -->
                            </div>
                        </div>

                    </div>
                </div>
            <?php else: // Mensagem se o produto não for encontrado ou não estiver disponível ?>
                <div class="no-products-message">
                    <p>O produto que você está procurando não foi encontrado ou não está disponível.</p>
                    <a href="../index.php" class="btn btn-primary">Voltar ao Início</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'footer_comprador.php'; ?>

    <script src="../js/dashboard-script.js"></script>
</body>
</html>