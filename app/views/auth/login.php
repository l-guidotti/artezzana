<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Artezzana</title>
    <link rel="stylesheet" href="../css/auth-styles.css">
    <link rel="stylesheet" href="../css/global.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo" onclick="goToHome()">
                <div class="logo-icon">
                    <img class="image-icon" src="../assets/logos/logo-png.png" alt="logo-artezzana">
                </div>
                <h1>Artezzana</h1>
            </div>
            <nav class="nav">
                <a href="../index.php" class="nav-link">Voltar ao Início</a>
            </nav>
        </div>
    </header>

    <!-- Auth Container -->
    <div class="auth-container">
        <div class="auth-wrapper">
            <!-- Login Form -->
            <div class="auth-form" id="loginForm">
                <div class="form-header">
                    <h2>Bem-vindo de volta!</h2>
                    <p>Entre na sua conta para continuar</p>
                </div>

                <form id="loginFormElement" method="POST" action="../../app/controllers/LoginController.php">
                    <div class="form-group">
                        <label for="loginEmail">E-mail</label>
                        <input type="email" id="loginEmail" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="loginPassword">Senha</label>
                        <div class="password-input">
                            <input type="password" id="loginPassword" name="senha" required>
                        </div>
                    </div>

                    <?php if (!empty($erroLogin)): ?>
                        <div class="erro-mensagem" style="color: red; margin-bottom: 1rem;">
                            <?= htmlspecialchars($erroLogin) ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" id="rememberMe">
                            <span class="checkmark"></span>
                            Lembrar de mim
                        </label>
                        <a href="#" class="forgot-password">Esqueci minha senha</a>
                    </div>

                    <button type="submit" class="btn btn-primary">Entrar</button>
                </form>

                <div class="form-footer">
                    <p>Não tem uma conta? <a href="./cadastro.php">Cadastre-se</a></p>
                </div>
            </div>

            

    <script src="../js/auth-script.js"></script>
</body>
</html>