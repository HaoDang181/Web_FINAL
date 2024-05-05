<?php
// Include the database connection file
require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if required parameters are provided
if (isset($_POST['id'], $_POST['barcode'], $_POST['name'], $_POST['import_price'], $_POST['retail_price'], $_POST['category'])) {
    // Retrieve the values from the POST request
    $id = $_POST['id'];
    $barcode = $_POST['barcode'];
    $name = $_POST['name'];
    $import_price = $_POST['import_price'];
    $retail_price = $_POST['retail_price'];
    $category = $_POST['category'];

    try {
        $updateProductValue = [
            "name" => $name,
            "import_price" => $import_price,
            "retail_price" => $retail_price,
            "category" => $category,
            "barcode" => $barcode,
        ];

        $updateProductCondition = ["id" => $id];

        // Execute the statement
        if (updateDataInTable($pdo, 'product', $updateProductValue, $updateProductCondition)) {
            echo json_encode(["message" => "Product information updated successfully"]);
        }
    } catch (PDOException $e) {
        // Handle the integrity constraint violation error
        if ($e->getCode() == '23000') {
            echo json_encode(["message" => "Failed to update product information: Barcode already exists"]);
        } else {
            echo json_encode(["message" => "Failed to update product information: " . $e->getMessage()]);
        }
    }
} else {
    // If required parameters are missing, return error message
    echo json_encode(["message" => "Missing required parameters"]);
}
?>
