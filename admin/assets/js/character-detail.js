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
    
    // Initialize equipment and inventory interactions
    initInventoryInteractions();
    
    // Initialize inventory pagination
    initInventoryPagination();
});

/**
 * Initialize tooltip functionality
 */
function initTooltips() {
    // Already implemented in CSS with pure CSS tooltips
    console.log("Tooltips initialized");
    
    // Add mobile touch support for tooltips
    const tooltipElements = document.querySelectorAll('.tooltip');
    tooltipElements.forEach(tooltip => {
        tooltip.addEventListener('touchstart', function(e) {
            // Find all tooltip texts and hide them
            document.querySelectorAll('.tooltip-text-visible').forEach(tt => {
                tt.classList.remove('tooltip-text-visible');
            });
            
            // Show this tooltip text
            const tooltipText = this.querySelector('.tooltip-text');
            if (tooltipText) {
                tooltipText.classList.add('tooltip-text-visible');
                e.preventDefault(); // Prevent normal touch event
                
                // Hide after 3 seconds
                setTimeout(() => {
                    tooltipText.classList.remove('tooltip-text-visible');
                }, 3000);
            }
        });
    });
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
                
                // Update URL hash for bookmarking
                window.location.hash = tabId;
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
    console.log(`Loading content for tab: ${tabId}`);
    
    // Handle inventory tab specifically
    if (tabId === 'inventory') {
        handleInventoryTabLoad();
    }
}

/**
 * Handle inventory tab loading with improved visuals
 */
