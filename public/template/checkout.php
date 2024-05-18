<?php
session_start();
if (!isset($_SESSION["authenticated"])) {
    header("Location: /final/src/login.php");
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Paycheck</title>
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
            min-width: 400px;
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

        li {
            cursor: pointer;
        }

        .subtract,
        .add,
        .paycheck {
            background-color: #34495e;
            color: white;
        }

        .subtract:hover {
            background-color: #f8b739;
        }

        .add:hover {
            background-color: #f8b739;
        }

        .paycheck:hover {
            background-color: #f8b739;
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
            <h2>Thanh toán hoá đơn</h2>
            <div class="row mb-3">
                <div class="col-6" style="text-align: left;">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input id="search" type="text" class="form-control"
                            placeholder="Nhập vào barcode hoặc tên sản phẩm(ít nhất 2 kí tự)" style="border-top-right-radius: 5px ;
                                    border-bottom-right-radius: 5px ;" autofocus>
                    </div>
                    <ul id="suggestions" class="list-group my-2"></ul>
                </div>
            </div>
            <table class="table table-striped text-center">
                <thead>
                    <th>Barcode</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Loại</th>
                    <th>Đơn giá($)</th>
                    <th>Số lượng</th>
                    <th>Tổng giá($)</th>
                </thead>
                <tbody id="checkoutList"></tbody>
            </table>
            <button id="paycheck" class="btn paycheck" data-bs-target="#customerInfoModal" data-bs-toggle="modal"><i
                    class="fa-solid fa-cart-shopping me-2"></i>Thanh toán</button>
        </div>
    </div>
    <div class="modal fade" id="customerInfoModal" aria-hidden="true" aria-labelledby="customerInfoModalLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="customerInfoModalLabel">Nhập vào số điện thoại của khách hàng</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-phone"></i></i></span>
                        <input id="customerPhoneInput" type="number" class="form-control"
                            placeholder="Nhập vào số điện thoại khách hàng">
                    </div>

                </div>
                <div class="modal-footer">
                    <button id="getPhoneNumber" class="btn paycheck"><i class="fa-solid fa-circle-check me-2"></i>Kiểm
                        tra</button>
                    <button id="moveToNext" class="btn btn-primary" data-bs-target="#customerInfoModal2"
                        data-bs-toggle="modal" hidden></button>
                    <button id="showInvoice" class="btn paycheck" data-bs-target="#billModal" data-bs-toggle="modal"
                        hidden></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="customerInfoModal2" aria-hidden="true" aria-labelledby="customerInfoModalLabel2"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="customerInfoModalLabel2">Oop! Chưa có dữ liệu khách hàng</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Nhập vào thông tin khách hàng để khởi tạo tài khoản khách hàng mới</h4>
                    <div class="form-group mb-2">
                        <label class="form-label" for="fullname"><i class="fa-solid fa-signature me-2"></i>Họ tên đầy
                            đủ:</label>
                        <input type="text" class="form-control fullname" placeholder="Nhập vào họ tên">
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="address"><i class="fa-solid fa-address-card me-2"></i>Địa
                            chỉ:</label>
                        <input type="text" class="form-control address" placeholder="Nhập địa chỉ">
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn paycheck" type="button" id="createNewCustomeAccount"><i
                            class="fa-solid fa-user-plus me-2"></i>Tạo tài khoản khách hàng
                        mới</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="billModal" aria-hidden="true" aria-labelledby="billModalLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="billModalLabel">Hoá đơn</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label class="form-label" for="fullname"><i class="fa-solid fa-signature me-2"></i>Họ tên khách
                            hàng:</label>
                        <input id="fullname" class="form-control fullname" name="customerName" readonly></input>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="address"><i class="fa-solid fa-address-card me-2"></i>Địa
                            chỉ:</label>
                        <input id="address" class="form-control address" name="customerAddress" readonly></input>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="phone"><i class="fa-solid fa-square-phone me-2"></i>Số điện
                            thoại:</label>
                        <input id="phone" class="form-control phone" name="customerPhone" readonly></input>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="totalBill"><i class="fa-solid fa-money-bills me-2"></i>Tổng giá
                            đơn hàng:</label>
                        <input id="totalBill" class="form-control totalBill" name="totalBill" readonly></input>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="totalGiven"><i
                                class="fa-solid fa-money-bill-transfer me-2"></i>Tiền nhận:</label>
                        <input type="number" class="form-control totalGiven" name="totalGiven" min="1"
                            placeholder="Nhập số tiền nhận từ khách hàng" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="totalChange"><i class="fa-solid fa-wallet me-2"></i>Tiền
                            trả:</label>
                        <input type="number" class="form-control totalChange" name="totalChange"
                            placeholder="Nhập số tiền trả lại khách hàng" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn paycheck" id="createOrder" type="button"><i class="fa-solid fa-file-invoice me-2"></i>Xác
                        nhận</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../static/js/Checkout.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="/final/public/static/js/sidebar/jquery.min.js"></script>
    <script src="/final/public/static/js/sidebar/popper.js"></script>
    <script src="/final/public/static/js/sidebar/bootstrap.min.js"></script>
    <script src="/final/public/static/js/sidebar/main.js"></script>
</body>

</html>