// Global variables
let cart = [];
let currentUser = null;
let appliedCoupon = null;
let itemToRemove = null;

// Sample cart data
const sampleCartItems = [
    {
        id: 1,
        name: "Tomates Orgânicos",
        description: "Tomates frescos cultivados sem agrotóxicos",
        price: 8.00,
        quantity: 2,
        unit: "kg",
        producer: {
            id: 1,
            name: "Sítio da Dona Maria",
            location: "Zona Rural - São Paulo",
            deliveryFee: 5.00
        },
        image: "/placeholder.svg?height=80&width=80"
    },
    {
        id: 2,
        name: "Alface Crespa",
        description: "Alface fresca e crocante",
        price: 4.00,
        quantity: 1,
        unit: "unidade",
        producer: {
            id: 1,
            name: "Sítio da Dona Maria",
            location: "Zona Rural - São Paulo",
            deliveryFee: 5.00
        },
        image: "/placeholder.svg?height=80&width=80"
    },
    {
        id: 3,
        name: "Vaso de Cerâmica",
        description: "Vaso artesanal feito em cerâmica tradicional",
        price: 45.00,
        quantity: 1,
        unit: "unidade",
        producer: {
            id: 2,
            name: "Artesanato do João",
            location: "Centro Histórico - Minas Gerais",
            deliveryFee: 8.00
        },
        image: "/placeholder.svg?height=80&width=80"
    }
];

const recommendedProducts = [
    {
        id: 4,
        name: "Mel Silvestre",
        description: "Mel puro extraído de colmeias naturais",
        price: 25.00,
        producer: "Apiário São José",
        image: "/placeholder.svg?height=150&width=250"
    },
    {
        id: 5,
        name: "Queijo Minas",
        description: "Queijo artesanal curado por 30 dias",
        price: 35.00,
        producer: "Fazenda Esperança",
        image: "/placeholder.svg?height=150&width=250"
    },
    {
        id: 6,
        name: "Tempero Caseiro",
        description: "Mix de ervas desidratadas da horta",
        price: 12.00,
        producer: "Sítio da Dona Maria",
        image: "/placeholder.svg?height=150&width=250"
    }
];

// Available coupons
const availableCoupons = {
    'DESCONTO10': { name: 'DESCONTO10', discount: 5.00, type: 'fixed' },
    'PRIMEIRA15': { name: 'PRIMEIRA15', discount: 15, type: 'percentage' },
    'FRETE5': { name: 'FRETE5', discount: 5.00, type: 'delivery' }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadUserData();
    loadCart();
    setupEventListeners();
    loadRecommendedProducts();
});

// Load user data
function loadUserData() {
    const userData = localStorage.getItem('artezzana_user');
    if (userData) {
        currentUser = JSON.parse(userData);
        updateUserInfo();
    } else {
        window.location.href = 'login.html';
    }
}

// Update user info in header
function updateUserInfo() {
    const userName = document.getElementById('userName');
    const userInitials = document.getElementById('userInitials');
    
    const initials = `${currentUser.firstName.charAt(0)}${currentUser.lastName.charAt(0)}`.toUpperCase();
    
    userName.textContent = currentUser.firstName;
    userInitials.textContent = initials;
}

// Load cart from localStorage or use sample data
function loadCart() {
    const savedCart = localStorage.getItem('artezzana_cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
    } else {
        // Use sample data for demonstration
        cart = sampleCartItems;
        saveCart();
    }
    
    renderCart();
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('artezzana_cart', JSON.stringify(cart));
}

// Render cart
function renderCart() {
    const cartContent = document.getElementById('cartContent');
    const emptyCart = document.getElementById('emptyCart');
    const cartCount = document.getElementById('cartCount');
    
    if (cart.length === 0) {
        cartContent.classList.add('hidden');
        emptyCart.classList.remove('hidden');
        cartCount.textContent = '0 itens';
        return;
    }
    
    emptyCart.classList.add('hidden');
    cartContent.classList.remove('hidden');
    
    // Update cart count
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = `${totalItems} ${totalItems === 1 ? 'item' : 'itens'}`;
    
    // Group items by producer
    const groupedItems = groupItemsByProducer();
    renderCartGroups(groupedItems);
    updateOrderSummary();
}

