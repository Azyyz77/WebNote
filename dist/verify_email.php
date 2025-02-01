<?php
session_start();
require_once("connect.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Fetch the token and its expiration time
    $verify_query = "SELECT verify_token, verify_status, verify_token_expiration FROM users WHERE verify_token = '$token' LIMIT 1";
    $result = $conn->query($verify_query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Check if the token has already been used
        if ($row['verify_status'] == "1") {
            $_SESSION['login_work'] = 'Email has already been verified. Please login.';
            header("location: Login.php");
            exit();
        }

        // Check if the token has expired
        $current_time = date('Y-m-d H:i:s');
        if ($current_time > $row['verify_token_expiration']) {
            $_SESSION['login_status'] = 'This verification link has expired.';
            header("location: Login.php");
            exit();
        }

        // Update the verify status if everything is valid
        $clicked_token = $row['verify_token'];
        $update_query = "UPDATE users SET verify_status = '1' WHERE verify_token = '$clicked_token' LIMIT 1";
        $update_result = $conn->query($update_query);

        if ($update_result) {
            $_SESSION['login_work'] = 'Email has been verified. Please login.';
            header("location: Login.php");
            exit();
        } else {
            $_SESSION['login_status'] = 'Failed to verify email. Please try again later.';
            header("location: Login.php");
            exit();
        }
    } else {
        $_SESSION['login_status'] = 'This token does not exist.';
        header("location: Login.php");
        exit();
    }
} else {
    $_SESSION['login_status'] = 'Not Allowed!';
    header("location: Login.php");
    exit();
}
?>
