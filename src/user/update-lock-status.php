<?php

require_once '../db-connect.php';

// Get the JSON data from the request body
$json_data = file_get_contents('php://input');
// Check if JSON data is received
if ($json_data !== false) {
    // Decode the JSON data into an associative array
    $data = json_decode($json_data, true);

    if(isset($data['id'], $data['lock_status'])){
        $id = $data['id'];
        $lock_status = $data['lock_status'];
    
        // Prepare the SQL statement to select all data from the table
        $sql = "Update user_account SET is_lock = :lock_status WHERE id = :id";
    
        // Prepare and execute the SQL statement with PDO
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':lock_status', $lock_status);
        $stmt->bindParam(':id', $id);
    
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
} else {
    // If JSON data is not received, return error message
    echo json_encode(array("message" => "No JSON data received"));
}

