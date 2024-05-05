<?php
session_start();

require_once '../db-connect.php';
require '../common/rest-api.php';

if (isset($_SESSION['user_id'], $_POST['password'])) {
    $userID = $_SESSION['user_id'];
    $password = $_POST['password'];

    // $new_password = password_hash($password, PASSWORD_BCRYPT);

    $updateUserValue = ["password" => $password, "is_active" => true];
    $updateUserCondition = ["id" => $userID];

    if (checkRecordExists($pdo, 'user_account', $updateUserCondition)) {
        // Execute the statement
        if (updateDataInTable($pdo, 'user_account', $updateUserValue, $updateUserCondition)) {
            unset($_SESSION['is_active']);
            echo json_encode(["message" => "Password updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update password"]);
        }
    } else {
        // If user does not exist, return error message
        echo json_encode(["message" => "User does not exist"]);
    }
} else {
    // If required parameters are missing, return error message
    echo json_encode(["message" => "Missing required parameters"]);
}
?>
