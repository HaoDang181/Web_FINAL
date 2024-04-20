<?php

// Include your database connection file here
require_once '../db-connect.php';

function getDataFromTableByCriteria($pdo, $tableName, $criteria)
{
    // Prepare the SQL statement to select data from the table based on the criteria
    $sql = "SELECT * FROM $tableName WHERE ";

    // Append each criterion to the SQL query
    foreach ($criteria as $columnName => $criteriaValue) {
        $sql .= "$columnName = :$columnName AND ";
    }

    // Remove the trailing 'AND' from the SQL query
    $sql = rtrim($sql, 'AND ');

    // Prepare and execute the SQL statement with PDO
    $stmt = $pdo->prepare($sql);
    foreach ($criteria as $columnName => $criteriaValue) {
        $stmt->bindParam(":$columnName", $criteriaValue);
    }
    $stmt->execute();

    // Get the number of rows returned
    $rowCount = $stmt->rowCount();

    // Fetch all rows as associative arrays
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rowCount === 0) {
        return null;
    }

    // If only one row is returned, return it as an object
    if ($rowCount === 1) {
        return (object) $data[0];
    }

    // Return the data
    return $data;
}

function updateDataInTable($pdo, $tableName, $data, $conditions)
{
    // Prepare the SQL statement to update data in the table
    $updateString = '';
    foreach ($data as $column => $value) {
        $updateString .= "$column = :$column, ";
    }
    $updateString = rtrim($updateString, ', ');

    $conditionString = '';
    foreach ($conditions as $conditionColumn => $conditionValue) {
        $conditionString .= "$conditionColumn = :$conditionColumn AND ";
    }
    $conditionString = rtrim($conditionString, 'AND ');

    $sql = "UPDATE $tableName SET $updateString WHERE $conditionString";

    // Prepare and execute the SQL statement with PDO
    $stmt = $pdo->prepare($sql);
    foreach ($data as $column => $value) {
        $stmt->bindValue(":$column", $value);
    }
    foreach ($conditions as $conditionColumn => $conditionValue) {
        $stmt->bindValue(":$conditionColumn", $conditionValue);
    }
    $stmt->execute();

    // Return true if the data was successfully updated, false otherwise
    return $stmt->rowCount() > 0;
}

function addDataToTable($pdo, $tableName, $data)
{
    // Prepare the SQL statement to insert data into the table
    $columns = implode(', ', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));
    $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";

    // Prepare and execute the SQL statement with PDO
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    // Return the ID of the inserted row
    return $pdo->lastInsertId();
}

function checkRecordExists($pdo, $tableName, $conditions)
{
    // Prepare the SQL statement to check if a record exists in the table
    $conditionString = '';
    foreach ($conditions as $conditionColumn => $conditionValue) {
        $conditionString .= "$conditionColumn = :$conditionColumn AND ";
    }
    $conditionString = rtrim($conditionString, 'AND ');

    $sql = "SELECT COUNT(*) FROM $tableName WHERE $conditionString";

    // Prepare and execute the SQL statement with PDO
    $stmt = $pdo->prepare($sql);
    foreach ($conditions as $conditionColumn => $conditionValue) {
        $stmt->bindValue(":$conditionColumn", $conditionValue);
    }
    $stmt->execute();

    // Fetch the result
    $count = $stmt->fetchColumn();

    // Return true if the record exists, false otherwise
    return $count > 0;
}

function deleteRecordsFromTable($pdo, $tableName, $conditions)
{
    // Prepare the SQL statement to delete records from the table
    $conditionString = '';
    foreach ($conditions as $conditionColumn => $conditionValue) {
        $conditionString .= "$conditionColumn = :$conditionColumn AND ";
    }
    $conditionString = rtrim($conditionString, 'AND ');

    $sql = "DELETE FROM $tableName WHERE $conditionString";

    // Prepare and execute the SQL statement with PDO
    $stmt = $pdo->prepare($sql);
    foreach ($conditions as $conditionColumn => $conditionValue) {
        $stmt->bindValue(":$conditionColumn", $conditionValue);
    }
    $stmt->execute();

    // Return true if the records were successfully deleted, false otherwise
    return $stmt->rowCount() > 0;
}
