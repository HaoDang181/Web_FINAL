<?php
require_once 'db-connect.php';

// Check if token is provided in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Prepare the SQL statement to fetch token and its expiration time
    $sql = "SELECT * FROM user_account WHERE token = :token";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token);

    try {
        // Execute the prepared statement
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // If token is found
        if ($result) {
            // Check if the token is not expired
            if (strtotime($result['expiration']) < time()) {
                // Token is expired, redirect to error.php
                header("Location: error.php");
                exit; // Ensure that no further code is executed after redirection
            } else {
                // Token is valid and not expired. Proceed with login or other actions.
                echo "Token is valid and not expired. Proceed with login or other actions.";
            }
        } else {
            // Token not found in the database, redirect to error.php
            header("Location: error.php");
            exit; // Ensure that no further code is executed after redirection
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "Database Error: " . $e->getMessage();
        exit;
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>

<body>
    <form action="validLogin.php" method="post">
        <input type="hidden" name="token" value=<?php echo isset($token) ? $token : "" ?>>
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>
</body>

</html>