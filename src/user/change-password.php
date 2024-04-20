<?php
require_once '../db-connect.php';

if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $new_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "Update user_account SET password = :new_password WHERE username = :username";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':new_password', $new_password);
    $stmt->bindParam(':username', $username);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Password updated successfully"));
    } else {
        echo json_encode(array("message" => "Failed to update password"));
    }
} else {
    // If required parameters are missing, return error message
    echo json_encode(array("message" => "Missing required parameters"));
}
