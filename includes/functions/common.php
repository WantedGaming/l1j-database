<?php
/**
 * Common Functions
 * Shared utility functions for L1J Remastered Database Browser
 */

/**
 * Sanitize input data
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Redirect to a specified URL
 * @param string $url URL to redirect to
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Generate page title based on current section
 * @param string $section Current section name
 * @param string $item Optional item name for detail views
 * @return string Formatted page title
 */
function pageTitle($section, $item = null) {
    $base = "L1J Remastered DB";
    
    if (empty($section)) {
        return $base;
    }
    
    $title = ucfirst($section) . " | " . $base;
    
    if (!empty($item)) {
        $title = $item . " - " . $title;
    }
    
    return $title;
}

/**
 * Check if a string contains a specific value
 * @param string $haystack String to search in
 * @param string $needle String to search for
 * @return bool True if found, false otherwise
 */
function contains($haystack, $needle) {
    return strpos($haystack, $needle) !== false;
}

/**
 * Format a database timestamp to a readable date format
 * @param string $timestamp Database timestamp
 * @param string $format Date format (default: Y-m-d H:i:s)
 * @return string Formatted date
 */
function formatDate($timestamp, $format = 'Y-m-d H:i:s') {
    $date = new DateTime($timestamp);
    return $date->format($format);
}

/**
 * Format a large number with comma separators
 * @param int $number Number to format
 * @return string Formatted number
 */
function formatNumber($number) {
    return number_format($number);
}

/**
 * Create pagination links
 * @param int $currentPage Current page number
 * @param int $totalPages Total number of pages
 * @param string $urlPattern URL pattern with %d placeholder for page number
 * @return string HTML pagination links
 */
function pagination($currentPage, $totalPages, $urlPattern) {
    $output = '<div class="pagination">';
    
    // Previous page link
    if ($currentPage > 1) {
        $output .= '<a href="' . sprintf($urlPattern, $currentPage - 1) . '" class="page-link">Previous</a>';
    } else {
        $output .= '<span class="page-link disabled">Previous</span>';
    }
    
    // Page number links
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    if ($start > 1) {
        $output .= '<a href="' . sprintf($urlPattern, 1) . '" class="page-link">1</a>';
        if ($start > 2) {
            $output .= '<span class="page-link disabled">...</span>';
        }
    }
    
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $currentPage) {
            $output .= '<span class="page-link current">' . $i . '</span>';
        } else {
            $output .= '<a href="' . sprintf($urlPattern, $i) . '" class="page-link">' . $i . '</a>';
        }
    }
    
    if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
            $output .= '<span class="page-link disabled">...</span>';
        }
        $output .= '<a href="' . sprintf($urlPattern, $totalPages) . '" class="page-link">' . $totalPages . '</a>';
    }
    
    // Next page link
    if ($currentPage < $totalPages) {
        $output .= '<a href="' . sprintf($urlPattern, $currentPage + 1) . '" class="page-link">Next</a>';
    } else {
        $output .= '<span class="page-link disabled">Next</span>';
    }
    
    $output .= '</div>';
    return $output;
}

/**
 * Generate breadcrumb navigation
 * @param array $items Array of breadcrumb items with 'label' and optional 'url'
 * @return string HTML breadcrumb navigation
 */
function breadcrumbs($items) {
    $output = '<div class="breadcrumbs">';
    
    $count = count($items);
    for ($i = 0; $i < $count; $i++) {
        $item = $items[$i];
        
        if ($i < $count - 1 && isset($item['url'])) {
            $output .= '<a href="' . $item['url'] . '">' . $item['label'] . '</a>';
            $output .= '<span class="breadcrumb-separator">/</span>';
        } else {
            $output .= '<span class="breadcrumb-current">' . $item['label'] . '</span>';
        }
    }
    
    $output .= '</div>';
    return $output;
}

/**
 * Debug utility to print variables in a readable format
 * @param mixed $var Variable to debug
 * @param bool $die Whether to terminate script execution after debugging
 */
function debug($var, $die = false) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    
    if ($die) {
        die();
    }
}

/**
 * Check if the current page matches a path pattern
 * @param string $path Path pattern to check
 * @return bool True if current page matches pattern, false otherwise
 */
function isCurrentPage($path) {
    $currentPath = $_SERVER['PHP_SELF'];
    return contains($currentPath, $path);
}

/**
 * Get category icon class
 * @param string $category Category name
 * @return string Icon class
 */
function getCategoryIcon($category) {
    $icons = [
        'weapons' => 'sword',
        'armor' => 'shield',
        'items' => 'potion',
        'monsters' => 'dragon',
        'maps' => 'map',
        'dolls' => 'doll',
        'npcs' => 'person',
        'skills' => 'magic',
        'polymorph' => 'transform'
    ];
    
    return isset($icons[$category]) ? $icons[$category] : 'default';
}

/**
 * Format database tables for display
 * @param string $table Database table name
 * @return string Formatted table name
 */
function formatTableName($table) {
    $table = str_replace('_', ' ', $table);
    return ucwords($table);
}
?>