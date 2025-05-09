<?php
/**
 * Database connection file
 * Establishes connection to MySQL database
 */

// Database configuration
$db_host = 'localhost';
$db_user = 'root';  // Replace with your database username
$db_pass = '';      // Replace with your database password
$db_name = 'l1j_remastered';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

/**
 * Execute SQL query with error handling
 * 
 * @param string $sql SQL query to execute
 * @param mysqli $connection Database connection
 * @return mysqli_result|bool Result object or boolean
 */
function executeQuery($sql, $connection) {
    $result = $connection->query($sql);
    if (!$result) {
        error_log("Query failed: " . $connection->error . " - SQL: " . $sql);
        return false;
    }
    return $result;
}
