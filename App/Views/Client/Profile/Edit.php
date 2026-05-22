<?php
// App/Views/Client/Profile/edit.php

// 1. Lấy thông tin tài khoản tươi mới nhất từ Database thông qua Repository đang dùng chung
$usernameSession = $_SESSION['user_name'] ?? '';
$user = $this->clientRepository->getUserByUsername($usernameSession); // Sử dụng hàm có sẵn của bạn

// Tách các biến dữ liệu để đổ vào Form (nếu trống sẽ lấy từ Session làm fallback)
$uName    = htmlspecialchars($user['user_name'] ?? $_SESSION['user_name'] ?? '');
$fName    = htmlspecialchars($user['full_name'] ?? $_SESSION['full_name'] ?? '');
$uEmail   = htmlspecialchars($user['email'] ?? $_SESSION['email'] ?? '');
$uGender  = $user['gender'];
$uAvatar  = htmlspecialchars($user['avatar'] ?? $_SESSION['avatar'] ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png');
?>

<style>
    .profile-edit-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    /* Giao diện khu vực ảnh đại diện */
    .avatar-edit-wrapper {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
    }
    .avatar-edit-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .btn-upload-img {
        background-color: #03254c !important;
        color: white !important;
        font-weight: 600;
        padding: 8px 18px;
        font-size: 0.9rem;
        border-radius: 4px;
    }
    .btn-remove-img {
        color: #b00f14 !important;
        font-weight: 700;
        text-decoration: none;
        font-size: 0.9rem;
    }
    
    /* Giao diện các ô Form nhập liệu màu be/xám nhạt */
    .form-custom-label {
        font-weight: 700;
        text-transform: uppercase;
        color: #8c8275;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .form-custom-input {
        background-color: #f1ede7 !important; /* Màu nền be nhạt chuẩn ảnh */
        border: none !important;
        border-radius: 0px !important;
        padding: 12px 15px !important;
        color: #333333 !important;
        font-weight: 500;
    }
    .form-custom-input:disabled, .form-custom-input[readonly] {
        background-color: #e6e1da !important; /* Màu sậm hơn một chút khi khóa */
        color: #666666 !important;
    }
    
    /* Cấu hình nhóm nút bấm góc dưới bên phải */
    .btn-action-edit {
        background-color: #b00f14 !important; /* Màu đỏ đậm */
        color: white !important;
        font-weight: 700;
        padding: 10px 30px;
        border-radius: 4px;
        text-transform: uppercase;
        font-size: 0.9rem;
        border: none;
    }
    .btn-action-save {
        background-color: #03254c !important; /* Màu xanh đen */
        color: white !important;
        font-weight: 700;
        padding: 10px 45px;
        border-radius: 4px;
        text-transform: uppercase;
        font-size: 0.9rem;
        border: none;
    }
</style>

<div class="profile-edit-container p-2">
    <?php if (isset($_SESSION['success_msg'])): ?>
        <div class="alert alert-success py-2 small"><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_msg'])): ?>
        <div class="alert alert-danger py-2 small"><?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></div>
    <?php endif; ?>

    <form action="index.php?page=handle_profile" method="POST" id="profileForm">
        
        <div class="d-flex align-items-center gap-4 mb-4">
            <div class="avatar-edit-wrapper border">
                <img src="<?= $uAvatar; ?>" alt="Avatar">
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-1" style="color: #03254c !important;">Ảnh đại diện</h5>
                <p class="text-muted small mb-2">Khuyên dùng ảnh hình vuông, dung lượng tối đa 2MB.</p>
                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="btn btn-upload-img btn-sm">TẢI ẢNH MỚI</button>
                    <a href="#" class="btn-remove-img">Gỡ bỏ</a>
                </div>
            </div>
        </div>

        <hr class="my-4" style="color: #eaeaea;">

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label form-custom-label">Tên đăng nhập</label>
                <input type="text" class="form-control form-custom-input"  name="username" id="inputUsername" value="<?= $uName; ?>" required disabled readonly>
            </div>

            <div class="col-md-6">
    <label class="form-label form-custom-label">Giới tính</label>
    <div class="d-flex gap-4 pt-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" value="male" id="genderNam" <?= $uGender === 'male' ? 'checked' : ''; ?> disabled>
            <label class="form-check-label fw-semibold text-dark" for="genderNam">Nam</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" value="female" id="genderNu" <?= $uGender === 'female' ? 'checked' : ''; ?> disabled>
            <label class="form-check-label fw-semibold text-dark" for="genderNu">Nữ</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" value="other" id="genderKhac" <?= $uGender === 'other' ? 'checked' : ''; ?> disabled>
            <label class="form-check-label fw-semibold text-dark" for="genderKhac">Khác</label>
        </div>
    </div>
</div>

            <div class="col-md-6">
                <label class="form-label form-custom-label">Họ tên</label>
                <input type="text" class="form-control form-custom-input" name="fullname" id="inputFullname" value="<?= $fName; ?>" required disabled readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label form-custom-label">Email</label>
                <input type="email" class="form-control form-custom-input" name="email" value="<?= $uEmail; ?>" required disabled readonly>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3 mt-5">
            <button type="button" class="btn btn-action-edit" id="btnEnableEdit">Chỉnh sửa</button>
            <button type="submit" class="btn btn-action-save" id="btnSubmitForm" disabled>Lưu</button>
        </div>

    </form>
</div>

<script>
    document.getElementById('btnEnableEdit').addEventListener('click', function() {
        // 1. Mở khóa các ô nhập liệu cần thiết
        document.getElementById('inputFullname').disabled = false;
        document.getElementById('inputFullname').readOnly = false;
        document.getElementById('inputEmail').disabled = true;
        document.getElementById('inputEmail').readOnly = true; // Email thường không cho phép chỉnh sửa để tránh rắc rối xác thực lại

        // 2. Mở khóa các nút lựa chọn Radio giới tính
        document.getElementsByName('gender').forEach(radio => {
            radio.disabled = false;
        });

        // 3. Kích hoạt nút Lưu sáng lên để có thể bấm Submit dữ liệu
        document.getElementById('btnSubmitForm').disabled = false;

        // 4. Làm mờ hoặc tắt trạng thái nút chỉnh sửa để người dùng tập trung làm việc
        this.disabled = true;
        this.style.opacity = '0.5';
    });
</script>