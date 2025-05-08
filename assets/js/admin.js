/**
 * Enhanced Admin-specific JavaScript for L1J Remastered Database
 * Adds modern admin interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Confirm delete actions with improved visual feedback
    const deleteButtons = document.querySelectorAll('.btn-delete, [data-action="delete"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const itemName = this.getAttribute('data-item-name') || 'this item';
            const deleteUrl = this.getAttribute('href') || this.getAttribute('data-url');
            
            // Use confirmDelete function from main.js
            if (typeof confirmDelete === 'function') {
                confirmDelete(itemName, deleteUrl);
            } else {
                // Fallback if main.js is not loaded or function not available
                if (confirm(`Are you sure you want to delete ${itemName}? This action cannot be undone.`)) {
                    window.location.href = deleteUrl;
                }
            }
        });
    });
    
    // Toggle advanced filters with smooth animation
    const filterToggle = document.getElementById('toggleFilters');
    if (filterToggle) {
        const filterSection = document.getElementById('advancedFilters');
        
        filterToggle.addEventListener('click', function() {
            if (filterSection.classList.contains('d-none')) {
                // Show filters
                filterSection.classList.remove('d-none');
                filterSection.style.opacity = '0';
                filterSection.style.maxHeight = '0';
                filterSection.style.overflow = 'hidden';
                
                setTimeout(() => {
                    filterSection.style.transition = 'opacity 0.3s ease, max-height 0.5s ease';
                    filterSection.style.opacity = '1';
                    filterSection.style.maxHeight = '1000px'; // Arbitrary large value
                }, 10);
                
                // Update button text
                this.textContent = 'Hide Advanced Filters';
                this.innerHTML = '<i class="fas fa-chevron-up me-2"></i> Hide Advanced Filters';
            } else {
                // Hide filters
                filterSection.style.transition = 'opacity 0.3s ease, max-height 0.5s ease';
                filterSection.style.opacity = '0';
                filterSection.style.maxHeight = '0';
                
                setTimeout(() => {
                    filterSection.classList.add('d-none');
                }, 500);
                
                // Update button text
                this.textContent = 'Show Advanced Filters';
                this.innerHTML = '<i class="fas fa-chevron-down me-2"></i> Show Advanced Filters';
            }
        });
    }
    
    // Auto-dismiss alerts with fade animation
    const autoDismissAlerts = document.querySelectorAll('.alert-auto-dismiss');
    autoDismissAlerts.forEach(alert => {
        setTimeout(() => {
            // Check if the alert still exists in the DOM
            if (document.body.contains(alert)) {
                // Slide up and fade out effect
                alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease, max-height 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                alert.style.maxHeight = '0';
                alert.style.marginBottom = '0';
                alert.style.paddingTop = '0';
                alert.style.paddingBottom = '0';
                
                // Remove from DOM after fade out
                setTimeout(() => {
                    if (document.body.contains(alert)) {
                        alert.remove();
                    }
                }, 500);
            }
        }, 5000); // Dismiss after 5 seconds
    });
    
    // Form validation with visual feedback and smooth scrolling
    const adminForms = document.querySelectorAll('.admin-form');
    adminForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    
                    // Add an error message if it doesn't exist
                    let errorMessage = field.nextElementSibling;
                    if (!errorMessage || !errorMessage.classList.contains('invalid-feedback')) {
                        errorMessage = document.createElement('div');
                        errorMessage.classList.add('invalid-feedback');
                        errorMessage.textContent = 'This field is required';
                        field.insertAdjacentElement('afterend', errorMessage);
                        
                        // Show the feedback with animation
                        errorMessage.style.display = 'block';
                        errorMessage.style.opacity = '0';
                        setTimeout(() => {
                            errorMessage.style.transition = 'opacity 0.3s ease';
                            errorMessage.style.opacity = '1';
                        }, 10);
                    }
                    
                    // Add shake animation to field
                    field.classList.add('shake');
                    setTimeout(() => {
                        field.classList.remove('shake');
                    }, 500);
                } else {
                    field.classList.remove('is-invalid');
                    
                    // Remove any existing error message
                    const errorMessage = field.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains('invalid-feedback')) {
                        errorMessage.style.display = 'none';
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                
                // Add a validation alert message if it doesn't exist
                let validationAlert = form.querySelector('.validation-alert');
                if (!validationAlert) {
                    validationAlert = document.createElement('div');
                    validationAlert.className = 'alert alert-danger validation-alert';
                    validationAlert.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i> Please fill out all required fields.';
                    form.prepend(validationAlert);
                    
                    // Animate the alert in
                    validationAlert.style.opacity = '0';
                    validationAlert.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        validationAlert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        validationAlert.style.opacity = '1';
                        validationAlert.style.transform = 'translateY(0)';
                    }, 10);
                    
                    // Auto-dismiss after 5 seconds
                    setTimeout(() => {
                        if (document.body.contains(validationAlert)) {
                            validationAlert.style.opacity = '0';
                            validationAlert.style.transform = 'translateY(-10px)';
                            setTimeout(() => validationAlert.remove(), 300);
                        }
                    }, 5000);
                }
                
                // Scroll to the first invalid field with smooth scroll
                const firstInvalidField = form.querySelector('.is-invalid');
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center'
                    });
                    firstInvalidField.focus();
                }
            }
        });
        
        // Clear invalid state on input
        const inputFields = form.querySelectorAll('input, textarea, select');
        inputFields.forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                
                // Hide the error message
                const errorMessage = this.nextElementSibling;
                if (errorMessage && errorMessage.classList.contains('invalid-feedback')) {
                    errorMessage.style.display = 'none';
                }
                
                // Check if all fields are valid, then hide the validation alert
                const invalidFields = form.querySelectorAll('.is-invalid');
                if (invalidFields.length === 0) {
                    const validationAlert = form.querySelector('.validation-alert');
                    if (validationAlert) {
                        validationAlert.style.opacity = '0';
                        validationAlert.style.transform = 'translateY(-10px)';
                        setTimeout(() => validationAlert.remove(), 300);
                    }
                }
            });
        });
    });
    
    // Preview image upload with fade-in effect
    const imageUploads = document.querySelectorAll('.image-upload-input');
    imageUploads.forEach(input => {
        input.addEventListener('change', function() {
            const previewId = this.getAttribute('data-preview');
            const preview = document.getElementById(previewId);
            
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Hide preview initially
                    preview.style.opacity = '0';
                    preview.src = e.target.result;
                    
                    // Show preview with animation
                    if (preview.style.display === 'none' || preview.style.display === '') {
                        preview.style.display = 'block';
                    }
                    
                    // Trigger reflow to ensure transition works
                    preview.offsetHeight;
                    
                    // Fade in
                    preview.style.transition = 'opacity 0.5s ease';
                    preview.style.opacity = '1';
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
    
    // Add hover effects to stat cards
    const statCards = document.querySelectorAll('.admin-stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            // Highlight value
            const statValue = this.querySelector('.stat-value');
            if (statValue) {
                statValue.style.transform = 'scale(1.1)';
                statValue.style.textShadow = '0 0 15px var(--color-accent-glow)';
                statValue.style.transition = 'transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), text-shadow 0.3s ease';
            }
            
            // Animate icon
            const statIcon = this.querySelector('.stat-icon');
            if (statIcon) {
                statIcon.style.transform = 'rotate(15deg) scale(1.1)';
                statIcon.style.color = 'rgba(224, 124, 79, 0.1)';
                statIcon.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), color 0.5s ease';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const statValue = this.querySelector('.stat-value');
            if (statValue) {
                statValue.style.transform = '';
                statValue.style.textShadow = '';
            }
            
            const statIcon = this.querySelector('.stat-icon');
            if (statIcon) {
                statIcon.style.transform = '';
                statIcon.style.color = '';
            }
        });
    });
    
    // Quick action buttons enhanced hover effect
    const quickActionBtns = document.querySelectorAll('.quick-actions .btn');
    quickActionBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1.2) rotate(5deg)';
                icon.style.transition = 'transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            }
        });
        
        btn.addEventListener('mouseleave', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = '';
            }
        });
    });
    
    // Number counter animation for stat values
    const animateCounter = (element, start, end, duration) => {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const value = Math.floor(progress * (end - start) + start);
            element.textContent = value.toLocaleString();
            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                element.textContent = end.toLocaleString();
            }
        };
        window.requestAnimationFrame(step);
    };
    
    // Apply to stat values
    const statValues = document.querySelectorAll('.stat-value');
    statValues.forEach(statValue => {
        const value = parseInt(statValue.textContent.replace(/,/g, ''));
        if (!isNaN(value)) {
            statValue.textContent = '0';
            
            // Use IntersectionObserver to trigger when visible
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(statValue, 0, value, 1000);
                        observer.unobserve(statValue);
                    }
                });
            }, { threshold: 0.1 });
            
            observer.observe(statValue);
        }
    });
});

/**
 * Toggle the status of an item (active/inactive) with improved visual feedback
 * @param {number} id - The ID of the item to toggle
 * @param {string} type - The type of item (category, item, etc.)
 * @param {boolean} currentStatus - The current status
 */