function handleInventoryTabLoad() {
    const inventoryGrid = document.getElementById('inventory-grid');
    const equipmentSlots = document.querySelector('.equipment-slots');
    
    if (inventoryGrid) {
        // Check if images need loading
        const images = inventoryGrid.querySelectorAll('img');
        let imagesLoaded = 0;
        const totalImages = images.length;
        
        // Add loading state class to inventory
        inventoryGrid.classList.add('loading');
        
        if (equipmentSlots) {
            equipmentSlots.classList.add('loading');
        }
        
        // If no images, remove loading immediately
        if (totalImages === 0) {
            inventoryGrid.classList.remove('loading');
            if (equipmentSlots) equipmentSlots.classList.remove('loading');
            return;
        }
        
        // Listen for all images to load or error
        images.forEach(img => {
            // Already loaded images
            if (img.complete) {
                imagesLoaded++;
                if (imagesLoaded === totalImages) {
                    inventoryGrid.classList.remove('loading');
                    if (equipmentSlots) equipmentSlots.classList.remove('loading');
                }
            }
            
            // Images still loading
            img.addEventListener('load', () => {
                imagesLoaded++;
                if (imagesLoaded === totalImages) {
                    inventoryGrid.classList.remove('loading');
                    if (equipmentSlots) equipmentSlots.classList.remove('loading');
                }
            });
            
            // Handle errors too
            img.addEventListener('error', () => {
                imagesLoaded++;
                if (imagesLoaded === totalImages) {
                    inventoryGrid.classList.remove('loading');
                    if (equipmentSlots) equipmentSlots.classList.remove('loading');
                }
            });
        });
        
        // Fallback - hide loading after 2 seconds regardless
        setTimeout(() => {
            inventoryGrid.classList.remove('loading');
            if (equipmentSlots) equipmentSlots.classList.remove('loading');
        }, 2000);
    }
    
    // Make sure we equalize inventory and equipment card heights
    equalizeHeights();
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
 * Initialize equipment and inventory interactions
 */
function initInventoryInteractions() {
    // Equipment slots
    const equipmentSlots = document.querySelectorAll('.equipment-slot');
    equipmentSlots.forEach(slot => {
        // Add hover effect
        slot.addEventListener('mouseenter', function() {
            if (!this.classList.contains('locked')) {
                this.style.transform = 'scale(1.05)';
                this.style.zIndex = '10';
            }
        });
        
        slot.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.zIndex = '';
        });
        
        // Add click action for future functionality
        slot.addEventListener('click', function() {
            if (!this.classList.contains('locked')) {
                const slotType = this.getAttribute('data-type');
                const slotName = this.getAttribute('data-slot');
                console.log(`Clicked on slot: ${slotName} (${slotType})`);
                // Future: Show equipment options or item details
            }
        });
    });
    
    // Inventory slots
    const inventorySlots = document.querySelectorAll('.inventory-slot:not(.empty)');
    inventorySlots.forEach(slot => {
        // Add hover effect
        slot.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.zIndex = '10';
        });
        
        slot.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.zIndex = '';
        });
        
        // Add click action for future functionality
        slot.addEventListener('click', function() {
            const itemId = this.querySelector('.item-icon')?.getAttribute('data-item-id');
            if (itemId) {
                console.log(`Clicked on inventory item: ${itemId}`);
                // Future: Show item details or action menu
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

/**
 * Adjust equipment and inventory areas to match heights
 */
function equalizeHeights() {
    const equipmentCard = document.querySelector('.equipment-card');
    const inventoryCard = document.querySelector('.inventory-card');
    
    if (equipmentCard && inventoryCard && window.innerWidth > 1200) {
        // Reset heights first
        equipmentCard.style.height = '';
        inventoryCard.style.height = '';
        
        // Get natural heights
        const equipmentHeight = equipmentCard.offsetHeight;
        const inventoryHeight = inventoryCard.offsetHeight;
        
        // Set both to the larger height
        const maxHeight = Math.max(equipmentHeight, inventoryHeight);
        equipmentCard.style.height = `${maxHeight}px`;
        inventoryCard.style.height = `${maxHeight}px`;
    }
}

/**
 * Initialize inventory pagination
 */
function initInventoryPagination() {
    const prevButton = document.getElementById('prev-page');
    const nextButton = document.getElementById('next-page');
    const currentPageSpan = document.getElementById('current-page');
    const totalItemsInput = document.getElementById('total-items');
    const itemsPerPageInput = document.getElementById('items-per-page');
    
    if (!prevButton || !nextButton || !currentPageSpan || !totalItemsInput || !itemsPerPageInput) {
        return; // Required elements not found
    }
    
    const totalItems = parseInt(totalItemsInput.value);
    const itemsPerPage = parseInt(itemsPerPageInput.value);
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    
    let currentPage = 1;
    
    // Disable prev button on first page
    prevButton.disabled = currentPage === 1;
    // Disable next button if only one page
    nextButton.disabled = currentPage >= totalPages;
    
    prevButton.addEventListener('click', function() {
        if (currentPage > 1) {
            loadPage(currentPage - 1);
        }
    });
    
    nextButton.addEventListener('click', function() {
        if (currentPage < totalPages) {
            loadPage(currentPage + 1);
        }
    });
    
    function loadPage(pageNum) {
        if (pageNum < 1 || pageNum > totalPages) {
            return;
        }
        
        // Show loading indicator
        const inventoryGrid = document.getElementById('inventory-grid');
        if (inventoryGrid) {
            inventoryGrid.classList.add('loading');
        }
        
        // Fetch data for the requested page
        const charId = window.location.search.match(/id=(\d+)/)[1]; // Extract character ID from URL
        
        // AJAX request to get inventory data for the page
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `inventory-data.php?char_id=${charId}&page=${pageNum}&per_page=${itemsPerPage}`, true);
        
        xhr.onload = function() {
            if (this.status >= 200 && this.status < 300) {
                try {
                    const response = JSON.parse(this.responseText);
                    
                    if (response.success) {
                        // Update inventory grid with new items
                        updateInventoryGrid(response.items);
                        
                        // Update pagination controls
                        currentPage = pageNum;
                        currentPageSpan.textContent = currentPage;
                        prevButton.disabled = currentPage === 1;
                        nextButton.disabled = currentPage >= totalPages;
                        
                        // For demo purposes, simulate pagination without AJAX
                        simulatePagination(pageNum);
                    } else {
                        console.error('Error loading inventory page:', response.message);
                    }
                } catch (e) {
                    console.error('Error parsing inventory data:', e);
                    // For demo purposes, simulate pagination without AJAX
                    simulatePagination(pageNum);
                }
            } else {
                console.error('Error loading inventory page:', this.statusText);
                // For demo purposes, simulate pagination without AJAX
                simulatePagination(pageNum);
            }
            
            // Hide loading indicator
            if (inventoryGrid) {
                inventoryGrid.classList.remove('loading');
            }
        };
        
        xhr.onerror = function() {
            console.error('Network error when loading inventory page');
            // For demo purposes, simulate pagination without AJAX
            simulatePagination(pageNum);
            
            // Hide loading indicator
            if (inventoryGrid) {
                inventoryGrid.classList.remove('loading');
            }
        };
        
        // Send request
        xhr.send();
    }
    
    function updateInventoryGrid(items) {
        const inventoryGrid = document.getElementById('inventory-grid');
        if (!inventoryGrid) return;
        
        // Clear existing items
        inventoryGrid.innerHTML = '';
        
        // Add new items
        items.forEach(item => {
            const itemHtml = `
                <div class="inventory-slot tooltip">
                    <div class="item-icon" data-item-id="${item.item_id}">
                        <img src="${item.icon_url}" onerror="this.src='../assets/img/placeholders/noiconid.png'" alt="${item.item_name}">
                        ${item.count > 1 ? `<div class="item-count">${item.count}</div>` : ''}
                        ${item.enchantlvl > 0 ? `<div class="item-enchant">+${item.enchantlvl}</div>` : ''}
                        <span class="tooltip-text">
                            <strong>${item.item_name}</strong><br>
                            Quantity: ${item.count}<br>
                            ${item.enchantlvl > 0 ? `Enchant: +${item.enchantlvl}<br>` : ''}
                            ${item.attr_enchantlvl > 0 ? `Attribute: +${item.attr_enchantlvl}<br>` : ''}
                            ${item.special_enchant > 0 ? `Special: +${item.special_enchant}<br>` : ''}
                            ${item.durability > 0 ? `Durability: ${item.durability}%<br>` : ''}
                            Item Type: ${item.item_type}<br>
                            Item ID: ${item.item_id}
                        </span>
                    </div>
                </div>
            `;
            inventoryGrid.innerHTML += itemHtml;
        });
        
        // Add empty slots to fill the grid
        const emptySlots = itemsPerPage - items.length;
        for (let i = 0; i < emptySlots; i++) {
            const emptySlotHtml = '<div class="inventory-slot empty"></div>';
            inventoryGrid.innerHTML += emptySlotHtml;
        }
        
        // Re-initialize inventory interactions
        initInventoryInteractions();
    }
    
    // Function to simulate pagination for demo purposes without actual AJAX
    function simulatePagination(pageNum) {
        // Update current page display
        currentPage = pageNum;
        currentPageSpan.textContent = currentPage;
        
        // Update button states
        prevButton.disabled = currentPage === 1;
        nextButton.disabled = currentPage >= totalPages;
        
        // For demo purposes only - in real implementation, the updateInventoryGrid function would be called with actual data
        console.log(`Simulated loading of page ${pageNum}`);
    }
}

// Call these functions on page load and resize
enhanceStatDisplay();
window.addEventListener('resize', function() {
    equalizeHeights();
});

// Initialize equalization after a short delay to ensure all elements are rendered
setTimeout(equalizeHeights, 500);
