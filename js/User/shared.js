// ============================================================
// shared.js — Inject Header & Footer (không cần server)
// ============================================================

const HEADER_HTML = `
<header class="header-container">
  <div class="container">
    <div class="header-top">
      <div class="header-meta">
        <span><i class="fas fa-calendar-alt"></i> Thứ Hai, 20/05/2024</span>
        <span><i class="fas fa-cloud-sun"></i> 30°C</span>
      </div>
      <a class="logo-text" href="Homepage.html">Trạm Tin Việt</a>
      <div class="auth-links">
        <a href="#">Đăng Ký</a>
        <span class="auth-sep">|</span>
        <a href="#">Đăng Nhập</a>
      </div>
    </div>
    <nav class="nav-bar">
      <ul class="menu-items">
        <li><a href="Homepage.html">Trang Chủ</a></li>
        <li><a href="#">Thời Sự</a></li>
        <li><a href="#">Kinh Tế</a></li>
        <li><a href="#">Tiện Ích</a></li>
      </ul>
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input class="search-input" type="text" placeholder="Tìm kiếm tin tức...">
      </div>
    </nav>
  </div>
</header>
`;

const FOOTER_HTML = `
<footer class="custom-footer">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4 mb-md-0">
        <a class="footer-logo" href="Homepage.html">Trạm Tin Việt</a>
        <p class="footer-desc">Kênh thông tin uy tín, chuyên sâu về Kinh tế, Chính trị và Xã hội.</p>
        <div class="footer-social">
          <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
        </div>
      </div>
      <div class="col-md-4 mb-4 mb-md-0">
        <div class="footer-title">Liên Hệ</div>
        <div class="footer-contact-item"><i class="fas fa-map-marker-alt"></i><span>123 Phố Huế, Hai Bà Trưng, Hà Nội</span></div>
        <div class="footer-contact-item"><i class="fas fa-phone"></i><span>1900 1234</span></div>
        <div class="footer-contact-item"><i class="fas fa-envelope"></i><span>toa-soan@tramtinviet.vn</span></div>
      </div>
      <div class="col-md-4">
        <div class="footer-title">Chính Sách</div>
        <ul class="footer-links">
          <li><a href="#">Điều khoản sử dụng</a></li>
          <li><a href="#">Bảo mật dữ liệu</a></li>
          <li><a href="#">Liên hệ quảng cáo</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom text-center">© 2024 Trạm Tin Việt.</div>
  </div>
</footer>
`;

// Inject vào trang — chạy được với file:// không cần server
(function () {
  const h = document.getElementById('header-placeholder');
  const f = document.getElementById('footer-placeholder');
  if (h) h.innerHTML = HEADER_HTML;
  if (f) f.innerHTML = FOOTER_HTML;

  // Active nav theo tên file hiện tại
  const page = location.pathname.split('/').pop() || 'Homepage.html';
  document.querySelectorAll('.menu-items a').forEach(a => a.classList.remove('active'));
  if (page === 'Homepage.html' || page === '') {
    const first = document.querySelector('.menu-items a');
    if (first) first.classList.add('active');
  }
})();