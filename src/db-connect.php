<?php
// Database connection parameters
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "mobilemart"; 

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // If connection fails, display error message
    echo "Connection failed: " . $e->getMessage();
}
?>
