/**
 * L1J Database Main JavaScript Functions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize dropdown menus
    initializeDropdowns();
    
    // Initialize search functionality
    initializeSearch();
    
    // Initialize admin form handlers if on admin page
    if (document.querySelector('.admin-content')) {
        initializeAdminForms();
    }
});

/**
 * Initialize dropdown menus
 */
function initializeDropdowns() {
    // Mobile dropdown toggle
    const dropdownToggles = document.querySelectorAll('.dropdown');
    
    if (window.innerWidth <= 768) {
        dropdownToggles.forEach(toggle => {
            const link = toggle.querySelector('.nav-link');
            const content = toggle.querySelector('.dropdown-content');
            
            if (link && content) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    content.style.display = content.style.display === 'block' ? 'none' : 'block';
                });
            }
        });
    }
}

/**
 * Initialize search functionality
 */
function initializeSearch() {
    const searchForm = document.querySelector('.search-form');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const searchInput = searchForm.querySelector('.search-input');
            const searchTerm = searchInput.value.trim();
            
            if (searchTerm.length < 2) {
                alert('Please enter at least 2 characters to search.');
                return;
            }
            
            // Redirect to search results page
            window.location.href = `/search.php?q=${encodeURIComponent(searchTerm)}`;
        });
    }
}

/**
 * Initialize admin form handlers
 */
function initializeAdminForms() {
    const adminForms = document.querySelectorAll('.admin-form');
    
    adminForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate the form
            if (validateForm(form)) {
                // Submit the form
                form.submit();
            }
        });
    });
}

/**
 * Validate form inputs
 * @param {HTMLFormElement} form The form to validate
 * @returns {boolean} Whether the form is valid
 */
function validateForm(form) {
    let isValid = true;
    const requiredInputs = form.querySelectorAll('[required]');
    
    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            
            // Display error message
            const formGroup = input.closest('.form-group');
            
            if (formGroup) {
                let errorMessage = formGroup.querySelector('.error-message');
                
                if (!errorMessage) {
                    errorMessage = document.createElement('div');
                    errorMessage.className = 'error-message';
                    errorMessage.style.color = 'red';
                    errorMessage.style.fontSize = '0.875rem';
                    errorMessage.style.marginTop = '0.25rem';
                    formGroup.appendChild(errorMessage);
                }
                
                errorMessage.textContent = 'This field is required.';
            }
            
            // Highlight the input
            input.style.borderColor = 'red';
        } else {
            // Clear any error messages
            const formGroup = input.closest('.form-group');
            
            if (formGroup) {
                const errorMessage = formGroup.querySelector('.error-message');
                
                if (errorMessage) {
                    errorMessage.remove();
                }
            }
            
            // Reset input style
            input.style.borderColor = '';
        }
    });
    
    return isValid;
}

/**
 * Toggle item details visibility
 * @param {string} itemId The ID of the item to toggle
 */
function toggleItemDetails(itemId) {
    const detailsElement = document.getElementById(`details-${itemId}`);
    
    if (detailsElement) {
        detailsElement.classList.toggle('hidden');
    }
}

/**
 * Confirm delete action
 * @param {string} itemName The name of the item to delete
 * @returns {boolean} Whether the user confirmed the deletion
 */
function confirmDelete(itemName) {
    return confirm(`Are you sure you want to delete "${itemName}"? This action cannot be undone.`);
}

/**
 * Format number with commas
 * @param {number} number The number to format
 * @returns {string} The formatted number
 */
function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/**
 * Create pagination controls
 * @param {number} currentPage The current page number
 * @param {number} totalPages The total number of pages
 * @param {string} baseUrl The base URL for pagination links
 * @returns {HTMLElement} The pagination element
 */
function createPagination(currentPage, totalPages, baseUrl) {
    const paginationElement = document.createElement('div');
    paginationElement.className = 'pagination';
    
    // Previous page link
    if (currentPage > 1) {
        const prevLink = document.createElement('a');
        prevLink.href = `${baseUrl}?page=${currentPage - 1}`;
        prevLink.className = 'page-link';
        prevLink.textContent = '←';
        
        const prevItem = document.createElement('div');
        prevItem.className = 'page-item';
        prevItem.appendChild(prevLink);
        
        paginationElement.appendChild(prevItem);
    }
    
    // Page number links
    for (let i = 1; i <= totalPages; i++) {
        // Display limited number of pages
        if (
            i === 1 || // First page
            i === totalPages || // Last page
            (i >= currentPage - 2 && i <= currentPage + 2) // 2 pages before and after current
        ) {
            const pageLink = document.createElement('a');
            pageLink.href = `${baseUrl}?page=${i}`;
            pageLink.className = 'page-link';
            
            if (i === currentPage) {
                pageLink.className += ' active';
            }
            
            pageLink.textContent = i.toString();
            
            const pageItem = document.createElement('div');
            pageItem.className = 'page-item';
            pageItem.appendChild(pageLink);
            
            paginationElement.appendChild(pageItem);
        } else if (
            (i === currentPage - 3 && currentPage > 3) ||
            (i === currentPage + 3 && currentPage < totalPages - 2)
        ) {
            // Add ellipsis
            const ellipsis = document.createElement('div');
            ellipsis.className = 'page-item';
            ellipsis.textContent = '...';
            paginationElement.appendChild(ellipsis);
        }
    }
    
    // Next page link
    if (currentPage < totalPages) {
        const nextLink = document.createElement('a');
        nextLink.href = `${baseUrl}?page=${currentPage + 1}`;
        nextLink.className = 'page-link';
        nextLink.textContent = '→';
        
        const nextItem = document.createElement('div');
        nextItem.className = 'page-item';
        nextItem.appendChild(nextLink);
        
        paginationElement.appendChild(nextItem);
    }
    
    return paginationElement;
}

/**
 * Filter table rows based on search input
 * @param {string} inputId The ID of the input element
 * @param {string} tableId The ID of the table element
 */
function filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toUpperCase();
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip header row
        let visible = false;
        const cells = rows[i].getElementsByTagName('td');
        
        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];
            
            if (cell) {
                const text = cell.textContent || cell.innerText;
                
                if (text.toUpperCase().indexOf(filter) > -1) {
                    visible = true;
                    break;
                }
            }
        }
        
        rows[i].style.display = visible ? '' : 'none';
    }
}
