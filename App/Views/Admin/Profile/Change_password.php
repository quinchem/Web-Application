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

            <!-- Thông báo kết quả -->
            <div id="changePwdAlert" class="d-none mx-4 mt-3 mb-0 rounded-3 px-3 py-2" style="font-size:13.5px; font-weight:500;"></div>

            <form id="changePasswordForm" class="custom-modal-body">
                
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
                        <strong>Lưu ý bảo mật:</strong> Sử dụng ít nhất 8 ký tự bao gồm chữ hoa, chữ thường, chữ số và ký hiệu đặc biệt.
                    </p>
                </div>

                <div class="custom-modal-footer">
                    <button type="button" class="btn-custom-cancel" id="btnCancelChangePassword">Hủy</button>
                    <button type="submit" class="btn-custom-submit" id="btnSubmitChangePwd">Cập nhật mật khẩu</button>
                </div>

            </form>
        </div>
    </div>
</div>