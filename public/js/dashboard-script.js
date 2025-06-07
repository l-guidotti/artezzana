// Global variables
let currentUser = null;
let userType = 'cliente';

// Sample data
const sampleProducts = [
    {
        id: 1,
        name: "Tomates Orgânicos",
        description: "Tomates frescos cultivados sem agrotóxicos",
        price: "R$ 8,00/kg",
        producer: "Sítio da Dona Maria",
        category: "frutas-verduras",
        image: "/placeholder.svg?height=200&width=280",
        rating: 4.8
    },
    {
        id: 2,
        name: "Vaso de Cerâmica",
        description: "Vaso artesanal feito em cerâmica tradicional",
        price: "R$ 45,00",
        producer: "Artesanato do João",
        category: "artesanato",
        image: "/placeholder.svg?height=200&width=280",
        rating: 4.9
    },
    {
        id: 3,
        name: "Mel Silvestre",
        description: "Mel puro extraído de colmeias naturais",
        price: "R$ 25,00",
        producer: "Apiário São José",
        category: "doces-conservas",
        image: "/placeholder.svg?height=200&width=280",
        rating: 4.7
    }
];

const sampleOrders = [
    {
        id: 1,
        items: "Tomates Orgânicos, Alface",
        producer: "Sítio da Dona Maria",
        total: "R$ 15,00",
        status: "confirmed",
        date: "2024-01-15"
    },
    {
        id: 2,
        items: "Vaso de Cerâmica",
        producer: "Artesanato do João",
        total: "R$ 45,00",
        status: "pending",
        date: "2024-01-14"
    }
];

const sampleProducers = [
    {
        id: 1,
        name: "Sítio da Dona Maria",
        description: "Especializada em hortaliças orgânicas",
        location: "5 km de distância",
        image: "/placeholder.svg?height=150&width=150",
        rating: 4.8,
        products: 12
    },
    {
        id: 2,
        name: "Artesanato do João",
        description: "Peças únicas em madeira e cerâmica",
        location: "8 km de distância",
        image: "/placeholder.svg?height=150&width=150",
        rating: 4.9,
        products: 8
    }
];

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    loadUserData();
    setupNavigation();
    loadDashboardContent();
    setupEventListeners();
});

// Load user data from localStorage
function loadUserData() {
    const userData = localStorage.getItem('artezzana_user');
    if (userData) {
        currentUser = JSON.parse(userData);
        userType = currentUser.userType || 'cliente';
        
        // Update user info in header
        updateUserInfo();
    } else {
        // Redirect to login if no user data
        window.location.href = 'login.html';
    }
}

// Update user info in header
function updateUserInfo() {
    const userName = document.getElementById('userName');
    const userInitials = document.getElementById('userInitials');
    const welcomeTitle = document.getElementById('welcomeTitle');
    const welcomeSubtitle = document.getElementById('welcomeSubtitle');
    
    const fullName = `${currentUser.firstName} ${currentUser.lastName}`;
    const initials = `${currentUser.firstName.charAt(0)}${currentUser.lastName.charAt(0)}`.toUpperCase();
    
    userName.textContent = currentUser.firstName;
    userInitials.textContent = initials;
    
    if (userType === 'produtor') {
        welcomeTitle.textContent = `Olá, ${currentUser.firstName}!`;
        welcomeSubtitle.textContent = 'Gerencie seus produtos e acompanhe suas vendas';
    } else {
        welcomeTitle.textContent = `Bem-vindo, ${currentUser.firstName}!`;
        welcomeSubtitle.textContent = 'Descubra produtos frescos e artesanatos únicos';
    }
}

