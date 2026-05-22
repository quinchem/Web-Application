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
            // Tìm ô input nằm ngay phía trước icon con mắt này
            const input = this.previousElementSibling;

            if (input && input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash'); // Đổi sang mắt gạch
            } else if (input) {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye'); // Đổi về mắt mở
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