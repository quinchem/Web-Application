<footer class="footer-container text-center py-4 bg-dark text-white mt-5">
    <p class="m-0">&copy; <?= date('Y') ?> Trạm Tin Việt - Bản quyền thuộc về tác giả.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<?php if (isset($_SESSION['error'])): ?>
    <script>
        // Hiển thị thông báo lỗi dạng Alert
        alert('<?= $_SESSION['error']; ?>');

    </script>
    <?php 
    // Xóa session lỗi ngay sau khi xử lý xong để tránh việc F5 trang bị hiện lại modal vô cớ
    unset($_SESSION['error']); 
    ?>
<?php endif; ?>

</body>
</html>