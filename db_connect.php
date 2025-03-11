<?php
// Replace these placeholders with your real database credentials
$servername = "sqlXXX.epizy.com"; // e.g., "sql301.epizy.com" from InfinityFree
$username   = "if0_38495865";    // e.g., "epiz_12345678"
$password   = "Okforgetit16"; // The password you set in your hosting control panel
$dbname     = "if0_38495865_innovation; // e.g., "epiz_12345678_dbname"

// Create the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If you want to confirm connection in development, uncomment:
// echo "Database connected successfully!";
?>
