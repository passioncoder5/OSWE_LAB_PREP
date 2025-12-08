<?php
// Database Configuration for XAMPP/Localhost
$servername = "localhost";
$username = "oswe_user";   // Created via setup.sql
$password = "oswe_password"; // Created via setup.sql
$dbname = "oswe_lab";

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // In a real exam, verbose connection errors might give hints, 
    // but here we just die.
    die("Connection failed: ". $conn->connect_error);
}
?>
