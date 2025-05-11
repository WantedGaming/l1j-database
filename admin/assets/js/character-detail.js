/**
 * Enhanced Character Detail Page JavaScript
 * Handles interactive functionality for the redesigned character detail page
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded');
    
    // Initialize dropdown menus
    initDropdowns();
    
    // Initialize modal dialogs
    initModals();
    
    // Initialize attribute animations
    initAttributeAnimations();
    
    // Initialize stat hover effects
    initStatHoverEffects();
    
    // Initialize placeholder charts
    initPlaceholderCharts();
    
    // Initialize map functionality with a slight delay
    setTimeout(initMapInteraction, 500);
});

/**
 * Initialize tab navigation
 */
function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    // Add click event to each tab button
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked button
            button.classList.add('active');
            
            // Show corresponding tab content
            const tabId = button.getAttribute('data-tab');
            const tabContent = document.getElementById(`${tabId}-tab`);
            
            if (tabContent) {
                tabContent.classList.add('active');
                console.log(`Activated tab: ${tabId}`);
            } else {
                console.error(`Tab content not found: ${tabId}-tab`);
            }
            
            // Store active tab in local storage for persistence
            localStorage.setItem('activeCharacterTab', tabId);
        });
    });
    
    // Check if there's a stored active tab
    const activeTab = localStorage.getItem('activeCharacterTab');
    if (activeTab) {
        const storedTabButton = document.querySelector(`.tab-button[data-tab="${activeTab}"]`);
        if (storedTabButton) {
            storedTabButton.click();
        } else {
            // If stored tab doesn't exist, default to first tab
            if (tabButtons.length > 0) {
                tabButtons[0].click();
            }
        }
    } else {
        // Default to first tab if no stored preference
        if (tabButtons.length > 0) {
            tabButtons[0].click();
        }
    }
}

/**
 * Initialize dropdown menus
 */
function initDropdowns() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            
            // Get parent dropdown container
            const dropdown = toggle.closest('.admin-action-dropdown');
            
            // Toggle active class
            dropdown.classList.toggle('active');
            
            // Close other open dropdowns
            document.querySelectorAll('.admin-action-dropdown.active').forEach(open => {
                if (open !== dropdown) {
                    open.classList.remove('active');
                }
            });
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        document.querySelectorAll('.admin-action-dropdown.active').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    });
}

/**
 * Initialize modal dialogs
 */
function initModals() {
    // Global function to open modal by ID
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }
    };
    
    // Global function to close modal by ID
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        }
    };
    
    // Close modal when clicking outside content
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal.active');
            if (activeModal) {
                closeModal(activeModal.id);
            }
        }
    });
}

/**
 * Initialize animations for attribute items
 */
function initAttributeAnimations() {
    const attributeItems = document.querySelectorAll('.attribute-item');
    
    attributeItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            animateAttributeValue(item);
        });
    });
}

/**
 * Animate attribute value with a counting effect
 */
function animateAttributeValue(attributeItem) {
    const valueElement = attributeItem.querySelector('.attribute-value');
    
    // Skip if already animated
    if (valueElement.dataset.animated === 'true') {
        return;
    }
    
    // Get the final value
    const finalValue = parseInt(valueElement.textContent, 10);
    if (isNaN(finalValue)) return;
    
    // Mark as animated
    valueElement.dataset.animated = 'true';
    
    // Starting value (70% of final)
    let currentValue = Math.floor(finalValue * 0.7);
    
    // Duration of animation in ms
    const duration = 600;
    
    // Number of steps
    const steps = 20;
    
    // Increment per step
    const increment = (finalValue - currentValue) / steps;
    
    // Interval between steps
    const interval = duration / steps;
    
    // Animation function
    const animate = () => {
        currentValue += increment;
        
        // If we've reached or exceeded the final value, set to final
        if (currentValue >= finalValue) {
            valueElement.textContent = finalValue;
            return;
        }
        
        // Update display with rounded value
        valueElement.textContent = Math.round(currentValue);
        
        // Continue animation
        setTimeout(animate, interval);
    };
    
    // Start animation
    animate();
}

/**
 * Initialize hover effects for stat rows
 */
function initStatHoverEffects() {
    const statRows = document.querySelectorAll('.stat-row');
    
    statRows.forEach(row => {
        row.addEventListener('mouseenter', () => {
            row.style.backgroundColor = 'rgba(255, 255, 255, 0.05)';
            row.style.paddingLeft = '10px';
        });
        
        row.addEventListener('mouseleave', () => {
            row.style.backgroundColor = '';
            row.style.paddingLeft = '';
        });
    });
}

/**
 * Initialize placeholder charts with randomized data
 */
function initPlaceholderCharts() {
    // Check if chart container exists
    const chartContainer = document.querySelector('.contribution-chart');
    if (!chartContainer) return;
    
    // Create canvas element
    const canvas = document.createElement('canvas');
    canvas.width = chartContainer.clientWidth;
    canvas.height = 200;
    canvas.style.width = '100%';
    canvas.style.height = '200px';
    
    // Replace placeholder with canvas
    chartContainer.innerHTML = '';
    chartContainer.appendChild(canvas);
    
    // Get context
    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    
    // Draw simple chart background
    ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Draw grid lines
    ctx.strokeStyle = 'rgba(255, 255, 255, 0.1)';
    ctx.lineWidth = 1;
    
    // Horizontal grid lines
    for (let i = 0; i < 5; i++) {
        const y = 40 + i * 30;
        ctx.beginPath();
        ctx.moveTo(30, y);
        ctx.lineTo(canvas.width - 20, y);
        ctx.stroke();
    }
    
    // Vertical grid lines
    for (let i = 0; i < 7; i++) {
        const x = 50 + i * ((canvas.width - 70) / 6);
        ctx.beginPath();
        ctx.moveTo(x, 30);
        ctx.lineTo(x, 180);
        ctx.stroke();
    }
    
    // Generate random data
    const dataPoints = 7;
    const data = Array.from({ length: dataPoints }, () => 
        Math.floor(Math.random() * 100) + 50
    );
    
    // Draw data line
    ctx.strokeStyle = 'rgba(249, 75, 31, 0.8)';
    ctx.lineWidth = 2;
    ctx.beginPath();
    
    // Plot points
    const pointWidth = (canvas.width - 70) / 6;
    for (let i = 0; i < dataPoints; i++) {
        const x = 50 + i * pointWidth;
        const y = 180 - (data[i] / 150) * 140;
        
        if (i === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
        
        // Draw point
        ctx.fillStyle = 'rgba(249, 75, 31, 1)';
        ctx.beginPath();
        ctx.arc(x, y, 4, 0, Math.PI * 2);
        ctx.fill();
    }
    
    // Stroke the line
    ctx.stroke();
    
    // Add gradient fill beneath the line
    const gradient = ctx.createLinearGradient(0, 40, 0, 180);
    gradient.addColorStop(0, 'rgba(249, 75, 31, 0.3)');
    gradient.addColorStop(1, 'rgba(249, 75, 31, 0.0)');
    
    ctx.fillStyle = gradient;
    ctx.beginPath();
    ctx.moveTo(50, 180);
    
    for (let i = 0; i < dataPoints; i++) {
        const x = 50 + i * pointWidth;
        const y = 180 - (data[i] / 150) * 140;
        ctx.lineTo(x, y);
    }
    
    ctx.lineTo(50 + (dataPoints - 1) * pointWidth, 180);
    ctx.closePath();
    ctx.fill();
    
    // Add labels
    ctx.fillStyle = 'rgba(255, 255, 255, 0.6)';
    ctx.font = '10px Arial';
    ctx.textAlign = 'center';
    
    const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    for (let i = 0; i < days.length; i++) {
        const x = 50 + i * pointWidth;
        ctx.fillText(days[i], x, 195);
    }
}

/**
 * Format number with commas for thousands
 */
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * Calculate time difference and format it
 */
function getTimeDifference(timestamp) {
    if (!timestamp) return 'Never';
    
    const now = new Date();
    const date = new Date(timestamp * 1000);
    const diffMs = now - date;
    const diffSec = Math.floor(diffMs / 1000);
    
    if (diffSec < 60) return `${diffSec} seconds ago`;
    
    const diffMin = Math.floor(diffSec / 60);
    if (diffMin < 60) return `${diffMin} minutes ago`;
    
    const diffHour = Math.floor(diffMin / 60);
    if (diffHour < 24) return `${diffHour} hours ago`;
    
    const diffDay = Math.floor(diffHour / 24);
    if (diffDay < 30) return `${diffDay} days ago`;
    
    const diffMonth = Math.floor(diffDay / 30);
    if (diffMonth < 12) return `${diffMonth} months ago`;
    
    const diffYear = Math.floor(diffMonth / 12);
    return `${diffYear} years ago`;
}

/**
 * Apply pulse effect to elements
 */
function pulseElement(element) {
    if (!element) return;
    
    // Add pulse class
    element.classList.add('pulse-animation');
    
    // Remove after animation completes
    setTimeout(() => {
        element.classList.remove('pulse-animation');
    }, 1000);
}

/**
 * Initialize map interaction (placeholder)
 */
function initMapInteraction() {
    const mapContainer = document.querySelector('.map-container');
    const mapMarker = document.querySelector('.map-marker');
    
    if (!mapContainer || !mapMarker) return;
    
    // Add click event to map container to simulate teleport functionality
    mapContainer.addEventListener('click', (e) => {
        // Get click coordinates relative to the container
        const rect = mapContainer.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        // Calculate percentage position
        const percentX = (x / mapContainer.clientWidth) * 100;
        const percentY = (y / mapContainer.clientHeight) * 100;
        
        // Limit marker to stay within bounds (5% margin)
        const limitedX = Math.min(95, Math.max(5, percentX));
        const limitedY = Math.min(95, Math.max(5, percentY));
        
        // Update marker position with animation
        mapMarker.style.transition = 'left 0.3s, top 0.3s';
        mapMarker.style.left = `${limitedX}%`;
        mapMarker.style.top = `${limitedY}%`;
        
        // Update coordinate display (simulated values)
        const coordX = document.querySelector('.coordinate-display:nth-child(1) .coordinate-value');
        const coordY = document.querySelector('.coordinate-display:nth-child(2) .coordinate-value');
        
        if (coordX && coordY) {
            // Convert percentage to simulated coordinates (0-32768 range)
            const simulatedX = Math.floor((limitedX / 100) * 32768);
            const simulatedY = Math.floor((limitedY / 100) * 32768);
            
            coordX.textContent = simulatedX;
            coordY.textContent = simulatedY;
            
            // Add pulse effect to coordinate displays
            pulseElement(coordX);
            pulseElement(coordY);
        }
    });
}

// Call map interaction initialization at the end of DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    // Log all tab elements to debug
    console.log('Tab buttons:');
    document.querySelectorAll('.tab-button').forEach(btn => {
        console.log(`- ${btn.textContent.trim()}: data-tab="${btn.getAttribute('data-tab')}"`);
    });
    
    console.log('Tab contents:');
    document.querySelectorAll('.tab-content').forEach(content => {
        console.log(`- ${content.id}`);
    });
});

// Fix tab functionality if script loads after page is already displayed
window.addEventListener('load', function() {
    // If no tabs are active after page load, activate the first tab
    if (!document.querySelector('.tab-content.active')) {
        const firstTabButton = document.querySelector('.tab-button');
        if (firstTabButton) {
            console.log('No active tab found, activating first tab');
            firstTabButton.click();
        }
    }
});
