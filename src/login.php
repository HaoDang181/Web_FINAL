<?php
require_once 'db-connect.php';
// Check if token is provided in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Prepare the SQL statement to fetch token and its expiration time
    $sql = "SELECT * FROM useraccount WHERE token = :token";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // If token is found
    if ($result) {
        // Check if the token is not expired
        if (strtotime($result['Expiration']) > time()) {
            // Token is valid and not expired, proceed with login or other actions
            // You can redirect the user to the login page or perform any other action here
            echo "Token is valid and not expired. Proceed with login or other actions.";
        } else {
            // Token is expired, redirect to error.php
            header("Location: error.php");
            exit; // Ensure that no further code is executed after redirection
        }
    } else {
        // Token not found in the database, redirect to error.php
        header("Location: error.php");
        exit; // Ensure that no further code is executed after redirection
    }
} else {
    // Token is not provided in the URL, redirect to error.php
    header("Location: error.php");
    exit; // Ensure that no further code is executed after redirection
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <input type="text" placeholder="Username">
    <input type="password" placeholder="Password">
</body>

</html>