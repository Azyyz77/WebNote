<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style_4.css">
</head>
<body class="bg-yellow-100 text-gray-800 overflow-hidden">
    <div class="layout">
        <!-- Left Section -->
        <div class="left-section">
            <h1>Welcome to Web Note</h1>
            <p>Keep track of your thoughts, organize your tasks, and capture your ideas all in one place.</p>
            <p>Our easy-to-use platform makes note-taking simple and efficient, so you can focus on what matters most.</p>
        </div>

        <!-- Right Section: Sign-Up Form -->
        <div class="container" id="signup">
            <h1 class="form-title">Register</h1>
            
                <?php if(isset($_SESSION['signup_status'])) { ?>
                    <div class="alert">
                    <h3><?php echo $_SESSION['signup_status']; ?></h3>
                    </div>
                    <?php unset($_SESSION['signup_status']); ?>
                <?php } ?>
            
            
                <?php if(isset($_SESSION['signup_work'])) { ?>
                    <div class="work">
                        <h3><?php echo $_SESSION['signup_work']; ?></h3>
                    </div>
                    <?php unset($_SESSION['signup_work']); ?>
                <?php } ?>
            
            <form method="post" action="register.php" onsubmit="return validatePassword();">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="fName" id="fName" placeholder="First Name" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>
                <small id="passwordError" style="color: red; display: none;">Password must be at least 8 characters , 1 Uppercase Letter, 1 Number and 1 Special character.</small>
                
                <input type="submit" class="btn" value="Sign Up" name="signUp">
            </form>
            
            <div class="links">
                <p>Already Have Account?</p>
                <a href="Login.php">Sign In</a>
            </div>
        </div>
    </div>
    <script>
        function validatePassword() {
        const password = document.getElementById('password').value;
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
