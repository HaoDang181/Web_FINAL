<?php
session_start();

require_once '../db-connect.php';
require '../common/rest-api.php';

if (isset($_SESSION['user_id'], $_FILES['avatar'])) {
    $userID = $_SESSION['user_id'];
    $avatar = $_FILES['avatar']['name']; // This will contain the filename of the uploaded image
    
    // Specify the directory where you want to save the uploaded image
    $uploadDirectory = '../uploadImage/'; // Update the directory path

    // Create the directory if it doesn't exist
    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, true)) {
        echo json_encode(["message" => "Failed to create directory"]);
        exit;
    }

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDirectory . $avatar)) {
        // Prepare the update query
        $updateUserValue = ["avatar" => $avatar];
        $updateUserCondition = ["id" => $userID];

        if (checkRecordExists($pdo, 'user_account', $updateUserCondition)) {
            updateDataInTable($pdo, 'user_account', $updateUserValue, $updateUserCondition);
            header("Location: /final/public/template/profile.php");
            exit; // Ensure that no further code is executed after redirection
        } else {
            // If user does not exist, return error message
            echo json_encode(["message" => "User does not exist"]);
        }
    } else {
        // If file upload failed, return error message
        echo json_encode(["message" => "Failed to upload avatar"]);
    }
} else {
    // If required parameters are missing, return error message
    echo json_encode(["message" => "Missing required parameters"]);
}
?>
