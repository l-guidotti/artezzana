<?php
require_once __DIR__ . '/../../../app/helpers/auth.php';
require_once __DIR__ . '/../../../config/database.php';

$sql = "
    SELECT p.id AS produto_id, p.nome, p.preco, p.imagem_url, u.nome AS nome_produtor
    FROM produtos p
    JOIN usuarios u ON p.produtor_id = u.id
    ORDER BY p.data_cadastro DESC
    LIMIT 5
";

$pdo = conectar();
$stmt = $pdo->prepare($sql);
$stmt->execute();
$ultimos_produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);




verificarLogin();
protegerRotaComprador();

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
    <title>Dashboard do Comprador - Artezzana</title> 
    <link rel="stylesheet" href="../../../public/css/dashboard-styles.css">
    <link rel="stylesheet" href="../../../public/css/global.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos adicionais para os cards de produtos, se precisar ajustar no CSS principal depois */
        .product-card {
            background-color: var(--color-white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s ease-in-out;
            min-width: 200px;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }
        .product-info {
            padding: 15px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .product-info h4 {
            font-size: 1.1em;
            margin-bottom: 5px;
            color: var(--color-text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .product-info p {
            font-size: 0.9em;
            color: var(--color-text-secondary);
            margin-bottom: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .product-info .price {
            font-size: 1.2em;
            font-weight: 600;
            color: var(--color-primary);
            margin-top: auto;
        }
        .products-carousel {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
            -webkit-overflow-scrolling: touch;
        }
        @media (max-width: 768px) {
            .product-card { min-width: 180px; }
        }
        @media (max-width: 480px) {
            .product-card { min-width: 160px; }
        }
    </style>
</head>

<body>

    <?php include 'header_comprador.php'; ?>

    <main class="main-content">
        <section class="welcome-section">
            <div class="container">
                <div class="welcome-content">
                    <h2 id="welcomeTitle">Bem-vindo(a) de volta, <?= $usuario_nome ?>!</h2>
                    <p id="welcomeSubtitle">
                        Vamos começar o dia explorando produtos locais.
                    </p>
                </div>
            </div>
        </section>

        <div id="clientDashboard" class="dashboard-content active-dashboard"> <section class="search-section">
                <div class="container">
                    <div class="search-bar">
                        <input type="text" placeholder="O que você está procurando hoje?" id="dashboardSearch">
                        <button class="search-btn" onclick="performDashboardSearch()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </section>

            <section class="recommendations">
                <div class="container">
                    <h3>Recomendados para você</h3>
                    <div class="products-carousel" id="recommendedProducts">
                        <?php if (!empty($ultimos_produtos)): ?>
                            <?php foreach ($ultimos_produtos as $produto): ?>
                                <a href="detalhes_produto.php?id=<?= htmlspecialchars($produto['produto_id']) ?>" class="product-card">
                                    <img src="<?= htmlspecialchars($produto['imagem_url'] ?? '../assets/images/placeholder_produto.png') ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                    <div class="product-info">
                                        <h4><?= htmlspecialchars($produto['nome']) ?></h4>
                                        <p>Por: <?= htmlspecialchars($produto['nome_produtor'] ?? 'Produtor') ?></p>
                                        <span class="price">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Nenhum produto recomendado encontrado no momento.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="categories" id="categories">
                <div class="container">
                    <h3>Categorias</h3>
                    <div class="categories-grid">
                        <div class="category-card" onclick="location.href='#'">
                            <div class="category-icon"><img class="images-categoy" src="../../../public/assets/images/frutas.jpg" alt="frutas e legumes"></div>
                            <h4>Frutas & Verduras</h4><p>Produtos frescos da horta</p>
                        </div>
                        <div class="category-card" onclick="location.href='#'">
                            <div class="category-icon"><img class="images-categoy" src="../../../public/assets/images/artesanatos.jpg" alt="Artesanato"></div>
                            <h4>Artesanato</h4><p>Peças únicas feitas à mão</p>
                        </div>
                        <div class="category-card" onclick="location.href='#'">
                            <div class="category-icon"><img class="images-categoy" src="../../../public/assets/images/doces.jpg" alt="Doces"></div>
                            <h4>Doces & Conservas</h4><p>Sabores caseiros tradicionais</p>
                        </div>
                        <div class="category-card" onclick="location.href='#'">
                            <div class="category-icon"><img class="images-categoy" src="../../../public/assets/images/laticinios.jpg" alt="Laticinios"></div>
                            <h4>Laticínios</h4><p>Queijos e derivados artesanais</p>
                        </div>
                        <div class="category-card" onclick="location.href='#'">
                            <div class="category-icon"><img class="images-categoy" src="../../../public/assets/images/mudas.jpg" alt="Plantas e mudas"></div>
                            <h4>Plantas & Mudas</h4><p>Verde para seu lar</p>
                        </div>
                        <div class="category-card" onclick="location.href='#'">
                            <div class="category-icon"><img class="images-categoy" src="../../../public/assets/images/temperos.jpg" alt="Temperos"></div>
                            <h4>Temperos & Ervas</h4><p>Sabores naturais</p>
                        </div>
                        <div class="category-card" onclick="location.href='#'">
                            <div class="category-icon"><img class="images-categoy" src="../../../public/assets/images/paes.jpg" alt="Pães"></div>
                            <h4>Pães</h4><p>Pães e derivados</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="recent-orders">
                <div class="container">
                    <div class="section-header">
                        <h3>Seus Pedidos Recentes</h3>
                        <a href="./meus_pedidos.php">Ver todos</a>
                    </div>
                    <div class="orders-list" id="recentOrdersList">
                        <p>Carregando seus pedidos recentes...</p>
                    </div>
                </div>
            </section>

            <section class="nearby-producers">
                <div class="container">
                    <h3>Produtores da sua região</h3>
                    <div class="producers-grid" id="nearbyProducers">
                        <p>Carregando produtores próximos...</p>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <?php include 'footer_comprador.php'; ?> 

    <script src="../js/dashboard-script.js"></script>
    <script>
        // Funções JavaScript globais ou específicas do dashboard
        function goToHome() { window.location.href = '../index.php'; }
        function toggleUserDropdown() { document.getElementById('userDropdown').classList.toggle('hidden'); }
        function showSettings() { alert('Configurações em desenvolvimento!'); }
        function showHelp() { alert('Ajuda em desenvolvimento!'); }
        function logout() { // Certifique-se que o link no header aponta para LogoutController.php
             if (confirm('Tem certeza que deseja sair?')) {
                window.location.href = '../../app/controllers/LogoutController.php'; 
            }
        }

        // Funções para ações específicas do comprador
        function performDashboardSearch() { alert('Funcionalidade de busca em desenvolvimento!'); }
        function showAllOrders() { window.location.href = './meus_pedidos.php'; }

        document.addEventListener('DOMContentLoaded', function() {
            // Lógica JS específica para o comprador (carregar dados via JS se necessário)
            // ex: loadRecentOrders(); loadNearbyProducers();
        });
    </script>
</body>
</html>