<?php
require __DIR__ . '/includes1/config.php';

// Create database
$conn->query("CREATE DATABASE IF NOT EXISTS ffms_db");
$conn->select_db('ffms_db');

// Create tables
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','referee','team_manager','spectator') NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    manager_id INT
)");

$conn->query("CREATE TABLE IF NOT EXISTS players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    team_id INT
)");

$conn->query("CREATE TABLE IF NOT EXISTS matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team1_id INT,
    team2_id INT,
    match_date DATETIME,
    venue VARCHAR(100)
)");

// Add admin user
$password = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO users (username, password, role) 
              VALUES ('admin', '$password', 'admin')");

echo "Database setup complete!";
?>