<?php
// Include the database connection file
require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if required parameters are provided
if (isset($_POST['name'], $_POST['import_price'], $_POST['retail_price'], $_POST['category'], $_FILES['images'])) {
    // Retrieve the values from the POST request
    $name = $_POST['name'];
    $import_price = $_POST['import_price'];
    $retail_price = $_POST['retail_price'];
    $category = $_POST['category'];
    $images = $_FILES['images'];

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
        // Save images to a folder and insert their filenames into product_image table
        $imageNames = [];
        foreach ($images['tmp_name'] as $key => $tmp_name) {
            $imageName = $productId . '_' . basename($images['name'][$key]); // Generate unique image name
            $imagePath = 'uploads/' . $imageName; // Path where images will be saved
            move_uploaded_file($tmp_name, $imagePath); // Move uploaded file to destination folder
            $imageNames[] = $imageName;
        }

        // Insert image names into product_image table
        foreach ($imageNames as $imageName) {
            $insertImageCondition = [
                "image_name" => $imageName,
                "product_id" => $productId
            ];
            addDataToTable($pdo, 'product_image', $insertImageCondition);
        }

        echo 'Product and images inserted successfully';
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
