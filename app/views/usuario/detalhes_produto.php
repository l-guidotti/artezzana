<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../app/models/Produto.php';
require_once __DIR__ . '/../../../app/models/Usuario.php';
require_once __DIR__ . '/../../../app/helpers/auth.php';

verificarLogin();
protegerRotaComprador();

// Checar se tem produto_id na URL
$id_produto = $_GET['id'] ?? null;
$mensagemErro = '';
$mensagemSucesso = '';
$produto = null;
$produtorDoProduto = null;

try {
    $pdo = conectar();

    if ($id_produto) {
        $produtoModel = new Produto($pdo);
        $produto = $produtoModel->buscarPorId($id_produto);

        if ($produto && isset($produto['produtor_id'])) {
            $usuarioModel = new Usuario($pdo);
            $produtorDoProduto = $usuarioModel->buscarPorId($produto['produtor_id']);
        }
    }

    // Lógica de adicionar ao carrinho (caso seja POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add_to_cart') {
        $quantidade = (int) ($_POST['quantidade'] ?? 1);

        if ($produto && $quantidade > 0 && $quantidade <= $produto['quantidade_estoque']) {
            // Exemplo de estrutura do carrinho na sessão
            $_SESSION['carrinho'][$id_produto] = [
                'produto_id' => $id_produto,
                'quantidade' => $quantidade
            ];
            $mensagemSucesso = 'Produto adicionado ao carrinho com sucesso!';
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
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produto['nome'] ?? 'Detalhes do Produto') ?> - Artezzana</title>
    <link rel="stylesheet" href="../css/detalhes-produto-styles.css">
    <link rel="stylesheet" href="../css/carrinho-styles.css">
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
                            <form action="detalhes_produto.php?id=<?= $id_produto ?>" method="POST">
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
                            <p>Vendido por: <a href="perfil_produtor.php?id=<?= htmlspecialchars($produtorDoProduto['id'] ?? '') ?>" class="producer-link"><?= htmlspecialchars($produtorDoProduto['nome'] ?? 'Desconhecido') ?></a></p>
                            <p>Localização: <?= htmlspecialchars($produtorDoProduto['cidade'] ?? 'N/A') ?>, <?= htmlspecialchars($produtorDoProduto['estado'] ?? 'N/A') ?></p>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('productQuantity');
            const addToCartBtn = document.querySelector('.add-to-cart-btn'); // Seleciona o botão de adicionar ao carrinho
            const buyNowBtn = document.querySelector('.buy-now-btn');
            
            // maxStock deve ser verificado se quantityInput existe
            const maxStock = quantityInput ? parseInt(quantityInput.max) : 0;

            if (quantityInput) {
                quantityInput.addEventListener('input', function() {
                    let value = parseInt(this.value);
                    if (isNaN(value) || value < 1) {
                        this.value = 1;
                    } else if (value > maxStock) {
                        this.value = maxStock;
                    }
                });
            }

            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function() {
                    const productId = this.dataset.productId; // Pega o ID do produto do atributo data-product-id do botão
                    const quantity = parseInt(quantityInput.value);

                    if (!productId) {
                        alert('Erro: ID do produto não disponível.');
                        return;
                    }

                    if (isNaN(quantity) || quantity <= 0) {
                        alert('Por favor, insira uma quantidade válida.');
                        return;
                    }
                    if (quantity > maxStock) {
                        alert(`A quantidade solicitada (${quantity}) excede o estoque disponível (${maxStock}).`);
                        return;
                    }

                    // Requisição AJAX para adicionar ao carrinho
                    fetch('../../app/controllers/gerenciar_carrinho.php', { // URL DO CONTROLADOR DO CARRINHO
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        // O 'action' no body do POST deve ser 'add_to_cart'
                        body: `action=add_to_cart&produto_id=${productId}&quantidade=${quantity}` 
                    })
                    .then(response => {
                        if (!response.ok) { // Verifica se a resposta HTTP não foi bem-sucedida (ex: 404, 500)
                            throw new Error('Erro de rede ou servidor ao adicionar ao carrinho. Status: ' + response.status);
                        }
                        return response.json(); // Converte a resposta para JSON
                    })
                    .then(data => {
                        if (data.success) {
                            alert(data.message); 
                            window.location.href = './carrinho.php'; // REDIRECIONAR PARA A PÁGINA DO CARRINHO APÓS SUCESSO
                        } else {
                            alert('Erro ao adicionar ao carrinho: ' + data.message); 
                        }
                    })
                    .catch(error => {
                        console.error('Erro na requisição AJAX:', error);
                        alert('Ocorreu um erro ao adicionar o produto ao carrinho. Tente novamente. Detalhes: ' + error.message);
                    });
                });
            }

            if (buyNowBtn) {
                buyNowBtn.addEventListener('click', function() {
                    const productId = this.dataset.productId; 
                    const quantity = parseInt(quantityInput.value);
                    if (!productId) {
                        alert('Erro: ID do produto não disponível.');
                        return;
                    }
                    alert(`Comprando Produto ${productId} (${quantity} unidades) agora! (Lógica de compra direta a ser implementada)`);
                });
            }
        });
    </script>
</body>
</html>