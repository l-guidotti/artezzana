// Global variables
let selectedUserType = '';
let isSubmitting = false;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Add phone mask
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', formatPhone);
    }
    
    // Add password validation
    const registerPassword = document.getElementById('registerPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    
    if (registerPassword && confirmPassword) {
        confirmPassword.addEventListener('input', validatePasswordMatch);
    }
});

// Navigation functions
function goToHome() {
    window.location.href = 'index.html';
}

function showLogin() {
    document.getElementById('loginForm').classList.remove('hidden');
    document.getElementById('registerForm').classList.add('hidden');
    document.getElementById('successMessage').classList.add('hidden');
}

function showRegister() {
    document.getElementById('loginForm').classList.add('hidden');
    document.getElementById('registerForm').classList.remove('hidden');
    document.getElementById('successMessage').classList.add('hidden');
}

// User type selection
function selectUserType(type) {
    selectedUserType = type;
    
    // Update UI
    const clienteCard = document.getElementById('clienteCard');
    const produtorCard = document.getElementById('produtorCard');
    const producerFields = document.getElementById('producerFields');
    const userTypeInput = document.getElementById('userType');
    
    // Reset selection
    clienteCard.classList.remove('selected');
    produtorCard.classList.remove('selected');
    
    // Select current type
    if (type === 'cliente') {
        clienteCard.classList.add('selected');
        producerFields.classList.add('hidden');
        // Remove required from producer fields
        setProducerFieldsRequired(false);
    } else if (type === 'produtor') {
        produtorCard.classList.add('selected');
        producerFields.classList.remove('hidden');
        // Add required to producer fields
        setProducerFieldsRequired(true);
    }
    
    userTypeInput.value = type;
}

function setProducerFieldsRequired(required) {
    const fields = ['businessName', 'businessType', 'businessDescription', 'deliveryRadius'];
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            if (required) {
                field.setAttribute('required', '');
            } else {
                field.removeAttribute('required');
            }
        }
    });
}

// Form validation
function validatePasswordMatch() {
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const confirmField = document.getElementById('confirmPassword');
    
    if (password !== confirmPassword && confirmPassword.length > 0) {
        confirmField.classList.add('error');
        showFieldError(confirmField, 'As senhas não coincidem');
    } else {
        confirmField.classList.remove('error');
        hideFieldError(confirmField);
    }
}

function showFieldError(field, message) {
    hideFieldError(field); // Remove existing error
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

function hideFieldError(field) {
    const existingError = field.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
}

// Phone formatting
function formatPhone(e) {
    let value = e.target.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        if (value.length < 14) {
            value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        }
    }
    
    e.target.value = value;
}

// Password toggle
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    
    if (field.type === 'password') {
        field.type = 'text';
        button.textContent = '🙈';
    } else {
        field.type = 'password';
        button.textContent = '👁️';
    }
}

// Form submission handlers
function handleLogin(event) {
    event.preventDefault();
    
    if (isSubmitting) return;
    isSubmitting = true;
    
    const form = event.target;
    const submitButton = form.querySelector('button[type="submit"]');
    const formData = new FormData(form);
    
    // Add loading state
    submitButton.classList.add('loading');
    submitButton.disabled = true;
    submitButton.textContent = 'Entrando...';
    
    // Simulate API call
    setTimeout(() => {
        const email = formData.get('email');
        const password = formData.get('password');
        
        // Basic validation (in real app, this would be server-side)
        if (email && password) {
            // Store user session (in real app, use proper authentication)
            localStorage.setItem('artezzana_user', JSON.stringify({
                email: email,
                type: 'cliente', // This would come from server
                loginTime: new Date().toISOString()
            }));
            
            // Redirect to main page
            window.location.href = 'index.html';
        } else {
            alert('Por favor, verifique suas credenciais.');
        }
        
        // Remove loading state
        submitButton.classList.remove('loading');
        submitButton.disabled = false;
        submitButton.textContent = 'Entrar';
        isSubmitting = false;
    }, 1500);
}

