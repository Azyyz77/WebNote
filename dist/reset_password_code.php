<?php
session_start();
require_once("connect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function send_password_reset($get_name, $get_email, $token){
    $mail = new PHPMailer(true);
    try {
    
    //Server settings

    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'bayremakka2003@gmail.com';                     //SMTP username
    $mail->Password   = 'ozfp gmzy oyac dmor';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = 587 ;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  
    //Recipients
    $mail->setFrom('bayremakka2003@gmail.com', $get_name);
    $mail->addAddress($get_email);     //Add a recipient
    
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = "Reset Password Notification"; 

    $email_template = "<h3><b>You are receiving this email because we received a password reset request for your account.</b></h3>

    <a href='http://localhost/stage_project/webNote/dist/change_password.php?token=$token&email=$get_email'>Click Me</a>";


    $mail->Body = $email_template;
    $mail->send();
    }catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}

if (isset($_POST['sendResetLink'])) {
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $token = bin2hex(random_bytes(32)); // Generates a secure 64-character token
    $expiration = date('Y-m-d H:i:s', strtotime('+5 minutes')); // Set expiration time

    $check_email = "SELECT * from users where email='$email' Limit 1";
    $result = $conn->query($check_email);

    if($result->num_rows > 0){
        $row = mysqli_fetch_array($result);
        $get_name = $row['firstName'];
        $get_email = $row['email'];

        $update_token = "UPDATE users SET verify_token='$token', token_expiration='$expiration' WHERE email='$get_email' LIMIT 1";
        $update_token_run = mysqli_query($conn, $update_token);

        if($update_token_run){
            send_password_reset($get_name,$get_email,$token);
            $_SESSION['message'] = "Password reset link has been sent to your email. Please check your inbox.";
            header("location: reset_password.php");
            exit();
        }
    }else{
        $_SESSION['error'] = "No user found with this email address.";
        header("location:reset_password.php");
        exit();
    }
}

if(isset($_POST['password_update'])){
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $new_password = mysqli_real_escape_string($conn,$_POST['newPassword']);
    $confirm_password = mysqli_real_escape_string($conn,$_POST['confirmPassword']);
    $token = mysqli_real_escape_string($conn,$_POST['token']);

    if(!empty($token) ){
        if(!empty($email) && !empty($new_password) && !empty($confirm_password)){
            $current_time = date('Y-m-d H:i:s');
            $check_token = "SELECT verify_token, token_expiration FROM users WHERE verify_token='$token' LIMIT 1";
            $result = $conn->query($check_token);

            if($result->num_rows > 0){
                $row = mysqli_fetch_array($result);
                if (strtotime($current_time) > strtotime($row['token_expiration'])) {
                    $_SESSION['error'] = "This reset password link has expired.";
                    header("location: reset_password.php");
                    exit();
                }
                elseif($new_password === $confirm_password){
                    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_password = "UPDATE users SET password='$new_password_hashed' WHERE verify_token='$token' limit 1";
                    $update_password_run = mysqli_query($conn, $update_password);
                    
                    if($update_password_run){
                        $_SESSION['login_work'] = '<i class="fas fa-check-circle"></i>'."Password has been updated successfully. You can now login.";
                        header("location: login.php");
                        exit();
                    }else{
                        $_SESSION['error'] = "Failed to update password. Please try again.";
                        header("location: change_password.php?token=$token&email=$email");
                        exit();
                    }
                }else{
                    $_SESSION['error'] = "Passwords do not match.";
                    header("location: change_password.php?token=$token&email=$email");
                    exit();


                }
            }else{
                $_SESSION['error'] = "Invalid or expired Link.";
                header("location: reset_password.php");
                exit();
            }
        }else{
            $_SESSION['error'] = "All fields are required.";
            header("location: change_password.php?token=$token&email=$email");
            exit();
        }
    }
    else{
        $_SESSION['error'] = "Token is missing. Please try again.";
        header("location: reset_password.php");
        exit();
    }
}
?>