// Setup navigation based on user type
function setupNavigation() {
    const nav = document.getElementById('mainNav');
    const quickActions = document.getElementById('quickActions');
    
    if (userType === 'produtor') {
        nav.innerHTML = `
            <a href="#" class="nav-link active" onclick="showDashboard()">Dashboard</a>
            <a href="#" class="nav-link" onclick="showProducts()">Meus Produtos</a>
            <a href="#" class="nav-link" onclick="showOrders()">Pedidos</a>
            <a href="#" class="nav-link" onclick="showAnalytics()">Relatórios</a>
        `;
        
        quickActions.innerHTML = `
            <a href="#" class="quick-action-btn" onclick="addNewProduct()">+ Novo Produto</a>
            <a href="#" class="quick-action-btn" onclick="manageOrders()">Gerenciar Pedidos</a>
        `;
    } else {
        nav.innerHTML = `
            <a href="#" class="nav-link active" onclick="showDashboard()">Início</a>
            <a href="#" class="nav-link" onclick="showProducts()">Produtos</a>
            <a href="#" class="nav-link" onclick="showProducers()">Produtores</a>
            <a href="#" class="nav-link" onclick="showOrders()">Meus Pedidos</a>
        `;
        
        quickActions.innerHTML = `
            <a href="#" class="quick-action-btn" onclick="exploreProducts()">Explorar Produtos</a>
            <a href="#" class="quick-action-btn" onclick="findProducers()">Encontrar Produtores</a>
        `;
    }
}

// Load dashboard content based on user type
function loadDashboardContent() {
    const clientDashboard = document.getElementById('clientDashboard');
    const producerDashboard = document.getElementById('producerDashboard');
    
    if (userType === 'produtor') {
        clientDashboard.classList.add('hidden');
        producerDashboard.classList.remove('hidden');
        loadProducerDashboard();
    } else {
        producerDashboard.classList.add('hidden');
        clientDashboard.classList.remove('hidden');
        loadClientDashboard();
    }
}

// Load client dashboard content
function loadClientDashboard() {
    loadRecommendedProducts();
    loadRecentOrders();
    loadNearbyProducers();
}

// Load producer dashboard content
function loadProducerDashboard() {
    loadProducerStats();
    loadProducerOrders();
    loadTopProducts();
}

// Load recommended products for clients
function loadRecommendedProducts() {
    const container = document.getElementById('recommendedProducts');
    container.innerHTML = '';
    
    sampleProducts.forEach(product => {
        const productCard = createProductCard(product);
        container.appendChild(productCard);
    });
}

// Create product card element
function createProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card fade-in';
    card.innerHTML = `
        <img src="${product.image}" alt="${product.name}" />
        <div class="product-info">
            <h4>${product.name}</h4>
            <p>${product.description}</p>
            <div class="product-price">${product.price}</div>
            <div class="product-producer">Por: ${product.producer}</div>
        </div>
    `;
    
    card.addEventListener('click', () => {
        showProductDetails(product);
    });
    
    return card;
}

