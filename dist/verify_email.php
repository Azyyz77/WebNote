<?php
session_start();
require_once("connect.php");

// Check if the token is set in the URL query parameters
if(isset($_GET['token'])){
    $token = $_GET['token'];
    
    // Check if the token exists in the database
    $verify_querry = "SELECT verify_token,verify_status FROM users WHERE verify_token='$token' limit 1";
    $result = $conn->query($verify_querry);

    if($result->num_rows > 0){
        $row = mysqli_fetch_array($result);
        
        if($row['verify_status'] == "0"){
            $clicked_token = $row['verify_token'];
            $update_query = "UPDATE users SET verify_status='1' WHERE verify_token='$clicked_token' limit 1";
            $result = $conn->query($update_query);
            
            if($result){
                $_SESSION['login_work'] = '<i class="fas fa-check-circle"></i>'."Email is verified.Please login";
                header("location:Login.php");
                exit();
            }else{
                $_SESSION['login_status'] = '<i class="fas fa-exclamation-circle"></i>'."Failed to verify email. Please try again later.";
                header("location:Login.php");
                exit();
            }

        }else{
            $_SESSION['login_work'] = '<i class="fas fa-check-circle"></i>'."Email has been verified.Please login";
            header("location:Login.php");
            exit();
        }
    }else{
        $_SESSION['login_status'] = '<i class="fas fa-exclamation-circle"></i>'."This Token does not Exists";
        header("location:Login.php");
        
    }


}else{
    $_SESSION['login_status'] = '<i class="fas fa-exclamation-circle"></i>'."Not Allowed!";
    header("location:Login.php");
    
}






?>