<?php
$host = "localhost";
$dbname = "pos_inventory_db";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
