/**
 * Admin-specific JavaScript for L1J Remastered Database Browser
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize admin-specific functionality
    setupDataTables();
    setupFormValidation();
    setupDynamicFormFields();
    setupMarkdownEditor();
    setupAlertDismissal();
    setupConfirmationDialogs();
});

/**
 * Setup data tables for list views
 */
function setupDataTables() {
    const tables = document.querySelectorAll('.list-table');
    
    tables.forEach(table => {
        // Handle row selection
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Skip if clicked on action buttons
                if (e.target.closest('.action-links')) {
                    return;
                }
                
                // Toggle selected state
                rows.forEach(r => r.classList.remove('selected'));
                this.classList.add('selected');
                
                // If data-url is set, navigate to it
                const url = this.getAttribute('data-url');
                if (url) {
                    window.location.href = url;
                }
            });
        });
        
        // Handle bulk actions
        const bulkActionForm = document.querySelector('.bulk-actions form');
        if (bulkActionForm) {
            const checkboxes = table.querySelectorAll('input[type="checkbox"]');
            const selectAll = document.querySelector('#select-all');
            
            // Select all functionality
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    
                    updateBulkActionsState();
                });
            }
            
            // Update select all state when individual checkboxes change
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllState();
                    updateBulkActionsState();
                });
            });
            
            // Disable bulk actions if no items selected
            function updateBulkActionsState() {
                const selectedCount = table.querySelectorAll('input[type="checkbox"]:checked').length - 
                                      (selectAll && selectAll.checked ? 1 : 0);
                
                const bulkActions = bulkActionForm.querySelector('select');
                const bulkSubmit = bulkActionForm.querySelector('button');
                
                if (bulkActions && bulkSubmit) {
                    bulkActions.disabled = selectedCount === 0;
                    bulkSubmit.disabled = selectedCount === 0;
                }
                
                // Update selected count
                const selectedCountEl = document.querySelector('.selected-count');
                if (selectedCountEl) {
                    selectedCountEl.textContent = selectedCount;
                }
            }
            
            // Update select all checkbox state
            function updateSelectAllState() {
                if (selectAll) {
                    const totalCheckboxes = checkboxes.length - 1; // Exclude the "select all" checkbox
                    const checkedCheckboxes = table.querySelectorAll('tbody input[type="checkbox"]:checked').length;
                    
                    selectAll.checked = totalCheckboxes > 0 && checkedCheckboxes === totalCheckboxes;
                    selectAll.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
                }
            }
        }
    });
}

/**
 * Setup form validation
 */
function setupFormValidation() {
    const forms = document.querySelectorAll('.admin-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let valid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            // Remove existing error messages
            const existingErrors = form.querySelectorAll('.form-error');
            existingErrors.forEach(error => error.remove());
            
            // Check required fields
            requiredFields.forEach(field => {
                field.classList.remove('error');
                
                if (!field.value.trim()) {
                    e.preventDefault();
                    valid = false;
                    
                    field.classList.add('error');
                    
                    // Add error message
                    const errorMsg = document.createElement('div');
                    errorMsg.classList.add('form-text', 'form-error');
                    errorMsg.textContent = 'This field is required';
                    
                    const formGroup = field.closest('.form-group');
                    if (formGroup) {
                        formGroup.appendChild(errorMsg);
                    }
                }
            });
            
            return valid;
        });
    });
}

/**
 * Setup dynamic form fields
 */
