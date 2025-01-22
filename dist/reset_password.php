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
</head>
<body>
    <h1>Reset Password</h1>
    <form method="post" action="reset_password_code.php">
    <?php if(isset($_SESSION['message'])) { ?>
                    <h3><?php echo $_SESSION['message']; ?></h3>
                    <?php unset($_SESSION['message']); ?>
                <?php } ?>
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" name="sendResetLink">Send Reset Link</button>
    </form>
</body>
</html>
