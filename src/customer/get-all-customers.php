<?php
require_once '../db-connect.php';

require '../common/rest-api.php';

$rows = getDataFromTableByCriteria($pdo, 'customer', []);

// Return the data as JSON
echo json_encode($rows);