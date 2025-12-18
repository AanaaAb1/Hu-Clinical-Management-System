// Authentication JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initLoginForm();
    initRegisterForm();
    initPasswordToggle();
    initPasswordStrength();
});

// Login Form
function initLoginForm() {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) return;
    
    // Demo login buttons
    const demoButtons = loginForm.querySelectorAll('.demo-login');
    demoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            const credentials = {
                'admin': { email: 'admin@hu.edu.et', password: 'admin123' },
                'doctor': { email: 'doctor@hu.edu.et', password: 'doctor123' },
                'nurse': { email: 'nurse@hu.edu.et', password: 'nurse123' }
            };
            
            if (credentials[type]) {
                document.getElementById('email').value = credentials[type].email;
                document.getElementById('password').value = credentials[type].password;
                showNotification(`Demo ${type} credentials loaded`, 'info');
            }
        });
    });
    
    // Form validation
    loginForm.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        
        if (!email || !password) {
            e.preventDefault();
            showNotification('Please enter both email and password', 'error');
            return false;
        }
        
        if (!isValidEmail(email)) {
            e.preventDefault();
            showNotification('Please enter a valid email address', 'error');
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
        submitBtn.disabled = true;
        
        // Re-enable after 3 seconds (in case of error)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });
}

// Register Form
function initRegisterForm() {
    const registerForm = document.getElementById('registerForm');
    if (!registerForm) return;
    
    // Password strength indicator
    const passwordInput = registerForm.querySelector('input[name="password"]');
    const strengthIndicator = document.getElementById('passwordStrength');
    
    if (passwordInput && strengthIndicator) {
        passwordInput.addEventListener('input', function() {
            const strength = calculatePasswordStrength(this.value);
            updateStrengthIndicator(strength, strengthIndicator);
        });
    }
    
    // Form validation
    registerForm.addEventListener('submit', function(e) {
        const password = this.querySelector('input[name="password"]').value;
        const confirmPassword = this.querySelector('input[name="confirm_password"]').value;
        const terms = this.querySelector('input[name="terms"]');
        
        // Check password match
        if (password !== confirmPassword) {
            e.preventDefault();
            showNotification('Passwords do not match', 'error');
            return false;
        }
        
        // Check password strength
        if (calculatePasswordStrength(password) < 3) {
            e.preventDefault();
            showNotification('Password is too weak. Please use a stronger password.', 'error');
            return false;
        }
        
        // Check terms agreement
        if (terms && !terms.checked) {
            e.preventDefault();
            showNotification('You must agree to the terms and conditions', 'error');
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        submitBtn.disabled = true;
        
        // Re-enable after 5 seconds (in case of error)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
}

// Password Toggle
function initPasswordToggle() {
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Toggle icon
            const icon = this.querySelector('i');
            if (icon) {
                icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            }
        });
    });
}

// Password Strength Calculator
function initPasswordStrength() {
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    
    passwordInputs.forEach(input => {
        if (input.name === 'password' || input.name === 'new_password') {
            input.addEventListener('input', function() {
                const strength = calculatePasswordStrength(this.value);
                const indicator = document.getElementById('passwordStrength') || createStrengthIndicator(this);
                updateStrengthIndicator(strength, indicator);
            });
        }
    });
}

function calculatePasswordStrength(password) {
    let strength = 0;
    
    if (!password) return 0;
    
    // Length check
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    
    // Character variety checks
    if (/[A-Z]/.test(password)) strength++; // Uppercase
    if (/[a-z]/.test(password)) strength++; // Lowercase
    if (/[0-9]/.test(password)) strength++; // Numbers
    if (/[^A-Za-z0-9]/.test(password)) strength++; // Special characters
    
    return Math.min(strength, 5); // Max strength is 5
}

function createStrengthIndicator(input) {
    const container = document.createElement('div');
    container.id = 'passwordStrength';
    container.style.marginTop = '5px';
    
    const bar = document.createElement('div');
    bar.style.height = '4px';
    bar.style.borderRadius = '2px';
    bar.style.background = '#ddd';
    bar.style.overflow = 'hidden';
    
    const progress = document.createElement('div');
    progress.style.height = '100%';
    progress.style.width = '0%';
    progress.style.transition = 'width 0.3s ease';
    bar.appendChild(progress);
    
    const text = document.createElement('div');
    text.style.fontSize = '0.8rem';
    text.style.marginTop = '3px';
    text.style.color = '#666';
    
    container.appendChild(bar);
    container.appendChild(text);
    
    input.parentNode.appendChild(container);
    
    return container;
}

function updateStrengthIndicator(strength, indicator) {
    const bar = indicator.querySelector('div:first-child > div');
    const text = indicator.querySelector('div:last-child');
    
    let width = 0;
    let color = '#e74c3c';
    let message = 'Very Weak';
    
    switch (strength) {
        case 1:
            width = 20;
            color = '#e74c3c';
            message = 'Weak';
            break;
        case 2:
            width = 40;
            color = '#e74c3c';
            message = 'Fair';
            break;
        case 3:
            width = 60;
            color = '#f39c12';
            message = 'Good';
            break;
        case 4:
            width = 80;
            color = '#27ae60';
            message = 'Strong';
            break;
        case 5:
            width = 100;
            color = '#2ecc71';
            message = 'Very Strong';
            break;
        default:
            width = 0;
            color = '#ddd';
            message = '';
    }
    
    if (bar) {
        bar.style.width = width + '%';
        bar.style.background = color;
    }
    
    if (text) {
        text.textContent = message;
        text.style.color = color;
    }
}

// Email validation
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Phone validation
function isValidPhone(phone) {
    const re = /^(\+251|0)[0-9]{9}$/;
    return re.test(phone);
}

// Show notification (fallback if main.js not loaded)
if (!window.showNotification) {
    window.showNotification = function(message, type = 'info') {
        alert(message);
    };
}