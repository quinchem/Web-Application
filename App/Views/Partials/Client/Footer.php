<footer class="footer-container text-center py-4 bg-dark text-white mt-5">
    <p class="m-0">&copy; <?= date('Y') ?> Trạm Tin Việt - Bản quyền thuộc về tác giả.</p>
</footer>

<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 p-3 shadow" style="border-radius: 8px;">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <span style="width: 20px; height: 2px; background-color: #c92a2a; display: inline-block; margin-right: 8px;"></span>
                        <span style="color: #0f3460; font-weight: 700; font-size: 1.1rem;">Trạm Tin Việt</span>
                    </div>
                    <h4 class="fw-bold mb-1">Chào mừng trở lại</h4>
                    <p class="text-muted small mb-0">Đăng nhập để cập nhật những tin tức mới nhất từ Trạm Tin Việt</p>
                </div>

                <form action="index.php?page=login" method="POST">
                    <div class="mb-3 fw-bold small text-dark">Đăng nhập</div>
                    
                    <div class="input-group mb-3 rounded overflow-hidden" style="background-color: #f8f9fa;">
                        <span class="input-group-text border-0 bg-transparent text-muted"><i class="bi bi-person"></i></span>
                        <input type="text" name="user_name" class="form-control border-0 bg-transparent py-2" placeholder="Tên đăng nhập" required>
                    </div>

                    <div class="input-group mb-3 rounded overflow-hidden" style="background-color: #f8f9fa;">
                        <span class="input-group-text border-0 bg-transparent text-muted"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="modalPasswordInput" class="form-control border-0 bg-transparent py-2" placeholder="Mật khẩu" required>
                        <span class="input-group-text border-0 bg-transparent text-muted" id="toggleModalPassword" style="cursor: pointer;">
                            <i class="bi bi-eye" id="modalEyeIcon"></i>
                        </span>
                    </div>

                    <div class="d-flex justify-content-end mb-3">
                        <a href="index.php?page=forgot-password" class="text-decoration-none small" style="color: #c92a2a;">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn w-100 mb-3 text-white py-2 fw-semibold" style="background-color: #c92a2a; border-radius: 6px;">Đăng nhập</button>
                    
                    <button type="button" class="btn w-100 mb-3 py-2 d-flex justify-content-center align-items-center small" style="border: 1px solid #e0e0e0; background-color: #fff; border-radius: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google me-2" viewBox="0 0 16 16" style="color: #ea4335;">
                            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                        </svg>
                        Tiếp tục với Google
                    </button>

                    <div class="text-center small pt-2">
                        <span class="text-muted">Bạn chưa có tài khoản?</span> 
                        <a href="index.php?page=register" class="text-decoration-none fw-semibold" style="color: #c92a2a;">Đăng ký ngay</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggleModalPassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('modalPasswordInput');
        const eyeIcon = document.getElementById('modalEyeIcon');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        
        passwordInput.setAttribute('type', type);
        if (type === 'password') {
            eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<?php if (isset($_SESSION['error'])): ?>
    <script>
        // Hiển thị thông báo lỗi dạng Alert
        alert('<?= $_SESSION['error']; ?>');

        // Kiểm tra xem có đang ở trang login độc lập hay không
        // Nếu đang ở trang chủ hoặc trang bài viết thì mới tự động bật Modal lên lại
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('page') !== 'login') {
            const myModal = new bootstrap.Modal(document.getElementById('loginModal'));
            myModal.show();
        }
    </script>
    <?php 
    // Xóa session lỗi ngay sau khi xử lý xong để tránh việc F5 trang bị hiện lại modal vô cớ
    unset($_SESSION['error']); 
    ?>
<?php endif; ?>


<?php if (isset($_SESSION['success_msg'])): ?>
    <script>
        alert('<?= $_SESSION['success_msg']; ?>');
    </script>
    <?php unset($_SESSION['success_msg']); ?>
<?php endif; ?>

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>