<?php
// Include the database connection file and common functions
require_once '../db-connect.php';
require '../common/rest-api.php';

// Check if the product ID is provided
if (isset($_POST['id'])) {
    // Retrieve the product ID from the POST request
    $id = $_POST['id'];
    $condition = ["id" => $id];

    // Check if the product exists
    if (checkRecordExists($pdo, 'product', $condition)) {
        try {
            // Retrieve the product image
            $stmt = $pdo->prepare("SELECT image FROM product WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $oldImage = $result['image'];
                $uploadDirectory = '../uploadImage/'; // Define your upload directory here

                // Delete the old image file if it exists
                if ($oldImage && file_exists($uploadDirectory . $oldImage)) {
                    unlink($uploadDirectory . $oldImage);
                }
            }

            // Delete the product record
            if (deleteRecordsFromTable($pdo, 'product', $condition)) {
                header("Location: /final/public/template/product-list.php");
            } else {
                echo json_encode(["message" => "Failed to delete product"]);
            }
        } catch (PDOException $e) {
            header("Location: /final/src/error.php?status=400");
        }
    } else {
        // If product doesn't exist, return an error message
        echo json_encode(["message" => "Product not found"]);
    }
} else {
    // If product ID is missing, return an error message
    echo json_encode(["message" => "Missing product ID"]);
}