// Load recent orders for clients
function loadRecentOrders() {
    const container = document.getElementById('recentOrdersList');
    container.innerHTML = '';
    
    if (sampleOrders.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <p>Você ainda não fez nenhum pedido.</p>
                <button class="btn btn-primary" onclick="exploreProducts()">Explorar Produtos</button>
            </div>
        `;
        return;
    }
    
    sampleOrders.forEach(order => {
        const orderCard = createOrderCard(order);
        container.appendChild(orderCard);
    });
}

// Create order card element
function createOrderCard(order) {
    const card = document.createElement('div');
    card.className = 'order-card fade-in';
    
    const statusText = {
        'pending': 'Pendente',
        'confirmed': 'Confirmado',
        'delivered': 'Entregue'
    };
    
    card.innerHTML = `
        <div class="order-info">
            <h4>Pedido #${order.id}</h4>
            <p>${order.items} - ${order.producer}</p>
            <small>${formatDate(order.date)}</small>
        </div>
        <div class="order-details">
            <div class="order-total">${order.total}</div>
            <div class="order-status ${order.status}">${statusText[order.status]}</div>
        </div>
    `;
    
    card.addEventListener('click', () => {
        showOrderDetails(order);
    });
    
    return card;
}

// Load nearby producers
function loadNearbyProducers() {
    const container = document.getElementById('nearbyProducers');
    container.innerHTML = '';
    
    sampleProducers.forEach(producer => {
        const producerCard = createProducerCard(producer);
        container.appendChild(producerCard);
    });
}

// Create producer card element
function createProducerCard(producer) {
    const card = document.createElement('div');
    card.className = 'producer-card fade-in';
    card.innerHTML = `
        <img src="${producer.image}" alt="${producer.name}" />
        <h4>${producer.name}</h4>
        <p>${producer.description}</p>
        <div class="producer-meta">
            <span>⭐ ${producer.rating}</span>
            <span>📦 ${producer.products} produtos</span>
        </div>
        <div class="producer-location">${producer.location}</div>
    `;
    
    card.addEventListener('click', () => {
        showProducerProfile(producer);
    });
    
    return card;
}

// Load producer statistics
function loadProducerStats() {
    // Simulate loading stats
    setTimeout(() => {
        document.getElementById('totalProducts').textContent = '12';
        document.getElementById('totalOrders').textContent = '28';
        document.getElementById('totalRevenue').textContent = 'R$ 1.240';
        document.getElementById('averageRating').textContent = '4.8';
    }, 500);
}

// Load producer orders
function loadProducerOrders() {
    const container = document.getElementById('producerOrdersTable');
    container.innerHTML = `
        <div class="table-header">
            <div>Cliente</div>
            <div>Produto</div>
            <div>Valor</div>
            <div>Status</div>
            <div>Data</div>
        </div>
    `;
    
    // Sample producer orders
    const producerOrders = [
        { client: 'Maria Silva', product: 'Tomates Orgânicos', value: 'R$ 16,00', status: 'pending', date: '2024-01-15' },
        { client: 'João Santos', product: 'Alface Crespa', value: 'R$ 8,00', status: 'confirmed', date: '2024-01-14' },
        { client: 'Ana Costa', product: 'Cenoura', value: 'R$ 12,00', status: 'delivered', date: '2024-01-13' }
    ];
    
    producerOrders.forEach(order => {
        const row = document.createElement('div');
        row.className = 'table-row';
        
        const statusText = {
            'pending': 'Pendente',
            'confirmed': 'Confirmado',
            'delivered': 'Entregue'
        };
        
        row.innerHTML = `
            <div>${order.client}</div>
            <div>${order.product}</div>
            <div>${order.value}</div>
            <div><span class="order-status ${order.status}">${statusText[order.status]}</span></div>
            <div>${formatDate(order.date)}</div>
        `;
        
        container.appendChild(row);
    });
}

// Load top products for producer
function loadTopProducts() {
    const container = document.getElementById('topProducts');
    container.innerHTML = '';
    
    const topProducts = [
        { name: 'Tomates Orgânicos', sales: 45, revenue: 'R$ 360,00' },
        { name: 'Alface Crespa', sales: 32, revenue: 'R$ 256,00' },
        { name: 'Cenoura Baby', sales: 28, revenue: 'R$ 336,00' }
    ];
    
    topProducts.forEach((product, index) => {
        const item = document.createElement('div');
        item.className = 'performance-item fade-in';
        item.innerHTML = `
            <div class="performance-rank">#${index + 1}</div>
            <div class="performance-info">
                <h4>${product.name}</h4>
                <p>${product.sales} vendas • ${product.revenue}</p>
            </div>
        `;
        container.appendChild(item);
    });
}

// Event listeners
function setupEventListeners() {
    // Search functionality
    const searchInput = document.getElementById('dashboardSearch');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performDashboardSearch();
            }
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.user-menu')) {
            document.getElementById('userDropdown').classList.add('hidden');
            document.querySelector('.user-info').classList.remove('active');
        }
    });
}

// Navigation functions
function showDashboard() {
    updateActiveNav('Dashboard');
    // Reload dashboard content
    loadDashboardContent();
}

function showProducts() {
    updateActiveNav('Produtos');
    if (userType === 'produtor') {
        alert('Funcionalidade de gerenciamento de produtos em desenvolvimento...');
    } else {
        alert('Navegando para catálogo de produtos...');
    }
}

function showProducers() {
    updateActiveNav('Produtores');
    alert('Navegando para lista de produtores...');
}

function showOrders() {
    updateActiveNav('Pedidos');
    alert('Navegando para lista de pedidos...');
}

function showAnalytics() {
    updateActiveNav('Relatórios');
    alert('Funcionalidade de relatórios em desenvolvimento...');
}

function updateActiveNav(section) {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => link.classList.remove('active'));
    
    const activeLink = Array.from(navLinks).find(link => 
        link.textContent.includes(section)
    );
    if (activeLink) {
        activeLink.classList.add('active');
    }
}

// User menu functions
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    const userInfo = document.querySelector('.user-info');
    
    dropdown.classList.toggle('hidden');
    userInfo.classList.toggle('active');
}

function showProfile() {
    document.getElementById('profileModal').classList.remove('hidden');
    toggleUserDropdown();
}

function showSettings() {
    alert('Funcionalidade de configurações em desenvolvimento...');
    toggleUserDropdown();
}

function showHelp() {
    alert('Funcionalidade de ajuda em desenvolvimento...');
    toggleUserDropdown();
}

function logout() {
    if (confirm('Tem certeza que deseja sair?')) {
        localStorage.removeItem('artezzana_user');
        localStorage.removeItem('artezzana_form_draft');
        window.location.href = 'login.html';
    }
}

// Action functions
function exploreProducts() {
    alert('Navegando para explorar produtos...');
}

function findProducers() {
    alert('Navegando para encontrar produtores...');
}

function addNewProduct() {
    alert('Funcionalidade de adicionar produto em desenvolvimento...');
}

function manageOrders() {
    alert('Funcionalidade de gerenciar pedidos em desenvolvimento...');
}

function updateProfile() {
    alert('Funcionalidade de atualizar perfil em desenvolvimento...');
}

function viewAnalytics() {
    alert('Funcionalidade de relatórios em desenvolvimento...');
}

// Search functions
function performDashboardSearch() {
    const searchTerm = document.getElementById('dashboardSearch').value;
    if (searchTerm.trim()) {
        alert(`Buscando por: "${searchTerm}"`);
    }
}

function filterByCategory(category) {
    alert(`Filtrando por categoria: ${category}`);
}

// Detail functions
function showProductDetails(product) {
    alert(`Visualizando detalhes do produto: ${product.name}`);
}

function showOrderDetails(order) {
    alert(`Visualizando detalhes do pedido #${order.id}`);
}

function showProducerProfile(producer) {
    alert(`Visualizando perfil do produtor: ${producer.name}`);
}

function showAllOrders() {
    alert('Navegando para todos os pedidos...');
}

function showAllProducerOrders() {
    alert('Navegando para todos os pedidos recebidos...');
}

// Floating Action Button
function handleFabClick() {
    if (userType === 'produtor') {
        addNewProduct();
    } else {
        exploreProducts();
    }
}

// Modal functions
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
}

function goToHome() {
    window.location.href = 'index.html';
}

// Mobile menu toggle
function toggleMobileMenu() {
    const nav = document.querySelector('.nav');
    const btn = document.querySelector('.mobile-menu-btn');
    
    nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
    btn.classList.toggle('active');
}

// Auto-refresh data periodically
setInterval(() => {
    if (userType === 'produtor') {
        loadProducerStats();
    }
}, 30000); // Refresh every 30 seconds

// Add loading states for better UX
function showLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.classList.add('loading');
    }
}

function hideLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.classList.remove('loading');
    }
}

// Initialize tooltips and other UI enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling
    document.documentElement.style.scrollBehavior = 'smooth';
    
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
        const animatedElements = document.querySelectorAll('.product-card, .order-card, .producer-card, .stat-card, .action-card');
        animatedElements.forEach(el => {
            observer.observe(el);
        });
    }, 100);
});