<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artezzana - Marketplace para Produtores Rurais e Artesãos</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/global.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <div class="logo-icon">
                    <img class="image-icon" src="./assets/logos/logo-png.png" alt="logo-artezzana">
                </div>
                <h1>Artezzana</h1>
            </div>
             <nav class="nav">
                <a href="./pages/login.php" class="nav-link">Login</a>
                <a href="./pages/cadastro.php" class="nav-link">Cadastro</a>
            </nav>
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>Conectando Produtores Locais com Consumidores Conscientes</h2>
                <p>Descubra produtos frescos, artesanatos únicos e sabores autênticos diretamente dos pequenos produtores da sua região.</p>
                <div class="hero-buttons">
                    <button class="btn btn-primary" onclick="scrollToSection('categories')">Explorar Produtos</button>
                    <button class="btn btn-secondary" onclick="scrollToSection('how-it-works')">Como Funciona</button>
                </div>
            </div>
            <div class="hero-image">
                <img src="./assets/images/home-page.jpg" alt="Produtores locais" />
            </div>
        </div>
    </section>

    <!-- Search Bar -->
    <section class="search-section">
        <div class="container">
            <div class="search-bar">
                <input type="text" placeholder="Busque por produtos, produtores ou categorias..." id="searchInput">
                <button class="search-btn" onclick="performSearch()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories" id="categories">
        <div class="container">
            <h3>Categorias</h3>
            <div class="categories-grid">
                <div class="category-card" onclick="redirectToLogin()">
                    <div class="category-icon">
                        <img class="images-categoy" src="./assets/images/frutas.jpg" alt="frutas e legumes">
                    </div>
                    <h4>Frutas & Verduras</h4>
                    <p>Produtos frescos da horta</p>
                </div>
                <div class="category-card" onclick="redirectToLogin()">
                    <div class="category-icon">
                        <img class="images-categoy" src="./assets/images/artesanatos.jpg" alt="Frutas e legumes">
                    </div>
                    <h4>Artesanato</h4>
                    <p>Peças únicas feitas à mão</p>
                </div>
                <div class="category-card" onclick="redirectToLogin()">
                    <div class="category-icon">
                        <img class="images-categoy" src="./assets/images/doces.jpg" alt="Doces">
                    </div>
                    <h4>Doces & Conservas</h4>
                    <p>Sabores caseiros tradicionais</p>
                </div>
                <div class="category-card" onclick="redirectToLogin()">
                    <div class="category-icon">
                        <img class="images-categoy" src="./assets/images/laticinios.jpg" alt="Laticinios">
                    </div>
                    <h4>Laticínios</h4>
                    <p>Queijos e derivados artesanais</p>
                </div>
                <div class="category-card" onclick="redirectToLogin()">
                    <div class="category-icon">
                        <img class="images-categoy" src="./assets/images/mudas.jpg" alt="Plantas e mudas">
                    </div>
                    <h4>Plantas & Mudas</h4>
                    <p>Verde para seu lar</p>
                </div>
                <div class="category-card" onclick="redirectToLogin()">
                    <div class="category-icon">
                        <img class="images-categoy" src="./assets/images/temperos.jpg" alt="Temperos">
                    </div>
                    <h4>Temperos & Ervas</h4>
                    <p>Sabores naturais</p>
                </div>
                <div class="category-card" onclick="redirectToLogin()">
                    <div class="category-icon">
                        <img class="images-categoy" src="./assets/images/paes.jpg" alt="Pães">
                    </div>
                    <h4>Pães</h4>
                    <p>Pães e deivados</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-products">
        <div class="container">
            <h3>Produtos em Destaque</h3>
            <div class="products-grid" id="productsGrid">
                <!-- Products will be loaded here by JavaScript -->
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <h3>Como Funciona</h3>
            <div class="steps-grid">
                <div class="step">
                    <div class="step-number">1</div>
                    <h4>Explore</h4>
                    <p>Navegue pelos produtos dos produtores locais da sua região</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h4>Escolha</h4>
                    <p>Selecione produtos frescos e artesanatos únicos</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h4>Conecte</h4>
                    <p>Entre em contato direto com o produtor</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <h4>Receba</h4>
                    <p>Retire no local ou receba em casa</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Producers Section -->
    <section class="producers">
        <div class="container">
            <h3>Nossos Produtores</h3>
            <div class="producers-grid">
                <div class="producer-card">
                    <img src="./assets/images/dona-maria.jpeg" alt="Sítio da Dona Maria" />
                    <h4>Sítio da Dona Maria</h4>
                    <p>Especializada em hortaliças orgânicas e temperos frescos</p>
                    <span class="location">📍 Zona Rural - São Paulo</span>
                </div>
                <div class="producer-card">
                    <img src="./assets/images/joao-artesao.jpeg" alt="Artesanato do João" />
                    <h4>Artesanato do João</h4>
                    <p>Peças em madeira e cerâmica feitas com técnicas tradicionais</p>
                    <span class="location">📍 Pelotas - Rio Grande do Sul</span>
                </div>
                <div class="producer-card">
                    <img src="./assets/images/laticinio-artesanal.jpeg" alt="Fazenda Esperança" />
                    <h4>Fazenda Esperança</h4>
                    <p>Laticínios artesanais e produtos da agricultura familiar</p>
                    <span class="location">📍 Interior - Rio Grande do Sul</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="logo">
                        <div class="logo-icon">
                            <img class="image-icon" src="./assets/logos/logo-png.png" alt="logo-artezzana">
                        </div>
                        <h4>Artezzana</h4>
                    </div>
                    <p>Conectando produtores locais com consumidores conscientes</p>
                </div>
                <div class="footer-section">
                    <h5>Para Produtores</h5>
                    <ul>
                        <li><a href="./pages/login.html">Cadastre-se</a></li>
                        <li><a href="#">Como Vender</a></li>
                        <li><a href="#">Suporte</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Para Compradores</h5>
                    <ul>
                        <li><a href="#">Como Comprar</a></li>
                        <li><a href="#">Entregas</a></li>
                        <li><a href="#">Dúvidas</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Contato</h5>
                    <ul>
                        <li>📧 lucasguidottipro@gmail.com</li>
                        <li>📱 (53) 98136-4363</li>
                        <li>📍 Pelotas - RS</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Artezzana. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="./js/index.js"></script>
</body>
</html>