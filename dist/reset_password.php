<?php
session_start();
require_once("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Ajouter le lien vers Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-100 flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full border border-yellow-400">
        <h1 class="text-3xl font-semibold text-yellow-500 mb-6 text-center">Reset Password</h1>
        
        <form method="post" action="reset_password_code.php">
            <?php if(isset($_SESSION['message'])) { ?>
                <div class="mb-4 text-yellow-500 text-center">
                    <h3><?php echo $_SESSION['message']; ?></h3>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php } ?>
            
            <input type="email" name="email" placeholder="Enter your email" required class="w-full p-3 mb-4 border border-yellow-400 rounded-md focus:ring-2 focus:ring-yellow-300 focus:outline-none" />
            
            <button type="submit" name="sendResetLink" class="w-full py-3 bg-yellow-500 text-white font-semibold rounded-md hover:bg-yellow-400 transition duration-300 ease-in-out">
                Send Reset Link
            </button>
        </form>
    </div>
</body>
</html>
