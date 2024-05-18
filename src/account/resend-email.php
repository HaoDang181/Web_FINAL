<?php
// Include the database connection file
require_once '../db-connect.php';
require 'send-email.php';
require '../common/rest-api.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the JSON data from the request body
    $json_data = file_get_contents("php://input");

    // Decode JSON data into PHP associative array
    $request_data = json_decode($json_data, true);

    // Retrieve the 'id' value from the request data
    $id = isset($request_data['id']) ? $request_data['id'] : '';

    // Generate a 32-character hexadecimal token
    $token = bin2hex(random_bytes(16));

    // Calculate expiration time (1 minute from now)
    $expiration = date('Y-m-d H:i:s', time() + 60);

    // Update the token and expiration time
    $updateTokenValue = [
        'token' => $token,
        'expiration' => $expiration
    ];

    $updateTokenCondition = ["id" => $id];

    // Retrieve user data from the database
    $user = getDataFromTableByCriteria($pdo, 'user_account', $updateTokenCondition);
    
    // Check if the user exists
    if (!empty($user)) {
        $username = $user[0]['username'];
        $password = $user[0]['password'];

        // Update the token and expiration time in the database
        if (updateDataInTable($pdo, 'user_account', $updateTokenValue, $updateTokenCondition)) {
            // Set email subject and body
            $mail->Subject = "Resend Login link";
            $mail->Body = "Đây là mail gửi cho nhân viên đường dẫn đăng nhập vào hệ thống. Tài khoản và mật khẩu mặc định của bạn là: $username và $password. 
            <br><br> Vui lòng nhấn vào <a href='http://localhost/final/src/login.php?token=$token'>đây</a> để đăng nhập. Đường dẫn này sẽ bị vô hiệu sau 1 phút.";

            // Send the email
            if ($mail->send()) {
                // Redirect to the admin dashboard if email sent successfully
                header("Location: /final/public/template/admin-dashboard.php");
                exit();
            } else {
                // Return an error message if the email sending fails
                echo json_encode(array("message" => "Failed to resend email user account"));
            }
        } else {
            // Return an error message if the database update fails
            echo json_encode(array("message" => "Failed to update token and expiration time"));
        }
    } else {
        // Return an error message if the user does not exist
        echo json_encode(array("message" => "User not found"));
    }
} else {
    // Return an error message if the request method is not POST
    echo json_encode(array("message" => "Invalid request method"));
}
