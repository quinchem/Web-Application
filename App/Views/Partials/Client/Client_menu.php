<style>
    .profile-sidebar {
        background-color: #e3edf7 !important; /* Màu nền xanh xám nhạt như hình */
        border-radius: 0px; /* Bỏ bo góc nếu muốn vuông vắn hoặc chỉnh 12px tùy ý */
        overflow: hidden;
        padding-bottom: 15px; /* Loại bỏ padding dưới cùng để sát với chân trang hơn */
        height: fit-content;    /* Khóa chiều cao: chỉ dài bằng nội dung bên trong nó */
        position: sticky;       /* Trượt theo màn hình khi cuộn */
        top: 20px;
    }
    
    .profile-sidebar .avatar-section {
        padding: 40px 15px 25px 15px;
        text-align: center;
    }
    
    .profile-sidebar .avatar-wrapper {
        width: 130px;
        height: 130px;
        margin: 0 auto 15px auto;
        border-radius: 50%;
        border: 4px solid #ffffff; /* Viền trắng dày bao quanh ảnh */
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    .profile-sidebar .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-sidebar .user-name {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #03254c; /* Màu xanh đen đậm */
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Thiết lập các item trong menu */
    .profile-sidebar .list-group-item {
        background: transparent !important;
        color: #495057 !important;
        font-weight: 600;
        font-size: 0.95rem;
        border: none !important;
        padding: 16px 28px !important;
        display: flex;
        align-items: center;
        position: relative;
        transition: all 0.15s ease;
    }
    
    .profile-sidebar .list-group-item i {
        font-size: 1.15rem;
        width: 30px;
        color: #495057 !important;
    }
    
    /* Hiệu ứng khi di chuột qua */
    .profile-sidebar .list-group-item:hover {
        background-color: rgba(99, 138, 231, 0.33) !important;
        color: #03254c !important;
    }
    
    .profile-sidebar .list-group-item.active {
    background-color: rgba(99, 138, 231, 0.33) !important; /* Màu xanh xám nhạt, sáng và dịu mắt */
    color: #03254c !important;            /* Chữ chuyển sang xanh đen đậm để nổi bật */
    font-weight: 700;                     /* Chữ đậm lên khi kích hoạt */
}

.profile-sidebar .list-group-item:hover i {
    color: #03254c !important;            /* Icon cũng chuyển màu đồng bộ với chữ */
}

.profile-sidebar .list-group-item.active i {
    color: #03254c !important;            /* Icon cũng chuyển màu đồng bộ với chữ */
}

/* 2. Thanh dọc nhỏ làm điểm nhấn ở rìa phải (Giữ nguyên hoặc tùy biến) */
.profile-sidebar .list-group-item.active::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 5px;                  /* Độ dày thanh đứng rìa phải */
    background-color: #03254c;   /* Màu xanh đen đậm tạo độ tương phản cực đẹp */
}
</style>

<div class="card profile-sidebar border-0 shadow-sm">
    <div class="avatar-section">
        <div class="avatar-wrapper">
            <img src="<?= htmlspecialchars($_SESSION['avatar'] ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png'); ?>" alt="Avatar">
        </div>
        <div class="user-name">
            <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['user_name'] ?? 'Tên Tài Khoản'); ?>
        </div>
    </div>
    
    <div class="list-group list-group-flush pb-4" id="account-menu">
        <a href="#" class="list-group-item list-group-item-action active" data-target="my_posts">
            <i class="fa-solid fa-file-signature me-2"></i> Bài viết của tôi
        </a>
        
        <a href="#" class="list-group-item list-group-item-action" data-target="edit">
            <i class="fa-solid fa-user-gear me-2"></i> Thông tin tài khoản
        </a>
        
        <a href="#" class="list-group-item list-group-item-action" data-target="change_password">
            <i class="fa-solid fa-lock me-2"></i> Đổi mật khẩu
        </a>
        
        <a href="#" class="list-group-item list-group-item-action" data-target="saved">
            <i class="fa-regular fa-bookmark me-2"></i> Bài viết đã lưu
        </a>
    </div>
</div>
   <script src="Public/Client/Js/ProfileTab.js"></script>