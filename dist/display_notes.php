<?php

include("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$user_email = $_SESSION['email'];

// Set default sorting option
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date-desc';  // Default is "date-desc"

// Build the SQL query based on the sort option
switch ($sort) {
    case 'date-asc':
        $order_by = 'ORDER BY created_at ASC';
        break;
    case 'date-desc':
        $order_by = 'ORDER BY created_at DESC';
        break;
    case 'title-asc':
        $order_by = 'ORDER BY title ASC';
        break;
    case 'title-desc':
        $order_by = 'ORDER BY title DESC';
        break;
    default:
        $order_by = 'ORDER BY created_at DESC';  // Default fallback
        break;
}

// Fetch the notes for the logged-in user with the selected sorting
$sql = "SELECT id, title, content FROM notes WHERE user_email = ? $order_by";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='note-item'>";
        echo "<h3 class='note-title'>" . htmlspecialchars($row['title']) . "</h3>";
        echo "<p class='note-content'>" . htmlspecialchars($row['content']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p class='no-notes'>No notes found.</p>";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Styles for note items */
.note-item {
    background-color: #fff;
    padding: 16px;
    margin-bottom: 16px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
}

.note-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

/* Style for note titles */
.note-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
    text-transform: capitalize;
}

/* Style for note content */
.note-content {
    font-size: 1rem;
    color: #555;
    line-height: 1.6;
}

/* Message when no notes are found */
.no-notes {
    font-size: 1.2rem;
    color: #777;
    text-align: center;
    margin-top: 20px;
}

/* Add responsiveness for smaller screens */
@media (max-width: 768px) {
    .note-item {
        padding: 12px;
        margin-bottom: 12px;
    }

    .note-title {
        font-size: 1.25rem;
    }

    .note-content {
        font-size: 0.9rem;
    }
}

    </style>
</head>

</html>