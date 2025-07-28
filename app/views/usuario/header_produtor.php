<!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <div class="logo-icon">
                    <img class="image-icon" src="../../../public/assets/logos/logo-png.png" alt="logo-artezzana">
                </div>
                <h1>Artezzana</h1>
            </div>

            <nav class="nav">
                <a href="./dashboard_produtor.php" class="nav-link active">Início</a>
                <a href="./produto.php" class="nav-link">Produtos</a>
                <a href="#" class="nav-link">Estatísticas</a>
                <a href="#" class="nav-link">Pedidos</a>
            </nav>

            <!-- User Menu -->
            <div class="user-menu">
                <div class="user-info" onclick="toggleUserDropdown()">
                    <div class="user-avatar" id="userAvatar">
                        <span id="userInitials"><?= $iniciais_usuario ?></span> </div>
                    <span id="userName"><?= $usuario_nome ?></span>
                    <svg class="dropdown-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <polyline points="6,9 12,15 18,9"></polyline>
                    </svg>
                </div>

                <div class="user-dropdown hidden" id="userDropdown">
                    <a href="./perfil_produtor.php">Meu Perfil</a>
                    <a href="#" onclick="showSettings()">Configurações</a>
                    <a href="#" onclick="showHelp()">Ajuda</a>
                    <hr>
                    <a href="../../app/controllers/LoginController.php?action=logout">Sair</a>

                </div>
            </div>
        </div>
    </header>