<?php
session_start();
require_once("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Note</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-100 text-gray-800">

<?php
    include("admin_navbar.php");
?>

</body>
</html>