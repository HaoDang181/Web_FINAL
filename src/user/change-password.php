<?php
require_once '../db-connect.php';
require '../common/rest-api.php';

if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $new_password = password_hash($password, PASSWORD_BCRYPT);

    $updateUserValue = ["password" => $new_password];
    $updateUserCondition = ["username" => $username];

    if (checkRecordExists($pdo, 'user_account', $updateUserCondition)) {
        // Execute the statement
        if (updateDataInTable($pdo, 'user_account', $updateUserValue, $updateUserCondition)) {
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
