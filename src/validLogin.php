<?php
session_start(); // Start the session

require_once './db-connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if both username and password are provided
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        // Retrieve username and password from the form
        $username = $_POST["username"];
        $password = $_POST["password"];
        $token = $_POST["token"];

        // Prepare a SQL statement to fetch the user's credentials from the database
        $sql = "SELECT * FROM user_account WHERE username = :username AND password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // If a row is returned, it means the user exists with the provided credentials
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $role = $user['role'];

            if ($user['is_active'] == false && $token == "") {
                header("Location: error.php");
                exit;
            } else {
                // Store user information in session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['is_active'] = $user['is_active'];
                $_SESSION['role'] = $role;
                $_SESSION['authenticated'] = true;

                // Redirect to appropriate page based on user's role
                switch ($role) {
                    case 'admin':
                        header("Location: http://localhost/final/public/template/admin-dashboard.php");
                        exit;
                    case 'sales':
                        header("Location: http://localhost/final/public/template/sale-dashboard.php");
                        exit;
                    case 'customer':
                        header("Location: customer_dashboard.php");
                        exit;
                    default:
                        // If the role is not recognized, redirect to a generic page
                        header("Location: index.php");
                        exit;
                }
            }
        } else {
            // If no rows are returned, it means the provided credentials are invalid
            header("Location: index.php?error=invalid_credentials");
            exit;
        }
    } else {
        // If username or password is missing, redirect back to the login page with an error message
        header("Location: index.php?error=missing_fields");
        exit;
    }
} else {
    // If the form is not submitted, redirect back to the login page
    header("Location: index.php");
    exit;
}
?>
