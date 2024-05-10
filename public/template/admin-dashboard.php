<?php
    session_start();
    if (!isset($_SESSION["authenticated"])){
        header("Location: /final/src/login.php");
    }
?>
<!doctype html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../static/css/home.css">
    <title>View Staff list</title>
</head>

<body>
    <div class="container">
        <div class="navigation">
            <nav id="sidebar">
                <div class="sidebar-header">
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="person-circle-outline"></ion-icon>
                        </span>
                        <span>You</span>
                    </a>
                </div>
                <ul class="list">
                    <li>
                        <a href="index.html">
                            <span class="icon">
                                <ion-icon name="home-outline"></ion-icon>
                            </span>
                            <span class="title">Home</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <span class="icon">
                                <ion-icon name="people-outline"></ion-icon> </span>
                            <span class="title">Employee</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <span class="icon">
                                <ion-icon name="log-out-outline"></ion-icon> </span>
                            <span class="title">Sign out</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>


        <div class="content">
            <div class="main">
                <div class="topbar">
                    <div class="toggle">
                        <ion-icon name="menu-outline"></ion-icon>
                    </div>
                    <div class="search">
                        <label>
                            <input type="text" placeholder="Search here">
                        </label>
                    </div>
                    <div class="user">
                        <img src="https://i.pinimg.com/564x/ef/5a/77/ef5a778b6fa98a57169609e8244a8bd8.jpg" alt="">
                    </div>
                </div>
            </div>

            <div class="content-EmployeeManage">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-wrapper">
                            <div class="col-sm-6 p-0 flex justify-content-lg-start justify-content-center">
                                <h2 class="ml-lg-2">Manage Employees</h2>
                            </div>
                        </div>

                    </div>
                </div>

                <section class="p-3">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-hover mt-3 text-center table-bordered">

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

                </section>
            </div>
            <div class="col-sm-6 p-0 d-flex justify-content-end">
                <button type="button" class="btn btn-primary newUser" data-bs-toggle="modal"
                    data-bs-target="#addEmployeeModal">
                    <ion-icon name="person-add-outline"></ion-icon>
                    Add Employee
                </button>
            </div>
        </div>


        <!-- add employee -->
        <div class="modal fade" id="addEmployeeModal">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Create account</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="http://localhost/final/src/account/create-new-user-account.php" method="post"
                            id="myForm">

                            <div class="card imgholder">
                                <label for="imgInput" class="upload">
                                    <input type="file" name="" id="imgInput">
                                    <i class="bi bi-plus-circle-dotted"></i>
                                </label>
                                <img src="https://i.pinimg.com/564x/ef/5a/77/ef5a778b6fa98a57169609e8244a8bd8.jpg"
                                    alt="" width="200" height="200" class="img">
                            </div>

                            <div class="inputField">
                                <div>
                                    <label for="name">Name:</label>
                                    <input type="text" name="fullname" id="name" required>
                                </div>
                                <div>
                                    <label for="email">E-mail:</label>
                                    <input type="email" name="email" id="email" required>
                                </div>

                            </div>

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" form="myForm" class="btn btn-primary submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- view information details -->
        <div class="modal fade" id="viewStaffInfo">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Profile</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="card imgholder">
                            <img id="showImage" src="" alt=""
                                width="200" height="200" class="showImg">
                        </div>

                        <div class="inputField">
                            <div>
                                <label for="name">Họ tên:</label>
                                <input type="text" id="showName" readonly>
                            </div>
                            <div>
                                <label for="email">E-mail:</label>
                                <input type="email" id="showEmail" readonly>
                            </div>
                            <div>
                                <label for="status">Trạng thái:</label>
                                <input type="text" id="showStatus" readonly>
                            </div>
                            <div>
                                <label for="showRole">Vai trò:</label>
                                <input type="text" id="showRole" readonly>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../static/js/addEmployee.js"></script>
</body>

</html>