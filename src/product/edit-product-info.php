<?php
// Include the database connection file
require_once '../db-connect.php';

// Check if required parameters are provided
if (isset($_POST['id'], $_POST['barcode'], $_POST['name'], $_POST['import_price'], $_POST['retail_price'], $_POST['category'])) {
    // Retrieve the values from the POST request
    $id = $_POST['id'];
    $barcode = $_POST['barcode'];
    $name = $_POST['name'];
    $import_price = $_POST['import_price'];
    $retail_price = $_POST['retail_price'];
    $category = $_POST['category'];

    // Prepare the SQL statement to update product information
    $sql = "UPDATE product SET name = :name, import_price = :import_price, retail_price = :retail_price, category = :category, barcode = :barcode, last_modified_date = NOW() WHERE id = :id";

    try{
        // Prepare and execute the SQL statement with PDO
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':import_price', $import_price);
        $stmt->bindParam(':retail_price', $retail_price);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':barcode', $barcode);
        $stmt->bindParam(':id', $id);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(array("message" => "Product information updated successfully"));
        }
    }catch(PDOException $e){
        // Handle the integrity constraint violation error
        if ($e->getCode() == '23000') {
            echo json_encode(array("message" => "Failed to update product information: Barcode already exists"));
        } else {
            echo json_encode(array("message" => "Failed to update product information: " . $e->getMessage()));
        }
    }

} else {
    // If required parameters are missing, return error message
    echo json_encode(array("message" => "Missing required parameters"));
}
?>
