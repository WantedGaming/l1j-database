<?php
/**
 * Database Connection Class
 * Handles database connectivity for L1J Remastered Database Browser
 */
class Database {
    // Database credentials
    private $host = "localhost";
    private $db_name = "l1j_remastered";
    private $username = "root";
    private $password = "";
    public $conn;
    
    /**
     * Get database connection
     * @return PDO|null Database connection object
     */
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        
        return $this->conn;
    }
}
?>