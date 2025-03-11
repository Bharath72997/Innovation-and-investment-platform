<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'investor') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Investor Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <nav class="navbar navbar-dark bg-success p-3">
    <span class="navbar-brand">Investor Dashboard</span>
    <div>
      <a href="chat.php" class="btn btn-light me-2">Chat</a>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </nav>
  <div class="container mt-5">
    <div class="card shadow-lg p-4">
      <h2 class="text-center text-success">Welcome, <?php echo $_SESSION['username']; ?> 💰</h2>
      <p class="text-center">Explore innovative projects and investment opportunities.</p>
      <div class="d-grid gap-3">
        <button class="btn btn-secondary" onclick="window.location.href='posts.php'">View Posts Feed</button>
      </div>
    </div>
  </div>
</body>
</html>
