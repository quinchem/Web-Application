<?php
// App/Views/Client/Profile/edit.php

// 1. Lấy thông tin tài khoản tươi mới nhất từ Database thông qua Repository đang dùng chung
$usernameSession = $_SESSION['user_name'] ?? '';
$user = $this->clientRepository->getUserByUsername($usernameSession);

// Tách các biến dữ liệu để đổ vào Form (nếu trống sẽ lấy từ Session làm fallback)
$uName = htmlspecialchars($user['user_name'] ?? $_SESSION['user_name'] ?? '');
$fName = htmlspecialchars($user['full_name'] ?? $_SESSION['full_name'] ?? '');
$uEmail = htmlspecialchars($user['email'] ?? $_SESSION['email'] ?? '');
$uGender = $user['gender'] ?? 'male';
$uAvatar  = htmlspecialchars($user['avatar'] ?? $_SESSION['avatar'] ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png');
?>

<style>
    .profile-edit-container {
        /* ĐỒNG BỘ: Chuyển sang font Montserrat */
        font-family: 'Montserrat', sans-serif;
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
        font-family: 'Montserrat', sans-serif;
        background-color: #03254c !important;
        color: white !important;
        font-weight: 600;
        padding: 8px 18px;
        font-size: 0.9rem;
        border-radius: 4px;
    }

    .btn-remove-img {
        font-family: 'Montserrat', sans-serif;
        color: #b00f14 !important;
        font-weight: 700;
        text-decoration: none;
        font-size: 0.9rem;
    }

    /* ĐỒNG BỘ: Tiêu đề chuyển sang font Barlow chữ hoa cứng cáp */
    .form-custom-label {
        font-family: 'Barlow', sans-serif;
        font-weight: 700;
        text-transform: uppercase;
        color: #8c8275;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    /* ĐỒNG BỘ: Ô nhập liệu đồng nhất màu be nhạt và bo góc 4px giống tab Đổi mật khẩu */
    .form-custom-input {
        font-family: 'Montserrat', sans-serif;
        background-color: #f1ede7 !important;
        border: none !important;
        border-radius: 4px !important;
        /* Đã sửa thành 4px cho đồng bộ */
        padding: 12px 15px !important;
        color: #333333 !important;
        font-weight: 500;
    }

    .form-custom-input:disabled,
    .form-custom-input[readonly] {
        background-color: #e6e1da !important;
        /* Màu sậm hơn một chút khi khóa */
        color: #666666 !important;
    }

    /* Cấu hình nhóm nút bấm góc dưới bên phải */
    .btn-action-edit {
        font-family: 'Montserrat', sans-serif;
        background-color: #b00f14 !important;
        /* Màu đỏ đậm */
        color: white !important;
        font-weight: 700;
        padding: 10px 30px;
        border-radius: 4px;
        text-transform: uppercase;
        font-size: 0.9rem;
        border: none;
    }

    .btn-action-save {
        font-family: 'Montserrat', sans-serif;
        background-color: #03254c !important;
        /* Màu xanh đen */
        color: white !important;
        font-weight: 700;
        padding: 10px 45px;
        border-radius: 4px;
        text-transform: uppercase;
        font-size: 0.9rem;
        border: none;
    }
</style>

<div class="card shadow-sm border-0 p-4" style="min-height: 500px;">
    <div class="profile-edit-container p-3">
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success py-2 small mb-4">
                <?= $_SESSION['success_msg'];
                unset($_SESSION['success_msg']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="alert alert-danger py-2 small mb-4"><?= $_SESSION['error_msg'];
            unset($_SESSION['error_msg']); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=handle_profile" method="POST" id="profileForm" enctype="multipart/form-data">

            <div class="d-flex align-items-center gap-4 mb-4">
                <div class="avatar-edit-wrapper border">
                    <img src="<?= $uAvatar; ?>" alt="Avatar" id="avatarPreview">
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1"
                        style="color: #03254c !important; font-family: 'Barlow', sans-serif;">Ảnh đại diện</h5>
                    <p class="text-muted small mb-2">Khuyên dùng ảnh hình vuông, dung lượng tối đa 2MB.</p>
                    <div class="d-flex align-items-center gap-3">

                        <input type="file" name="avatar_file" id="avatarInput" accept="image/*" class="d-none"
                            style="display: none;">

                        <input type="hidden" name="remove_avatar" id="removeAvatarFlag" value="0">

                        <button type="button" class="btn btn-upload-img btn-sm" id="btnUploadAvatar">TẢI ẢNH
                            MỚI</button>
                        <a href="#" class="btn-remove-img" id="btnRemoveAvatar" style="margin-left: 10px;">Gỡ bỏ</a>
                    </div>
                </div>
            </div>

            <hr class="my-4" style="color: #eaeaea;">

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label form-custom-label">Tên đăng nhập</label>
                    <input type="text" class="form-control form-custom-input" name="username" id="inputUsername"
                        value="<?= $uName; ?>" required readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label form-custom-label">Giới tính</label>
                    <input type="hidden" name="current_gender" value="<?= $uGender; ?>">
                    <div class="d-flex gap-4 pt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="male" id="genderNam"
                                <?= $uGender === 'male' ? 'checked' : ''; ?> disabled>
                            <label class="form-check-label fw-semibold text-dark" for="genderNam">Nam</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="female" id="genderNu"
                                <?= $uGender === 'female' ? 'checked' : ''; ?> disabled>
                            <label class="form-check-label fw-semibold text-dark" for="genderNu">Nữ</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="other" id="genderKhac"
                                <?= $uGender === 'other' ? 'checked' : ''; ?> disabled>
                            <label class="form-check-label fw-semibold text-dark" for="genderKhac">Khác</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label form-custom-label">Họ tên</label>
                    <input type="text" class="form-control form-custom-input" name="fullname" id="inputFullname"
                        value="<?= $fName; ?>" required disabled readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label form-custom-label">Email</label>
                    <input type="email" class="form-control form-custom-input" name="email" value="<?= $uEmail; ?>"
                        required disabled readonly>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5">
                <button type="button" class="btn btn-action-edit" id="btnEnableEdit">Chỉnh sửa</button>
                <button type="submit" class="btn btn-action-save" id="btnSubmitForm" disabled>Lưu</button>
            </div>

        </form>

    </div>
</div>
<script src="/Public/Client/Js/ProfileEdit.js"></script>