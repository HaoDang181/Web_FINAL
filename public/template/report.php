<?php
session_start();
if (!isset($_SESSION["authenticated"])) {
    header("Location: /final/src/login.php");
    exit(); // Always use exit() after header redirection
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Report and Analyse</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/a9175947db.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/final/public/static/css/style.css">
    <style>
        .form-label {
            color: black;
        }

        .modal {
            width: fit-content;
            position: absolute;
            float: left;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .showImg {
            width: 100%;
        }

        td {
            vertical-align: middle;
        }

        #custom-date-inputs {
            display: none;
            align-items: center;
            padding: 8px;
            background-color: #34495e;
            max-width: 200px;
            border-radius: 5px;
        }

        #custom-date-inputs .form-label,
        #custom-date-inputs .form-control {
            margin-right: 10px;
        }

        .form-label {
            color: white;
        }

        .dropdown-menu li {
            cursor: pointer;
        }

        .card {
            height: 130px;
        }

        .card .card-title {
            height: 60%;
        }
    </style>
</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar" style="background-color: #34495e;">
            <div class="p-4 pt-5">
                <!-- <a href="#" class="img logo rounded-circle mb-5" style="background-image: url(images/logo.jpg);"></a> -->
                <ul class="list-unstyled components mb-5">
                    <?php
                    if (isset($_SESSION["role"])) {
                        $role = $_SESSION["role"];
                        if ($role == "admin") {
                            echo '<li class="active">
                                        <a class="nav-link" href="/final/public/template/admin-dashboard.php">
                                            Trang chủ
                                        </a>
                                    </li>';
                        } else {
                            echo '<li class="active">
                                <a class="nav-link" href="/final/public/template/sale-dashboard.php">
                                    Trang chủ
                                </a>
                                </li>';
                        }
                    }
                    ?>
                    <li>
                        <a class="nav-link view" href="/final/public/template/profile.php">Thông tin tài
                            khoản</a>
                    </li>
                    <li>
                        <a class="nav-link" href="/final/public/template/product-list.php">
                            Danh sách sản phẩm
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="/final/public/template/checkout.php">
                            Thanh toán đơn hàng
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="/final/public/template/report.php">
                            Thống kê
                        </a>
                    </li>
                    <li>
                        <a href="/final/src/login.php">Đăng xuất</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <button type="button" id="sidebarCollapse" class="btn btn-primary">
                <i class="fa fa-bars"></i>
                <span class="sr-only">Toggle Menu</span>
            </button>
            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>

            <h2 class="mb-3">Báo cáo và tổng kết</h2>
            <div class="selectTime mb-3">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa-regular fa-calendar-days me-2"></i> Chọn mốc thời gian
                    </button>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item" value="today">Hôm nay</li>
                        <li class="dropdown-item" value="yesterday">Hôm qua</li>
                        <li class="dropdown-item" value="last_7_days">7 ngày vừa rồi</li>
                        <li class="dropdown-item" value="this_month">Tháng này</li>
                        <li class="dropdown-item" value="custom">Tự chọn</li>
                    </ul>
                </div>
            </div>

            <div id="custom-date-inputs" class="mb-3" style="display: none;">
                <label class="form-label ms-2" for="start_date">Từ ngày:</label>
                <input class="form-control" type="date" id="start_date" name="start_date">
                <label class="form-label" for="end_date">Đến ngày:</label>
                <input class="form-control" type="date" id="end_date" name="end_date">
                <button class="btn btn-primary" id="fetch-report">Xác nhận</button>
            </div>

            <?php
            // Assuming $userRole contains the user's role
            if (isset($_SESSION["role"])) {
                $role = $_SESSION["role"];
                // Define classes for card columns based on user role
                $noPurchaseColClass = 'col-12 col-sm-6 col-md-3 col-lg-3 col-xl-3';
                $totalEarnColClass = 'col-12 col-sm-6 col-md-3 col-lg-3 col-xl-3';
                $noProductColClass = 'col-12 col-sm-6 col-md-3 col-lg-3 col-xl-3';
                $totalProfitColClass = 'col-12 col-sm-6 col-md-3 col-lg-3 col-xl-3';
                // If user is not admin, hide totalProfit card and adjust classes for other cards
                if ($role !== 'admin') {
                    $totalProfitColClass = 'd-none'; // Hide totalProfit card
                    // Adjust classes for other cards to occupy full width
                    $noPurchaseColClass = 'col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4';
                    $totalEarnColClass = 'col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4';
                    $noProductColClass = 'col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4';
                }
            }
            ?>
            <div class="row">
                <div class="<?php echo $noPurchaseColClass; ?>">
                    <div id="noPurchase" class="card text-bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-file-invoice me-2"></i>Tổng số lượng đơn hàng:</h5>
                            <p class="card-text"></p>
                        </div>
                    </div>
                </div>
                <div class="<?php echo $totalEarnColClass; ?>">
                    <div id="totalEarn" class="card text-bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-coins me-2"></i>Tổng số tiền nhận:</h5>
                            <p class="card-text"></p>
                        </div>
                    </div>
                </div>
                <div class="<?php echo $noProductColClass; ?>">
                    <div id="noProduct" class="card text-bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-champagne-glasses me-2"></i>Tổng số lượng hàng bán</h5>
                            <p class="card-text"></p>
                        </div>
                    </div>
                </div>
                <div class="<?php echo $totalProfitColClass; ?>">
                    <div id="totalProfit" class="card text-bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fa-solid fa-hand-holding-dollar me-2"></i>Tổng lợi nhuận</h5>
                            <p class="card-text"></p>
                        </div>
                    </div>
                </div>
            </div>
            <h3 class="mb-3">Danh sách chi tiết</h3>
            <div class="row">
                <table class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Khách hàng</th>
                            <th scope="col">Tổng hoá đơn</th>
                            <th scope="col">Tiền nhận</th>
                            <th scope="col">Tiền thừa</th>
                            <th scope="col">Ngày lập</th>
                            <th scope="col">Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody id="purchaseItemList"></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- View detail invoice -->
    <div class="modal fade" id="billModal" aria-hidden="true" aria-labelledby="billModalLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="billModalLabel">Chi tiết hoá đơn</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Giá tiền</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Tổng tiền</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="/final/public/static/js/Report.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="/final/public/static/js/sidebar/jquery.min.js"></script>
    <script src="/final/public/static/js/sidebar/popper.js"></script>
    <script src="/final/public/static/js/sidebar/bootstrap.min.js"></script>
    <script src="/final/public/static/js/sidebar/main.js"></script>
</body>

</html>