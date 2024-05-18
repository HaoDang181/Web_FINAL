<?php
// Include the database connection file
require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if required parameters are provided
if (isset($_POST['name'], $_POST['import_price'], $_POST['retail_price'], $_POST['category'], $_FILES['product_image'])) {
    // Retrieve the values from the POST request
    $name = $_POST['name'];
    $import_price = $_POST['import_price'];
    $retail_price = $_POST['retail_price'];
    $category = $_POST['category'];
    $product_image = $_FILES['product_image']['name']; // This will contain the filename of the uploaded image
    $createDate = date('Y-m-d H:i:s');

    // Specify the directory where you want to save the uploaded image
    $uploadDirectory = '../uploadImage/'; // Update the directory path

    // Create the directory if it doesn't exist
    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, true)) {
        echo json_encode(["message" => "Failed to create directory"]);
        exit;
    }

    $barcode = generateBarcode();

    $insertProductCondition = [
        "barcode" => $barcode,
        "name" => $name,
        "import_price" => $import_price,
        "retail_price" => $retail_price,
        "category" => $category,
        "image" => $product_image,
        "create_date" => $createDate
    ];

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadDirectory . $product_image)) {
        // Insert product into product table
        if ($productId = addDataToTable($pdo, 'product', $insertProductCondition)) {
            header("Location: /final/public/template/product-list.php");
        } else {
            // If data insertion fails, return error message
            echo json_encode(["message" => "Failed to insert product"]);
        }
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
