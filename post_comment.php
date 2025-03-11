<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'], $_POST['post_id'])) {
    $post_id = (int)$_POST['post_id'];
    $username = $_SESSION['username'];
    $comment = trim($_POST['comment']);

    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, username, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $post_id, $username, $comment);
        $stmt->execute();
        $stmt->close();
    }
}
header("Location: posts.php");
exit();
?>