function toggleStatus(id, type, currentStatus) {
    const newStatus = !currentStatus;
    const url = `update-status.php?id=${id}&type=${type}&status=${newStatus ? 1 : 0}`;
    
    // Button to update UI
    const button = document.querySelector(`#status-${id}`);
    
    // Optimistically update UI (while showing a loading state)
    if (button) {
        // Save original text
        const originalText = button.innerHTML;
        
        // Show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button with success message temporarily
                    button.innerHTML = '<i class="fas fa-check"></i> Updated!';
                    button.className = 'btn btn-success';
                    
                    // After a short delay, show the final state
                    setTimeout(() => {
                        if (newStatus) {
                            button.classList.remove('btn-secondary', 'btn-success');
                            button.classList.add('btn-success');
                            button.innerHTML = '<i class="fas fa-check-circle"></i> Active';
                        } else {
                            button.classList.remove('btn-success', 'btn-success');
                            button.classList.add('btn-secondary');
                            button.innerHTML = '<i class="fas fa-times-circle"></i> Inactive';
                        }
                        button.setAttribute('onclick', `toggleStatus(${id}, '${type}', ${newStatus})`);
                        button.disabled = false;
                    }, 800);
                } else {
                    // Show error state temporarily
                    button.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Failed';
                    button.className = 'btn btn-danger';
                    
                    // After a short delay, revert to original state
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.className = currentStatus ? 'btn btn-success' : 'btn btn-secondary';
                        button.disabled = false;
                    }, 1500);
                    
                    // Show an error message
                    showToast('Error updating status: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error state temporarily
                button.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error';
                button.className = 'btn btn-danger';
                
                // After a short delay, revert to original state
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.className = currentStatus ? 'btn btn-success' : 'btn btn-secondary';
                    button.disabled = false;
                }, 1500);
                
                // Show an error message
                showToast('An error occurred while updating the status', 'error');
            });
    }
}

