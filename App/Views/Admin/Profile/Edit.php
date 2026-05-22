<div id="editProfileModal" class="custom-modal-backdrop">
    <div class="custom-modal-dialog" style="max-width: 680px;"> 
        <div class="custom-modal-content">
            
            <div class="custom-modal-header">
                <div style="display: flex; gap: 16px; align-items: center;">
                    <div>
                        <h3 class="modal-title-text">Chỉnh sửa thông tin</h3>
                        <p class="modal-subtitle-text">Thông tin hồ sơ sẽ giúp nhận diện quản trị viên trong hệ thống.</p>
                    </div>
                </div>
                <button type="button" id="btnCloseEditProfile" class="btn-close-modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="Index.php?page=update-profile" method="POST" enctype="multipart/form-data" style="margin: 0;">
                <div class="custom-modal-body">
                    <div class="profile-layout-container">
                        
                        <div class="profile-avatar-section">
                            <div class="avatar-upload-wrapper">
                                <img id="profileAvatarPreview" src="Public/Admin/Images/admin-avatar.png" alt="Avatar">
                                <label for="avatarFileInput" class="avatar-edit-badge">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                                <input type="file" id="avatarFileInput" name="avatar" accept="image/*" style="display: none;">
                            </div>
                            <h4 class="profile-display-name"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Nguyễn Văn An') ?></h4>
                            <span class="profile-display-role">QUẢN TRỊ VIÊN</span>
                        </div>

                        <div class="profile-fields-section">
                            <div class="profile-form-row">
                                <div class="custom-form-group">
                                    <label class="form-label-custom">HỌ VÀ TÊN</label>
                                    <div class="custom-input-wrapper">
                                        <input type="text" name="fullname" value="<?= htmlspecialchars($_SESSION['admin_name'] ?? 'Nguyễn Văn An') ?>" placeholder="Nhập họ và tên...">
                                    </div>
                                </div>
                                <div class="custom-form-group">
                                    <label class="form-label-custom">TÊN ĐĂNG NHẬP</label>
                                    <div class="custom-input-wrapper">
                                        <input type="text" name="username" value="<?= htmlspecialchars($_SESSION['admin_username'] ?? 'annguyen123') ?>" placeholder="Nhập tên đăng nhập...">
                                    </div>
                                </div>
                            </div>

                            <div class="custom-form-group">
                                <label class="form-label-custom">EMAIL</label>
                                <div class="custom-input-wrapper">
                                    <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['admin_email'] ?? 'annguyen123@example.com') ?>" placeholder="Nhập địa chỉ email...">
                                </div>
                            </div>

                            <div class="custom-form-group" style="margin-bottom: 8px;">
                                <label class="form-label-custom">GIỚI THIỆU NGẮN</label>
                                <textarea class="custom-textarea-profile" name="bio" rows="3" placeholder="Nhập mô tả ngắn về bản thân..."><?= htmlspecialchars($_SESSION['admin_bio'] ?? 'Quản trị nội dung hệ thống Trạm Tin Việt') ?></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="custom-modal-footer">
                    <button type="button" id="btnCancelEditProfile" class="btn-custom-cancel">Hủy</button>
                    <button type="submit" class="btn-custom-submit">Lưu thay đổi</button>
                </div>
            </form>

        </div>
    </div>
</div>