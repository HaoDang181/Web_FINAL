<?php

require_once '../db-connect.php';
require '../common/rest-api.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents("php://input");
    $result = json_decode($data, true);
    $phone = $result['phone'];
    $existCustomerCondition = array("phone" => $phone);
    
    if (checkRecordExists($pdo, 'customer', $existCustomerCondition)) {
        $customerCondition = ["phone" => $phone];
        $customer = getDataFromTableByCriteria($pdo, 'customer', $customerCondition);
        $customerName = $customer->name;
        $totalGivenAmount = $result['total_given_amount'];
        $totalChangeAmount = $result['total_change_amount'];
        $createDate = date('Y-m-d H:i:s');
        $totalBill = 0;

        $insertPurchaseCondition = [
            "customer_phone" => $phone,
            "customer_name" => $customerName,
            "total_given_amount" => $totalGivenAmount,
            "total_change_amount" => $totalChangeAmount,
            "create_date" => $createDate
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
                    $unitPrice = $tempProduct->retail_price;

                    $totalBill += $quantity * $unitPrice;

                    $insertOrdersCondition = [
                        "purchase_id" => $purchaseId,
                        "product_barcode" => $barcode,
                        "product_name" => $tempProduct->name,
                        "quantity" => $quantity,
                        "unit_price" => $unitPrice,
                        "total_amount" => $quantity * $unitPrice
                    ];

                    addDataToTable($pdo, 'orders', $insertOrdersCondition);
                }
            }

            $updateValue = ["total_bill" => $totalBill];
            $updatePurchaseCondition = ["id" => $purchaseId];
            updateDataInTable($pdo, 'purchase', $updateValue, $updatePurchaseCondition);
        } else {
            echo "Failed to insert purchase record.";
        }
    } else {
        echo "Customer not found.";
    }
} else {
    echo "Invalid request method.";
}
