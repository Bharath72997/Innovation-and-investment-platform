<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <span class="navbar-brand">Chat Room</span>
        <a href="dashboard.php" class="btn btn-warning">Dashboard</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </nav>
    <div class="container mt-4">
        <div class="card shadow-lg p-4">
            <h4>Welcome, <?php echo $_SESSION['username']; ?>!</h4>
            <div id="chat-box" class="mb-3">
                <!-- Chat messages will load here -->
            </div>
            <form id="chat-form">
                <div class="input-group">
                    <input type="text" id="message" name="message" class="form-control" placeholder="Type your message here..." required>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Function to fetch chat messages
    function fetchMessages() {
        $.ajax({
            url: 'fetch_messages.php',
            method: 'GET',
            success: function(data) {
                $('#chat-box').html(data);
                // Auto-scroll to the bottom
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            }
        });
    }

    // Fetch messages every 2 seconds
    setInterval(fetchMessages, 2000);
    fetchMessages();

    // Submit new message
    $('#chat-form').on('submit', function(e) {
        e.preventDefault();
        var message = $('#message').val();
        $.ajax({
            url: 'post_message.php',
            method: 'POST',
            data: { message: message },
            success: function(response) {
                $('#message').val('');
                fetchMessages(); // Refresh the chat messages
            }
        });
    });
    </script>
</body>
</html>
