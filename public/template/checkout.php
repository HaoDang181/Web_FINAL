<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div role="search">
        <input id="searchField" class="form-control me-2" type="search" placeholder="Nhập tên hoặc barcode" aria-label="Search">
        <button class="btn btn-outline-success" id="search" type="button">Thêm</button>
    </div>
    <table>
        <thead>
            <th>Barcode</th>
            <th>Tên sản phẩm</th>
            <th>Giá bán</th>
            <th>Loại</th>
        </thead>
        <tbody id="checkoutList"></tbody>
    </table>
    <script src="../static/js/Checkout.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>