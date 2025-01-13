<?php

session_start();
include("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Retrieve form data
$title = $_POST['title'];
$content = $_POST['content'];
$user_email = $_SESSION['email'];

// Prepare and execute insert query
$sql = "INSERT INTO notes (user_email, title, content) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $user_email, $title, $content);

if ($stmt->execute()) {
    echo "Note saved successfully!";
} else {
    echo "Error saving note: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
