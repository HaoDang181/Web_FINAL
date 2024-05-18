<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Page</title>
</head>

<body>
    <?php
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        $messages = [
            '400' => 'Không thể xoá sản phẩm vì có đơn hàng liên quan',
            '407' => 'Tài khoản của bạn đã bị khoá. Vui lòng liên hệ người quản lý',
            '403' => 'Đã quá thời gian đăng nhập. Vui lòng liên hệ admin để cung cấp đường dẫn đăng nhập mới',
            '406' => 'Không thể đăng nhập trực tiếp khi tài khoản chưa kích hoạt'
        ];

        if (isset($messages[$status])) {
            echo "<h1>{$messages[$status]}</h1>";

            if (in_array($status, ['400', '407', '406'])) {
                echo '<script>
                        setTimeout(function(){
                            window.history.go(-1); // Go back to the previous URL
                        }, 1500);
                      </script>';
            }
        }
    }
    ?>
</body>

</html>
