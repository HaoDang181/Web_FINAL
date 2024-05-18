<?php
session_start();
if (!isset($_SESSION["authenticated"])) {
    header("Location: /final/src/login.php");
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>
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

            <h2 class="mb-4">Trang quản lý nhân viên</h2>

            <div class="row">
                <div class="col">
                    <table class="table table-striped text-center">

                        <thead>
                            <tr>
                                <th>Mã số nhân viên</th>
                                <th>Avatar</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="data">
                        </tbody>

                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: right">
                    <button type="button" class="btn btn-primary newUser" data-bs-toggle="modal"
                        data-bs-target="#addEmployeeModal" style="background-color: #34495e; border: none;">
                        Thêm nhân viên
                    </button>
                </div>
            </div>
        </div>


        <!-- add employee -->
        <div class="modal fade" id="addEmployeeModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Tạo tài khoản nhân viên mới</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="http://localhost/final/src/account/create-new-user-account.php" method="post"
                            id="myForm">
                            <div class="inputField">
                                <div class="form-group">
                                    <label for="fullname" class="form-label"><i class="fa-solid fa-signature me-2"></i>Họ và tên:</label>
                                    <input type="text" class="form-control" name="fullname" id="fullname"
                                        placeholder="VD: Đặng Anh Hào" autofocus required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label"><i class="fa-solid fa-envelope me-2"></i>Email:</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="VD: haodang1810@gmail.com" required>
                                </div>

                            </div>

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Huỷ</button>
                        <button type="submit" form="myForm" class="btn btn-primary submit">Tạo</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- view information details -->
        <div class="modal fade" id="viewStaffInfo">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 class="modal-title">Chi tiết nhân viên</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="card shadow">
                            <div class="row">
                                <div class="d-none d-sm-block col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <img class="showImg" id="showImage" src="/final/src/uploadImage/default-avatar.avif"
                                        class="card-img-top" alt="default avatar">
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <div class="p-2">
                                        <div class="form-group">
                                            <label for="name" class="form-label"><i class="fa-solid fa-signature me-2"></i>Họ tên:</label>
                                            <input type="text" id="showName" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="form-label"><i class="fa-solid fa-envelope me-2"></i>E-mail:</label>
                                            <input type="email" id="showEmail" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class="form-label"><i class="fa-solid fa-signal me-2"></i>Trạng thái:</label>
                                            <input type="text" id="showStatus" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="showRole" class="form-label"><i class="fa-solid fa-hat-cowboy me-2"></i>Vai trò:</label>
                                            <input type="text" id="showRole" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../static/js/addEmployee.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="/final/public/static/js/sidebar/jquery.min.js"></script>
    <script src="/final/public/static/js/sidebar/popper.js"></script>
    <script src="/final/public/static/js/sidebar/bootstrap.min.js"></script>
    <script src="/final/public/static/js/sidebar/main.js"></script>
</body>

</html>