/**
 * Show a toast notification message
 * @param {string} message - The message to display
 * @param {string} type - The type of message (success, error, warning, info)
 * @param {number} duration - The duration in milliseconds
 */
function showToast(message, type = 'info', duration = 3000) {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.top = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    // Create the toast element
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.style.backgroundColor = type === 'error' ? '#ff5252' : 
                                 type === 'success' ? '#20c997' : 
                                 type === 'warning' ? '#ffc107' : '#4c9aff';
    toast.style.color = '#fff';
    toast.style.padding = '12px 20px';
    toast.style.borderRadius = '8px';
    toast.style.marginBottom = '10px';
    toast.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
    toast.style.display = 'flex';
    toast.style.alignItems = 'center';
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(50px)';
    toast.style.transition = 'opacity 0.3s, transform 0.3s';
    
    // Add the appropriate icon
    let icon;
    switch(type) {
        case 'success': icon = 'check-circle'; break;
        case 'error': icon = 'exclamation-circle'; break;
        case 'warning': icon = 'exclamation-triangle'; break;
        default: icon = 'info-circle';
    }
    
    toast.innerHTML = `
        <i class="fas fa-${icon}" style="margin-right: 10px;"></i>
        <span>${message}</span>
    `;
    
    // Add the toast to the container
    toastContainer.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    // Animate out after duration
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(50px)';
        
        // Remove from DOM after animation
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, duration);
}

/**
 * Bulk action handler with progress animation
 * @param {string} action - The action to perform (delete, update, etc.)
 * @param {string} formId - The ID of the form containing the checkboxes
 */
function bulkAction(action, formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    // Check if any items are selected
    const selectedItems = form.querySelectorAll('input[name="selected_items[]"]:checked');
    if (selectedItems.length === 0) {
        showToast('Please select at least one item to perform this action', 'warning');
        return;
    }
    
    // Confirm the action
    let actionLabel;
    switch(action) {
        case 'delete': actionLabel = 'delete'; break;
        case 'activate': actionLabel = 'activate'; break;
        case 'deactivate': actionLabel = 'deactivate'; break;
        default: actionLabel = action;
    }
    
    if (confirm(`Are you sure you want to ${actionLabel} the selected items? This may affect ${selectedItems.length} items.`)) {
        // Set the action value
        const actionInput = form.querySelector('input[name="bulk_action"]');
        if (actionInput) {
            actionInput.value = action;
        }
        
        // Submit the form
        form.submit();
    }
}
