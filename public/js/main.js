/**
 * Main JavaScript for L1J Remastered Database Browser
 */

document.addEventListener('DOMContentLoaded', function() {
    // Mobile navigation toggle
    setupMobileNav();
    
    // Initialize search functionality
    setupSearch();
    
    // Initialize list filters
    setupListFilters();
    
    // Initialize detail tabs if present
    setupDetailTabs();
    
    // Initialize tooltips
    setupTooltips();
});

/**
 * Setup mobile navigation
 */
function setupMobileNav() {
    const dropdowns = document.querySelectorAll('.dropdown');
    
    // For desktop, use hover
    // For mobile, use click
    if (window.innerWidth <= 600) {
        dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.dropdown-toggle');
            
            if (toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Close all other dropdowns
                    dropdowns.forEach(d => {
                        if (d !== dropdown && d.classList.contains('active')) {
                            d.classList.remove('active');
                        }
                    });
                    
                    // Toggle current dropdown
                    dropdown.classList.toggle('active');
                });
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                dropdowns.forEach(d => {
                    d.classList.remove('active');
                });
            }
        });
    }
}

/**
 * Setup search functionality
 */
function setupSearch() {
    const searchForms = document.querySelectorAll('.search-container form');
    
    searchForms.forEach(form => {
        const input = form.querySelector('input[name="q"]');
        
        if (input) {
            // Clear search results when input is cleared
            input.addEventListener('input', function() {
                if (this.value.trim() === '') {
                    const resultsContainer = document.querySelector('.search-results');
                    if (resultsContainer) {
                        resultsContainer.innerHTML = '';
                    }
                }
            });
            
            // Focus input on page load if empty
            if (input.value.trim() === '') {
                input.focus();
            }
        }
    });
}

/**
 * Setup list view filters
 */
function setupListFilters() {
    const filterForm = document.querySelector('.list-filters form');
    
    if (filterForm) {
        const inputs = filterForm.querySelectorAll('input, select');
        
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                // Auto-submit form on change for selects and checkboxes
                if (input.tagName === 'SELECT' || (input.type === 'checkbox' && input.checked !== undefined)) {
                    filterForm.submit();
                }
            });
        });
        
        // Handle filter reset
        const resetButton = filterForm.querySelector('.filter-reset');
        if (resetButton) {
            resetButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                inputs.forEach(input => {
                    if (input.type === 'text' || input.type === 'number' || input.type === 'search') {
                        input.value = '';
                    } else if (input.type === 'checkbox' || input.type === 'radio') {
                        input.checked = false;
                    } else if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    }
                });
                
                filterForm.submit();
            });
        }
    }
}

/**
 * Setup detail view tabs
 */
function setupDetailTabs() {
    const tabContainers = document.querySelectorAll('.tabs-container');
    
    tabContainers.forEach(container => {
        const tabs = container.querySelectorAll('.tab-link');
        const tabContents = container.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('data-tab');
                
                // Hide all tab contents
                tabContents.forEach(content => {
                    content.classList.remove('active');
                });
                
                // Deactivate all tabs
                tabs.forEach(t => {
                    t.classList.remove('active');
                });
                
                // Activate clicked tab and content
                this.classList.add('active');
                
                const targetContent = container.querySelector(`#${targetId}`);
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });
        
        // Activate first tab by default
        if (tabs.length > 0 && tabContents.length > 0) {
            tabs[0].classList.add('active');
            tabContents[0].classList.add('active');
        }
    });
}

/**
 * Setup tooltips
 */
function setupTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(element => {
        const tooltipText = element.getAttribute('data-tooltip');
        
        // Create tooltip element
        const tooltip = document.createElement('div');
        tooltip.classList.add('tooltip');
        tooltip.textContent = tooltipText;
        
        // Add tooltip to element
        element.appendChild(tooltip);
        
        // Show tooltip on hover
        element.addEventListener('mouseenter', function() {
            tooltip.classList.add('show');
        });
        
        // Hide tooltip on mouse leave
        element.addEventListener('mouseleave', function() {
            tooltip.classList.remove('show');
        });
    });
}

/**
 * Get URL parameters
 * @returns {Object} Object containing URL parameters
 */
function getUrlParams() {
    const params = {};
    const queryString = window.location.search.substring(1);
    const pairs = queryString.split('&');
    
    for (const pair of pairs) {
        if (pair === '') continue;
        
        const parts = pair.split('=');
        const key = decodeURIComponent(parts[0]);
        const value = parts.length > 1 ? decodeURIComponent(parts[1]) : null;
        
        params[key] = value;
    }
    
    return params;
}

/**
 * Format number with commas
 * @param {number} num Number to format
 * @returns {string} Formatted number
 */
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/**
 * Toggle element visibility
 * @param {string} selector Element selector
 */
function toggleElement(selector) {
    const element = document.querySelector(selector);
    
    if (element) {
        element.classList.toggle('hidden');
    }
}

/**
 * Show a confirmation dialog
 * @param {string} message Confirmation message
 * @returns {boolean} Result of confirmation
 */
function confirmAction(message) {
    return confirm(message || 'Are you sure you want to perform this action?');
}

/**
 * Handle image errors by showing a placeholder
 * @param {HTMLImageElement} img Image element
 */
function handleImageError(img) {
    img.onerror = null;
    img.src = '/public/images/placeholder.png';
}
