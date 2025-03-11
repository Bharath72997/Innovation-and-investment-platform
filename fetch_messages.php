<?php
include 'db_connect.php';

// Fetch the latest chat messages, you can order by created_at
$sql = "SELECT username, message, created_at FROM chat_messages ORDER BY created_at ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        // Format each message
        echo "<div class='mb-2'><strong>" . htmlspecialchars($row['username']) . ":</strong> " . htmlspecialchars($row['message']) . " <small class='text-muted'>(" . $row['created_at'] . ")</small></div>";
    }
} else {
    echo "<p>No messages yet.</p>";
}
$conn->close();
?>
