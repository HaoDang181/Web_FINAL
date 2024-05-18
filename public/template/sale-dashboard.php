<?php
session_start();
if (!isset($_SESSION["authenticated"])) {
	header("Location: /final/src/login.php");
}
?>
<!doctype html>
<html lang="en">

<head>
	<title>Sales Dashboard</title>
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

		.paycheck {
			background-color: #34495e;
			color: white;
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
			<h2>Danh sách khách hàng</h2>
			<input class="userId" type="hidden" value=<?php
			if (isset($_SESSION['user_id'])) {
				echo $_SESSION['user_id'];
			} ?>>
			<input class="isActive" type="hidden" value=<?php
			if (isset($_SESSION['is_active'])) {
				echo $_SESSION['is_active'];
			} ?>>
			<div class="row">
				<div class="col">
					<table class="table table-striped text-center">
						<thead>
							<tr>
								<th>Stt</th>
								<th>Họ tên</th>
								<th>Số điện thoại</th>
								<th>Địa chỉ</th>
								<th>Thao tác</th>
							</tr>
						</thead>
						<tbody id="customer-data"></tbody>
					</table>
				</div>
			</div>

			<!-- Modal force change password-->
			<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
				aria-labelledby="staticBackdropLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h1 class="modal-title fs-5" id="staticBackdropLabel">Cần đổi mật khẩu!</h1>
						</div>
						<form action="/final/src/user/change-password.php" method="post">
							<div class="modal-body">
								<div class="form-group">
									<label class="form-label" for="newPass"><i class="fa-solid fa-key me-2"></i>Nhập mật
										khẩu
										mới</label>
									<input class="form-control" type="password" name="password" id="newPass"
										placeholder="Nhập mật khẩu mới">
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">Xác nhận</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Offcanvas customer's purchase history -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
				aria-labelledby="offcanvasRightLabel">
				<div class="offcanvas-header">
					<h5 class="offcanvas-title" id="offcanvasRightLabel">Lịch sử mua hàng</h5>
					<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body">
					<table class="table text-center">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Tổng hoá đơn($)</th>
								<th scope="col">Tiền nhận($)</th>
								<th scope="col">Tiền thừa($)</th>
								<th scope="col">Ngày lập</th>
								<th scope="col">Chi tiết</th>
							</tr>
						</thead>
						<tbody></tbody>
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
									<th scope="col">Giá tiền($)</th>
									<th scope="col">Số lượng</th>
									<th scope="col">Tổng tiền($)</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
			crossorigin="anonymous"></script>
		<script src="../static/js/SaleDashboard.js"></script>
		<script src="/final/public/static/js/sidebar/jquery.min.js"></script>
		<script src="/final/public/static/js/sidebar/popper.js"></script>
		<script src="/final/public/static/js/sidebar/bootstrap.min.js"></script>
		<script src="/final/public/static/js/sidebar/main.js"></script>
		<script>
			var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
				keyboard: false
			})
			const isActive = document.querySelector('.isActive').value;
			if (isActive === '0') {
				myModal.show();
			}
		</script>
</body>

</html>