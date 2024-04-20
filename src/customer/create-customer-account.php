<?php
// Include the database connection file
require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if required parameters are provided
if (isset($_GET['phone'], $_POST['name'], $_POST['address'])) {
    // Retrieve the values from the POST request
    $name = $_POST['name'];
    $phone = $_GET['phone'];
    $address = $_POST['address'];

    $customer = [
        "name" => $name,
        "phone" => $phone,
        "address" => $address
    ];

    if (addDataToTable($pdo, 'customer', $customer)) {
        echo 'okay';
    } else {
        // If data insertion fails, return error message
        echo json_encode(["message" => "Failed to add customer"]);
    }
} else {
    // If required parameters are missing, return error message
    echo json_encode(["message" => "Missing required parameters"]);
}
?>
