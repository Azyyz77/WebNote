<?php

include("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$user_email = $_SESSION['email'];

// Fetch the notes for the logged-in user
$sql = "SELECT id, title, content FROM notes WHERE user_email = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='note-item'>";
        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
        echo "<p>Content: " . $row['content'] . "</p>";
        echo "</div>";
    }
} else {
    echo "No notes found.";
}

$stmt->close();
$conn->close();
?>
