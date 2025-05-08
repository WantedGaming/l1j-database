/**
 * Enhanced Main JavaScript file for L1J Remastered Database
 * Adds modern interactions and visual enhancements
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    if (tooltipTriggerList.length > 0) {
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: document.body
            });
        });
    }
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    if (popoverTriggerList.length > 0) {
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {
                trigger: 'focus'
            });
        });
    }
    
    // Add animation to hero elements
    const heroTitle = document.querySelector('.hero-title');
    const heroSubtitle = document.querySelector('.hero-subtitle');
    const heroSearch = document.querySelector('.hero-search');
    
    if (heroTitle) heroTitle.classList.add('fade-in');
    if (heroSubtitle) {
        heroSubtitle.style.opacity = '0';
        heroSubtitle.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            heroSubtitle.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            heroSubtitle.style.opacity = '1';
            heroSubtitle.style.transform = 'translateY(0)';
        }, 200);
    }
    
    if (heroSearch) {
        heroSearch.style.opacity = '0';
        heroSearch.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            heroSearch.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            heroSearch.style.opacity = '1';
            heroSearch.style.transform = 'translateY(0)';
        }, 400);
    }
    
    // Card hover effects with modern interactions
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const img = this.querySelector('.card-img-top');
            const cardTitle = this.querySelector('.card-title');
            const cardFooter = this.querySelector('.card-footer');
            
            if (img) {
                img.style.transform = 'scale(1.08)';
                img.style.transition = 'transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            }
            
            if (cardTitle) {
                cardTitle.style.color = '#ff8c5a';
                cardTitle.style.transition = 'color 0.3s ease';
            }
            
            if (cardFooter) {
                cardFooter.style.borderTopColor = 'var(--color-accent)';
                cardFooter.style.transition = 'border-top-color 0.3s ease';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const img = this.querySelector('.card-img-top');
            const cardTitle = this.querySelector('.card-title');
            const cardFooter = this.querySelector('.card-footer');
            
            if (img) {
                img.style.transform = 'scale(1)';
            }
            
            if (cardTitle) {
                cardTitle.style.color = 'var(--color-accent)';
            }
            
            if (cardFooter) {
                cardFooter.style.borderTopColor = 'var(--color-border)';
            }
        });
    });
    
    // Search form validation with improved feedback
    const searchForms = document.querySelectorAll('.search-form');
    searchForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[type="search"]');
            if (searchInput.value.trim() === '') {
                e.preventDefault();
                
                // Add shake effect and visual feedback
                searchInput.classList.add('is-invalid');
                searchInput.classList.add('shake');
                
                // Create a feedback element if it doesn't exist
                let feedback = form.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Please enter a search term';
                    searchInput.parentNode.appendChild(feedback);
                }
                
                // Show the feedback
                feedback.style.display = 'block';
                
                // Focus the input
                searchInput.focus();
                
                // Remove shake effect after animation completes
                setTimeout(() => {
                    searchInput.classList.remove('shake');
                }, 500);
                
                // Remove invalid class after 2 seconds
                setTimeout(() => {
                    searchInput.classList.remove('is-invalid');
                    if (feedback) feedback.style.display = 'none';
                }, 2000);
            }
        });
        
        // Clear invalid state on input
        const searchInput = form.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const feedback = form.querySelector('.invalid-feedback');
                if (feedback) feedback.style.display = 'none';
            });
        }
    });
    
    // Add scroll animations to cards and stat sections
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.category-card, .stat-card, .section-title, .dashboard-card');
        
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementBottom = element.getBoundingClientRect().bottom;
            const windowHeight = window.innerHeight;
            
            // If element is in viewport
            if (elementTop < windowHeight - 100 && elementBottom > 0) {
                // Add slide-up animation if not already added
                if (!element.classList.contains('visible')) {
                    element.classList.add('visible');
                    element.style.animation = 'slideUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards';
                }
            }
        });
    };
    
    // Run once on page load
    setTimeout(animateOnScroll, 100);
    
    // Add scroll event listener
    window.addEventListener('scroll', animateOnScroll);
    
    // Button hover effects
    const buttons = document.querySelectorAll('.btn-primary, .btn-outline-primary');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 6px 12px rgba(224, 124, 79, 0.4)';
            this.style.transition = 'all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
    
    // Table row hover enhancement
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(224, 124, 79, 0.05)';
            this.style.transition = 'background-color 0.2s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Auto dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert && document.body.contains(alert)) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                
                setTimeout(() => {
                    if (alert && document.body.contains(alert)) {
                        alert.remove();
                    }
                }, 500);
            }
        }, 5000);
    });
    
    // Add parallax effect to hero section
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;
            if (scrollY < 500) {
                heroSection.style.backgroundPositionY = scrollY * 0.5 + 'px';
            }
        });
    }
});

/**
 * Confirm deletion with a styled modal
 * @param {string} itemName - The name of the item to delete
 * @param {string} deleteUrl - The URL to send the delete request to
 */
function confirmDelete(itemName, deleteUrl) {
    // First check if we can use SweetAlert (if included in the project)
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you really want to delete "${itemName}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e07c4f',
            cancelButtonColor: '#3f3f3f',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = deleteUrl;
            }
        });
    } else {
        // Fallback to regular confirm
        if (confirm(`Are you sure you want to delete "${itemName}"? This action cannot be undone.`)) {
            window.location.href = deleteUrl;
        }
    }
}

/**
 * Toggle visibility of an element with animation
 * @param {string} elementId - The ID of the element to toggle
 */
function toggleElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        if (element.style.display === 'none' || element.style.display === '') {
            element.style.display = 'block';
            element.style.opacity = '0';
            element.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                element.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, 10);
        } else {
            element.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            element.style.opacity = '0';
            element.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                element.style.display = 'none';
            }, 300);
        }
    }
}

/**
 * Copy text to clipboard with visual feedback
 * @param {string} text - The text to copy
 * @param {string} feedbackId - The ID of the element to show feedback in
 */
function copyToClipboard(text, feedbackId) {
    navigator.clipboard.writeText(text).then(() => {
        const feedbackElement = document.getElementById(feedbackId);
        if (feedbackElement) {
            feedbackElement.textContent = 'Copied!';
            feedbackElement.style.display = 'block';
            feedbackElement.style.opacity = '1';
            
            setTimeout(() => {
                feedbackElement.style.opacity = '0';
                setTimeout(() => {
                    feedbackElement.style.display = 'none';
                }, 300);
            }, 2000);
        }
    }).catch(err => {
        console.error('Failed to copy text: ', err);
    });
}
