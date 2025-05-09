/**
 * Admin JavaScript file for the Lineage II Database (Admin Section)
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize modals
    initModals();
    
    // Initialize alerts auto-hide
    initAlerts();
    
    // Initialize form validations
    initFormValidations();
});

/**
 * Initialize modals functionality
 */
function initModals() {
    // Close modal when clicking on backdrop
    const modals = document.querySelectorAll('.modal-backdrop');
    
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this);
            }
        });
        
        // Close button functionality
        const closeButtons = modal.querySelectorAll('.modal-close, [data-dismiss="modal"]');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                closeModal(modal);
            });
        });
    });
}

/**
 * Close a modal
 * 
 * @param {HTMLElement} modal - Modal element to close
 */
function closeModal(modal) {
    modal.classList.remove('show');
}

/**
 * Show a modal
 * 
 * @param {string} modalId - ID of modal to show
 */
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
    }
}

/**
 * Initialize alerts auto-hide
 */
function initAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-persistent)');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            
            setTimeout(() => {
                alert.style.display = 'none';
            }, 300);
        }, 5000);
    });
}

/**
 * Initialize form validations
 */
function initFormValidations() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    
                    // Add error message if not exists
                    const fieldParent = field.parentElement;
                    if (!fieldParent.querySelector('.error-message')) {
                        const errorMessage = document.createElement('div');
                        errorMessage.className = 'error-message';
                        errorMessage.textContent = 'This field is required';
                        fieldParent.appendChild(errorMessage);
                    }
                } else {
                    field.classList.remove('is-invalid');
                    
                    // Remove error message if exists
                    const fieldParent = field.parentElement;
                    const errorMessage = fieldParent.querySelector('.error-message');
                    if (errorMessage) {
                        fieldParent.removeChild(errorMessage);
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Confirm deletion of an item
 * 
 * @param {string} itemType - Type of item being deleted
 * @param {number} itemId - ID of item being deleted
 * @param {string} itemName - Name of item being deleted
 * @param {string} formId - ID of form to submit for deletion
 */
function confirmDelete(itemType, itemId, itemName, formId) {
    // Set confirmation message
    const message = `Are you sure you want to delete the ${itemType} "${itemName}" (ID: ${itemId})?`;
    
    if (confirm(message)) {
        // Submit deletion form
        document.getElementById(formId).submit();
    }
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
 * Toggle field visibility based on checkbox state
 * 
 * @param {string} checkboxId - ID of checkbox
 * @param {string} fieldId - ID of field to toggle
 * @param {boolean} invert - Whether to invert the logic (show when unchecked)
 */
function toggleFieldVisibility(checkboxId, fieldId, invert = false) {
    const checkbox = document.getElementById(checkboxId);
    const field = document.getElementById(fieldId);
    
    if (!checkbox || !field) return;
    
    function updateVisibility() {
        const isChecked = checkbox.checked;
        field.style.display = (isChecked !== invert) ? 'block' : 'none';
    }
    
    // Initial visibility
    updateVisibility();
    
    // Update on change
    checkbox.addEventListener('change', updateVisibility);
}
