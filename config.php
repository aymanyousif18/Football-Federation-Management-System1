<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'ffms_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['role'] ?? null;
}

function requireRole($requiredRole) {
    if (!isLoggedIn() || getUserRole() != $requiredRole) {
        redirect('../login1.php');
    }
}
?>