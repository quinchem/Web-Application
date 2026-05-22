
 <?php
// 1. KHỞI TẠO SESSION (BẮT BUỘC)
// Phải đặt ở trên cùng để PHP có thể lưu và kiểm tra trạng thái đăng nhập của người dùng
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Lấy thông tin trang từ URL, mặc định là 'homepage'
$page = $_GET['page'] ?? 'homepage';


// 2. NHÚNG CÁC FILE ĐỊNH TUYẾN (ROUTES)
// Nạp các luồng xử lý tùy theo chức năng
require_once __DIR__ . '/Routes/Auth.php';  // Thêm dòng này để hệ thống nhận diện route xử lý Đăng nhập / Đăng xuất
require_once __DIR__ . '/Routes/Post.php';  
require_once __DIR__ . '/Routes/Client.php';
