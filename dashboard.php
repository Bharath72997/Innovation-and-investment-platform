<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

// Process new post submission if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_content'])) {
    $username = $_SESSION['username'];
    $content = trim($_POST['post_content']);
    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO posts (username, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $content);
        $stmt->execute();
        $stmt->close();
    }
}

// Retrieve posts from the database (newest first)
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
       .post-card {
           margin-bottom: 20px;
       }
   </style>
</head>
<body class="bg-light">
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
       <div class="container-fluid">
           <a class="navbar-brand" href="#">Dashboard</a>
           <div class="d-flex">
               <a href="chat.php" class="btn btn-info me-2">Chat</a>
               <a href="logout.php" class="btn btn-danger">Logout</a>
           </div>
       </div>
   </nav>
   
   <div class="container mt-4">
       <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
       
       <!-- Post submission form -->
       <div class="card mb-4">
           <div class="card-body">
               <form method="POST" action="">
                   <div class="mb-3">
                       <textarea name="post_content" class="form-control" rows="3" placeholder="What's on your mind?" required></textarea>
                   </div>
                   <button type="submit" class="btn btn-primary">Post</button>
               </form>
           </div>
       </div>
       
       <!-- Display posts feed -->
       <h3 class="mb-3">Recent Posts</h3>
       <?php
       if ($result->num_rows > 0) {
           while ($row = $result->fetch_assoc()){
               echo "<div class='card post-card'>";
               echo "  <div class='card-body'>";
               echo "    <h5 class='card-title'>" . htmlspecialchars($row['username']) . "</h5>";
               echo "    <p class='card-text'>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
               echo "    <p class='card-text'><small class='text-muted'>" . $row['created_at'] . "</small></p>";
               echo "  </div>";
               echo "</div>";
           }
       } else {
           echo "<p>No posts yet. Be the first to post!</p>";
       }
       ?>
   </div>
</body>
</html>
<?php $conn->close(); ?>