// Group cart items by producer
function groupItemsByProducer() {
    const groups = {};
    
    cart.forEach(item => {
        const producerId = item.producer.id;
        if (!groups[producerId]) {
            groups[producerId] = {
                producer: item.producer,
                items: []
            };
        }
        groups[producerId].items.push(item);
    });
    
    return Object.values(groups);
}

// Render cart groups
function renderCartGroups(groups) {
    const container = document.getElementById('cartGroups');
    container.innerHTML = '';
    
    groups.forEach(group => {
        const groupElement = createCartGroup(group);
        container.appendChild(groupElement);
    });
}

// Create cart group element
function createCartGroup(group) {
    const groupDiv = document.createElement('div');
    groupDiv.className = 'cart-group fade-in';
    
    const producerInitials = group.producer.name.split(' ').map(word => word.charAt(0)).join('').substring(0, 2);
    
    groupDiv.innerHTML = `
        <div class="cart-group-header">
            <div class="producer-avatar">${producerInitials}</div>
            <div class="producer-info">
                <h4>${group.producer.name}</h4>
                <p>${group.producer.location}</p>
            </div>
        </div>
        <div class="cart-group-items">
            ${group.items.map(item => createCartItemHTML(item)).join('')}
        </div>
    `;
    
    return groupDiv;
}

// Create cart item HTML
function createCartItemHTML(item) {
    return `
        <div class="cart-item" data-item-id="${item.id}">
            <img src="${item.image}" alt="${item.name}" class="item-image">
            <div class="item-info">
                <h4>${item.name}</h4>
                <p>${item.description}</p>
                <div class="item-price">R$ ${item.price.toFixed(2)}/${item.unit}</div>
            </div>
            <div class="item-controls">
                <div class="quantity-control">
                    <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity - 1})" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                    <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="99" onchange="updateQuantity(${item.id}, this.value)">
                    <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                </div>
                <button class="remove-item-btn" onclick="showRemoveItemModal(${item.id})" title="Remover item">
                    🗑️
                </button>
            </div>
        </div>
    `;
}

// Update item quantity
function updateQuantity(itemId, newQuantity) {
    newQuantity = parseInt(newQuantity);
    
    if (newQuantity < 1) {
        showRemoveItemModal(itemId);
        return;
    }
    
    if (newQuantity > 99) {
        newQuantity = 99;
    }
    
    const itemIndex = cart.findIndex(item => item.id === itemId);
    if (itemIndex !== -1) {
        cart[itemIndex].quantity = newQuantity;
        saveCart();
        renderCart();
    }
}

// Show remove item modal
function showRemoveItemModal(itemId) {
    itemToRemove = itemId;
    document.getElementById('removeItemModal').classList.remove('hidden');
}

// Confirm remove item
function confirmRemoveItem() {
    if (itemToRemove) {
        const itemIndex = cart.findIndex(item => item.id === itemToRemove);
        if (itemIndex !== -1) {
            cart.splice(itemIndex, 1);
            saveCart();
            renderCart();
        }
        itemToRemove = null;
    }
    closeModal('removeItemModal');
}

// Clear entire cart
function clearCart() {
    if (confirm('Tem certeza que deseja limpar todo o carrinho?')) {
        cart = [];
        saveCart();
        renderCart();
    }
}

// Update order summary
function updateOrderSummary() {
    const subtotal = calculateSubtotal();
    const deliveryFee = calculateDeliveryFee();
    const discountAmount = calculateDiscount(subtotal);
    const total = subtotal + deliveryFee - discountAmount;
    
    document.getElementById('subtotal').textContent = `R$ ${subtotal.toFixed(2)}`;
    document.getElementById('deliveryFee').textContent = `R$ ${deliveryFee.toFixed(2)}`;
    document.getElementById('totalAmount').textContent = `R$ ${total.toFixed(2)}`;
    
    // Update discount display
    const discountLine = document.getElementById('discountLine');
    const discountAmountElement = document.getElementById('discountAmount');
    
    if (discountAmount > 0) {
        discountLine.classList.remove('hidden');
        discountAmountElement.textContent = `-R$ ${discountAmount.toFixed(2)}`;
    } else {
        discountLine.classList.add('hidden');
    }
    
    // Enable/disable checkout button
    const checkoutBtn = document.getElementById('checkoutBtn');
    checkoutBtn.disabled = cart.length === 0;
}

