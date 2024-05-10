<?php

require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if 'phone' parameter is set in the GET request
if (isset($_GET['phone'])) {
    // Get the phone number from the GET parameters
    $phone = $_GET['phone'];

    $condition = ["phone" => $phone];

    // Call the function and echo the result
    $customerData = getDataFromTableByCriteria($pdo, 'customer', $condition);
    echo json_encode($customerData);
}
