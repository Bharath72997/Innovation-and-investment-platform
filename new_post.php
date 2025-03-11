<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'innovator') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $content = trim($_POST['content']);

    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO posts (username, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $content);
        if ($stmt->execute()) {
            echo "<script>alert('Post submitted successfully'); window.location='innovator-dashboard.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please enter some content.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Create New Post</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
   <div class="container mt-5">
      <div class="card shadow-lg p-4 mx-auto" style="max-width: 600px;">
         <h2 class="text-center text-primary">Create New Post</h2>
         <form method="post" action="">
            <div class="mb-3">
               <textarea class="form-control" name="content" rows="4" placeholder="Share your idea or project..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Post</button>
         </form>
         <p class="text-center mt-3"><a href="innovator-dashboard.php">Back to Dashboard</a></p>
      </div>
   </div>
</body>
</html>
