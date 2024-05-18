<?php
session_start();

require_once 'db-connect.php';

if (isset($_SESSION["authenticated"])) {
    unset($_SESSION['authenticated']);
}

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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a9175947db.js" crossorigin="anonymous"></script>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f0f4f8;
            /* Light grey background */
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background: #ffffff;
            /* White background */
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            text-align: center;
        }

        .login-container h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #34495e;
            /* Dark grey text */
        }

        .login-container h6 {
            font-size: 1rem;
            margin-bottom: 1rem;
            margin-top: 1rem;
            color: #34495e;
            /* Dark grey text */
        }

        .form-control {
            margin-bottom: 1rem;
            border: 2px solid #ced4da;
            /* Light grey border */
            border-radius: 5px;
            padding: 10px;
            font-size: 1rem;
            color: #34495e;
            /* Dark grey text */
        }

        .btn-primary {
            width: 100%;
            background-color: #34495e;
            /* Dark grey button */
            border: none;
            padding: 10px;
            font-size: 1rem;
            color: #ffffff;
            /* White text */
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #2c3e50;
            /* Slightly darker grey on hover */
        }

        .text-decoration-none {
            color: #34495e;
            /* Dark grey text */
        }

        .text-decoration-none:hover {
            color: #2c3e50;
            /* Slightly darker grey on hover */
        }

        .logo {
            width: 100px;
            /* Adjust the size as needed */
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <i class="fa-brands fa-xing fa-2xl" style="color: #34495e;"></i>
        <h6>Point of Sale</h6>
        <h1>Trang đăng nhập</h1>
        <form action="validLogin.php" method="post">
            <input type="hidden" name="token" value=<?php echo isset($token) ? $token : "" ?>>
            <div class="mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Tài khoản" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
            <div class="mt-3">
                <a href="#" class="text-decoration-none">Quên mật khẩu?</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>