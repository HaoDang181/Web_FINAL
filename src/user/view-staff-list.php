<?php
require_once '../db-connect.php';

// Prepare the SQL statement to select all data from the table
$sql = "SELECT * FROM user_account";

// Prepare and execute the SQL statement with PDO
$stmt = $pdo->query($sql);

// Check if there are any rows returned
if ($stmt->rowCount() > 0) {
    // Fetch all rows as associative arrays
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode($rows);
} else {
    // If no rows are returned, return an empty JSON array
    echo json_encode([]);
}
