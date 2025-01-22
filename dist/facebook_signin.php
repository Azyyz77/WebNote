<?php
include 'connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$name = $data['name'];

// Check if user exists or register them
$result = $conn->query("SELECT * FROM users WHERE email='$email'");
if ($result->num_rows == 0) {
    $conn->query("INSERT INTO users (firstName, lastName, email) VALUES ('$name', '', '$email')");
}
session_start();
$_SESSION['email'] = $email;
echo json_encode(["success" => true]);
?>
