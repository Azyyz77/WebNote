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
    <title>Admin Dashboard - Web Note</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-100 text-gray-800">

<?php
    include("admin_navbar.php");
?>

<!-- Welcome Section -->
<section class="flex items-center justify-center h-screen">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-gray-800">
            Welcome 
        </h1>
        <p class="mt-4 text-lg text-gray-600">
            As an administrator, you have full control over managing the Web Note platform.
        </p>
        
    </div>
</section>

</body>
</html>
