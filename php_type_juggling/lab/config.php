<?php
// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'type_juggling_lab');

// Create connection with error handling
function getDbConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }
        
        return $conn;
    } catch (Exception $e) {
        die("Database error: " . $e->getMessage());
    }
}

// Security configurations
define('SALT', 'type_juggling_lab_salt_2024');

// Log type juggling attempts
function logTypeJuggling($test_type, $input_data, $result) {
    try {
        $conn = getDbConnection();
        $session_id = session_id();
        
        $stmt = $conn->prepare("INSERT INTO type_juggling_logs (session_id, test_type, input_data, result) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $session_id, $test_type, $input_data, $result);
            $stmt->execute();
            $stmt->close();
        }
        $conn->close();
    } catch (Exception $e) {
        // Silently fail logging - don't break the page
        error_log("Logging failed: " . $e->getMessage());
    }
}
?>
