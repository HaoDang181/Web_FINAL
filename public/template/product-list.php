<?php
    session_start();
    if (!isset($_SESSION["authenticated"])){
        header("Location: /final/src/login.php");
    }
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <input id="user-role" class="user-role" type="hidden" value=<?php session_start();
                                                                if (isset($_SESSION['role'])) {
                                                                    echo $_SESSION['role'];
                                                                } ?>>
    <table>
        <thead id="thead">
        </thead>
        <tbody id="tbody">
        </tbody>
    </table>
    <button type="button" class="btn btn-primary add-product-btn" data-bs-toggle="modal" data-bs-target="#addProduct">
        Thêm sản phẩm mới
    </button>
    <!-- Add product -->
    <div class="modal fade" id="addProduct">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Thêm sản phẩm mới</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="http://localhost/final/src/product/add-new-product.php" method="post">
                    <div class="modal-body">

                        <div class="inputField">
                            <div>
                                <label for="name">Tên sản phẩm:</label>
                                <input type="text" name="name" id="name" required>
                            </div>
                            <div>
                                <label for="import_price">Giá gốc:</label>
                                <input type="text" name="import_price" id="import_price" required>
                            </div>
                            <div>
                                <label for="retail_price">Giá bán:</label>
                                <input type="text" name="retail_price" id="retail_price" required>
                            </div>
                            <div>
                                <label for="category">Loại:</label>
                                <input type="text" name="category" id="category" required>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
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
                <form action="http://localhost/final/src/product/edit-product-info.php" method="post">
                    <div class="modal-body">
                        <div class="inputField">
                            <div>
                                <input type="hidden" name="id" id="productId">
                            </div>
                            <div>
                                <label for="barcode">Barcode:</label>
                                <input type="text" name="barcode" id="barcode" required>
                            </div>
                            <div>
                                <label for="name">Tên sản phẩm:</label>
                                <input type="text" name="name" id="name" required>
                            </div>
                            <div>
                                <label for="import_price">Giá gốc:</label>
                                <input type="text" name="import_price" id="import_price" required>
                            </div>
                            <div>
                                <label for="retail_price">Giá bán:</label>
                                <input type="text" name="retail_price" id="retail_price" required>
                            </div>
                            <div>
                                <label for="category">Loại:</label>
                                <input type="text" name="category" id="category" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteConfirmModalLabel">Xoá sản phẩm</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="http://localhost/final/src/product/delete-product.php" method="post">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>