// Main JavaScript file for HU CMS

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initMobileNavigation();
    initFlashMessages();
    initForms();
    initTooltips();
    initDropdowns();
});

// Mobile Navigation Toggle
function initMobileNavigation() {
    const mobileToggle = document.getElementById('mobileToggle');
    const navContainer = document.getElementById('navContainer');
    
    if (mobileToggle && navContainer) {
        mobileToggle.addEventListener('click', function() {
            navContainer.classList.toggle('active');
            this.innerHTML = navContainer.classList.contains('active') 
                ? '<i class="fas fa-times"></i>' 
                : '<i class="fas fa-bars"></i>';
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileToggle.contains(event.target) && !navContainer.contains(event.target)) {
                navContainer.classList.remove('active');
                mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });
    }
}

// Flash Messages
function initFlashMessages() {
    const flashMessages = document.querySelectorAll('.flash-message');
    
    flashMessages.forEach(message => {
        const closeBtn = message.querySelector('.flash-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                message.style.display = 'none';
            });
        }
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (message.style.display !== 'none') {
                message.style.opacity = '0';
                setTimeout(() => {
                    message.style.display = 'none';
                }, 300);
            }
        }, 5000);
    });
}

// Form Validation
function initForms() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showNotification('Please fix the errors in the form', 'error');
            }
        });
        
        // Add live validation
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
    });
}

function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    const name = field.name;
    
    // Clear previous errors
    clearFieldError(field);
    
    // Check required fields
    if (field.required && !value) {
        showFieldError(field, 'This field is required');
        return false;
    }
    
    // Validate based on type
    switch (type) {
        case 'email':
            if (value && !isValidEmail(value)) {
                showFieldError(field, 'Please enter a valid email address');
                return false;
            }
            break;
            
        case 'tel':
            if (value && !isValidPhone(value)) {
                showFieldError(field, 'Please enter a valid phone number');
                return false;
            }
            break;
            
        case 'password':
            if (value && value.length < 8) {
                showFieldError(field, 'Password must be at least 8 characters');
                return false;
            }
            break;
    }
    
    // Custom validations
    switch (name) {
        case 'confirm_password':
            const password = field.form.querySelector('input[name="password"]');
            if (password && password.value !== value) {
                showFieldError(field, 'Passwords do not match');
                return false;
            }
            break;
    }
    
    return true;
}

function showFieldError(field, message) {
    field.classList.add('error');
    
    let errorElement = field.parentNode.querySelector('.error-message');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.style.color = '#e74c3c';
        errorElement.style.fontSize = '0.85rem';
        errorElement.style.marginTop = '5px';
        field.parentNode.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
}

function clearFieldError(field) {
    field.classList.remove('error');
    const errorElement = field.parentNode.querySelector('.error-message');
    if (errorElement) {
        errorElement.remove();
    }
}

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function isValidPhone(phone) {
    const re = /^(\+251|0)[0-9]{9}$/;
    return re.test(phone);
}

// Tooltips
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function(e) {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            tooltip.style.position = 'fixed';
            tooltip.style.background = 'rgba(0,0,0,0.8)';
            tooltip.style.color = 'white';
            tooltip.style.padding = '8px 12px';
            tooltip.style.borderRadius = '4px';
            tooltip.style.fontSize = '0.85rem';
            tooltip.style.zIndex = '9999';
            tooltip.style.pointerEvents = 'none';
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
            
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                delete this._tooltip;
            }
        });
    });
}

// Dropdowns
function initDropdowns() {
    const dropdowns = document.querySelectorAll('.user-dropdown');
    
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.user-btn');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (toggle && menu) {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('show');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                menu.classList.remove('show');
            });
        }
    });
}

// Notification System
window.showNotification = function(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `flash-message flash-${type}`;
    notification.innerHTML = `
        <div class="container">
            <span>${message}</span>
            <button class="flash-close">&times;</button>
        </div>
    `;
    
    document.body.insertBefore(notification, document.body.firstChild);
    
    // Animate in
    setTimeout(() => {
        notification.style.opacity = '1';
    }, 10);
    
    // Add close functionality
    const closeBtn = notification.querySelector('.flash-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        });
    }
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }
    }, 5000);
};

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Export to global scope
window.HU_CMS = {
    showNotification,
    debounce,
    throttle
};