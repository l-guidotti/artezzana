// Sample product data
const products = [
    {
        id: 1,
        name: "Tomates Orgânicos",
        description: "Tomates frescos cultivados sem agrotóxicos",
        price: "R$ 8,00/kg",
        producer: "Sítio da Dona Maria",
        category: "frutas-verduras",
        image: "./assets/images/tomates.jpg?height=200&width=280"
    },
    {
        id: 2,
        name: "Vaso de Cerâmica",
        description: "Vaso artesanal feito em cerâmica tradicional",
        price: "R$ 45,00",
        producer: "Artesanato do João",
        category: "artesanato",
        image: "./assets/images/vaso-ceramico.jpg?height=200&width=280"
    },
    {
        id: 3,
        name: "Mel Silvestre",
        description: "Mel puro extraído de colmeias naturais",
        price: "R$ 25,00",
        producer: "Apiário São José",
        category: "doces-conservas",
        image: "./assets/images/mel.jpg?height=200&width=280"
    },
    {
        id: 4,
        name: "Queijo Minas Artesanal",
        description: "Queijo curado tradicionalmente por 30 dias",
        price: "R$ 35,00/kg",
        producer: "Fazenda Esperança",
        category: "laticínios",
        image: "./assets/images/queijo-minas.jpg?height=200&width=280"
    },
    {
        id: 5,
        name: "Mudas de Manjericão",
        description: "Mudas orgânicas prontas para plantio",
        price: "R$ 5,00",
        producer: "Viveiro Verde Vida",
        category: "plantas",
        image: "./assets/images/muda-majericao.jpg?height=200&width=280"
    },
    {
        id: 6,
        name: "Tempero Caseiro",
        description: "Mix de ervas desidratadas da horta",
        price: "R$ 12,00",
        producer: "Sítio da Dona Maria",
        category: "temperos",
        image: "./assets/images/tempero.jpg?height=200&width=280"
    }
];

let currentProducts = products;

// Load products on page load
document.addEventListener('DOMContentLoaded', function() {
    loadProducts(products);
});

// Load products into grid
function loadProducts(productsToShow) {
    const grid = document.getElementById('productsGrid');
    grid.innerHTML = '';
    
    productsToShow.forEach(product => {
        const productCard = createProductCard(product);
        grid.appendChild(productCard);
    });
}

// Create product card element
function createProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card';
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

// Filter products by category
function filterByCategory(category) {
    const filteredProducts = products.filter(product => product.category === category);
    loadProducts(filteredProducts);
    
    // Scroll to products section
    document.querySelector('.featured-products').scrollIntoView({
        behavior: 'smooth'
    });
}

// Search functionality
function performSearch() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    
    if (searchTerm.trim() === '') {
        loadProducts(products);
        return;
    }
    
    const filteredProducts = products.filter(product => 
        product.name.toLowerCase().includes(searchTerm) ||
        product.description.toLowerCase().includes(searchTerm) ||
        product.producer.toLowerCase().includes(searchTerm) ||
        product.category.toLowerCase().includes(searchTerm)
    );
    
    loadProducts(filteredProducts);
    
    // Scroll to products section
    document.querySelector('.featured-products').scrollIntoView({
        behavior: 'smooth'
    });
}

// Search on Enter key
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        performSearch();
    }
});

// Show product details (modal or new page)
function showProductDetails(product) {
    alert(`Produto: ${product.name}\nPreço: ${product.price}\nProdutor: ${product.producer}\n\nEm breve você poderá ver mais detalhes e entrar em contato com o produtor!`);
}

// Smooth scroll to section
function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({
        behavior: 'smooth'
    });
}

// Mobile menu toggle
function toggleMobileMenu() {
    const nav = document.querySelector('.nav');
    const btn = document.querySelector('.mobile-menu-btn');
    
    nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
    btn.classList.toggle('active');
}

// Add scroll effect to header
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    if (window.scrollY > 100) {
        header.style.background = 'rgba(255, 255, 255, 0.95)';
        header.style.backdropFilter = 'blur(10px)';
    } else {
        header.style.background = '#fff';
        header.style.backdropFilter = 'none';
    }
});

// Add loading animation for images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
    });
});

// Add intersection observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements for animation
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('.category-card, .product-card, .step, .producer-card');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});

function redirectToLogin(){
    window.location.href = 'pages/login.html'
}