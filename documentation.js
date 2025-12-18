// Documentation page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initTabNavigation();
    initSidebarNavigation();
    initCodeBlocks();
    initSearch();
});

// Tab Navigation
function initTabNavigation() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Update active button
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Show active tab pane
            tabPanes.forEach(pane => {
                pane.classList.remove('active');
                if (pane.id === tabId) {
                    pane.classList.add('active');
                }
            });
        });
    });
}

// Sidebar Navigation
function initSidebarNavigation() {
    const sidebarLinks = document.querySelectorAll('.sidebar-nav a');
    const sections = document.querySelectorAll('.doc-section');
    
    // Highlight active section on scroll
    window.addEventListener('scroll', function() {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 150;
            const sectionHeight = section.clientHeight;
            
            if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });
        
        sidebarLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });
    
    // Smooth scroll for sidebar links
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId.startsWith('#')) {
                e.preventDefault();
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
}

// Code Block Enhancement
function initCodeBlocks() {
    const codeBlocks = document.querySelectorAll('.code-block');
    
    codeBlocks.forEach(block => {
        // Add copy button
        const copyBtn = document.createElement('button');
        copyBtn.className = 'copy-btn';
        copyBtn.innerHTML = '<i class="fas fa-copy"></i>';
        copyBtn.style.position = 'absolute';
        copyBtn.style.top = '10px';
        copyBtn.style.right = '10px';
        copyBtn.style.background = 'rgba(255,255,255,0.1)';
        copyBtn.style.color = '#ecf0f1';
        copyBtn.style.border = 'none';
        copyBtn.style.padding = '5px 10px';
        copyBtn.style.borderRadius = '4px';
        copyBtn.style.cursor = 'pointer';
        copyBtn.style.fontSize = '0.9rem';
        
        block.style.position = 'relative';
        block.appendChild(copyBtn);
        
        copyBtn.addEventListener('click', function() {
            const code = block.textContent.replace(/^\s*\/\/.*\n?/gm, '');
            navigator.clipboard.writeText(code).then(() => {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 2000);
            });
        });
    });
}
// Search Functionality
function initSearch() {
    const searchInput = document.getElementById('docSearch');
    if (!searchInput) return;
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const sections = document.querySelectorAll('.doc-section');
        
        sections.forEach(section => {
            const content = section.textContent.toLowerCase();
            const isVisible = content.includes(searchTerm) || searchTerm === '';
            section.style.display = isVisible ? 'block' : 'none';
        });
    });
}

// Print Documentation
function printDocumentation() {
    window.print();
}

// Download PDF
function downloadPDF() {
    alert('PDF download functionality would be implemented here.');
}

// Export functions to global scope
window.Documentation = {
    print: printDocumentation,
    downloadPDF: downloadPDF,
    search: function(term) {
        const searchInput = document.getElementById('docSearch');
        if (searchInput) {
            searchInput.value = term;
            searchInput.dispatchEvent(new Event('input'));
        }
    }
};