<?php 
session_start();
require_once("connect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function sendemail_verify($firstName,$email,$verify_token)
{
    try {
        $mail = new PHPMailer(true);
        //Server settings
    
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'bayremakka2003@gmail.com';                     //SMTP username
        $mail->Password   = 'ozfp gmzy oyac dmor';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587 ;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
      
        //Recipients
        $mail->setFrom('bayremakka2003@gmail.com', $firstName);
        $mail->addAddress($email);     //Add a recipient
        
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = "Verify Email Notification"; 
    
        $email_template = "<h1>b>You have Registred to WebNote with this email</h1>
        <h3>Verify your email address to Login with click the below given link</h3>
        <a href='http://localhost/stage/webNote/dist/verify_email.php?token=$verify_token&email=$email'>Click Me</a>";
        
        $mail->Body = $email_template;
        $mail->send();
        }catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    
}

if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verify_token = bin2hex(random_bytes(32));
    
    // Set the expiration time (5 minutes from now)
    $verify_token_expiration = date('Y-m-d H:i:s', time() + 300); // 300 seconds = 5 minutes
    
    // Use password_hash for secure password storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    
    if ($result->num_rows > 0) {
        $_SESSION['signup_status'] = '<i class="fas fa-exclamation-circle"></i>'."Email Address Already Exists!";
        header("location:sign_up.php");
        exit();
    } else {
        $insertQuery = "INSERT INTO users (firstName, lastName, email, password, verify_token, verify_token_expiration)
                        VALUES ('$firstName', '$lastName', '$email', '$hashedPassword', '$verify_token', '$verify_token_expiration')";
        if ($conn->query($insertQuery) === TRUE) {
            sendemail_verify("$firstName", "$email", "$verify_token");
            $_SESSION['signup_work'] = '<i class="fas fa-check-circle"></i>'."Registration Successful. Please verify your Email Address.";
            header("location:sign_up.php");
            exit();
        } else {
            $_SESSION['signup_status'] = '<i class="fas fa-exclamation-circle"></i>'."Registration Failed!";
            header("location:sign_up.php");
            exit();
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $verify_token= $row['verify_status'];
        $hashedPassword = $row['password'];
        $role = $row['role'];
        
        // Use password_verify to check password
        if (password_verify($password, $hashedPassword) && $verify_token == "1" ) {
            if($role == "user"){
                session_start();
                $_SESSION['email'] = $row['email'];
                header("Location: home.php");
                exit();
            }
            else{
                session_start();
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role'];
                header("Location: admin.php");
                exit();
            }

        } else {
            if ($verify_token == "0") {
                $_SESSION['login_status'] = '<i class="fas fa-exclamation-circle"></i>'."Your Email Address is not Verified. Please Verify your Email Address.";
                header("location:Login.php");
                exit();
            }else{
            $_SESSION['login_status'] = '<i class="fas fa-exclamation-circle"></i>'."Incorrect Email or Password!";
            header("location:Login.php");
            exit();
            }
        }
    } else {
        $_SESSION['login_status'] = '<i class="fas fa-exclamation-circle"></i>'."Incorrect Email or Password!";
        header("location:Login.php");
        exit();
    }
}
?>
