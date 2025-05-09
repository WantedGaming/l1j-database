<?php
/**
 * Admin Configuration file
 * Contains admin-specific settings and functions
 */

// Include the main configuration file
require_once __DIR__ . '/../../includes/config.php';

// Admin-specific settings
$adminPageSize = 50;

// Define CRUD operation messages
$messages = [
    'create_success' => 'Record created successfully.',
    'update_success' => 'Record updated successfully.',
    'delete_success' => 'Record deleted successfully.',
    'create_error' => 'Error creating record.',
    'update_error' => 'Error updating record.',
    'delete_error' => 'Error deleting record.',
    'not_found' => 'Record not found.'
];

/**
 * Display admin alert message
 * 
 * @param string $message Message to display
 * @param string $type Alert type (success, danger, warning, info)
 * @return string HTML for alert message
 */
function displayAlert($message, $type = 'info') {
    $icon = '';
    
    switch ($type) {
        case 'success':
            $icon = '<i class="fas fa-check-circle alert-icon"></i>';
            break;
        case 'danger':
            $icon = '<i class="fas fa-exclamation-circle alert-icon"></i>';
            break;
        case 'warning':
            $icon = '<i class="fas fa-exclamation-triangle alert-icon"></i>';
            break;
        case 'info':
        default:
            $icon = '<i class="fas fa-info-circle alert-icon"></i>';
            break;
    }
    
    return '<div class="alert alert-' . $type . '">' . $icon . $message . '</div>';
}

/**
 * Verify that the admin has permission to access the page
 * This is a placeholder function - implement proper authentication
 * 
 * @return bool True if admin has permission, false otherwise
 */
function verifyAdminPermission() {
    // Placeholder for proper authentication logic
    return true;
}

/**
 * Generate admin pagination controls
 * 
 * @param int $currentPage Current page number
 * @param int $totalPages Total number of pages
 * @param int $totalRecords Total number of records
 * @param string $urlPattern URL pattern with placeholder for page number
 * @return string HTML for admin pagination
 */
function generateAdminPagination($currentPage, $totalPages, $totalRecords, $urlPattern) {
    global $adminPageSize;
    
    $startRecord = ($currentPage - 1) * $adminPageSize + 1;
    $endRecord = min($startRecord + $adminPageSize - 1, $totalRecords);
    
    $output = '<div class="admin-pagination">';
    $output .= '<div class="pagination-info">Showing ' . $startRecord . ' to ' . $endRecord . ' of ' . $totalRecords . ' records</div>';
    $output .= '<ul class="pagination-controls">';
    
    // Previous page
    if ($currentPage > 1) {
        $output .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $currentPage - 1) . '"><i class="fas fa-angle-left"></i></a></li>';
    } else {
        $output .= '<li class="page-item disabled"><span class="page-link"><i class="fas fa-angle-left"></i></span></li>';
    }
    
    // Page numbers
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $startPage + 4);
    
    if ($startPage > 1) {
        $output .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, 1) . '">1</a></li>';
        if ($startPage > 2) {
            $output .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $currentPage) {
            $output .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $output .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $i) . '">' . $i . '</a></li>';
        }
    }
    
    if ($endPage < $totalPages) {
        if ($endPage < $totalPages - 1) {
            $output .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $output .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $totalPages) . '">' . $totalPages . '</a></li>';
    }
    
    // Next page
    if ($currentPage < $totalPages) {
        $output .= '<li class="page-item"><a class="page-link" href="' . sprintf($urlPattern, $currentPage + 1) . '"><i class="fas fa-angle-right"></i></a></li>';
    } else {
        $output .= '<li class="page-item disabled"><span class="page-link"><i class="fas fa-angle-right"></i></span></li>';
    }
    
    $output .= '</ul></div>';
    return $output;
}
