<?php

require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if 'phone' parameter is set in the GET request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the phone number from the GET parameters
    $json_data = file_get_contents("php://input");

    // Decode JSON data into PHP associative array
    $request_data = json_decode($json_data, true);

    // Now $request_data contains the JSON data sent from the client as an associative array
    $phone = isset($request_data['phone']) ? $request_data['phone'] : '';

    $condition = ["phone" => $phone];

    // Call the function and echo the result
    $customerData = getDataFromTableByCriteria($pdo, 'customer', $condition);
    echo json_encode($customerData);
}else{
    echo json_encode(array('msg'=> 'Missing parameter'));
}
