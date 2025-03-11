<?php
include 'db_connect.php'; // Ensure this file exists and is correct

$password = password_hash('123456', PASSWORD_DEFAULT); // Hash the password

$sql = "UPDATE users SET password='$password' WHERE username='testuser'";
if ($conn->query($sql) === TRUE) {
    echo "Password updated successfully.";
} else {
    echo "Error updating password: " . $conn->error;
}
?>
