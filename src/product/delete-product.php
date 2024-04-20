<?php
// Include the database connection file
require_once '../db-connect.php';

require '../common/rest-api.php';

// Check if the product ID is provided
if (isset($_POST['id'])) {
    // Retrieve the product ID from the POST request
    $id = $_POST['id'];

    $condition = ["id" => $id];

    if (checkRecordExists($pdo, 'product', $condition)) {
        try {
            // Execute the statement
            if (deleteRecordsFromTable($pdo, 'product', $condition)) {
                echo json_encode(array("message" => "Product deleted successfully"));
            } else {
                echo json_encode(array("message" => "Failed to delete product"));
            }
        } catch (PDOException $e) {
            // Handle any PDO exception
            echo json_encode(array("message" => "Failed to delete product: " . $e->getMessage()));
        }
    }
} else {
    // If product ID is missing, return error message
    echo json_encode(array("message" => "Missing product ID"));
}
