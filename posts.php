<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

// Retrieve posts ordered by newest first
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Posts Feed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
      .post-card {
          margin-bottom: 20px;
      }
  </style>
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-info">
      <div class="container-fluid">
          <a class="navbar-brand" href="#">Posts Feed</a>
          <div class="d-flex">
              <a href="dashboard.php" class="btn btn-warning me-2">Dashboard</a>
              <a href="logout.php" class="btn btn-danger">Logout</a>
          </div>
      </div>
  </nav>
  
  <div class="container mt-4">
      <h2 class="mb-4">Latest Posts</h2>
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()){
              $post_id = $row['id'];
              echo "<div class='card post-card'>";
              echo "  <div class='card-body'>";
              echo "    <h5 class='card-title'>" . htmlspecialchars($row['username']) . "</h5>";
              echo "    <p class='card-text'>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
              echo "    <p class='card-text'><small class='text-muted'>" . $row['created_at'] . "</small></p>";
              
              // Display Like Count and Like Button
              $reactionResult = $conn->query("SELECT COUNT(*) as likeCount FROM reactions WHERE post_id = $post_id AND reaction_type='like'");
              $reactionData = $reactionResult->fetch_assoc();
              $likeCount = $reactionData['likeCount'];
              echo "<p><a href='like_post.php?post_id=$post_id' class='btn btn-sm btn-outline-primary'>Like</a> <span>$likeCount Likes</span></p>";
              
              // Display Comments for this post
              $commentQuery = "SELECT * FROM comments WHERE post_id = $post_id ORDER BY created_at ASC";
              $commentsResult = $conn->query($commentQuery);
              echo "<div class='ms-4'>";
              while($comment = $commentsResult->fetch_assoc()){
                  echo "<div class='mb-2'><strong>" . htmlspecialchars($comment['username']) . ":</strong> " . htmlspecialchars($comment['comment']) . " <small class='text-muted'>(" . $comment['created_at'] . ")</small></div>";
              }
              echo "</div>";
              
              // Comment submission form for this post
              echo "<form action='post_comment.php' method='POST' class='ms-4 mt-2'>";
              echo "<input type='hidden' name='post_id' value='$post_id'>";
              echo "<div class='input-group'>";
              echo "  <input type='text' name='comment' class='form-control' placeholder='Write a comment...' required>";
              echo "  <button type='submit' class='btn btn-outline-secondary'>Comment</button>";
              echo "</div>";
              echo "</form>";
              
              echo "  </div>";
              echo "</div>";
          }
      } else {
          echo "<p>No posts available.</p>";
      }
      $conn->close();
      ?>
  </div>
</body>
</html>
