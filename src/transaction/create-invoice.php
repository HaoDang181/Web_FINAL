<?php
require_once '../db-connect.php';
require '../common/rest-api.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['customerPhone'];
    $customerName = $_POST['customerName'];
    $customerAddress = $_POST['customerAddress'];
    $totalGivenAmount = $_POST['totalGiven'];
    $totalChangeAmount = $_POST['totalChange'];
    $totalBill = $_POST['totalBill'];
    $createDate = date('Y-m-d H:i:s');
    $insertPurchaseCondition = [
        "total_bill" => $totalBill,
        "customer_phone" => $phone,
        "customer_name" => $customerName,
        "customer_address" => $customerAddress,
        "total_given_amount" => $totalGivenAmount,
        "total_change_amount" => $totalChangeAmount,
        "create_date" => $createDate
    ];
    if (addDataToTable($pdo, 'purchase', $insertPurchaseCondition)) {
        header("Location: /final/public/template/sale-dashboard.php");
    } else {
        echo "error";
    }
} else {
    echo "Invalid request method.";
}

