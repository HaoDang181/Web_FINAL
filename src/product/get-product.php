<?php

require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if either 'barcode' or 'name' parameter is set in the GET request
if (isset($_GET['barcode']) || isset($_GET['name'])) {
    // Get the barcode number and name from the GET parameters if they are set
    $barcode = isset($_GET['barcode']) ? $_GET['barcode'] : null;
    $name = isset($_GET['name']) ? $_GET['name'] : null;

    // If both 'barcode' and 'name' are not set, exit the script
    if ($barcode == null && $name == null) {
        exit("Neither 'barcode' nor 'name' parameter is set.");
    }

    // Prepare a SQL statement to select user based on username and password
    $stmt = $pdo->prepare("SELECT * FROM product WHERE barcode = :barcode OR name = :name");
    $stmt->execute(array(':barcode' => $barcode, ':name' => $name));

    // Fetch the user from the database
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product != null) {
        echo json_encode($product);
    } else {
        echo json_encode(["message" => "Sản phẩm không tồn tại!" . $e->getMessage()]);
    }

}