// Calculate subtotal
function calculateSubtotal() {
    return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
}

// Calculate delivery fee
function calculateDeliveryFee() {
    const deliveryType = document.querySelector('input[name="delivery"]:checked').value;
    
    if (deliveryType === 'pickup') {
        return 0;
    }
    
    // Get unique producers and sum their delivery fees
    const producers = [...new Set(cart.map(item => item.producer.id))];
    return producers.reduce((sum, producerId) => {
        const item = cart.find(item => item.producer.id === producerId);
        return sum + (item ? item.producer.deliveryFee : 0);
    }, 0);
}

// Calculate discount
function calculateDiscount(subtotal) {
    if (!appliedCoupon) return 0;
    
    const coupon = availableCoupons[appliedCoupon];
    if (!coupon) return 0;
    
    switch (coupon.type) {
        case 'fixed':
            return coupon.discount;
        case 'percentage':
            return (subtotal * coupon.discount) / 100;
        case 'delivery':
            return Math.min(coupon.discount, calculateDeliveryFee());
        default:
            return 0;
    }
}

// Update delivery fee when delivery option changes
function updateDeliveryFee() {
    updateOrderSummary();
}

// Apply coupon
function applyCoupon() {
    const couponCode = document.getElementById('couponCode').value.trim().toUpperCase();
    
    if (!couponCode) {
        alert('Por favor, digite um código de cupom.');
        return;
    }
    
    if (availableCoupons[couponCode]) {
        appliedCoupon = couponCode;
        
        // Update UI
        document.querySelector('.coupon-input').style.display = 'none';
        const appliedCouponElement = document.getElementById('appliedCoupon');
        appliedCouponElement.classList.remove('hidden');
        
        document.getElementById('couponName').textContent = couponCode;
        
        // Calculate and display discount
        const discount = calculateDiscount(calculateSubtotal());
        document.getElementById('couponDiscount').textContent = `-R$ ${discount.toFixed(2)}`;
        
        updateOrderSummary();
        
        // Show success message
        showToast('Cupom aplicado com sucesso!', 'success');
    } else {
        showToast('Cupom inválido ou expirado.', 'error');
    }
    
    document.getElementById('couponCode').value = '';
}

// Remove coupon
function removeCoupon() {
    appliedCoupon = null;
    
    // Update UI
    document.querySelector('.coupon-input').style.display = 'flex';
    document.getElementById('appliedCoupon').classList.add('hidden');
    
    updateOrderSummary();
    showToast('Cupom removido.', 'info');
}

// Proceed to checkout
function proceedToCheckout() {
    if (cart.length === 0) {
        alert('Seu carrinho está vazio.');
        return;
    }
    
    // Show loading
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Simulate checkout process
    setTimeout(() => {
        // Generate order number
        const orderNumber = Math.floor(Math.random() * 100000) + 10000;
        document.getElementById('orderNumber').textContent = `#${orderNumber}`;
        
        // Clear cart
        cart = [];
        saveCart();
        
        // Hide loading and show success
        document.getElementById('loadingOverlay').classList.add('hidden');
        document.getElementById('checkoutSuccessModal').classList.remove('hidden');
        
        // Send order data to producers (in real app, this would be an API call)
        console.log('Order placed:', {
            orderNumber,
            user: currentUser,
            items: cart,
            total: document.getElementById('totalAmount').textContent,
            delivery: document.querySelector('input[name="delivery"]:checked').value,
            payment: document.querySelector('input[name="payment"]:checked').value,
            coupon: appliedCoupon
        });
        
    }, 2000);
}

