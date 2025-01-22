<?php

session_start();
require_once("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: Login.php");
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
    header("Location: createnote.php");
    exit();
} else {
    echo "Error saving note: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>
