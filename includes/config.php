<?php
/**
 * Configuration file
 * Contains global settings and paths
 */

// Define base URL
// Adjust this based on your server configuration
$baseUrl = '/lineage2db/';
$adminBaseUrl = '/lineage2db/admin/';

// Set default timezone
date_default_timezone_set('UTC');

// Error reporting settings
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define item grades and their corresponding CSS classes
$itemGrades = [
    'ONLY' => ['name' => 'Unique', 'class' => 'grade-unique'],
    'MYTH' => ['name' => 'Mythical', 'class' => 'grade-mythical'],
    'LEGEND' => ['name' => 'Legendary', 'class' => 'grade-legendary'],
    'HERO' => ['name' => 'Hero', 'class' => 'grade-hero'],
    'RARE' => ['name' => 'Rare', 'class' => 'grade-rare'],
    'ADVANC' => ['name' => 'Advanced', 'class' => 'grade-advanced'],
    'NORMAL' => ['name' => 'Normal', 'class' => 'grade-normal']
];

// Default pagination settings
$defaultPageSize = 20;

/**
 * Function to generate pagination HTML
 * 
 * @param int $currentPage Current page number
 * @param int $totalPages Total number of pages
 * @param string $urlPattern URL pattern with placeholder for page number
 * @return string HTML pagination controls
 */
function generatePagination($currentPage, $totalPages, $urlPattern) {
    $output = '<div class="pagination"><ul class="pagination-list">';
    
    // Previous page link
    if ($currentPage > 1) {
        $output .= '<li class="pagination-item"><a href="' . sprintf($urlPattern, $currentPage - 1) . '">&laquo;</a></li>';
    } else {
        $output .= '<li class="pagination-item disabled"><span>&laquo;</span></li>';
    }
    
    // Page numbers
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $currentPage + 2);
    
    if ($startPage > 1) {
        $output .= '<li class="pagination-item"><a href="' . sprintf($urlPattern, 1) . '">1</a></li>';
        if ($startPage > 2) {
            $output .= '<li class="pagination-item disabled"><span>...</span></li>';
        }
    }
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $currentPage) {
            $output .= '<li class="pagination-item active"><a href="#">' . $i . '</a></li>';
        } else {
            $output .= '<li class="pagination-item"><a href="' . sprintf($urlPattern, $i) . '">' . $i . '</a></li>';
        }
    }
    
    if ($endPage < $totalPages) {
        if ($endPage < $totalPages - 1) {
            $output .= '<li class="pagination-item disabled"><span>...</span></li>';
        }
        $output .= '<li class="pagination-item"><a href="' . sprintf($urlPattern, $totalPages) . '">' . $totalPages . '</a></li>';
    }
    
    // Next page link
    if ($currentPage < $totalPages) {
        $output .= '<li class="pagination-item"><a href="' . sprintf($urlPattern, $currentPage + 1) . '">&raquo;</a></li>';
    } else {
        $output .= '<li class="pagination-item disabled"><span>&raquo;</span></li>';
    }
    
    $output .= '</ul></div>';
    return $output;
}

/**
 * Function to sanitize input data
 * 
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Get CSS class for item grade
 * 
 * @param string $grade Item grade
 * @return string CSS class
 */
function getGradeClass($grade) {
    global $itemGrades;
    return isset($itemGrades[$grade]) ? $itemGrades[$grade]['class'] : 'grade-normal';
}

/**
 * Get formatted name for item grade
 * 
 * @param string $grade Item grade
 * @return string Formatted name
 */
function getGradeName($grade) {
    global $itemGrades;
    return isset($itemGrades[$grade]) ? $itemGrades[$grade]['name'] : 'Normal';
}
