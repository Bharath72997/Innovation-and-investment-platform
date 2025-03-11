<?php
session_start();
include 'db_connect.php';

if (isset($_GET['post_id'])) {
    $post_id = (int)$_GET['post_id'];
    $username = $_SESSION['username'];
    // Optional: Check if the user already liked the post to prevent duplicate likes.
    $stmt = $conn->prepare("INSERT INTO reactions (post_id, username, reaction_type) VALUES (?, ?, 'like')");
    $stmt->bind_param("is", $post_id, $username);
    $stmt->execute();
    $stmt->close();
}
header("Location: posts.php");
exit();
?>
