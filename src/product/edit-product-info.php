<?php
// Include the database connection file
require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if required parameters are provided
if (isset($_POST['id'], $_POST['barcode'], $_POST['name'], $_POST['import_price'], $_POST['retail_price'], $_POST['category'], $_FILES['product_image'])) {
    // Retrieve the values from the POST request
    $id = $_POST['id'];
    $barcode = $_POST['barcode'];
    $name = $_POST['name'];
    $import_price = $_POST['import_price'];
    $retail_price = $_POST['retail_price'];
    $category = $_POST['category'];
    $product_image = $_FILES['product_image']['name']; // This will contain the filename of the uploaded image
    $lastModifiedDate = date('Y-m-d H:i:s');

    // Specify the directory where you want to save the uploaded image
    $uploadDirectory = '../uploadImage/'; // Update the directory path

    // Create the directory if it doesn't exist
    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true)) {
        echo json_encode(["message" => "Failed to create directory"]);
        exit;
    }

    // Retrieve the old image filename from the database
    try {
        $stmt = $pdo->prepare("SELECT image FROM product WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $oldImage = $result['image'];

            // Delete the old image file if it exists
            if ($oldImage && file_exists($uploadDirectory . $oldImage)) {
                unlink($uploadDirectory . $oldImage);
            }
        }

        // Update the product information
        $updateProductValue = [
            "name" => $name,
            "import_price" => $import_price,
            "retail_price" => $retail_price,
            "category" => $category,
            "barcode" => $barcode,
            "image" => $product_image,
            "last_modified_date" => $lastModifiedDate
        ];

        $updateProductCondition = ["id" => $id];

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadDirectory . $product_image)) {
            // Execute the update statement
            if (updateDataInTable($pdo, 'product', $updateProductValue, $updateProductCondition)) {
                header("Location: /final/public/template/product-list.php");
                exit;
            } else {
                echo json_encode(["message" => "Failed to update product information"]);
            }
        } else {
            echo json_encode(["message" => "Failed to upload new image"]);
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
