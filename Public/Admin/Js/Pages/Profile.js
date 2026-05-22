/* =========================================================================
   PART 1: PROFILE MENU (Bấm Avatar hiện menu & Click ra ngoài để ẩn)
   ========================================================================= */
function toggleProfileMenu() {
    const menu = document.getElementById('profileMenu');
    if (menu) {
        menu.classList.toggle('active');
    }
}

// Click ra ngoài vùng chọn để tự động đóng Menu Avatar
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('profileWrapper');
    const menu = document.getElementById('profileMenu');
    if (wrapper && !wrapper.contains(e.target)) {
        if (menu) {
            menu.classList.remove('active');
        }
    }
});


/* =========================================================================
   PART 2: TOÀN BỘ LOGIC KHI ĐỒNG BỘ TRANG (DOM LOADED)
   ========================================================================= */
document.addEventListener("DOMContentLoaded", function () {
    
    // --- 2.1. XỬ LÝ ẨN/HIỆN MẮT XEM MẬT KHẨU (Ẩn/Hiện password) ---
const togglePasswordIcons = document.querySelectorAll('.toggle-password');

togglePasswordIcons.forEach(function (icon) {
    icon.addEventListener('click', function () {
        // Tìm thẻ cha bao bọc gần nhất (.custom-input-wrapper)
        const wrapper = this.closest('.custom-input-wrapper');
        
        // Từ thẻ cha, tìm chính xác thẻ input bên trong nó
        const input = wrapper ? wrapper.querySelector('input') : null;

        if (input) {
            if (input.type === 'password') {
                // Hiện số: Đổi type thành text
                input.type = 'text';
                
                // Đổi icon thành mắt gạch (ẩn số)
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                // Tắt số (ẩn mật khẩu): Đổi type về password
                input.type = 'password';
                
                // Đổi icon thành mắt mở (hiện số)
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        }
    });
});

    // --- 2.2. XỬ LÝ ĐÓNG/MỞ MODAL ĐỔI MẬT KHẨU TẠI CHỖ ---
    const btnOpen = document.getElementById('btnOpenChangePassword');
    const btnClose = document.getElementById('btnCloseChangePassword'); // Nút X góc trên Modal
    const btnCancel = document.getElementById('btnCancelChangePassword'); // Nút Hủy góc dưới Form
    const modalBackdrop = document.getElementById('changePasswordModal'); // ID của thẻ div phủ bên ngoài Modal

    // Hàm mở Modal và đóng luôn cái Menu Avatar nhỏ phía sau
    if (btnOpen) {
        btnOpen.addEventListener('click', function() {
            if (modalBackdrop) modalBackdrop.style.display = 'flex';
            
            const menu = document.getElementById('profileMenu');
            if (menu) menu.classList.remove('active'); // Đóng menu nhỏ lại cho đẹp
        });
    }

    // Hàm đóng Modal
    function closeModal() {
        if (modalBackdrop) modalBackdrop.style.display = 'none';
    }

    if (btnClose) btnClose.addEventListener('click', closeModal);
    if (btnCancel) btnCancel.addEventListener('click', closeModal);

    // Click vào vùng nền mờ bên ngoài hộp thoại cũng tự động đóng Modal
    if (modalBackdrop) {
        modalBackdrop.addEventListener('click', function(e) {
            if (e.target === modalBackdrop) {
                closeModal();
            }
        });
    }
});
// =========================================================================
    // PART 3: XỬ LÝ ĐÓNG/MỞ MODAL CHỈNH SỬA THÔNG TIN TÀI KHOẢN
    // =========================================================================
    
    // Gán ID cho nút "Thông tin tài khoản" trong HTML menu con của bạn (ví dụ id="btnOpenEditProfile")
    // Hoặc bạn có thể gán trực tiếp class hoặc tìm thông qua cấu trúc phần tử.
    const btnOpenEdit = document.getElementById('btnOpenEditProfile') || document.querySelector('.profile-menu-item:not(.logout):not(#btnOpenChangePassword)');
    const btnCloseEdit = document.getElementById('btnCloseEditProfile');
    const btnCancelEdit = document.getElementById('btnCancelEditProfile');
    const modalEditProfile = document.getElementById('editProfileModal');

    // Hàm mở Modal chỉnh sửa thông tin
    if (btnOpenEdit) {
        btnOpenEdit.addEventListener('click', function(e) {
            e.preventDefault();
            if (modalEditProfile) modalEditProfile.style.display = 'flex';
            
            // Ẩn menu nhỏ phía sau
            const menu = document.getElementById('profileMenu');
            if (menu) menu.classList.remove('active');
        });
    }

    // Hàm đóng Modal chỉnh sửa thông tin
    function closeEditModal() {
        if (modalEditProfile) modalEditProfile.style.display = 'none';
    }

    if (btnCloseEdit) btnCloseEdit.addEventListener('click', closeEditModal);
    if (btnCancelEdit) btnCancelEdit.addEventListener('click', closeEditModal);

    // Click vùng ngoài nền mờ để tự đóng
    if (modalEditProfile) {
        modalEditProfile.addEventListener('click', function(e) {
            if (e.target === modalEditProfile) {
                closeEditModal();
            }
        });
    }

    // --- LOGIC XỬ LÝ PREVIEW ẢNH AVATAR NGAY KHI CHỌN FILE ---
    const avatarInput = document.getElementById('avatarFileInput');
    const avatarPreview = document.getElementById('profileAvatarPreview');

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }