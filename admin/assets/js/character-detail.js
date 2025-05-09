/**
 * Character Detail Page JavaScript
 * Enhances the character detail page with interactive elements and visualizations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize visual elements
    initAttributeBars();
    initResourceBars();
    initAlignmentIndicator();
    initToggleableDetails();
    
    // Setup tooltips
    setupTooltips();
});

/**
 * Initialize attribute bars to visually represent character attributes
 */
function initAttributeBars() {
    // Get all attribute values and create bars
    const attributes = ['Str', 'Dex', 'Con', 'Wis', 'Intel', 'Cha'];
    
    attributes.forEach(attr => {
        const statItems = document.querySelectorAll(`.stat-item:has(.stat-label:contains("${attr}"))`);
        
        if (statItems.length > 0) {
            const statItem = statItems[0];
            const valueElement = statItem.querySelector('.stat-value');
            
            if (valueElement) {
                const rawValue = valueElement.textContent.trim();
                const value = parseInt(rawValue) || 0;
                
                // Create bar element
                const barEl = document.createElement('div');
                barEl.className = 'attribute-bar';
                
                const fillEl = document.createElement('div');
                fillEl.className = 'attribute-fill';
                
                // Calculate percentage (assuming max is around 30 for attributes)
                const percent = Math.min(Math.round((value / 30) * 100), 100);
                fillEl.style.width = percent + '%';
                
                barEl.appendChild(fillEl);
                statItem.appendChild(barEl);
            }
        }
    });
}

/**
 * Initialize HP and MP bars
 */
function initResourceBars() {
    // HP Bar
    const hpItem = document.querySelector('.stat-item:has(.stat-label:contains("HP"))');
    if (hpItem) {
        const valueText = hpItem.querySelector('.stat-value').textContent;
        const matches = valueText.match(/(\d+)\s*\/\s*(\d+)/);
        
        if (matches && matches.length === 3) {
            const current = parseInt(matches[1]);
            const max = parseInt(matches[2]);
            const percent = Math.round((current / max) * 100);
            
            const barsContainer = document.createElement('div');
            barsContainer.className = 'resource-bars';
            
            const barContainer = document.createElement('div');
            barContainer.className = 'resource-bar';
            
            const bar = document.createElement('div');
            bar.className = 'hp-bar';
            bar.style.width = percent + '%';
            
            barContainer.appendChild(bar);
            barsContainer.appendChild(barContainer);
            hpItem.appendChild(barsContainer);
        }
    }
    
    // MP Bar
    const mpItem = document.querySelector('.stat-item:has(.stat-label:contains("MP"))');
    if (mpItem) {
        const valueText = mpItem.querySelector('.stat-value').textContent;
        const matches = valueText.match(/(\d+)\s*\/\s*(\d+)/);
        
        if (matches && matches.length === 3) {
            const current = parseInt(matches[1]);
            const max = parseInt(matches[2]);
            const percent = Math.round((current / max) * 100);
            
            const barsContainer = document.createElement('div');
            barsContainer.className = 'resource-bars';
            
            const barContainer = document.createElement('div');
            barContainer.className = 'resource-bar';
            
            const bar = document.createElement('div');
            bar.className = 'mp-bar';
            bar.style.width = percent + '%';
            
            barContainer.appendChild(bar);
            barsContainer.appendChild(barContainer);
            mpItem.appendChild(barsContainer);
        }
    }
}

/**
 * Initialize alignment indicator
 */
function initAlignmentIndicator() {
    const alignmentItem = document.querySelector('.stat-item:has(.stat-label:contains("Alignment"))');
    
    if (alignmentItem) {
        const valueElement = alignmentItem.querySelector('.stat-value');
        if (valueElement) {
            const alignmentValue = parseInt(valueElement.textContent.replace(/,/g, ''));
            
            // Create alignment indicator
            const indicator = document.createElement('div');
            indicator.className = 'alignment-indicator';
            
            const marker = document.createElement('div');
            marker.className = 'alignment-marker';
            
            // Calculate position (from -10000 to 10000 typically)
            const position = ((alignmentValue + 10000) / 20000) * 100;
            marker.style.left = `${Math.min(Math.max(position, 0), 100)}%`;
            
            const labels = document.createElement('div');
            labels.className = 'alignment-labels';
            labels.innerHTML = '<span>Chaotic</span><span>Neutral</span><span>Lawful</span>';
            
            indicator.appendChild(marker);
            alignmentItem.appendChild(indicator);
            alignmentItem.appendChild(labels);
        }
    }
}

/**
 * Setup collapsible sections for buffs and other details
 */
function initToggleableDetails() {
    // Find all card headers that could be toggleable
    const headers = document.querySelectorAll('.admin-card-header');
    
    headers.forEach(header => {
        // Make headers clickable to toggle content visibility
        header.style.cursor = 'pointer';
        
        // Add toggle icon
        const title = header.querySelector('.admin-card-title');
        if (title) {
            const icon = document.createElement('i');
            icon.className = 'fas fa-chevron-down';
            icon.style.float = 'right';
            icon.style.transition = 'transform 0.3s ease';
            title.appendChild(icon);
        }
        
        // Get the corresponding card body
        const card = header.closest('.admin-card');
        const body = card ? card.querySelector('.admin-card-body') : null;
        
        if (body) {
            header.addEventListener('click', () => {
                // Toggle body visibility with animation
                if (body.style.maxHeight) {
                    body.style.maxHeight = null;
                    header.querySelector('i').style.transform = 'rotate(0deg)';
                } else {
                    body.style.maxHeight = body.scrollHeight + 'px';
                    header.querySelector('i').style.transform = 'rotate(180deg)';
                }
            });
            
            // Initialize all sections as expanded
            body.style.overflow = 'hidden';
            body.style.transition = 'max-height 0.3s ease';
            body.style.maxHeight = body.scrollHeight + 'px';
        }
    });
}

/**
 * Setup tooltips for attributes and stats
 */
function setupTooltips() {
    // Define tooltip data
    const tooltips = {
        'STR': 'Strength affects physical attack power and weight capacity.',
        'DEX': 'Dexterity affects accuracy, evasion, and attack speed.',
        'CON': 'Constitution affects HP, physical defense, and status resistance.',
        'WIS': 'Wisdom affects MP recovery and magical resistance.',
        'INT': 'Intelligence affects magic attack power and max MP.',
        'CHA': 'Charisma affects trading prices and certain social skills.',
        'AC': 'Armor Class - Determines your character\'s physical defense.',
        'Alignment': 'Alignment affects karma and certain quests or items.',
        'Karma': 'Negative karma is gained through PKing. Severe negative karma causes item drops on death.',
        'PK Count': 'Number of players killed in PvP combat.'
    };
    
    // Apply tooltips to stat labels
    for (const [key, value] of Object.entries(tooltips)) {
        const elements = document.querySelectorAll(`.stat-label:contains("${key}")`);
        
        elements.forEach(el => {
            el.title = value;
            el.style.cursor = 'help';
            el.style.borderBottom = '1px dotted #999';
            el.style.display = 'inline-block';
        });
    }
}

// Helper function for :contains selector since it's not standard
// This needs to be polyfilled
if (!Element.prototype.matches) {
    Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}

if (window.jQuery === undefined) {
    // Polyfill for :contains selector when jQuery is not available
    document.querySelectorAll = function(selector) {
        if (selector.includes(':contains')) {
            const parts = selector.split(':contains');
            const baseSelector = parts[0];
            const textToMatch = parts[1].replace(/[()'"]/g, '').trim();
            
            const results = [];
            const elements = document.querySelectorAll(baseSelector);
            
            elements.forEach(el => {
                if (el.textContent.includes(textToMatch)) {
                    results.push(el);
                }
            });
            
            return results;
        } else {
            return document.querySelectorAll(selector);
        }
    };
}
