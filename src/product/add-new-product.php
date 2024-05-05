<?php
// Include the database connection file
require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if required parameters are provided
if (isset($_POST['name'], $_POST['import_price'], $_POST['retail_price'], $_POST['category'])) {
    // Retrieve the values from the POST request
    $name = $_POST['name'];
    $import_price = $_POST['import_price'];
    $retail_price = $_POST['retail_price'];
    $category = $_POST['category'];

    // Generate a barcode (you might want to implement your own logic to generate a unique barcode)
    $barcode = generateBarcode();

    $insertProductCondition = [
        "barcode" => $barcode,
        "name" => $name,
        "import_price" => $import_price,
        "retail_price" => $retail_price,
        "category" => $category
    ];

    // Insert product into product table
    if ($productId = addDataToTable($pdo, 'product', $insertProductCondition)) {
        echo 'Product inserted successfully';
    } else {
        // If data insertion fails, return error message
        echo json_encode(["message" => "Failed to insert product"]);
    }
} else {
    // If required parameters are missing, return error message
    echo json_encode(["message" => "Missing required parameters"]);
}

// Function to generate a random barcode (replace this with your own logic)
function generateBarcode()
{
    return rand(1000000000000, 9999999999999);
}

?>
