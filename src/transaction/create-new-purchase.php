<?php
session_start();

require_once '../db-connect.php';
require '../common/rest-api.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents("php://input");
    $result = json_decode($data, true);
    $phone = $result['phone'];
    $existCustomerCondition = array("phone" => $phone);

    $customerCondition = ["phone" => $phone];
    $customerName = $result['name'];
    $customerAddress = $result['address'];
    $totalGivenAmount = $result['total_given_amount'];
    $totalChangeAmount = $result['total_change_amount'];
    $createDate = date('Y-m-d H:i:s');
    $totalBill = 0;
    $totalProducts = 0;
    $createBy = "";
    if (isset($_SESSION['user_id'])) {
        $createBy = $_SESSION['user_id'];
    }

    $insertPurchaseCondition = [
        "customer_phone" => $phone,
        "customer_name" => $customerName,
        "customer_address" => $customerAddress,
        "total_given_amount" => $totalGivenAmount,
        "total_change_amount" => $totalChangeAmount,
        "create_date" => $createDate,
        "create_by"=> $createBy,
    ];

    $purchaseId = addDataToTable($pdo, 'purchase', $insertPurchaseCondition);

    if ($purchaseId) {
        foreach ($result['order'] as $product) {
            $barcode = $product['product_barcode'];
            $existProductCondition = array("barcode" => $barcode);

            if (checkRecordExists($pdo, 'product', $existProductCondition)) {
                $getProductCondition = ["barcode" => $barcode];
                $tempProduct = getDataFromTableByCriteria($pdo, 'product', $getProductCondition);
                $quantity = $product['quantity'];
                $unitPrice = $tempProduct[0]['retail_price'];
                $originalUnitPrice = $tempProduct[0]['import_price'];

                $totalBill += $quantity * $unitPrice;
                $totalProducts += $quantity;

                $insertOrdersCondition = [
                    "purchase_id" => $purchaseId,
                    "product_barcode" => $barcode,
                    "product_name" => $tempProduct[0]['name'],
                    "quantity" => $quantity,
                    "unit_price" => $unitPrice,
                    "total_amount" => $quantity * $unitPrice,
                    "total_profit"=> ($quantity * $unitPrice) - ($quantity * $originalUnitPrice)
                ];

                addDataToTable($pdo, 'orders', $insertOrdersCondition);
            }
        }

        $updateValue = ["total_bill" => $totalBill, "total_products" => $totalProducts];
        $updatePurchaseCondition = ["id" => $purchaseId];
        updateDataInTable($pdo, 'purchase', $updateValue, $updatePurchaseCondition);
        echo json_encode(array('code'=> 200,'msg'=> 'Create purchase successfully!'));
    } else {
        echo "Failed to insert purchase record.";
    }

} else {
    echo "Invalid request method.";
}
