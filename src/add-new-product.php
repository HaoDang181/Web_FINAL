<?php
// Include the database connection file
require_once 'db-connect.php';

// Check if required parameters are provided
if (isset($_POST['name'], $_POST['import_price'], $_POST['retail_price'], $_POST['category'])) {
    // Retrieve the values from the POST request
    $name = $_POST['name'];
    $import_price = $_POST['import_price'];
    $retail_price = $_POST['retail_price'];
    $category = $_POST['category'];

    // Prepare the SQL statement to insert a new product
    $sql = "INSERT INTO product (barcode, name, import_price, retail_price, category, create_date) 
            VALUES (:barcode, :name, :import_price, :retail_price, :category, NOW())";

    // Generate a barcode (you might want to implement your own logic to generate a unique barcode)
    $barcode = generateBarcode();

    // Prepare and execute the SQL statement with PDO
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':barcode', $barcode);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':import_price', $import_price);
    $stmt->bindParam(':retail_price', $retail_price);
    $stmt->bindParam(':category', $category);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Product added successfully"));
    } else {
        echo json_encode(array("message" => "Failed to add product"));
    }
} else {
    // If required parameters are missing, return error message
    echo json_encode(array("message" => "Missing required parameters"));
}

// Function to generate a random barcode (replace this with your own logic)
function generateBarcode()
{
    return rand(1000000000000, 9999999999999);
}
