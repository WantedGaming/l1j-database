<?php
/**
 * Download Backup File
 * Handles secure downloading of database backup files
 */

// Include required configuration files
require_once '../../includes/db_connect.php';
require_once '../includes/admin-config.php';

// Verify admin permission
if (!verifyAdminPermission()) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Access denied';
    exit;
}

// Validate file parameter
if (!isset($_GET['file']) || empty($_GET['file'])) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Missing file parameter';
    exit;
}

// Sanitize and validate filename
$filename = sanitizeInput($_GET['file']);

// Check for path traversal attempts
if (preg_match('/\.\./', $filename) || preg_match('/\//', $filename)) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid file name';
    exit;
}

// Define backup directory
$backupDir = __DIR__ . '/../../backups/';
$filePath = $backupDir . $filename;

// Verify file exists and is readable
if (!file_exists($filePath) || !is_readable($filePath)) {
    header('HTTP/1.1 404 Not Found');
    echo 'File not found or not readable';
    exit;
}

// Verify file extension
$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
if ($extension !== 'sql') {
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid file type';
    exit;
}

// Set headers for download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filePath));
header('Pragma: no-cache');
header('Expires: 0');

// Output file content
readfile($filePath);
exit;
?>