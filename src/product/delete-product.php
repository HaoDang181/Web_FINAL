<?php
// Include the database connection file
require_once '../db-connect.php';

// Check if the product ID is provided
if (isset($_POST['id'])) {
    // Retrieve the product ID from the POST request
    $id = $_POST['id'];

    // Prepare the SQL statement to delete the product
    $sql = "DELETE FROM product WHERE id = :id";

    try {
        // Prepare and execute the SQL statement with PDO
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(array("message" => "Product deleted successfully"));
        } else {
            echo json_encode(array("message" => "Failed to delete product"));
        }
    } catch (PDOException $e) {
        // Handle any PDO exception
        echo json_encode(array("message" => "Failed to delete product: " . $e->getMessage()));
    }
} else {
    // If product ID is missing, return error message
    echo json_encode(array("message" => "Missing product ID"));
}
?>
