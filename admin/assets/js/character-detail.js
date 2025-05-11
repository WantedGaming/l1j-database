/**
 * Character Detail Page JavaScript
 * Provides interactive functionality for the character detail page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips if any exist
    initTooltips();
    
    // Initialize tab navigation
    initTabNavigation();
    
    // Initialize attribute cards
    initAttributeCards();
    
    // Initialize modal dialogs
    initModals();
    
    // Character list item highlighting
    initCharacterList();
});

/**
 * Initialize tooltip functionality
 */
function initTooltips() {
    // Already implemented in CSS with pure CSS tooltips
    console.log("Tooltips initialized");
}

/**
 * Initialize tab navigation
 */
function initTabNavigation() {
    const tabs = document.querySelectorAll('.character-tab');
    
    // Set first tab as active by default
    if (!document.querySelector('.character-tab.active') && tabs.length > 0) {
        tabs[0].classList.add('active');
        const firstTabId = tabs[0].getAttribute('data-tab');
        const firstTabContent = document.getElementById(firstTabId + '-tab');
        if (firstTabContent) {
            firstTabContent.classList.add('active');
        }
    }
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('.character-tab').forEach(t => {
                t.classList.remove('active');
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Show corresponding tab content
            const tabId = this.getAttribute('data-tab');
            const tabContent = document.getElementById(tabId + '-tab');
            if (tabContent) {
                tabContent.classList.add('active');
                
                // Trigger any lazy-loaded content
                loadTabContent(tabId);
            }
        });
    });
    
    // Check for hash in URL to activate specific tab
    const hash = window.location.hash.substring(1);
    if (hash) {
        const tabToActivate = document.querySelector(`.character-tab[data-tab="${hash}"]`);
        if (tabToActivate) {
            tabToActivate.click();
        }
    }
}

/**
 * Load tab-specific content if needed
 */
function loadTabContent(tabId) {
    // This function can be used to load content via AJAX when a tab is selected
    console.log(`Loading content for tab: ${tabId}`);
    
    // Example: Load map data when location tab is clicked
    if (tabId === 'location') {
        // Simulate map loading
        const mapVisual = document.querySelector('.map-visual');
        if (mapVisual) {
            mapVisual.classList.add('loading');
            
            // Simulate loading delay
            setTimeout(() => {
                mapVisual.classList.remove('loading');
                console.log('Map loaded');
            }, 500);
        }
    }
}

/**
 * Initialize attribute cards
 */
function initAttributeCards() {
    const attributeCards = document.querySelectorAll('.attribute-card');
    
    attributeCards.forEach(card => {
        // Add subtle animation on hover
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

/**
 * Initialize modal dialogs
 */
function initModals() {
    // Edit character modal
    window.openEditModal = function(charId) {
        console.log(`Opening edit modal for character ID: ${charId}`);
        const modal = document.getElementById('editModal');
        if (modal) {
            modal.style.display = 'block';
            
            // Add escape key listener
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
            
            // Close when clicking outside modal
            window.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }
    };
    
    window.closeModal = function() {
        const modal = document.getElementById('editModal');
        if (modal) {
            modal.style.display = 'none';
        }
    };
}

/**
 * Initialize character list interactions
 */
function initCharacterList() {
    const characterItems = document.querySelectorAll('.compact-character-item');
    
    characterItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.borderLeftWidth = '3px';
        });
        
        item.addEventListener('mouseleave', function() {
            if (!this.classList.contains('current')) {
                this.style.borderLeftWidth = '1px';
            }
        });
    });
}

/**
 * Utility function to format numbers with commas
 */
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * Add visual feedback for stat values
 */
function enhanceStatDisplay() {
    const statValues = document.querySelectorAll('.account-info-value');
    
    statValues.forEach(value => {
        const text = value.textContent.trim();
        
        // Add color coding for numerical values
        if (!isNaN(parseInt(text))) {
            const num = parseInt(text);
            
            // Example: Color code PvP stats
            if (value.parentElement.querySelector('.account-info-label').textContent.includes('Kill')) {
                if (num > 100) {
                    value.classList.add('text-accent');
                } else if (num > 50) {
                    value.style.color = '#ffc107'; // Yellow
                }
            }
            
            // Format large numbers
            if (num > 1000) {
                value.textContent = formatNumber(num);
            }
        }
    });
}

// Call this function on page load
enhanceStatDisplay();
