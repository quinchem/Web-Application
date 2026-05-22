<h4 class="fw-bold mb-1 text-dark">Chỉnh sửa hồ sơ</h4>
<p class="text-muted small mb-4">Cập nhật thông tin tài khoản công khai của bạn tại đây.</p>

<form action="/client/handle-profile" method="POST">
    <div class="row g-3" style="max-width: 550px;">
        <div class="col-12">
            <label class="form-label fw-semibold text-secondary">Họ và tên</label>
            <input type="text" class="form-control" name="fullname" value="Nguyễn Quỳnh Trâm" required>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold text-secondary">Địa chỉ Email</label>
            <input type="email" class="form-control" name="email" value="tramnq@ueh.edu.vn" required>
        </div>
        <div class="col-12 mt-4">
            <button type="submit" class="btn btn-primary px-4">Lưu thay đổi</button>
        </div>
    </div>
</form>