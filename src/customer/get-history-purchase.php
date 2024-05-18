<?php

require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if 'customer_phone' parameter is set in the GET request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the customer_phone number from the GET parameters
    $json_data = file_get_contents("php://input");

    // Decode JSON data into PHP associative array
    $request_data = json_decode($json_data, true);

    // Now $request_data contains the JSON data sent from the client as an associative array
    $customer_phone = isset($request_data['customer_phone']) ? $request_data['customer_phone'] : '';

    $condition = ["customer_phone" => $customer_phone];

    // Call the function and echo the result
    $customerData = getDataFromTableByCriteria($pdo, 'purchase', $condition);
    echo json_encode($customerData);
}else{
    echo json_encode(array('msg'=> 'Missing parameter'));
}
