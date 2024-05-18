<?php
require_once '../db-connect.php';

require '../common/rest-api.php';
// PHP code for handling suggestion requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Read JSON data from the request body
    $json_data = file_get_contents("php://input");

    // Decode JSON data into PHP associative array
    $request_data = json_decode($json_data, true);

    // Now $request_data contains the JSON data sent from the client as an associative array
    $input = isset($request_data['input']) ? $request_data['input'] : '';

    $sql = "SELECT * FROM product WHERE name LIKE '%" . $input . "%' OR barcode LIKE '%" . $input . "%'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($rows);
        exit; // Stop further execution
    } else {
        echo json_encode([]);
        exit;
    }
}
