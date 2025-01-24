<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style_4.css">
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
</head>
<body class="bg-yellow-100 text-gray-800 overflow-hidden">
<div class="layout">
  <!-- Left Section -->
  <div class="left-section">
    <h1>Welcome Back!</h1>
    <p>Access your notes, organize your tasks, and never lose track of your ideas again.</p>
    <p>Sign in to continue your productivity journey with Web Note.</p>
  </div>

  <!-- Right Section: Login Form -->
  <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <div class="alert">
        <?php if(isset($_SESSION['login_status'])) { ?>
            <h3><?php echo $_SESSION['login_status']; ?></h3>
            <?php unset($_SESSION['login_status']); ?>
        <?php } ?>
        </div>
        <form method="post" action="register.php" onsubmit="return validatePassword();">
          <div class="input-group">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" id="email" placeholder="Email" required>
              <input type="hidden" name="role" required>
          </div>
          <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" placeholder="Password" required>
          </div>

          <div class="work">
            <?php if(isset($_SESSION['login_work'])) { ?>
              <h3><?php echo $_SESSION['login_work']; ?></h3>
              <?php unset($_SESSION['login_work']); ?>
            <?php } ?>
          </div>
          <p class="recover">
            <a href="reset_password.php" class="text-blue-600 hover:underline">Recover Password</a>
          </p>
         <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <div class="links">
          <p>Don't have an account yet?</p>
          <a href="sign_up.php">Sign Up</a>
        </div>
      </div>
      
</body>
</html>
