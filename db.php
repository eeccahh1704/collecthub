<?php
// Database configuration
$host = 'localhost';
$dbname = 'innovation_system';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set PDO default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Remove or comment the line below
    // echo "Connected Successfully";
} catch (PDOException $e) {
    // Catch any connection error
    echo "Connection failed: " . $e->getMessage();
}
?>
