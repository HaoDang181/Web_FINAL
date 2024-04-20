<?php
// Include the database connection file
require_once '../db-connect.php';

// Check if required parameters are provided
if (isset($_GET['phone'], $_POST['name'], $_POST['address'])) {
    // Retrieve the values from the POST request
    $name = $_POST['name'];
    $phone = $_GET['phone'];
    $address = $_POST['address'];
    // Prepare the SQL statement to insert a new product
    $sql = "INSERT INTO customer (name, phone, address) 
            VALUES (:name, :phone, :address)";

    // Prepare and execute the SQL statement with PDO
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Customer account create successfully"));
    } else {
        echo json_encode(array("message" => "Failed to create customer account"));
    }
} else {
    // If required parameters are missing, return error message
    echo json_encode(array("message" => "Missing required parameters"));
}
