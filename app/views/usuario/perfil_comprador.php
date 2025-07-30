<?php
require_once __DIR__ . '/../../../app/helpers/auth.php';
require_once __DIR__ . '/../../../config/database.php';

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
    <title>Meu Perfil - Artezzana</title>
    <link rel="stylesheet" href="../../../public/css/perfil-styles.css">
    <link rel="stylesheet" href="../../../public/css/global.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include 'header_comprador.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Breadcrumb -->
        <section class="breadcrumb">
            <div class="container">
                <nav class="breadcrumb-nav">
                    <a href="dashboard_produtor.php">Início</a>
                    <span>›</span>
                    <span>Meu Perfil</span>
                </nav>
            </div>
        </section>

        <!-- Profile Content -->
        <section class="profile-section">
            <div class="container">
                <div class="profile-layout">
                    <!-- Sidebar Navigation -->
                    <aside class="profile-sidebar">
                        <div class="profile-card">
                            <div class="profile-avatar-section">
                                <div class="profile-avatar" id="profileAvatar">
                                    <img id="avatarImage" src="/placeholder.svg" alt="Foto do perfil" class="hidden">
                                    <span id="avatarInitials">U</span>
                                </div>
                                <button class="change-photo-btn" onclick="changeProfilePhoto()">
                                    📷 Alterar Foto
                                </button>
                                <input type="file" id="photoInput" accept="image/*" class="hidden" onchange="handlePhotoUpload(event)">
                            </div>
                            
                            <div class="profile-info">
                                <h2 id="profileName">Nome do Usuário</h2>
                                <p id="profileType">Cliente</p>
                                <p id="profileEmail">email@exemplo.com</p>
                            </div>
                        </div>

                        <nav class="profile-nav">
                            <a href="#" class="profile-nav-link active" onclick="showSection('personal')" data-section="personal">
                                👤 Dados Pessoais
                            </a>
                            <a href="#" class="profile-nav-link" onclick="showSection('addresses')" data-section="addresses">
                                📍 Endereços
                            </a>
                            <a href="#" class="profile-nav-link" onclick="showSection('payment')" data-section="payment">
                                💳 Pagamento
                            </a>
                            <a href="#" class="profile-nav-link" onclick="showSection('security')" data-section="security">
                                🔒 Segurança
                            </a>
                            <a href="#" class="profile-nav-link" onclick="showSection('notifications')" data-section="notifications">
                                🔔 Notificações
                            </a>
                            <a href="#" class="profile-nav-link producer-only hidden" onclick="showSection('business')" data-section="business">
                                🏪 Dados do Negócio
                            </a>
                            <a href="#" class="profile-nav-link" onclick="showSection('preferences')" data-section="preferences">
                                ⚙️ Preferências
                            </a>
                        </nav>
                    </aside>

                    <!-- Main Content Area -->
                    <div class="profile-content">
                        <!-- Personal Data Section -->
                        <div class="profile-section-content active" id="personal-section">
                            <div class="section-header">
                                <h2>Dados Pessoais</h2>
                                <p>Mantenha suas informações sempre atualizadas</p>
                            </div>

                            <form class="profile-form" id="personalForm">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="firstName">Nome *</label>
                                        <input type="text" id="firstName" name="firstName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="lastName">Sobrenome *</label>
                                        <input type="text" id="lastName" name="lastName" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="email">E-mail *</label>
                                        <input type="email" id="email" name="email" required>
                                        <small class="form-help">Usado para login e comunicações importantes</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Telefone *</label>
                                        <input type="tel" id="phone" name="phone" required placeholder="(11) 99999-9999">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="cpf">CPF *</label>
                                        <input type="text" id="cpf" name="cpf" required placeholder="000.000.000-00" readonly>
                                        <small class="form-help">CPF não pode ser alterado</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="birthDate">Data de Nascimento</label>
                                        <input type="date" id="birthDate" name="birthDate">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="bio">Sobre você</label>
                                    <textarea id="bio" name="bio" rows="4" placeholder="Conte um pouco sobre você..."></textarea>
                                    <small class="form-help">Máximo 500 caracteres</small>
                                </div>

                                <div class="form-actions">
                                    <button type="button" class="btn btn-secondary" onclick="resetPersonalForm()">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                </div>
                            </form>
                        </div>

                        <!-- Addresses Section -->
                        <div class="profile-section-content" id="addresses-section">
                            <div class="section-header">
                                <h2>Meus Endereços</h2>
                                <p>Gerencie seus endereços de entrega</p>
                                <button class="btn btn-primary" onclick="showAddAddressModal()">+ Novo Endereço</button>
                            </div>

                            <div class="addresses-list" id="addressesList">
                                <!-- Addresses will be loaded here -->
                            </div>
                        </div>

                        <!-- Payment Methods Section -->
                        <div class="profile-section-content" id="payment-section">
                            <div class="section-header">
                                <h2>Métodos de Pagamento</h2>
                                <p>Gerencie suas formas de pagamento</p>
                            </div>

                            <div class="payment-methods-list">
                                <div class="payment-method-card">
                                    <div class="payment-method-info">
                                        <div class="payment-icon">💳</div>
                                        <div>
                                            <h4>PIX</h4>
                                            <p>Pagamento instantâneo via PIX</p>
                                        </div>
                                    </div>
                                    <div class="payment-method-status">
                                        <span class="status-badge active">Ativo</span>
                                    </div>
                                </div>

                                <div class="payment-method-card">
                                    <div class="payment-method-info">
                                        <div class="payment-icon">💰</div>
                                        <div>
                                            <h4>Dinheiro</h4>
                                            <p>Pagamento na entrega ou retirada</p>
                                        </div>
                                    </div>
                                    <div class="payment-method-status">
                                        <span class="status-badge active">Ativo</span>
                                    </div>
                                </div>

                                <div class="payment-method-card">
                                    <div class="payment-method-info">
                                        <div class="payment-icon">💳</div>
                                        <div>
                                            <h4>Cartão de Crédito/Débito</h4>
                                            <p>Adicione seus cartões para pagamento rápido</p>
                                        </div>
                                    </div>
                                    <div class="payment-method-actions">
                                        <button class="btn btn-secondary btn-sm" onclick="addPaymentMethod()">Adicionar Cartão</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Section -->
                        <div class="profile-section-content" id="security-section">
                            <div class="section-header">
                                <h2>Segurança</h2>
                                <p>Mantenha sua conta segura</p>
                            </div>

                            <div class="security-options">
                                <div class="security-card">
                                    <div class="security-info">
                                        <h4>Alterar Senha</h4>
                                        <p>Recomendamos alterar sua senha regularmente</p>
                                    </div>
                                    <button class="btn btn-secondary" onclick="showChangePasswordModal()">Alterar Senha</button>
                                </div>

                                <div class="security-card">
                                    <div class="security-info">
                                        <h4>Verificação em Duas Etapas</h4>
                                        <p>Adicione uma camada extra de segurança</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="twoFactor" onchange="toggleTwoFactor()">
                                        <label for="twoFactor"></label>
                                    </div>
                                </div>

                                <div class="security-card">
                                    <div class="security-info">
                                        <h4>Sessões Ativas</h4>
                                        <p>Gerencie onde você está logado</p>
                                    </div>
                                    <button class="btn btn-secondary" onclick="showActiveSessions()">Ver Sessões</button>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications Section -->
                        <div class="profile-section-content" id="notifications-section">
                            <div class="section-header">
                                <h2>Notificações</h2>
                                <p>Escolha como deseja ser notificado</p>
                            </div>

                            <div class="notifications-settings">
                                <div class="notification-group">
                                    <h4>Pedidos</h4>
                                    <div class="notification-option">
                                        <div class="notification-info">
                                            <strong>Confirmação de pedidos</strong>
                                            <p>Receba confirmação quando um pedido for aceito</p>
                                        </div>
                                        <div class="notification-controls">
                                            <label class="checkbox-container">
                                                <input type="checkbox" name="order_confirmation_email" checked>
                                                <span class="checkmark"></span>
                                                E-mail
                                            </label>
                                            <label class="checkbox-container">
                                                <input type="checkbox" name="order_confirmation_sms">
                                                <span class="checkmark"></span>
                                                SMS
                                            </label>
                                        </div>
                                    </div>

                                    <div class="notification-option">
                                        <div class="notification-info">
                                            <strong>Status de entrega</strong>
                                            <p>Atualizações sobre o status da sua entrega</p>
                                        </div>
                                        <div class="notification-controls">
                                            <label class="checkbox-container">
                                                <input type="checkbox" name="delivery_status_email" checked>
                                                <span class="checkmark"></span>
                                                E-mail
                                            </label>
                                            <label class="checkbox-container">
                                                <input type="checkbox" name="delivery_status_sms" checked>
                                                <span class="checkmark"></span>
                                                SMS
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="notification-group">
                                    <h4>Marketing</h4>
                                    <div class="notification-option">
                                        <div class="notification-info">
                                            <strong>Ofertas e promoções</strong>
                                            <p>Receba ofertas especiais e novidades</p>
                                        </div>
                                        <div class="notification-controls">
                                            <label class="checkbox-container">
                                                <input type="checkbox" name="marketing_email">
                                                <span class="checkmark"></span>
                                                E-mail
                                            </label>
                                        </div>
                                    </div>

                                    <div class="notification-option">
                                        <div class="notification-info">
                                            <strong>Novos produtores</strong>
                                            <p>Saiba quando novos produtores se juntarem à plataforma</p>
                                        </div>
                                        <div class="notification-controls">
                                            <label class="checkbox-container">
                                                <input type="checkbox" name="new_producers_email" checked>
                                                <span class="checkmark"></span>
                                                E-mail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-primary" onclick="saveNotificationSettings()">Salvar Preferências</button>
                            </div>
                        </div>

                        <!-- Business Data Section (Producer Only) -->
                        <div class="profile-section-content producer-only hidden" id="business-section">
                            <div class="section-header">
                                <h2>Dados do Negócio</h2>
                                <p>Informações sobre seu negócio</p>
                            </div>

                            <form class="profile-form" id="businessForm">
                                <div class="form-group">
                                    <label for="businessName">Nome do Negócio *</label>
                                    <input type="text" id="businessName" name="businessName" required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="cnpj">CNPJ</label>
                                        <input type="text" id="cnpj" name="cnpj" placeholder="00.000.000/0000-00">
                                    </div>
                                    <div class="form-group">
                                        <label for="businessType">Tipo de Negócio *</label>
                                        <select id="businessType" name="businessType" required>
                                            <option value="">Selecione...</option>
                                            <option value="agricultura">Agricultura</option>
                                            <option value="artesanato">Artesanato</option>
                                            <option value="alimentacao">Alimentação</option>
                                            <option value="outros">Outros</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="businessDescription">Descrição do Negócio *</label>
                                    <textarea id="businessDescription" name="businessDescription" rows="4" required placeholder="Descreva seu negócio, produtos e diferenciais..."></textarea>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="deliveryRadius">Raio de Entrega (km)</label>
                                        <input type="number" id="deliveryRadius" name="deliveryRadius" min="0" max="100">
                                    </div>
                                    <div class="form-group">
                                        <label for="deliveryFee">Taxa de Entrega (R$)</label>
                                        <input type="number" id="deliveryFee" name="deliveryFee" min="0" step="0.01">
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="button" class="btn btn-secondary" onclick="resetBusinessForm()">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                </div>
                            </form>
                        </div>

                        <!-- Preferences Section -->
                        <div class="profile-section-content" id="preferences-section">
                            <div class="section-header">
                                <h2>Preferências</h2>
                                <p>Personalize sua experiência</p>
                            </div>

                            <div class="preferences-settings">
                                <div class="preference-group">
                                    <h4>Aparência</h4>
                                    <div class="preference-option">
                                        <div class="preference-info">
                                            <strong>Tema</strong>
                                            <p>Escolha entre tema claro ou escuro</p>
                                        </div>
                                        <select id="theme" name="theme" onchange="changeTheme()">
                                            <option value="light">Claro</option>
                                            <option value="dark">Escuro</option>
                                            <option value="auto">Automático</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="preference-group">
                                    <h4>Localização</h4>
                                    <div class="preference-option">
                                        <div class="preference-info">
                                            <strong>Compartilhar localização</strong>
                                            <p>Ajuda a encontrar produtores próximos</p>
                                        </div>
                                        <div class="toggle-switch">
                                            <input type="checkbox" id="shareLocation" onchange="toggleLocationSharing()">
                                            <label for="shareLocation"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="preference-group">
                                    <h4>Privacidade</h4>
                                    <div class="preference-option">
                                        <div class="preference-info">
                                            <strong>Perfil público</strong>
                                            <p>Permite que outros usuários vejam seu perfil</p>
                                        </div>
                                        <div class="toggle-switch">
                                            <input type="checkbox" id="publicProfile" checked onchange="togglePublicProfile()">
                                            <label for="publicProfile"></label>
                                        </div>
                                    </div>

                                    <div class="preference-option">
                                        <div class="preference-info">
                                            <strong>Mostrar avaliações</strong>
                                            <p>Exibe suas avaliações no perfil público</p>
                                        </div>
                                        <div class="toggle-switch">
                                            <input type="checkbox" id="showReviews" checked onchange="toggleShowReviews()">
                                            <label for="showReviews"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-primary" onclick="savePreferences()">Salvar Preferências</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Modals -->
    <!-- Add Address Modal -->
    <div class="modal hidden" id="addAddressModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Novo Endereço</h3>
                <button class="close-btn" onclick="closeModal('addAddressModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addressForm">
                    <div class="form-group">
                        <label for="addressName">Nome do Endereço *</label>
                        <input type="text" id="addressName" name="addressName" required placeholder="Ex: Casa, Trabalho">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="zipCode">CEP *</label>
                            <input type="text" id="zipCode" name="zipCode" required placeholder="00000-000" onblur="searchZipCode()">
                        </div>
                        <div class="form-group">
                            <label for="street">Rua *</label>
                            <input type="text" id="street" name="street" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="number">Número *</label>
                            <input type="text" id="number" name="number" required>
                        </div>
                        <div class="form-group">
                            <label for="complement">Complemento</label>
                            <input type="text" id="complement" name="complement" placeholder="Apto, Bloco, etc.">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="neighborhood">Bairro *</label>
                            <input type="text" id="neighborhood" name="neighborhood" required>
                        </div>
                        <div class="form-group">
                            <label for="city">Cidade *</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="state">Estado *</label>
                        <select id="state" name="state" required>
                            <option value="">Selecione...</option>
                            <option value="SP">São Paulo</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="MG">Minas Gerais</option>
                            <!-- Add more states -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-container">
                            <input type="checkbox" id="isDefault" name="isDefault">
                            <span class="checkmark"></span>
                            Definir como endereço padrão
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addAddressModal')">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveAddress()">Salvar Endereço</button>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal hidden" id="changePasswordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Alterar Senha</h3>
                <button class="close-btn" onclick="closeModal('changePasswordModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="passwordForm">
                    <div class="form-group">
                        <label for="currentPassword">Senha Atual *</label>
                        <input type="password" id="currentPassword" name="currentPassword" required>
                    </div>

                    <div class="form-group">
                        <label for="newPassword">Nova Senha *</label>
                        <input type="password" id="newPassword" name="newPassword" required minlength="8">
                        <small class="form-help">Mínimo 8 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirmar Nova Senha *</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                    </div>

                    <div class="password-strength" id="passwordStrength">
                        <div class="strength-bar">
                            <div class="strength-fill"></div>
                        </div>
                        <span class="strength-text">Digite uma senha</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('changePasswordModal')">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="changePassword()">Alterar Senha</button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay hidden" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Salvando alterações...</p>
        </div>
    </div>

    <script src="../js/perfil-script.js"></script>
</body>
</html>