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
    <style>
        body {
            background-color: #eedb92;
            color: #374151;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
    </style>
</head>
<body >
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h1 class="text-3xl font-semibold text-black mb-6 text-center">Reset Password</h1>
        <?php if(isset($_SESSION['message'])) { ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        <h3><?php echo $_SESSION['message']; ?></h3>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php } ?>
                
                <?php if(isset($_SESSION['error'])) { ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                        <h3><?php echo $_SESSION['error']; ?></h3>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php } ?>
        
        <form method="post" action="reset_password_code.php">
            <!-- Champ de saisie d'email avec bordure qui change au focus -->
            <input type="email" name="email" placeholder="Enter your email" required class="w-full p-3 mb-4 border border-transparent rounded-md focus:border-yellow-400 focus:outline-none" />
            
            <button type="submit" name="sendResetLink" class="w-full py-3 bg-yellow-500 text-white font-semibold rounded-md hover:bg-yellow-400 active:bg-yellow-600 transition duration-300 ease-in-out">
                Send Reset Link
            </button>
        </form>
    </div>
</body>
</html>
