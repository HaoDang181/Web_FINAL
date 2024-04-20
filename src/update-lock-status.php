<?php

require_once 'db-connect.php';

if(isset($_POST['username'], $_POST['lock_status'])){
    $username = $_POST['username'];
    $lock_status = $_POST['lock_status'];

    echo $lock_status;
    // Prepare the SQL statement to select all data from the table
    $sql = "Update user_account SET is_lock = :lock_status WHERE username = :username";

    // Prepare and execute the SQL statement with PDO
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':lock_status', $lock_status);
    $stmt->bindParam(':username', $username);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Lock status updated successfully"));
    } else {
        echo json_encode(array("message" => "Failed to update lock status"));
    }
} else {
    // If required parameters are missing, return error message
    echo json_encode(array("message" => "Missing required parameters"));
}
