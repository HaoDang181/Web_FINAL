<?php

require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if either 'barcode' or 'name' parameter is set in the GET request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the phone number from the GET parameters
    $json_data = file_get_contents("php://input");

    // Decode JSON data into PHP associative array
    $request_data = json_decode($json_data, true);
    // Retrieve the values from the POST request

    // Now $request_data contains the JSON data sent from the client as an associative array
    $barcode = isset($request_data['product_barcode']) ? $request_data['product_barcode'] : '';
    if (!checkRecordExists($pdo, 'orders', ['product_barcode'=> $barcode])) {
        echo json_encode($barcode);
    }

}
