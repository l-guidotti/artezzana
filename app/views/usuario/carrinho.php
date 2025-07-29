<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Artezzana</title>
    <link rel="stylesheet" href="../css/carrinho-styles.css">
    <link rel="stylesheet" href="../css/global.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php include 'header_comprador.php'; ?>

    <main class="main-content">
        <section class="breadcrumb">
            <div class="container">
                <nav class="breadcrumb-nav">
                    <a href="./dashboard_comprador.php">Início</a> <span>›</span>
                    <span>Carrinho</span>
                </nav>
            </div>
        </section>

        <section class="cart-section">
            <div class="container">
                <div class="cart-header">
                    <h1>Seu Carrinho</h1>
                    <div class="cart-count" id="cartCount"><?= $total_itens ?> item(s)</div>
                </div>

                <div class="empty-cart <?= $carrinho_vazio ? '' : 'hidden' ?>" id="emptyCart">
                    <div class="empty-cart-icon">🛒</div>
                    <h2>Seu carrinho está vazio</h2>
                    <p>Que tal explorar nossos produtos frescos e artesanatos únicos?</p>
                    <button class="btn btn-primary" onclick="goToProducts()">Explorar Produtos</button>
                </div>

                <div class="cart-content <?= $carrinho_vazio ? 'hidden' : '' ?>" id="cartContent">
                    <div class="cart-layout">
                        <div class="cart-items">
                            <div class="cart-items-header">
                                <h2>Itens no Carrinho</h2>
                                <button class="clear-cart-btn" onclick="clearCart()">Limpar Carrinho</button>
                            </div>

                            <div class="cart-groups" id="cartGroups">
                                <?php if (!empty($carrinho_por_produtor)): ?>
                                    <?php foreach ($carrinho_por_produtor as $produtor_id => $produtor_data): ?>
                                        <div class="producer-group">
                                            <h3>Vendido por: <?= htmlspecialchars($produtor_data['nome_produtor']) ?></h3>
                                            <div class="producer-items">
                                                <?php foreach ($produtor_data['itens'] as $item): ?>
                                                    <div class="cart-item-card" data-product-id="<?= htmlspecialchars($item['produto_id']) ?>">
                                                        <img src="<?= htmlspecialchars($item['imagem'] ?? '../assets/images/placeholder_produto.png') ?>" alt="<?= htmlspecialchars($item['nome_produto']) ?>">
                                                        <div class="item-details">
                                                            <h4><?= htmlspecialchars($item['nome_produto']) ?></h4>
                                                            <p>Preço unitário: R$ <?= number_format($item['preco'], 2, ',', '.') ?> / <?= htmlspecialchars($item['unidade_medida']) ?></p>
                                                            <div class="item-quantity-controls">
                                                                <button class="quantity-btn decrease-qty" data-product-id="<?= htmlspecialchars($item['produto_id']) ?>">-</button>
                                                                <input type="number" class="item-qty-input" value="<?= htmlspecialchars($item['quantidade']) ?>" min="1" max="<?= htmlspecialchars($item['estoque_disponivel']) ?>" data-product-id="<?= htmlspecialchars($item['produto_id']) ?>">
                                                                <button class="quantity-btn increase-qty" data-product-id="<?= htmlspecialchars($item['produto_id']) ?>">+</button>
                                                            </div>
                                                        </div>
                                                        <div class="item-price-remove"> 
                                                            <span class="item-total-price">R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></span>
                                                            <button class="remove-item-btn" data-product-id="<?= htmlspecialchars($item['produto_id']) ?>">Remover</button>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="producer-subtotal">
                                                <span>Subtotal do Produtor:</span>
                                                <span>R$ <?= number_format($produtor_data['subtotal_produtor'], 2, ',', '.') ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Nenhum item no carrinho.</p>
                                <?php endif; ?>
                            </div>

                            <div class="coupon-section">
                                <h3>Cupom de Desconto</h3>
                                <div class="coupon-input">
                                    <input type="text" id="couponCode" placeholder="Digite seu cupom">
                                    <button class="btn btn-secondary" onclick="applyCoupon()">Aplicar</button>
                                </div>
                                <div class="applied-coupon hidden" id="appliedCoupon">
                                    <span class="coupon-info">
                                        <span id="couponName">DESCONTO10</span>
                                        <span id="couponDiscount">-R$ 5,00</span>
                                    </span>
                                    <button class="remove-coupon" onclick="removeCoupon()">&times;</button>
                                </div>
                            </div>
                        </div>

                        <div class="order-summary">
                            <div class="summary-card">
                                <h3>Resumo do Pedido</h3>

                                <div class="summary-line">
                                    <span>Subtotal</span>
                                    <span id="subtotal">R$ <?= number_format($subtotal_carrinho, 2, ',', '.') ?></span>
                                </div>

                                <div class="summary-line">
                                    <span>Taxa de entrega</span>
                                    <span id="deliveryFee">R$ 0,00</span> </div>

                                <div class="summary-line discount hidden" id="discountLine">
                                    <span>Desconto</span>
                                    <span id="discountAmount">-R$ 0,00</span>
                                </div>

                                <hr>

                                <div class="summary-total">
                                    <span>Total</span>
                                    <span id="totalAmount">R$ <?= number_format($subtotal_carrinho, 2, ',', '.') ?></span>
                                </div>

                                <div class="delivery-options">
                                    <h4>Opções de Entrega</h4>
                                    <div class="delivery-option">
                                        <label class="radio-container">
                                            <input type="radio" name="delivery" value="pickup" checked
                                                onchange="updateDeliveryFee()">
                                            <span class="radio-checkmark"></span>
                                            <div class="delivery-info">
                                                <strong>Retirada no Local</strong>
                                                <small>Grátis - Combine com o produtor</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="delivery-option">
                                        <label class="radio-container">
                                            <input type="radio" name="delivery" value="delivery"
                                                onchange="updateDeliveryFee()">
                                            <span class="radio-checkmark"></span>
                                            <div class="delivery-info">
                                                <strong>Entrega em Casa</strong>
                                                <small>Taxa varia por produtor</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="payment-methods">
                                    <h4>Forma de Pagamento</h4>
                                    <div class="payment-option">
                                        <label class="radio-container">
                                            <input type="radio" name="payment" value="pix" checked>
                                            <span class="radio-checkmark"></span>
                                            <div class="payment-info">
                                                <strong>PIX</strong>
                                                <small>Pagamento instantâneo</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="payment-option">
                                        <label class="radio-container">
                                            <input type="radio" name="payment" value="money">
                                            <span class="radio-checkmark"></span>
                                            <div class="payment-info">
                                                <strong>Dinheiro</strong>
                                                <small>Pagamento na entrega/retirada</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="payment-option">
                                        <label class="radio-container">
                                            <input type="radio" name="payment" value="card">
                                            <span class="radio-checkmark"></span>
                                            <div class="payment-info">
                                                <strong>Cartão</strong>
                                                <small>Débito ou crédito</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <button class="btn btn-primary btn-checkout" onclick="proceedToCheckout()"
                                    id="checkoutBtn">
                                    Finalizar Pedido
                                </button>

                                <div class="security-info">
                                    <small>🔒 Seus dados estão protegidos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="recommended-section">
            <div class="container">
                <h3>Você também pode gostar</h3>
                <div class="recommended-products" id="recommendedProducts">
                    </div>
            </div>
        </section>
    </main>

    <div class="modal hidden" id="removeItemModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Remover Item</h3>
                <button class="close-btn" onclick="closeModal('removeItemModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja remover este item do carrinho?</p>
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="closeModal('removeItemModal')">Cancelar</button>
                    <button class="btn btn-primary" onclick="confirmRemoveItem()">Remover</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal hidden" id="checkoutSuccessModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Pedido Realizado!</h3>
            </div>
            <div class="modal-body">
                <div class="success-icon">✅</div>
                <h4>Seu pedido foi enviado com sucesso!</h4>
                <p>Os produtores foram notificados e entrarão em contato em breve para confirmar os detalhes.</p>
                <div class="order-number">
                    <strong>Número do pedido: <span id="orderNumber">#12345</span></strong>
                </div>
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="goToOrders()">Ver Meus Pedidos</button>
                    <button class="btn btn-primary" onclick="goToHome()">Continuar Comprando</button>
                </div>
            </div>
        </div>
    </div>

    <div class="loading-overlay hidden" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Processando seu pedido...</p>
        </div>
    </div>


    <?php include 'footer_comprador.php'; ?>

    <script src="../js/carrinho-script.js"></script>
    <script>
        // Funções JavaScript básicas (se não estiverem em global.js ou dashboard-script.js)
        function toggleUserDropdown() {
            document.getElementById('userDropdown').classList.toggle('hidden');
        }
        function goToHome() {
            window.location.href = '../index.php'; // Caminho para a página principal
        }
        function goToProducts() {
            window.location.href = 'produtos.php'; // Ou o link correto para sua página de produtos
        }
        function goToOrders() {
            alert('Meus Pedidos em desenvolvimento!'); // Implementar redirecionamento real
        }
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // --- Funções JavaScript para o Carrinho (mover para carrinho-script.js) ---
        // (Serão detalhadas na próxima resposta para serem movidas para carrinho-script.js)
    </script>
</body>

</html>