<?php

session_start();
require_once("connect.php");

if (isset($_POST['verify'])) {
    $email = $_POST['email'];
    $verificationCode = $_POST['verification_code'];

    // Find the user by email and verification code
    $sql = "SELECT * FROM temp_users WHERE email='$email' AND verification_code='$verificationCode'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $password = $row['password'];

        // Move the user to the main users table
        $insertQuery = "INSERT INTO users (firstName, lastName, email, password) 
                        VALUES ('$firstName', '$lastName', '$email', '$password')";
        if ($conn->query($insertQuery) === TRUE) {
            // Delete the user from temp_users after successful verification
            $deleteQuery = "DELETE FROM temp_users WHERE email='$email'";
            $conn->query($deleteQuery);
            header("Location: index.php");
                exit();
        } else {
            echo "Error moving your data to the main table.";
        }
    } else {
        echo "Invalid verification code or email.";
    }
}
?>
