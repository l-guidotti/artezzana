// Global variables
let currentUser = null;
let userType = 'cliente';
let addresses = [];
let currentSection = 'personal';

// Sample data
const sampleAddresses = [
    {
        id: 1,
        name: 'Casa',
        street: 'Rua das Flores, 123',
        neighborhood: 'Centro',
        city: 'São Paulo',
        state: 'SP',
        zipCode: '01234-567',
        complement: 'Apto 45',
        isDefault: true
    },
    {
        id: 2,
        name: 'Trabalho',
        street: 'Av. Paulista, 1000',
        neighborhood: 'Bela Vista',
        city: 'São Paulo',
        state: 'SP',
        zipCode: '01310-100',
        complement: 'Sala 1205',
        isDefault: false
    }
];

// // Initialize page
// document.addEventListener('DOMContentLoaded', function() {
//     loadUserData();
//     loadAddresses();
//     setupEventListeners();
//     setupFormValidation();
// });

// // Load user data
// function loadUserData() {
//     const userData = localStorage.getItem('artezzana_user');
//     if (userData) {
//         currentUser = JSON.parse(userData);
//         userType = currentUser.userType || 'cliente';
//         updateUserInterface();
//         populatePersonalForm();
//         populateBusinessForm();
//     } else {
//         window.location.href = 'login.html';
//     }
// }

// Update user interface
function updateUserInterface() {
    // Update header
    const headerUserName = document.getElementById('headerUserName');
    const headerInitials = document.getElementById('headerInitials');
    const headerAvatar = document.getElementById('headerAvatar');
    
    const initials = `${currentUser.firstName.charAt(0)}${currentUser.lastName.charAt(0)}`.toUpperCase();
    
    headerUserName.textContent = currentUser.firstName;
    headerInitials.textContent = initials;
    
    // Update profile card
    const profileName = document.getElementById('profileName');
    const profileType = document.getElementById('profileType');
    const profileEmail = document.getElementById('profileEmail');
    const avatarInitials = document.getElementById('avatarInitials');
    const profileAvatar = document.getElementById('profileAvatar');
    
    profileName.textContent = `${currentUser.firstName} ${currentUser.lastName}`;
    profileType.textContent = userType === 'produtor' ? 'Produtor' : 'Cliente';
    profileEmail.textContent = currentUser.email;
    avatarInitials.textContent = initials;
    
    // Show/hide producer-only sections
    const producerElements = document.querySelectorAll('.producer-only');
    producerElements.forEach(element => {
        if (userType === 'produtor') {
            element.classList.remove('hidden');
        } else {
            element.classList.add('hidden');
        }
    });
    
    // Load profile photo if exists
    const savedPhoto = localStorage.getItem(`artezzana_profile_photo_${currentUser.email}`);
    if (savedPhoto) {
        const avatarImage = document.getElementById('avatarImage');
        avatarImage.src = savedPhoto;
        avatarImage.classList.remove('hidden');
        avatarInitials.style.display = 'none';
    }
}

// Populate personal form
function populatePersonalForm() {
    document.getElementById('firstName').value = currentUser.firstName || '';
    document.getElementById('lastName').value = currentUser.lastName || '';
    document.getElementById('email').value = currentUser.email || '';
    document.getElementById('phone').value = currentUser.phone || '';
    document.getElementById('cpf').value = currentUser.cpf || '';
    document.getElementById('birthDate').value = currentUser.birthDate || '';
    document.getElementById('bio').value = currentUser.bio || '';
}

// Populate business form (for producers)
function populateBusinessForm() {
    if (userType === 'produtor' && currentUser.business) {
        document.getElementById('businessName').value = currentUser.business.name || '';
        document.getElementById('cnpj').value = currentUser.business.cnpj || '';
        document.getElementById('businessType').value = currentUser.business.type || '';
        document.getElementById('businessDescription').value = currentUser.business.description || '';
        document.getElementById('deliveryRadius').value = currentUser.business.deliveryRadius || '';
        document.getElementById('deliveryFee').value = currentUser.business.deliveryFee || '';
    }
}

// Load addresses
function loadAddresses() {
    const savedAddresses = localStorage.getItem(`artezzana_addresses_${currentUser.email}`);
    if (savedAddresses) {
        addresses = JSON.parse(savedAddresses);
    } else {
        addresses = sampleAddresses;
        saveAddresses();
    }
    renderAddresses();
}

// Save addresses
function saveAddresses() {
    localStorage.setItem(`artezzana_addresses_${currentUser.email}`, JSON.stringify(addresses));
}

// Render addresses
function renderAddresses() {
    const container = document.getElementById('addressesList');
    container.innerHTML = '';
    
    if (addresses.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <p>Nenhum endereço cadastrado.</p>
                <button class="btn btn-primary" onclick="showAddAddressModal()">Adicionar Primeiro Endereço</button>
            </div>
        `;
        return;
    }
    
    addresses.forEach(address => {
        const addressCard = createAddressCard(address);
        container.appendChild(addressCard);
    });
}

// Create address card
function createAddressCard(address) {
    const card = document.createElement('div');
    card.className = 'address-card fade-in';
    card.innerHTML = `
        <div class="address-info">
            <h4>
                ${address.name}
                ${address.isDefault ? '<span class="default-badge">Padrão</span>' : ''}
            </h4>
            <p>${address.street}${address.complement ? ', ' + address.complement : ''}</p>
            <p>${address.neighborhood}, ${address.city} - ${address.state}</p>
            <p>CEP: ${address.zipCode}</p>
        </div>
        <div class="address-actions">
            <button onclick="editAddress(${address.id})" title="Editar">✏️</button>
            <button onclick="deleteAddress(${address.id})" title="Excluir">🗑️</button>
            ${!address.isDefault ? `<button onclick="setDefaultAddress(${address.id})" title="Definir como padrão">⭐</button>` : ''}
        </div>
    `;
    return card;
}

// Section navigation
function showSection(sectionName) {
    // Hide all sections
    const sections = document.querySelectorAll('.profile-section-content');
    sections.forEach(section => section.classList.remove('active'));
    
    // Show selected section
    const targetSection = document.getElementById(`${sectionName}-section`);
    if (targetSection) {
        targetSection.classList.add('active');
    }
    
    // Update navigation
    const navLinks = document.querySelectorAll('.profile-nav-link');
    navLinks.forEach(link => link.classList.remove('active'));
    
    const activeLink = document.querySelector(`[data-section="${sectionName}"]`);
    if (activeLink) {
        activeLink.classList.add('active');
    }
    
    currentSection = sectionName;
}

// Profile photo handling
// function changeProfilePhoto() {
//     document.getElementById('photoInput').click();
// }

// function handlePhotoUpload(event) {
//     const file = event.target.files[0];
//     if (file) {
//         if (file.size > 5 * 1024 * 1024) { // 5MB limit
//             showToast('Arquivo muito grande. Máximo 5MB.', 'error');
//             return;
//         }
        
//         if (!file.type.startsWith('image/')) {
//             showToast('Por favor, selecione uma imagem válida.', 'error');
//             return;
//         }
        
//         const reader = new FileReader();
//         reader.onload = function(e) {
//             const imageData = e.target.result;
            
//             // Update