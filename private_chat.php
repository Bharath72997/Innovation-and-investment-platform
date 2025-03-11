<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

$currentUser = $_SESSION['username'];

// If a recipient is chosen via GET parameter:
$receiver = isset($_GET['receiver']) ? $_GET['receiver'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'], $_POST['receiver'])) {
    $receiver = trim($_POST['receiver']);
    $message = trim($_POST['message']);
    if (!empty($receiver) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO private_messages (sender, receiver, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $currentUser, $receiver, $message);
        $stmt->execute();
        $stmt->close();
    }
    // Redirect to avoid form resubmission
    header("Location: private_chat.php?receiver=" . urlencode($receiver));
    exit();
}

// Fetch private messages between current user and receiver (if set)
$messages = [];
if (!empty($receiver)) {
    $stmt = $conn->prepare("SELECT * FROM private_messages WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) ORDER BY created_at ASC");
    $stmt->bind_param("ssss", $currentUser, $receiver, $receiver, $currentUser);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        $messages[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Private Chat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
      #chat-box {
          height: 400px;
          overflow-y: scroll;
          border: 1px solid #ccc;
          padding: 10px;
          background-color: #f8f8f8;
      }
  </style>
</head>
<body class="bg-light">
  <nav class="navbar navbar-dark bg-secondary p-3">
    <span class="navbar-brand">Private Chat</span>
    <div>
      <a href="dashboard.php" class="btn btn-warning me-2">Dashboard</a>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </nav>
  <div class="container mt-4">
    <h4>Welcome, <?php echo htmlspecialchars($currentUser); ?></h4>
    <form class="row g-3 mb-4" method="GET" action="">
      <div class="col-auto">
        <input type="text" name="receiver" class="form-control" placeholder="Enter recipient username" value="<?php echo htmlspecialchars($receiver); ?>" required>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-primary mb-3">Open Chat</button>
      </div>
    </form>

    <?php if (!empty($receiver)): ?>
    <h5>Chat with <?php echo htmlspecialchars($receiver); ?></h5>
    <div id="chat-box" class="mb-3">
      <?php 
      if (!empty($messages)) {
          foreach ($messages as $msg) {
              $sender = htmlspecialchars($msg['sender']);
              $message = nl2br(htmlspecialchars($msg['message']));
              $time = $msg['created_at'];
              echo "<p><strong>$sender:</strong> $message <small class='text-muted'>($time)</small></p>";
          }
      } else {
          echo "<p>No messages yet.</p>";
      }
      ?>
    </div>
    <form method="POST" action="">
      <input type="hidden" name="receiver" value="<?php echo htmlspecialchars($receiver); ?>">
      <div class="input-group mb-3">
        <input type="text" name="message" class="form-control" placeholder="Type your message here..." required>
        <button class="btn btn-primary" type="submit">Send</button>
      </div>
    </form>
    <?php endif; ?>
  </div>
</body>
</html>
<?php $conn->close(); ?>
