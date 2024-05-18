<?php
session_start();
if (!isset($_SESSION["authenticated"])) {
    header("Location: /final/src/login.php");
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Product Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/a9175947db.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/final/public/static/css/style.css">
    <style>
        .form-label {
            color: black;
        }

        .modal {
            width: fit-content;
            min-width: 300px;
            max-width: 500px;
            left: 40%;
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
            <h2>Quản lý sản phẩm</h2>
            <input id="user-role" class="user-role" type="hidden" value=<?php
            if (isset($_SESSION['role'])) {
                echo $_SESSION['role'];
            } ?>>
            <table class="table table-striped text-center">
                <thead id="thead">
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
            <?php
            if (isset($_SESSION["role"])) {
                $role = $_SESSION["role"];
                if ($role == "admin") {
                    echo '<div style="text-align: right;">
                        <button type="button" class="btn btn-primary add-product-btn" data-bs-toggle="modal"
                            data-bs-target="#addProduct">
                            <i class="fa-solid fa-plus me-2"></i>Thêm sản phẩm mới
                        </button>
                    </div>';
                }
            }
            ?>
        </div>
    </div>
    <!-- Add product -->
    <div class="modal fade" id="addProduct">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Thêm sản phẩm mới</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/final/src/product/add-new-product.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">

                        <div class="inputField">
                            <div class="form-group">
                                <label for="name" class="form-label">Tên sản phẩm:</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="VD: Iphone 13" required>
                            </div>
                            <div class="form-group">
                                <label for="import_price" class="form-label">Giá gốc:</label>
                                <input type="text" class="form-control" name="import_price" id="import_price"
                                    placeholder="VD: 99.99" required>
                            </div>
                            <div class="form-group">
                                <label for="retail_price" class="form-label">Giá bán:</label>
                                <input type="text" class="form-control" name="retail_price" id="retail_price"
                                    placeholder="VD: 139.99" required>
                            </div>
                            <div class="form-group">
                                <label for="category" class="form-label">Loại:</label>
                                <select class="form-select" name="category" id="category" required>
                                    <option value="">Chọn loại sản phẩm</option>
                                    <option value="SmartPhone">SmartPhone</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Laptop">Laptop</option>
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" id="imageInput" class="form-control"
                                    placeholder="Chọn ảnh từ máy tính" name="product_image">
                                <label class="input-group-text" for="imageInput">Tải ảnh lên</label>
                            </div>
                            <img class="showImg" id="showImage" src="/final/src/uploadImage/default-avatar.avif"
                                alt="default avatar">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Update product -->
    <div class="modal fade" id="updateProduct">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Cập nhật sản phẩm</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/final/src/product/edit-product-info.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="inputField">
                            <div>
                                <input type="hidden" name="id" id="productId">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="barcode">Barcode:</label>
                                <input class="form-control" type="text" name="barcode" id="barcode" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="name">Tên sản phẩm:</label>
                                <input class="form-control" type="text" name="name" id="name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="import_price">Giá gốc:</label>
                                <input class="form-control" type="text" name="import_price" id="import_price" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="retail_price">Giá bán:</label>
                                <input class="form-control" type="text" name="retail_price" id="retail_price" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="category">Loại:</label>
                                <input class="form-control" type="text" name="category" id="category" required>
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" id="imageInput2" class="form-control"
                                    placeholder="Chọn ảnh từ máy tính" name="product_image">
                                <label class="input-group-text" for="imageInput2">Tải ảnh lên</label>
                            </div>
                            <img class="showImg" id="showImage2" src="/final/src/uploadImage/default-avatar.avif"
                                alt="default avatar">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteConfirmModalLabel">Xoá sản phẩm</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteProductForm" action="/final/src/product/delete-product.php" method="post">
                    <div class="modal-body">
                        <div>
                            <input type="hidden" name="id" id="productId">
                        </div>
                        Có chắc chắn muốn xoá sản phẩm này?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-primary">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../static/js/productManagement.js"></script>
    <script src="/final/public/static/js/sidebar/jquery.min.js"></script>
    <script src="/final/public/static/js/sidebar/popper.js"></script>
    <script src="/final/public/static/js/sidebar/bootstrap.min.js"></script>
    <script src="/final/public/static/js/sidebar/main.js"></script>
</body>

</html>