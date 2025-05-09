/**
 * Main JavaScript file for the Lineage II Database (Public Section)
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize mobile menu toggle
    initMobileMenu();
    
    // Initialize search functionality
    initSearch();
    
    // Initialize card hover effects
    initCardHover();
});

/**
 * Initialize mobile menu toggle functionality
 */
function initMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (!menuToggle || !navMenu) return;
    
    menuToggle.addEventListener('click', function() {
        navMenu.classList.toggle('active');
        menuToggle.classList.toggle('active');
    });
}

/**
 * Initialize search functionality
 */
function initSearch() {
    const searchForm = document.querySelector('.search-box form');
    
    if (!searchForm) return;
    
    searchForm.addEventListener('submit', function(e) {
        const searchInput = this.querySelector('input[name="q"]');
        if (searchInput && searchInput.value.trim() === '') {
            e.preventDefault();
            searchInput.focus();
        }
    });
}

/**
 * Initialize card hover effects
 */
function initCardHover() {
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

/**
 * Show a toast message
 * 
 * @param {string} message - Message to display
 * @param {string} type - Message type (success, error, warning, info)
 * @param {number} duration - Duration in milliseconds
 */
function showToast(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = message;
    
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    // Hide and remove toast after duration
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, duration);
}

/**
 * Format number with commas
 * 
 * @param {number} number - Number to format
 * @return {string} Formatted number
 */
function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * Format date to local string
 * 
 * @param {string} dateString - Date string in ISO format
 * @return {string} Formatted date
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString();
}
