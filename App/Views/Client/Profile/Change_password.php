<?php
// App/Views/Client/Profile/change_password.php
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700&family=Newsreader:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/../Web-Application/Public/Client/Css/ProfileChangePassword.css">
</head>
<body>

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
                <button type="submit" class="btn-update-password">CẬP NHẬT</button>
            </div>

        </form>
    </div>
</div>
</body>
</html>