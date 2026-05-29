function toggleProfileMenu() {
    const menu = document.getElementById('profileMenu');
    if (menu) menu.classList.toggle('active');
}

document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('profileWrapper');
    const menu = document.getElementById('profileMenu');
    if (wrapper && !wrapper.contains(e.target)) {
        if (menu) menu.classList.remove('active');
    }
});

document.addEventListener("DOMContentLoaded", function () {

    const adminProfile = document.getElementById('adminProfile');
    if (adminProfile) {
        adminProfile.addEventListener('click', function(e) {
            if (e.target.closest('.logout-btn')) return;
            toggleProfileMenu();
        });
    }

    document.querySelectorAll('.toggle-password').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const wrapper = this.closest('.custom-input-wrapper');
            const input = wrapper ? wrapper.querySelector('input') : null;
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            }
        });
    });

    // --- MODAL ĐỔI MẬT KHẨU ---
    const btnOpenPwd    = document.getElementById('btnOpenChangePassword');
    const btnClosePwd   = document.getElementById('btnCloseChangePassword');
    const btnCancelPwd  = document.getElementById('btnCancelChangePassword');
    const modalPwd      = document.getElementById('changePasswordModal');

    function openPwdModal()  { if (modalPwd) modalPwd.style.display = 'flex'; }
    function closePwdModal() { if (modalPwd) modalPwd.style.display = 'none'; }

    if (btnOpenPwd) btnOpenPwd.addEventListener('click', function() {
        openPwdModal();
        const menu = document.getElementById('profileMenu');
        if (menu) menu.classList.remove('active');
    });
    if (btnClosePwd)  btnClosePwd.addEventListener('click', closePwdModal);
    if (btnCancelPwd) btnCancelPwd.addEventListener('click', closePwdModal);
    if (modalPwd) {
        modalPwd.addEventListener('click', function(e) {
            if (e.target === modalPwd) closePwdModal();
        });
    }

    // --- MODAL CHỈNH SỬA THÔNG TIN ---
    const btnOpenEdit   = document.getElementById('btnOpenEditProfile');
    const btnCloseEdit  = document.getElementById('btnCloseEditProfile');
    const btnCancelEdit = document.getElementById('btnCancelEditProfile');
    const modalEdit     = document.getElementById('editProfileModal');

    function openEditModal()  { if (modalEdit) modalEdit.style.display = 'flex'; }
    function closeEditModal() { if (modalEdit) modalEdit.style.display = 'none'; }

    if (btnOpenEdit) btnOpenEdit.addEventListener('click', function(e) {
        e.preventDefault();
        openEditModal();
        const menu = document.getElementById('profileMenu');
        if (menu) menu.classList.remove('active');
    });
    if (btnCloseEdit)  btnCloseEdit.addEventListener('click', closeEditModal);
    if (btnCancelEdit) btnCancelEdit.addEventListener('click', closeEditModal);
    if (modalEdit) {
        modalEdit.addEventListener('click', function(e) {
            if (e.target === modalEdit) closeEditModal();
        });
    }

    // --- PREVIEW ẢNH AVATAR ---
    const avatarInput   = document.getElementById('avatarFileInput');
    const avatarPreview = document.getElementById('profileAvatarPreview');
    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) { avatarPreview.src = e.target.result; };
                reader.readAsDataURL(file);
            }
        });
    }

    // --- SUBMIT ĐỔI MẬT KHẨU ---
const changePwdForm = document.getElementById('changePasswordForm');
if (changePwdForm) {
    changePwdForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const btn      = document.getElementById('btnSubmitChangePwd');
        const alertBox = document.getElementById('changePwdAlert');
        const formData = new FormData(this);

        const newPwd     = this.querySelector('[name="new_password"]').value;
        const confirmPwd = this.querySelector('[name="confirm_password"]').value;

        // ✅ Kiểm tra độ dài
        if (newPwd.length < 8) {
            alertBox.classList.remove('d-none');
            alertBox.style.cssText = 'color:#cc2429; background:#fdf2f2; border:1px solid #f9d5d6;';
            alertBox.textContent   = 'Mật khẩu phải có ít nhất 8 ký tự!';
            return;
        }

        // ✅ Kiểm tra chữ hoa
        if (!/[A-Z]/.test(newPwd)) {
            alertBox.classList.remove('d-none');
            alertBox.style.cssText = 'color:#cc2429; background:#fdf2f2; border:1px solid #f9d5d6;';
            alertBox.textContent   = 'Mật khẩu phải có ít nhất 1 chữ hoa!';
            return;
        }

        // ✅ Kiểm tra chữ thường
        if (!/[a-z]/.test(newPwd)) {
            alertBox.classList.remove('d-none');
            alertBox.style.cssText = 'color:#cc2429; background:#fdf2f2; border:1px solid #f9d5d6;';
            alertBox.textContent   = 'Mật khẩu phải có ít nhất 1 chữ thường!';
            return;
        }

        // ✅ Kiểm tra chữ số
        if (!/[0-9]/.test(newPwd)) {
            alertBox.classList.remove('d-none');
            alertBox.style.cssText = 'color:#cc2429; background:#fdf2f2; border:1px solid #f9d5d6;';
            alertBox.textContent   = 'Mật khẩu phải có ít nhất 1 chữ số!';
            return;
        }

        // ✅ Kiểm tra ký hiệu đặc biệt
        if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(newPwd)) {
            alertBox.classList.remove('d-none');
            alertBox.style.cssText = 'color:#cc2429; background:#fdf2f2; border:1px solid #f9d5d6;';
            alertBox.textContent   = 'Mật khẩu phải có ít nhất 1 ký hiệu đặc biệt!';
            return;
        }

        // ✅ Kiểm tra khớp
        if (newPwd !== confirmPwd) {
            alertBox.classList.remove('d-none');
            alertBox.style.cssText = 'color:#cc2429; background:#fdf2f2; border:1px solid #f9d5d6;';
            alertBox.textContent   = 'Mật khẩu mới và xác nhận không khớp!';
            return;
        }

        btn.disabled    = true;
        btn.textContent = 'Đang cập nhật...';
        alertBox.classList.add('d-none');

        fetch('Admin_index.php?page=change_password', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alertBox.classList.remove('d-none');
            if (data.success) {
                alertBox.style.cssText = 'color:#208b3a; background:#f0fdf4; border:1px solid #bbf7d0;';
                alertBox.textContent   = data.message ?? 'Đổi mật khẩu thành công!';
                changePwdForm.reset();
                setTimeout(() => {
                    alertBox.classList.add('d-none');
                    if (modalPwd) modalPwd.style.display = 'none';
                }, 1500);
            } else {
                alertBox.style.cssText = 'color:#cc2429; background:#fdf2f2; border:1px solid #f9d5d6;';
                alertBox.textContent   = data.message ?? 'Đổi mật khẩu thất bại.';
            }
        })
        .catch(() => {
            alertBox.classList.remove('d-none');
            alertBox.style.cssText = 'color:#cc2429; background:#fdf2f2; border:1px solid #f9d5d6;';
            alertBox.textContent   = 'Lỗi kết nối, vui lòng thử lại.';
        })
        .finally(() => {
            btn.disabled    = false;
            btn.textContent = 'Cập nhật mật khẩu';
        });
    });
}

});