<?php
// Include the database connection file
require_once '../db-connect.php';
require '../common/rest-api.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Function to fetch sales results based on a date range
function getSalesResults($pdo, $startDate, $endDate) {
    $sql = "SELECT * FROM purchase WHERE create_date BETWEEN :startDate AND :endDate";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['startDate' => $startDate, 'endDate' => $endDate]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo json_encode($results);
    } else {
        echo json_encode(array());
    }
}

// Get the current date
$currentDate = date('Y-m-d');
$currentDateTime = date('Y-m-d H:i:s');

// Determine the timeframes
$yesterday = date('Y-m-d', strtotime('-1 day'));
$last7Days = date('Y-m-d', strtotime('-7 days'));
$startOfMonth = date('Y-m-01');

// Handle different timeframes
if (isset($_GET['timeframe'])) {
    $timeframe = $_GET['timeframe'];
    
    switch ($timeframe) {
        case 'today':
            getSalesResults($pdo, $currentDate, $currentDateTime);
            break;
        
        case 'yesterday':
            getSalesResults($pdo, $yesterday, $currentDate);
            break;

        case 'last_7_days':
            getSalesResults($pdo, $last7Days, $currentDateTime);
            break;

        case 'this_month':
            getSalesResults($pdo, $startOfMonth, $currentDateTime);
            break;

        case 'custom':
            if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
                $startDate = $_GET['start_date'];
                $endDate = $_GET['end_date'];
                getSalesResults($pdo, $startDate, $endDate);
            } else {
                echo "Please provide a start_date and end_date for the custom timeframe.";
            }
            break;

        default:
            echo "Invalid timeframe specified.";
            break;
    }
} else {
    echo "No timeframe specified.";
}