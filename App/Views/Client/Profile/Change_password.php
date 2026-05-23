<?php
// App/Views/Client/Profile/change_password.php
?>

<style>
    .password-change-container {
        font-family: 'Montserrat', sans-serif; 
    }
    
    /* 1. TIÊU ĐỀ/NHÃN: Sử dụng font Barlow */
    .form-custom-label {
        font-family: 'Barlow', sans-serif;
        font-weight: 700;
        text-transform: uppercase;
        color: #8c8275;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    /* 2. NỘI DUNG NHẬP & PHẦN PHỤ TRỢ: Sử dụng font Montserrat */
    .form-custom-input {
        font-family: 'Montserrat', sans-serif;
        background-color: #f1ede7 !important;
        border: none !important;
        border-radius: 4px !important;
        padding: 14px 15px !important;
        color: #333333 !important;
        font-weight: 500;
        font-size: 0.95rem;
    }
    
    .password-wrapper {
        position: relative;
    }
    
    .password-wrapper .toggle-password {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #8c8275;
        font-size: 1.1rem;
        transition: color 0.2s;
        z-index: 10;
    }
    
    .password-wrapper .toggle-password:hover {
        color: #03254c;
    }
    
    .btn-update-password {
        font-family: 'Montserrat', sans-serif;
        background-color: #03254c !important;
        color: white !important;
        font-weight: 700;
        padding: 12px 35px;
        border-radius: 4px;
        font-size: 0.95rem;
        border: none;
        transition: background-color 0.2s;
    }
    
    .btn-update-password:hover {
        background-color: #021b3a !important;
    }
    
    .password-note {
        font-family: 'Montserrat', sans-serif;
        color: #8c8275;
        font-style: italic;
        font-size: 0.8rem;
        margin-top: 6px;
    }

    /* Khử hoàn toàn con mắt mặc định hệ thống của các trình duyệt */
    .password-wrapper input::-ms-reveal,
    .password-wrapper input::-ms-clear {
        display: none !important;
    }
</style>

<div class="card shadow-sm border-0 p-4 bg-white" style="min-height: 500px;">
    <div class="password-change-container p-3">
        
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success py-2 small mb-4"><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="alert alert-danger py-2 small mb-4"><?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></div>
        <?php endif; ?>

        <form action="index.php?page=handle_change_password" method="POST" id="changePasswordForm" 
              onsubmit="const n=this.new_password.value; const c=this.confirm_password.value; if(!/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test(n)){ alert('Mật khẩu mới không đáp ứng đủ điều kiện!\n(Phải từ 8 ký tự trở lên, bao gồm ít nhất 1 chữ hoa, 1 chữ thường và 1 chữ số)'); return false; } if(n!==c){ alert('Xác nhận mật khẩu mới không trùng khớp với mật khẩu mới!'); return false; }">
            
            <div class="mb-4">
                <label class="form-label form-custom-label">Mật khẩu hiện tại</label>
                <div class="password-wrapper">
                    <input type="password" class="form-control form-custom-input pe-5" name="current_password" placeholder="Nhập mật khẩu hiện tại" required>
                    <i class="fa-regular fa-eye-slash toggle-password" onclick="const input = this.previousElementSibling; if(input.type === 'password') { input.type = 'text'; this.classList.remove('fa-eye-slash'); this.classList.add('fa-eye'); } else { input.type = 'password'; this.classList.remove('fa-eye'); this.classList.add('fa-eye-slash'); }"></i>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label form-custom-label">Mật khẩu mới</label>
                <div class="password-wrapper">
                    <input type="password" class="form-control form-custom-input pe-5" name="new_password" placeholder="Nhập mật khẩu mới" required>
                    <i class="fa-regular fa-eye-slash toggle-password" onclick="const input = this.previousElementSibling; if(input.type === 'password') { input.type = 'text'; this.classList.remove('fa-eye-slash'); this.classList.add('fa-eye'); } else { input.type = 'password'; this.classList.remove('fa-eye'); this.classList.add('fa-eye-slash'); }"></i>
                </div>
                <div class="password-note">
                    Mật khẩu phải bao gồm ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và chữ số.
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label form-custom-label">Xác nhận mật khẩu mới</label>
                <div class="password-wrapper">
                    <input type="password" class="form-control form-custom-input pe-5" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
                    <i class="fa-regular fa-eye-slash toggle-password" onclick="const input = this.previousElementSibling; if(input.type === 'password') { input.type = 'text'; this.classList.remove('fa-eye-slash'); this.classList.add('fa-eye'); } else { input.type = 'password'; this.classList.remove('fa-eye'); this.classList.add('fa-eye-slash'); }"></i>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-update-password">Cập nhật mật khẩu</button>
            </div>

        </form>
    </div>
</div>