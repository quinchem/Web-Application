<h1 align="center"> 
  <div>PHÁT TRIỂN ỨNG DỤNG WEB - NHÓM 1</div>
  <div>WEBSITE TIN TỨC KINH TẾ & CHÍNH TRỊ TOÀN CẦU - TRẠM TIN VIỆT</div>
</h1>

<h2>About The Project</h2>

Trạm Tin Việt là một nền tảng tin tức trực tuyến tập trung vào các lĩnh vực Kinh tế và Chính trị. Hệ thống cho phép người dùng theo dõi tin tức mới nhất, tìm kiếm nội dung theo danh mục, tương tác với bài viết và tham gia đóng góp nội dung thông qua cơ chế đăng bài.

Dự án được phát triển bằng PHP theo mô hình MVC (Model - View - Controller) kết hợp với lập trình hướng đối tượng (OOP), sử dụng TiDB Cloud làm hệ quản trị cơ sở dữ liệu, Cloudinary để lưu trữ hình ảnh và PHPMailer để xử lý các chức năng gửi email.

<h2>User Roles</h2>

<h3>Reader</h3>

<ul>
  <li>Đọc bài viết theo danh mục</li>
  <li>Tìm kiếm và lọc bài viết</li>
  <li>Bình luận bài viết</li>
  <li>Thích bài viết</li>
  <li>Lưu bài viết</li>
  <li>Quản lý tài khoản cá nhân</li>
  <li>Đăng, chỉnh sửa và quản lý bài viết cá nhân</li>
</ul>

<h3>Administrator</h3>

<ul>
  <li>Quản lý bài viết cá nhân</li>
  <li>Duyệt bài viết của người dùng</li>
  <li>Quản lý người dùng</li>
  <li>Khóa / Mở khóa tài khoản</li>
  <li>Quản lý nội dung hệ thống</li>
  <li>Theo dõi Dashboard thống kê</li>
</ul>

<h2>Tech Stack</h2>

| Layer | Technology |
|---------|-----------|
| Frontend | HTML5, CSS3, Bootstrap, JavaScript, jQuery |
| Backend | PHP (MVC, OOP) |
| Database | TiDB Cloud |
| Cloud Storage | Cloudinary |
| Email Service | PHPMailer |
| Data Communication | AJAX, JSON |
| Version Control | Git & GitHub |

<h2>Project Structure</h2>

```text
Web-Application/
│
├── .vscode/
│
├── App/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
│       ├── Admin/
│       ├── Client/
│       └── Partials/
│
├── Configs/
│
├── Public/
│   ├── Admin/
│   └── Client/
│
├── Repositories/
├── Routes/
├── vendor/
│
├── diagram/
│   ├── ERD
│   ├── Class Diagram
│   └── Use Case Diagram
│
├── .env
├── .gitignore
├── CA.pem
│
├── Index.php
├── Admin_index.php
│
├── composer.json
├── composer.lock
└── README.md
```

<h2>Installation</h2>

<h3>1. Clone Repository</h3>

```bash
git clone https://github.com/quinchem/Web-Application.git
```

<h3>2. Install Dependencies</h3>

```bash
composer install
```

<h3>3. Configure Environment Variables</h3>

Tạo file <code>.env</code> tại thư mục gốc của dự án và cấu hình theo mẫu bên dưới.

<h2>Environment Variables</h2>

```env
# Database Configuration
DB_HOST=your_tidb_host
DB_PORT=4000
DB_NAME=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Cloudinary Configuration
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret

# PHPMailer Configuration
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM=your_email@gmail.com
MAIL_FROM_NAME=Tram Tin Viet
```

<h3>4. Start Application</h3>

Yêu cầu môi trường:

<ul>
  <li>PHP 8+</li>
  <li>Composer</li>
  <li>Apache (XAMPP)</li>
  <li>TiDB Cloud Database</li>
</ul>

Sau khi cấu hình hoàn tất, truy cập:

```text
http://localhost/Web-Application
```

<h2>Team Members</h2>

| Name | GitHub |
|------|--------|
| Nguyễn Quỳnh Trâm (Nhóm trưởng) | [quinchem](https://github.com/quinchem) |
| Lâm Quỳnh Giang | [lamzangzzzzz](https://github.com/lamzangzzzzz) |
| Lê Đoan Thy | [ledoanthy-love](https://github.com/ledoanthy-love) |
| Hướng Ngọc Trâm | [huongngoctram](https://github.com/huongngoctram) |

<h2>License</h2>

This project was developed for educational purposes as part of the Web Application Development course at the University of Economics Ho Chi Minh City (UEH).
