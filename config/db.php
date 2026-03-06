<?php
// config/db.php

// Database Configuration Constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Default XAMPP user
define('DB_PASS', '');          // Default XAMPP password is empty
define('DB_NAME', 'studynest');

// Create Connection using MySQLi OOP
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check Connection
if ($conn->connect_error) {
    // In a production environment, you should log this error to a file
    // and show a user-friendly message.
    die("Connection failed: " . $conn->connect_error);
}

// Set Charset to utf8mb4 for emoji and special character support
$conn->set_charset("utf8mb4");

// Global setting for timezone (Optional but recommended for StudyNest orders)
date_default_timezone_set('UTC'); 

// The $conn variable is now ready to be used in your auth files.
?>