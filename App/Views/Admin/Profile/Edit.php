<div id="editProfileModal" class="custom-modal-backdrop">
    <div class="custom-modal-dialog" style="max-width: 680px;"> 
        <div class="custom-modal-content">
            
            <div class="custom-modal-header d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <h3 class="modal-title-text">Chỉnh sửa thông tin</h3>
                        <p class="modal-subtitle-text">Thông tin hồ sơ sẽ giúp nhận diện quản trị viên trong hệ thống.</p>
                    </div>
                </div>
                <button type="button" id="btnCloseEditProfile" class="btn-close-modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div id="editProfileAlert" class="d-none mx-4 mt-3 mb-0 rounded-3 px-3 py-2" style="font-size: 13.5px; font-weight: 500;"></div>

            <form id="editProfileForm" enctype="multipart/form-data" class="m-0">
                <input type="hidden" name="page" value="update-profile">

                <div class="custom-modal-body">
                    <div class="profile-layout-container">
                        
                        <div class="profile-avatar-section">
                            <div class="avatar-upload-wrapper">
                                <img id="profileAvatarPreview" 
                                     src="<?= htmlspecialchars($_SESSION['user']->avatar ?? 'Public/Admin/Images/admin-avatar.png') ?>" 
                                     alt="Avatar">
                                <label for="avatarFileInput" class="avatar-edit-badge">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                                <input type="file" id="avatarFileInput" name="avatar" accept="image/*" class="d-none">
                            </div>
                            <h4 class="profile-display-name" id="profileDisplayName">
                                <?= htmlspecialchars($_SESSION['user']->full_name ?? '') ?>
                            </h4>
                            <span class="profile-display-role">QUẢN TRỊ VIÊN</span>
                        </div>

                        <div class="profile-fields-section">
                            <div class="profile-form-row">
                                <div class="custom-form-group">
                                    <label class="form-label-custom">HỌ VÀ TÊN</label>
                                    <div class="custom-input-wrapper">
                                        <input type="text" name="fullname" id="inputFullname" 
                                               value="<?= htmlspecialchars($_SESSION['user']->full_name ?? '') ?>" 
                                               placeholder="Nhập họ và tên...">
                                    </div>
                                </div>
                                <div class="custom-form-group">
                                    <label class="form-label-custom">TÊN ĐĂNG NHẬP</label>
                                    <div class="custom-input-wrapper">
                                        <input type="text" name="username" 
                                               value="<?= htmlspecialchars($_SESSION['user']->user_name ?? '') ?>" 
                                               placeholder="Nhập tên đăng nhập...">
                                    </div>
                                </div>
                            </div>

                            <div class="custom-form-group">
                                <label class="form-label-custom">EMAIL</label>
                                <div class="custom-input-wrapper">
                                    <input type="email" name="email" 
                                           value="<?= htmlspecialchars($_SESSION['user']->email ?? '') ?>" 
                                           placeholder="Nhập địa chỉ email...">
                                </div>
                            </div>

                            <div class="custom-form-group mb-2">
                                <label class="form-label-custom">GIỚI THIỆU NGẮN</label>
                                <textarea class="custom-textarea-profile" name="bio" rows="3" 
                                          placeholder="Nhập mô tả ngắn về bản thân..."><?= htmlspecialchars($_SESSION['user']->bio ?? '') ?></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="custom-modal-footer">
                    <button type="button" id="btnCancelEditProfile" class="btn-custom-cancel">Hủy</button>
                    <button type="submit" class="btn-custom-submit" id="btnSubmitEditProfile">Lưu thay đổi</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form     = this;
    const btn      = document.getElementById('btnSubmitEditProfile');
    const alertBox = document.getElementById('editProfileAlert');
    const formData = new FormData(form);

    btn.disabled    = true;
    btn.textContent = 'Đang lưu...';
    alertBox.classList.add('d-none');

    fetch('Admin_index.php?page=update-profile', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alertBox.classList.remove('d-none');

        if (data.success) {
            alertBox.style.cssText = 'color:#208b3a; background:#f0fdf4; border:1px solid #bbf7d0;';
            alertBox.textContent   = data.message ?? 'Cập nhật thành công!';

            const nameEl = document.getElementById('profileDisplayName');
            if (nameEl && formData.get('fullname')) {
                nameEl.textContent = formData.get('fullname');
            }

            setTimeout(() => {
                alertBox.classList.add('d-none');
                document.getElementById('editProfileModal').style.display = 'none';
            }, 1200);
        } else {
            alertBox.style.cssText = 'color:#cc2429; background:#fdf2f2; border:1px solid #f9d5d6;';
            alertBox.textContent   = data.message ?? 'Cập nhật thất bại, vui lòng thử lại.';
        }
    })
    .catch(() => {
        alertBox.classList.remove('d-none');
        alertBox.style.cssText = 'color:#cc2429; background:#fdf2f2; border:1px solid #f9d5d6;';
        alertBox.textContent   = 'Lỗi kết nối, vui lòng thử lại.';
    })
    .finally(() => {
        btn.disabled    = false;
        btn.textContent = 'Lưu thay đổi';
    });
});
</script>