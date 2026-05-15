<?php 
/**
 * Home.php - Trang chủ
 * 
 * Hiển thị trang chủ với:
 * - Hero section: bài viết nổi bật (trending)
 * - Các phần danh mục: Thời sự, Kinh tế, Chính trị, v.v.
 * 
 * Biến được truyền từ ClientController:
 * - $heroPost: bài viết nổi bật để hiển thị ở đầu
 * - $thoiSu: mảng bài viết danh mục "Thời sự"
 * - $kinhTe: mảng bài viết danh mục "Kinh tế"
 * - $chiTriThi: mảng bài viết danh mục "Chính trị"
 */
include __DIR__ . '/../Partials/Client/Header.php'; 
?>

<?php
  // Chèn vào dòng 1 của file Home.php để kiểm tra biến
  if (empty($thoiSu)) {
      echo "";
  } else {
      echo "";
  }
?>

<main class="max-w-6xl mx-auto bg-white min-h-screen p-10 my-8 rounded-3xl">
    
    <!-- HERO SECTION -->
    <?php if (isset($heroPost) && $heroPost): ?>
    <section class="relative h-[500px] rounded-3xl overflow-hidden mb-16 group shadow-lg">
        <img src="<?= htmlspecialchars($heroPost['thumbnail_URL'] ?? '') ?>" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-[#003049] via-transparent"></div>
        <div class="absolute bottom-0 p-12 text-white">
            <span class="bg-[#b90c17] px-3 py-1 text-[9px] font-bold uppercase tracking-widest mb-4 inline-block">Tiêu điểm</span>
            <h1 class="text-5xl font-serif font-bold mb-6 leading-tight"><?= htmlspecialchars($heroPost['title'] ?? '') ?></h1>
            <p class="text-white/80 text-lg max-w-3xl font-light line-clamp-2"><?= htmlspecialchars($heroPost['summary'] ?? '') ?></p>
        </div>
    </section>
    <?php endif; ?>

    <!-- THỜI SỰ SECTION -->
    <section class="mb-20">
        <div class="flex justify-between items-end border-b-2 border-[#003049] mb-12 pb-3">
            <h2 class="text-3xl barlow font-bold text-[#003049]">Thời sự</h2>
            <a href="?page=category&name=Thời sự" class="text-[#b90c17] text-[11px] font-bold uppercase tracking-widest hover:opacity-70 transition">Xem thêm</a>
        </div>
        
        <div class="grid grid-cols-12 gap-12">
            <?php if (!empty($thoiSu)): 
                $first = array_shift($thoiSu); ?>
                <div class="col-span-7">
                    <img src="<?= htmlspecialchars($first['thumbnail_URL'] ?? '') ?>" class="w-full h-[400px] object-cover rounded-2xl mb-6 shadow-md">
                    <h3 class="text-3xl font-serif font-bold hover:text-[#b90c17] transition cursor-pointer"><?= htmlspecialchars($first['title'] ?? '') ?></h3>
                    <p class="text-gray-500 mt-4 font-light"><?= htmlspecialchars($first['summary'] ?? '') ?></p>
                    <span class="text-[10px] text-gray-400 font-bold uppercase mt-3 block"><?= isset($first['published_at']) ? date('d/m/Y', strtotime($first['published_at'])) : '' ?></span>
                </div>
                <div class="col-span-5 space-y-8">
                    <?php foreach ($thoiSu as $p): ?>
                        <div class="border-b border-gray-100 pb-6 last:border-b-0">
                            <h4 class="font-serif font-bold text-xl hover:text-[#b90c17] cursor-pointer transition line-clamp-2"><?= htmlspecialchars($p['title'] ?? '') ?></h4>
                            <span class="text-[10px] text-gray-400 font-bold uppercase mt-2 block"><?= isset($p['published_at']) ? date('d/m/Y', strtotime($p['published_at'])) : '' ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- KINH TẾ SECTION -->
    <section class="mb-20">
        <div class="flex justify-between items-end border-b-2 border-[#003049] mb-12 pb-3">
            <h2 class="text-3xl barlow font-bold text-[#003049]">Kinh tế</h2>
            <a href="?page=category&name=Kinh tế" class="text-[#b90c17] text-[11px] font-bold uppercase tracking-widest hover:opacity-70 transition">Xem thêm</a>
        </div>
        
        <div class="grid grid-cols-12 gap-12">
            <?php if (!empty($kinhTe)): 
                $first = array_shift($kinhTe); ?>
                <div class="col-span-7">
                    <img src="<?= htmlspecialchars($first['thumbnail_URL'] ?? '') ?>" class="w-full h-[400px] object-cover rounded-2xl mb-6 shadow-md">
                    <h3 class="text-3xl font-serif font-bold hover:text-[#b90c17] transition cursor-pointer"><?= htmlspecialchars($first['title'] ?? '') ?></h3>
                    <p class="text-gray-500 mt-4 font-light"><?= htmlspecialchars($first['summary'] ?? '') ?></p>
                    <span class="text-[10px] text-gray-400 font-bold uppercase mt-3 block"><?= isset($first['published_at']) ? date('d/m/Y', strtotime($first['published_at'])) : '' ?></span>
                </div>
                <div class="col-span-5 space-y-8">
                    <?php foreach ($kinhTe as $p): ?>
                        <div class="border-b border-gray-100 pb-6 last:border-b-0">
                            <h4 class="font-serif font-bold text-xl hover:text-[#b90c17] cursor-pointer transition line-clamp-2"><?= htmlspecialchars($p['title'] ?? '') ?></h4>
                            <span class="text-[10px] text-gray-400 font-bold uppercase mt-2 block"><?= isset($p['published_at']) ? date('d/m/Y', strtotime($p['published_at'])) : '' ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>



</main>

<?php include __DIR__ . '/../Partials/Client/Footer.php'; ?>