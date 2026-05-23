<link rel="stylesheet" href="Public/Admin/Css/Profile.css">
<script src="Public/Admin/Js/Profile.js" defer></script>

<div class="custom-modal-backdrop" id="changePasswordModal">
    <div class="custom-modal-dialog">
        <div class="custom-modal-content">
            
            <div class="custom-modal-header d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-center gap-3">
                    <div class="modal-icon-title">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h4 class="modal-title-text">Đổi mật khẩu</h4>
                        <p class="modal-subtitle-text">Vui lòng cập nhật mật khẩu định kỳ để bảo mật.</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal" id="btnCloseChangePassword">&times;</button>
            </div>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger mx-4 mt-3 mb-0 py-2" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= htmlspecialchars($_SESSION['error_message']) ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success mx-4 mt-3 mb-0 py-2" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> <?= htmlspecialchars($_SESSION['success_message']) ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <form action="Index.php?page=change_password" method="POST" class="custom-modal-body">
                
                <div class="custom-form-group">
                    <label class="form-label-custom">Mật khẩu hiện tại</label>
                    <div class="custom-input-wrapper">
                        <i class="fa-solid fa-key left-icon"></i>
                        <input type="password" name="current_password" required placeholder="Nhập mật khẩu đang dùng">
                        <i class="fa-regular fa-eye toggle-password right-icon"></i>
                    </div>
                </div>

                <div class="custom-form-group">
                    <label class="form-label-custom">Mật khẩu mới</label>
                    <div class="custom-input-wrapper">
                        <i class="fa-solid fa-lock left-icon"></i>
                        <input type="password" name="new_password" required placeholder="Tối thiểu 8 ký tự">
                        <i class="fa-regular fa-eye toggle-password right-icon"></i>
                    </div>
                </div>

                <div class="custom-form-group">
                    <label class="form-label-custom">Xác nhận mật khẩu mới</label>
                    <div class="custom-input-wrapper">
                        <i class="fa-solid fa-shield-cat left-icon"></i>
                        <input type="password" name="confirm_password" required placeholder="Nhập lại mật khẩu mới">
                        <i class="fa-regular fa-eye toggle-password right-icon"></i>
                    </div>
                </div>

                <div class="security-notice-box">
                    <i class="fa-solid fa-circle-info notice-icon"></i>
                    <p class="notice-text">
                        <strong>Lưu ý bảo mật:</strong> Sử dụng ít nhất 8 ký tự bao gồm chữ hoa, chữ thường, chữ số và ký hiệu đặc biệt để bảo vệ tài khoản quản trị của bạn tốt nhất.
                    </p>
                </div>

                <div class="custom-modal-footer">
                    <button type="button" class="btn-custom-cancel" id="btnCancelChangePassword">Hủy</button>
                    <button type="submit" class="btn-custom-submit">Cập nhật mật khẩu</button>
                </div>

            </form>
        </div>
    </div>
</div>