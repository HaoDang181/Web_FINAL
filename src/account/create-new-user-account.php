<?php
// Include the database connection file
require_once '../db-connect.php';
require 'send-email.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');
// Check if required parameters are provided
if (isset($_POST['fullname'], $_POST['email'])) {
    // Retrieve the values from the POST request
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = strstr($email, '@', true);
    $role = "sales";
    $password = $username;
    $isActive = false;
    $isLock = false;
    $avatar = "default-avatar.avif";

    // Generate a 32-character hexadecimal token
    $token = bin2hex(random_bytes(16));

    // Calculate expiration time (1 minute from now)
    // Current timestamp + 60 seconds (1 minute)
    $expiration = date('Y-m-d H:i:s', time() + 60);

    // Prepare the SQL statement to insert a new user account
    $sql = "INSERT INTO user_account (username, email, password, role, fullname, token, expiration, is_active, is_lock, avatar) 
    VALUES (:username, :email, :password, :role, :fullname, :token, :expiration, :isActive, :isLock, :avatar)";

    // Prepare and execute the SQL statement with PDO
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expiration', $expiration);
    $stmt->bindParam(':isActive', $isActive);
    $stmt->bindParam(':isLock', $isLock);
    $stmt->bindParam(':avatar', $avatar);

    //Set email body
    $mail->Subject = "Send login link";
    $mail->Body = "Đây là mail gửi cho nhân viên đường dẫn đăng nhập vào hệ thống. Tài khoản và mật khẩu mặc định của bạn là: $username và $password. 
            <br><br> Vui lòng nhấn vào <a href='http://localhost/final/src/login.php?token=$token'>đây</a> để đăng nhập. Đường dẫn này sẽ bị vô hiệu sau 1 phút.";

    // Execute the statement
    if ($stmt->execute()) {
        // If insertion is successful, send email and return success message
        $mail->send();
        header("Location: /final/public/template/admin-dashboard.php");
    } else {
        // If insertion fails, return error message
        echo json_encode(array("message" => "Failed to add user account"));
    }
} else {
    // If required parameters are missing, return error message
    echo json_encode(array("message" => "Missing required parameters"));
}
