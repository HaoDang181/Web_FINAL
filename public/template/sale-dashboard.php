<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Bootstrap demo</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
	<h1>Hello, world!</h1>
	<button class="view" data-bs-toggle="modal" data-bs-target="#viewStaffInfo">
		Xem thông tin
	</button>
	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePassword">
		Đổi mật khẩu
	</button>
	<input class="userId" type="hidden" value=<?php session_start();
												if (isset($_SESSION['user_id'])) {
													echo $_SESSION['user_id'];
												} ?>>

	<?php

	// Check if a specific session variable exists and retrieve its value
	if (isset($_SESSION['is_active'])) {
		$isActive = $_SESSION['is_active'];

		// Check if the user account is inactive
		if (!$isActive) {
			// If the user account is inactive, add the "show" class to force the modal to display
			echo '<div class="modal fade show" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-modal="true" style="display: block;">';
		} else {
			// If the user account is active, simply display the modal without the "show" class
			echo '<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"> style="display: none;"';
		}
	} else {
		echo '<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"> style="display: none;"';
	}
	?>
	<!-- Modal force change password-->
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="staticBackdropLabel">Cần đổi mật khẩu!</h1>
			</div>
			<form action="/final/src/user/change-password.php" method="post">
				<div class="modal-body">

					<input type="password" name="password" placeholder="Nhập mật khẩu mới">
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Xác nhận</button>
				</div>
			</form>
		</div>
	</div>
	</div>

	<!-- Modal change password -->
	<div class="modal fade" id="changePassword">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">

				<div class="modal-header">
					<h4 class="modal-title">Đổi mật khẩu</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<form action="/final/src/user/change-password.php" method="post">
					<div class="modal-body">
						<input type="password" name="password" placeholder="Nhập mật khẩu mới">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Xác nhận</button>
					</div>
				</form>
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
				<form action="/final/src/user/change-avatar.php" method="post" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="card imgholder">
							<img id="showImage" src="" alt="" width="200" height="200" class="showImg">
							<input type="file" id="imageInput" name="avatar" style="display: none;">
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
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bỏ qua</button>
						<button type="submit" class="btn btn-primary">Lưu thay đổi</button>
					</div>
				</form>

			</div>
		</div>
	</div>

	<script src="../static/js/saleDashboard.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>