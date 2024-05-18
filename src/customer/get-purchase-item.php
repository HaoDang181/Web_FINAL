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
    $purchase_id = isset($request_data['purchase_id']) ? $request_data['purchase_id'] : '';

    $condition = ["purchase_id" => $purchase_id];

    // Call the function and echo the result
    $purchaseList = getDataFromTableByCriteria($pdo, 'orders', $condition);
    echo json_encode($purchaseList);
}else{
    echo json_encode(array('msg'=> 'Missing parameter'));
}
