<?php
$host = 'localhost';         // or your server IP
$dbname = 'habit_tracker';   // your database name
$username = 'root';          // your DB username
$password = '';              // your DB password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optional: set charset
    $conn->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>