function setupDynamicFormFields() {
    // Handle repeatable field groups
    const addButtons = document.querySelectorAll('.add-field-group');
    
    addButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const container = this.closest('.repeatable-container');
            if (!container) return;
            
            // Clone the template
            const template = container.querySelector('.field-group-template');
            if (!template) return;
            
            const clone = template.cloneNode(true);
            clone.classList.remove('field-group-template');
            clone.classList.add('field-group');
            
            // Clear input values
            const inputs = clone.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.value = '';
                input.name = input.name.replace('[template]', `[${Date.now()}]`);
            });
            
            // Setup remove button
            const removeButton = clone.querySelector('.remove-field-group');
            if (removeButton) {
                removeButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    clone.remove();
                });
            }
            
            // Add to container
            const fieldGroups = container.querySelector('.field-groups');
            if (fieldGroups) {
                fieldGroups.appendChild(clone);
            }
        });
    });
    
    // Setup existing remove buttons
    const removeButtons = document.querySelectorAll('.field-group .remove-field-group');
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const group = this.closest('.field-group');
            if (group) {
                group.remove();
            }
        });
    });
    
    // Handle dependent fields (show/hide based on another field's value)
    const dependentTriggers = document.querySelectorAll('[data-toggle-field]');
    
    dependentTriggers.forEach(trigger => {
        const updateDependentField = function() {
            const targetSelector = trigger.getAttribute('data-toggle-field');
            const targetValue = trigger.getAttribute('data-toggle-value');
            const targetField = document.querySelector(targetSelector);
            
            if (targetField) {
                const fieldContainer = targetField.closest('.form-group');
                
                if (fieldContainer) {
                    if (trigger.type === 'checkbox') {
                        fieldContainer.style.display = trigger.checked ? 'block' : 'none';
                    } else {
                        fieldContainer.style.display = (trigger.value === targetValue) ? 'block' : 'none';
                    }
                }
            }
        };
        
        // Initial update
        updateDependentField();
        
        // Update on change
        trigger.addEventListener('change', updateDependentField);
    });
}

/**
 * Setup simplified markdown editor
 */
function setupMarkdownEditor() {
    const editors = document.querySelectorAll('.markdown-editor');
    
    editors.forEach(editor => {
        const textarea = editor.querySelector('textarea');
        if (!textarea) return;
        
        // Create toolbar
        const toolbar = document.createElement('div');
        toolbar.classList.add('markdown-toolbar');
        editor.insertBefore(toolbar, textarea);
        
        // Add toolbar buttons
        const buttons = [
            { icon: 'B', title: 'Bold', action: () => insertMarkdown('**', '**') },
            { icon: 'I', title: 'Italic', action: () => insertMarkdown('_', '_') },
            { icon: 'H1', title: 'Heading 1', action: () => insertMarkdown('# ', '') },
            { icon: 'H2', title: 'Heading 2', action: () => insertMarkdown('## ', '') },
            { icon: 'H3', title: 'Heading 3', action: () => insertMarkdown('### ', '') },
            { icon: 'UL', title: 'Bullet List', action: () => insertMarkdown('- ', '') },
            { icon: 'OL', title: 'Numbered List', action: () => insertMarkdown('1. ', '') },
            { icon: 'Link', title: 'Link', action: () => insertMarkdown('[', '](url)') },
            { icon: 'Image', title: 'Image', action: () => insertMarkdown('![alt text](', ')') },
            { icon: 'Code', title: 'Code', action: () => insertMarkdown('`', '`') },
            { icon: 'Table', title: 'Table', action: insertTable }
        ];
        
        buttons.forEach(button => {
            const btn = document.createElement('button');
            btn.innerHTML = button.icon;
            btn.title = button.title;
            btn.type = 'button';
            btn.classList.add('md-button');
            btn.addEventListener('click', button.action);
            toolbar.appendChild(btn);
        });
        
        function getSelectionText() {
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            return textarea.value.substring(start, end);
        }
        
        function insertMarkdown(prefix, suffix) {
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = getSelectionText();
            const text = textarea.value;
            
            textarea.value = text.substring(0, start) + prefix + selectedText + suffix + text.substring(end);
            
            // Set cursor position
            textarea.focus();
            textarea.setSelectionRange(start + prefix.length, start + prefix.length + selectedText.length);
        }
        
        function insertTable() {
            const tableTemplate = `
| Header | Header | Header |
|--------|--------|--------|
| Cell   | Cell   | Cell   |
| Cell   | Cell   | Cell   |
`;
            insertMarkdown(tableTemplate, '');
        }
    });
}

/**
 * Setup alert dismissal
 */
function setupAlertDismissal() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        // Add dismiss button
        const dismissBtn = document.createElement('button');
        dismissBtn.innerHTML = '&times;';
        dismissBtn.classList.add('alert-dismiss');
        dismissBtn.addEventListener('click', function() {
            alert.remove();
        });
        
        alert.appendChild(dismissBtn);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alert.classList.add('fade-out');
            
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
}

/**
 * Setup confirmation dialogs for destructive actions
 */
function setupConfirmationDialogs() {
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    
    confirmButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 'Are you sure you want to proceed?';
            
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });
}
