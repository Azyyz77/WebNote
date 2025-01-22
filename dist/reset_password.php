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
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h1 class="text-3xl font-semibold text-black mb-6 text-center">Reset Password</h1>
        
        <form method="post" action="reset_password_code.php">
            <?php if(isset($_SESSION['message'])) { ?>
                <div class="mb-4 text-yellow-500 text-center">
                    <h3><?php echo $_SESSION['message']; ?></h3>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php } ?>
            
            <!-- Champ de saisie d'email avec bordure qui change au focus -->
            <input type="email" name="email" placeholder="Enter your email" required class="w-full p-3 mb-4 border border-transparent rounded-md focus:border-yellow-400 focus:outline-none" />
            
            <button type="submit" name="sendResetLink" class="w-full py-3 bg-yellow-500 text-white font-semibold rounded-md hover:bg-yellow-400 active:bg-yellow-600 transition duration-300 ease-in-out">
                Send Reset Link
            </button>
        </form>
    </div>
</body>
</html>
