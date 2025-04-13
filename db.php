<?php
$host = "localhost";
$user = "u233761808_admin";
$password = "D!c0d1ng2025";
$dbname = "u233761808_admin";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>