function handleRegister(event) {
    event.preventDefault();
    
    if (isSubmitting) return;
    
    // Validate user type selection
    if (!selectedUserType) {
        alert('Por favor, selecione se você quer comprar ou vender produtos.');
        return;
    }
    
    // Validate password match
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (password !== confirmPassword) {
        alert('As senhas não coincidem.');
        return;
    }
    
    if (password.length < 6) {
        alert('A senha deve ter pelo menos 6 caracteres.');
        return;
    }
    
    isSubmitting = true;
    
    const form = event.target;
    const submitButton = form.querySelector('button[type="submit"]');
    const formData = new FormData(form);
    
    // Add loading state
    submitButton.classList.add('loading');
    submitButton.disabled = true;
    submitButton.textContent = 'Criando conta...';
    
    // Simulate API call
    setTimeout(() => {
        // Collect form data
        const userData = {
            userType: selectedUserType,
            firstName: formData.get('firstName'),
            lastName: formData.get('lastName'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            city: formData.get('city'),
            state: formData.get('state'),
            agreeTerms: formData.get('agreeTerms'),
            agreeMarketing: formData.get('agreeMarketing'),
            registrationTime: new Date().toISOString()
        };
        
        // Add producer-specific data if applicable
        if (selectedUserType === 'produtor') {
            userData.businessName = formData.get('businessName');
            userData.businessType = formData.get('businessType');
            userData.businessDescription = formData.get('businessDescription');
            userData.deliveryRadius = formData.get('deliveryRadius');
        }
        
        // Store user data (in real app, this would be sent to server)
        localStorage.setItem('artezzana_user', JSON.stringify(userData));
        
        // Show success message
        showSuccessMessage(userData);
        
        // Remove loading state
        submitButton.classList.remove('loading');
        submitButton.disabled = false;
        submitButton.textContent = 'Criar Conta';
        isSubmitting = false;
    }, 2000);
}

function showSuccessMessage(userData) {
    const successMessage = document.getElementById('successMessage');
    const successText = document.getElementById('successText');
    
    let message = `Bem-vindo à Artezzana, ${userData.firstName}!`;
    
    if (userData.userType === 'produtor') {
        message += ' Sua conta de produtor foi criada com sucesso. Em breve você poderá começar a cadastrar seus produtos.';
    } else {
        message += ' Agora você pode explorar e comprar produtos dos nossos produtores locais.';
    }
    
    successText.textContent = message;
    
    // Hide forms and show success
    document.getElementById('loginForm').classList.add('hidden');
    document.getElementById('registerForm').classList.add('hidden');
    successMessage.classList.remove('hidden');
}

// Utility functions
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const cleanPhone = phone.replace(/\D/g, '');
    return cleanPhone.length >= 10;
}

// Auto-redirect if user is already logged in
document.addEventListener('DOMContentLoaded', function() {
    const user = localStorage.getItem('artezzana_user');
    if (user) {
        const userData = JSON.parse(user);
        const loginTime = new Date(userData.loginTime);
        const now = new Date();
        const hoursDiff = (now - loginTime) / (1000 * 60 * 60);
        
        // Auto-login if less than 24 hours
        if (hoursDiff < 24) {
            // Could redirect to dashboard or main page
            // window.location.href = 'index.html';
        }
    }
});

// Form auto-save (optional)
function autoSaveForm() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);
                localStorage.setItem('artezzana_form_draft', JSON.stringify(data));
            });
        });
    });
}

// Load saved form data
function loadFormDraft() {
    const draft = localStorage.getItem('artezzana_form_draft');
    if (draft) {
        const data = JSON.parse(draft);
        Object.keys(data).forEach(key => {
            const field = document.querySelector(`[name="${key}"]`);
            if (field) {
                field.value = data[key];
            }
        });
    }
}

// Clear form draft on successful submission
function clearFormDraft() {
    localStorage.removeItem('artezzana_form_draft');
}