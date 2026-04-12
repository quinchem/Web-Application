// ============================================
// SLIDER LOGIC — Horizontal Slide
// ============================================
let currentSlide = 0;
const totalSlides = 5;
let autoSlideTimer;
let isAnimating = false;

function getSlides() { return document.querySelectorAll('.hero-slide'); }

function updateDots() {
  document.querySelectorAll('.hero-dots .dot').forEach((dot, i) => {
    dot.classList.toggle('active', i === currentSlide);
  });
}

function doSlide(fromIdx, toIdx, dir) {
  if (isAnimating) return;
  isAnimating = true;
  const slides = getSlides();
  const fromSlide = slides[fromIdx];
  const toSlide = slides[toIdx];

  // Position incoming slide off-screen in the correct direction
  toSlide.style.transition = 'none';
  toSlide.style.transform = dir > 0 ? 'translateX(100%)' : 'translateX(-100%)';
  toSlide.style.pointerEvents = 'none';
  toSlide.getBoundingClientRect(); // force reflow

  // Restore transitions
  toSlide.style.transition = '';
  fromSlide.style.transition = 'transform 0.55s cubic-bezier(0.4, 0, 0.2, 1)';

  // Slide both
  fromSlide.style.transform = dir > 0 ? 'translateX(-100%)' : 'translateX(100%)';
  toSlide.style.transform = 'translateX(0%)';
  toSlide.classList.add('active');

  setTimeout(() => {
    fromSlide.classList.remove('active');
    fromSlide.style.transform = '';
    fromSlide.style.transition = '';
    toSlide.style.pointerEvents = '';
    isAnimating = false;
  }, 560);

  updateDots();
}

function slideHero(dir) {
  const prev = currentSlide;
  currentSlide = (currentSlide + dir + totalSlides) % totalSlides;
  doSlide(prev, currentSlide, dir);
  resetAutoSlide();
}

function goToSlide(idx) {
  if (idx === currentSlide) return;
  const dir = idx > currentSlide ? 1 : -1;
  const prev = currentSlide;
  currentSlide = idx;
  doSlide(prev, currentSlide, dir);
  resetAutoSlide();
}

function resetAutoSlide() {
  clearInterval(autoSlideTimer);
  autoSlideTimer = setInterval(() => { slideHero(1); }, 5000);
}

// Init: slide 0 already active with translateX(0), rest at translateX(100%) via CSS default
autoSlideTimer = setInterval(() => { slideHero(1); }, 5000);

// Click on slide image area also navigates
document.getElementById('heroSlider').addEventListener('click', function(e) {
  // only if not clicking arrows or dots
  if (!e.target.closest('.hero-arrow') && !e.target.closest('.hero-dots') && !e.target.closest('.read-more')) {
    showArticle(currentSlide);
  }
});

// ============================================
// PAGE NAVIGATION
// ============================================
const articleData = [
  {
    title: 'Chiến lược kinh tế mới: Việt Nam hướng tới trung tâm công nghệ bán dẫn khu vực',
    cat: 'Kinh Tế · Công Nghệ',
    img: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1200',
    sapo: 'Trong bối cảnh cạnh tranh địa chiến lược ngày càng gay gắt, Việt Nam đang định vị mình như một cứ điểm sản xuất công nghệ cao không thể thiếu trong chuỗi cung ứng toàn cầu.',
    author: 'Nguyễn Văn Nam',
    time: '20/05/2024, 08:30',
    views: '12.543'
  },
  {
    title: 'Đẩy mạnh cải cách hành chính hướng tới chính phủ số toàn diện',
    cat: 'Chính Trị',
    img: 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?q=80&w=1200',
    sapo: 'Nghị quyết mới tập trung vào việc số hóa hoàn toàn các thủ tục hành chính cấp tỉnh và thành phố, giảm thiểu phiền hà cho người dân trong giai đoạn 2024-2030.',
    author: 'Nguyễn Văn Nam',
    time: '20/05/2024, 07:15',
    views: '8.214'
  },
  {
    title: 'Dòng tiền thông minh đang chảy vào nhóm cổ phiếu ngân hàng',
    cat: 'Kinh Tế · Chứng Khoán',
    img: 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?q=80&w=1200',
    sapo: 'Khối lượng giao dịch tăng đột biến tại các mã vốn hóa lớn giúp chỉ số ổn định và tạo đà bứt phá cho thị trường trong ngắn hạn.',
    author: 'Trần Thu Hằng',
    time: '20/05/2024, 06:00',
    views: '5.932'
  },
  {
    title: 'Tăng cường hợp tác quốc phòng đa phương bảo vệ chủ quyền',
    cat: 'Quân Sự',
    img: 'https://images.unsplash.com/photo-1540910419892-4a36d2c3266c?q=80&w=1200',
    sapo: 'Việt Nam cử đoàn đại biểu cấp cao tham gia diễn tập an ninh biển quốc tế, khẳng định cam kết bảo vệ chủ quyền biển đảo.',
    author: 'Lê Đức Anh',
    time: '19/05/2024, 21:00',
    views: '6.741'
  },
  {
    title: 'Chính sách an sinh xã hội mới cho công nhân khu công nghiệp',
    cat: 'Xã Hội',
    img: 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200',
    sapo: 'Đảm bảo chỗ ở và dịch vụ y tế cho hàng nghìn lao động tại các vùng kinh tế trọng điểm, góp phần ổn định xã hội và tăng năng suất lao động.',
    author: 'Phạm Thị Lan',
    time: '19/05/2024, 16:30',
    views: '9.105'
  }
];

function showArticle(idx) {
  const d = articleData[idx] || articleData[0];
  document.querySelector('.article-cat-label').textContent = d.cat;
  document.querySelector('.article-title').textContent = d.title;
  document.querySelector('.sapo-box').textContent = d.sapo;
  document.querySelector('.article-hero-img').src = d.img;
  document.querySelector('.article-meta-bar').innerHTML = `
    <span><i class="fas fa-user"></i> ${d.author}</span>
    <span><i class="fas fa-clock"></i> ${d.time}</span>
    <span><i class="fas fa-eye"></i> ${d.views} lượt đọc</span>
  `;
  showPage('article');
}

function showPage(page) {
  document.getElementById('page-home').classList.toggle('active', page === 'home');
  document.getElementById('page-article').classList.toggle('active', page === 'article');
  window.scrollTo({ top: 0, behavior: 'smooth' });

  // Update active nav
  document.querySelectorAll('.menu-items a').forEach(a => a.classList.remove('active'));
  if (page === 'home') document.querySelectorAll('.menu-items a')[0].classList.add('active');
}
