<?php
// Include the database connection file
require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if required parameters are provided
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the phone number from the GET parameters
    $json_data = file_get_contents("php://input");

    // Decode JSON data into PHP associative array
    $request_data = json_decode($json_data, true);
    // Retrieve the values from the POST request

    // Now $request_data contains the JSON data sent from the client as an associative array
    $phone = isset($request_data['phone']) ? $request_data['phone'] : '';
    $name = isset($request_data['name']) ? $request_data['name'] : '';
    $address = isset($request_data['address']) ? $request_data['address'] : '';

    $customer = [
        "name" => $name,
        "phone" => $phone,
        "address" => $address
    ];

    if (addDataToTable($pdo, 'customer', $customer)) {
        echo json_encode(["message" => "Create new customer successfully!"]);
    } else {
        // If data insertion fails, return error message
        echo json_encode(["message" => "Failed to add customer"]);
    }
} else {
    // If required parameters are missing, return error message
    echo json_encode(["message" => "Missing required parameters"]);
}