<?php
session_start();
require_once("connect.php");

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Get the search query
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Check if search query is empty
if (empty($search_query)) {
    echo "<h1>Please provide a search query.</h1>";
    exit();
}

// Prepare SQL query to fetch matching notes
$email = $_SESSION['email']; // Assuming you're linking notes to the logged-in user
$sql = "SELECT * FROM notes WHERE user_email = ? AND (title LIKE ? OR content LIKE ?) ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$like_query = "%" . $search_query . "%";
$stmt->bind_param("sss", $email, $like_query, $like_query);
$stmt->execute();
$result = $stmt->get_result();

// Check if any notes are found
if ($result->num_rows > 0) {
    echo "<h2>Search Results for '$search_query':</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='note-item'>";
        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
        echo "<p>" . htmlspecialchars(substr($row['content'], 0, 100)) . "...</p>";
        echo "<a href='view_note.php?id=" . $row['id'] . "'>Read More</a>";
        echo "</div>";
    }
} else {
    echo "<h2>No notes found for '$search_query'.</h2>";
}

$stmt->close();
$conn->close();
?>