// Load recommended products
function loadRecommendedProducts() {
    const container = document.getElementById('recommendedProducts');
    container.innerHTML = '';
    
    recommendedProducts.forEach(product => {
        const productElement = createRecommendedProduct(product);
        container.appendChild(productElement);
    });
}

// Create recommended product element
function createRecommendedProduct(product) {
    const productDiv = document.createElement('div');
    productDiv.className = 'recommended-product fade-in';
    productDiv.innerHTML = `
        <img src="${product.image}" alt="${product.name}">
        <div class="recommended-product-info">
            <h4>${product.name}</h4>
            <p>${product.description}</p>
            <div class="recommended-product-price">R$ ${product.price.toFixed(2)}</div>
        </div>
    `;
    
    productDiv.addEventListener('click', () => {
        addToCart(product);
    });
    
    return productDiv;
}

// Add product to cart
function addToCart(product) {
    const existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        // For recommended products, we need to add producer info
        const newItem = {
            ...product,
            quantity: 1,
            unit: 'unidade',
            producer: {
                id: 1,
                name: product.producer,
                location: 'Localização do produtor',
                deliveryFee: 5.00
            }
        };
        cart.push(newItem);
    }
    
    saveCart();
    renderCart();
    showToast(`${product.name} adicionado ao carrinho!`, 'success');
}

// Setup event listeners
function setupEventListeners() {
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.user-menu')) {
            document.getElementById('userDropdown').classList.add('hidden');
        }
    });
    
    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.classList.add('hidden');
        }
    });
    
    // Coupon input enter key
    document.getElementById('couponCode').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyCoupon();
        }
    });
}

// Navigation functions
function goToHome() {
    window.location.href = 'dashboard.html';
}

function goToProducts() {
    window.location.href = 'dashboard.html';
}

function goToOrders() {
    alert('Navegando para meus pedidos...');
}

// User menu functions
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('hidden');
}

function showProfile() {
    alert('Funcionalidade de perfil em desenvolvimento...');
    toggleUserDropdown();
}

function showSettings() {
    alert('Funcionalidade de configurações em desenvolvimento...');
    toggleUserDropdown();
}

function logout() {
    if (confirm('Tem certeza que deseja sair?')) {
        localStorage.removeItem('artezzana_user');
        localStorage.removeItem('artezzana_cart');
        window.location.href = 'login.html';
    }
}

// Modal functions
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Mobile menu toggle
function toggleMobileMenu() {
    const nav = document.querySelector('.nav');
    const btn = document.querySelector('.mobile-menu-btn');
    
    nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
    btn.classList.toggle('active');
}

// Toast notification system
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
    `;
    
    // Add toast styles if not already added
    if (!document.querySelector('#toast-styles')) {
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.textContent = `
            .toast {
                position: fixed;
                top: 100px;
                right: 20px;
                background: white;
                border-radius: 8px;
                padding: 1rem;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
                display: flex;
                align-items: center;
                gap: 1rem;
                max-width: 300px;
                animation: slideInRight 0.3s ease-out;
            }
            .toast-success { border-left: 4px solid #22c55e; }
            .toast-error { border-left: 4px solid #ef4444; }
            .toast-info { border-left: 4px solid #3b82f6; }
            .toast button {
                background: none;
                border: none;
                font-size: 1.2rem;
                cursor: pointer;
                color: #6b7280;
            }
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Add to page
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 3000);
}

// Initialize cart count in header (if needed for other pages)
function updateCartCount() {
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartBadge.textContent = totalItems;
        cartBadge.style.display = totalItems > 0 ? 'block' : 'none';
    }
}

// Auto-save cart periodically
setInterval(() => {
    if (cart.length > 0) {
        saveCart();
    }
}, 30000); // Save every 30 seconds

// Handle page visibility change (save cart when user leaves)
document.addEventListener('visibilitychange', function() {
    if (document.visibilityState === 'hidden') {
        saveCart();
    }
});

// Initialize animations
document.addEventListener('DOMContentLoaded', function() {
    // Add intersection observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    setTimeout(() => {
        const animatedElements = document.querySelectorAll('.cart-group, .recommended-product');
        animatedElements.forEach(el => {
            observer.observe(el);
        });
    }, 100);
});