<?php
session_start();
require_once("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }
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
<body>
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md animate-fade-in">
        <h2 class="text-2xl font-bold text-black mb-6 text-center">Change Password</h2>

        <form action="reset_password_code.php" method="post" onsubmit="return validatePassword();">
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
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    value="<?php echo $_GET['email']; ?>" 
                    name="email" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-400" 
                    placeholder="Enter your Email" 
                    required>
            </div>
            <div class="mb-4">
                <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input 
                    type="password" 
                    id="newPassword" 
                    name="newPassword" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-400" 
                    placeholder="Enter new password" 
                    required>
            </div>
            <small id="passwordError" style="color: red; display: none;">Password must be at least 8 characters , 1 Uppercase Letter, 1 Number and 1 Special character.</small>
            <div class="mb-4">
                <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <input 
                    type="password" 
                    id="confirmPassword" 
                    name="confirmPassword" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-400" 
                    placeholder="Confirm new password" 
                    required>
            </div>
            <button 
                type="submit" 
                name="password_update" 
                class="w-full bg-yellow-500 text-white py-2 rounded-md font-medium hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition transform hover:scale-105">
                Change Password
            </button>
        </form>
    </div>
    <script>
        function validatePassword() {
        const password = document.getElementById('newPassword').value;
        const passwordError = document.getElementById('passwordError');
        const strongPassword = /^(?=.*[A-Z])(?=.*\d)(?=.*[?!@#$%^&.*])[A-Za-z\d?!@#$%.^&*]{8,}$/;

        if (!strongPassword.test(password)) {
            passwordError.style.display = 'block';
            return false;
        }
        passwordError.style.display = 'none';
        return true;
    }
    </script>
</body>
</html>
