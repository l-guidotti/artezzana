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
    <title>Dashboard - Artezzana</title>
    <link rel="stylesheet" href="../../../public/css/dashboard-styles.css">
    <link rel="stylesheet" href="../../../public/css/global.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php include 'header_produtor.php'; ?>

    <!-- Main Content -->
     <main class="main-content">
        <section class="welcome-section">
            <div class="container">
                <div class="welcome-content">
                    <h2 id="welcomeTitle">Bem-vindo(a) de volta, <?= htmlspecialchars($_SESSION["usuario_nome"] ?? 'Usuário') ?>!</h2>
                    <p id="welcomeSubtitle">
                        Gerencie seu negócio e produtos com facilidade.
                    </p>
                </div>
                <div class="quick-actions" id="quickActions">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </section>
<!-- Producer Dashboard -->
        <div id="producerDashboard" class="dashboard-content">
            <!-- Stats Overview -->
            <section class="stats-overview">
                <div class="container">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">📦</div>
                            <div class="stat-content">
                                <h4 id="totalProducts">0</h4>
                                <p>Produtos Cadastrados</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">🛒</div>
                            <div class="stat-content">
                                <h4 id="totalOrders">0</h4>
                                <p>Pedidos Este Mês</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">💰</div>
                            <div class="stat-content">
                                <h4 id="totalRevenue">R$ 0</h4>
                                <p>Faturamento</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">⭐</div>
                            <div class="stat-content">
                                <h4 id="averageRating">0.0</h4>
                                <p>Avaliação Média</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quick Actions for Producers -->
            <section class="producer-actions">
                <div class="container">
                    <div class="actions-grid">
                        <button class="action-card" onclick="addNewProduct()">
                            <div class="action-icon">➕</div>
                            <h4>Adicionar Produto</h4>
                            <p>Cadastre um novo produto</p>
                        </button>
                        <button class="action-card" onclick="manageOrders()">
                            <div class="action-icon">📋</div>
                            <h4>Gerenciar Pedidos</h4>
                            <p>Veja pedidos pendentes</p>
                        </button>
                        <button class="action-card" onclick="updateProfile()">
                            <div class="action-icon">🏪</div>
                            <h4>Atualizar Perfil</h4>
                            <p>Edite informações do negócio</p>
                        </button>
                        <button class="action-card" onclick="viewAnalytics()">
                            <div class="action-icon">📊</div>
                            <h4>Relatórios</h4>
                            <p>Veja estatísticas detalhadas</p>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Recent Orders for Producer -->
            <section class="producer-orders">
                <div class="container">
                    <div class="section-header">
                        <h3>Pedidos Recentes</h3>
                        <a href="#" onclick="showAllProducerOrders()">Ver todos</a>
                    </div>
                    <div class="orders-table" id="producerOrdersTable">
                        <!-- Orders table will be loaded here -->
                    </div>
                </div>
            </section>

            <!-- Product Performance -->
            <section class="product-performance">
                <div class="container">
                    <h3>Seus Produtos Mais Vendidos</h3>
                    <div class="performance-list" id="topProducts">
                        <!-- Top products will be loaded here -->
                    </div>
                </div>
            </section>
        </div>
    </main>

    <?php include 'footer_produtor.php'; ?> 

    <script src="../js/dashboard-script.js"></script>
    <script>
        // Funções JavaScript globais ou específicas do dashboard
        function goToHome() {
            window.location.href = '../index.php';
        }

        // Função para mostrar/esconder o dropdown do usuário
        function toggleUserDropdown() {
            document.getElementById('userDropdown').classList.toggle('hidden');
        }

        // Funções placeholders para ações do menu de usuário
        function showSettings() {
            alert('Configurações em desenvolvimento!');
        }
        function showHelp() {
            alert('Ajuda em desenvolvimento!');
        }

        // Função para simular cliques em botões (no futuro, redirecionaria para páginas reais)
        function addNewProduct() {
            window.location.href = './cadastra_produto.php';
        }
        function manageOrders() {
            window.location.href = './gerenciar_pedidos.php';
        }
        function updateProfile() {
            window.location.href = './perfil.php';
        }
        function viewAnalytics() {
            window.location.href = './relatorios.php';
        }
        function performDashboardSearch() {
            alert('Funcionalidade de busca em desenvolvimento!');
        }

        function handleFabClick() {
            // No dashboard do produtor, o FAB pode adicionar um produto
            addNewProduct();
        }
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Script para inicializar o dashboard com base no tipo de usuário
        document.addEventListener('DOMContentLoaded', function() {
            // No dashboard_produtor.php, sabemos que o tipo de usuário é 'produtor'
            // Não há necessidade de verificar dinamicamente como antes.
            // Apenas carregamos o conteúdo relevante.
            loadProducerDashboard(); // Chama a função para carregar dados do produtor

            // Ocultar dropdown quando clicar fora (manter)
            window.addEventListener('click', function(event) {
                const userMenu = document.querySelector('.user-menu');
                const userDropdown = document.getElementById('userDropdown');
                if (userMenu && userDropdown && !userMenu.contains(event.target)) {
                    userDropdown.classList.add('hidden');
                }
            });

            // Ocultar o modal clicando fora (opcional, pode ser no dashboard-script.js)
            const profileModal = document.getElementById('profileModal');
            if (profileModal) {
                profileModal.addEventListener('click', function(event) {
                    if (event.target === profileModal) { // Se clicou diretamente no fundo escuro
                        closeModal('profileModal');
                    }
                });
            }

        });
    </script>
    </body>
</html>