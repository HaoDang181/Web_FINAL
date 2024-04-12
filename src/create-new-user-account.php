<?php
// Include the database connection file
require_once 'db-connect.php';
require 'send-email.php';

// Check if required parameters are provided
if (isset($_POST['fullname'], $_POST['email'])) {
    // Retrieve the values from the POST request
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = strstr($email, '@', true);
    $role = "sales";
    $password = $username;

    // Generate a 32-character hexadecimal token
    $token = bin2hex(random_bytes(16));

    // Calculate expiration time (1 minute from now)
    // Current timestamp + 60 seconds (1 minute)
    $expiration = date('Y-m-d H:i:s', time() + 60);

    // Prepare the SQL statement to insert a new user account
    $sql = "INSERT INTO useraccount (username, email, password, role, fullname, token, expiration) 
    VALUES (:username, :email, :password, :role, :fullname, :token, :expiration)";

    // Prepare and execute the SQL statement with PDO
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expiration', $expiration);

    //Set email body
    $mail->Subject = "Test mail";
    $mail->Body = "This is some email that contains a login link. Your default username and password are: $username and $password. 
    <br><br> Please click <a href='http://localhost/mobilemart/src/login.php?token=$token'>here</a> to login. This link will expire in 1 minute.";

    // Execute the statement
    if ($stmt->execute()) {
        // If insertion is successful, send email and return success message
        $mail->send();
        echo json_encode(array("message" => "User account added successfully"));
    } else {
        // If insertion fails, return error message
        echo json_encode(array("message" => "Failed to add user account"));
    }
} else {
    // If required parameters are missing, return error message
    echo json_encode(array("message" => "Missing required parameters"));
}
