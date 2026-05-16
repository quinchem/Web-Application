<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

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


<main class="max-w-6xl mx-auto bg-white min-h-screen p-10 my-8 rounded-3xl">

    <!-- HERO SECTION -->
    <?php if (isset($heroPost) && $heroPost): ?>
        <section class="relative h-[500px] rounded-2xl overflow-hidden mb-16 group shadow-lg">
            <img src="<?= $heroPost['thumbnail_url'] ?? '' ?>"
                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-[#003049] via-transparent"></div>
            <div class="absolute inset-0 flex flex-col justify-center px-16 md:px-24 text-white max-w-4xl">
            
            <div>
                <span class="bg-[#b90c17] px-3 py-1.5 text-[11px] font-sans font-bold uppercase tracking-wider mb-6 inline-block">
                    Tiêu điểm tuần qua
                </span>
            </div>
            
            <h1 class="text-4xl md:text-4xl font-serif font-bold mb-6 leading-tight max-w-3xl">
                <?= htmlspecialchars($heroPost['title'] ?? '') ?>
            </h1>
            
            <p class="text-white/60 text-base md:text-lg font-light leading-relaxed max-w-2xl mb-8 line-clamp-4">
                <?= htmlspecialchars($heroPost['summary'] ?? '') ?>
            </p>
            
            <div class="flex items-center gap-2 text-sm font-medium border-b border-white/40 w-fit pb-1 hover:border-white transition-colors cursor-pointer">
                <span>Đọc toàn bộ bài viết</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </div>
        </section>
    <?php endif; ?>

    <!-- THỜI SỰ SECTION -->
    <section class="mb-20">
        <div class="flex justify-between items-end border-b-2 border-[#003049] mb-12 pb-3">
            <h2 class="text-3xl barlow font-bold text-[#003049]">Thời sự</h2>
            <a href="?page=category&name=Thời sự"
                class="text-[#b90c17] text-[14px] font-barlow font-bold uppercase tracking-widest hover:opacity-70 transition">Xem
                thêm</a>
        </div>

        <div class="grid grid-cols-12 gap-12">
            <?php if (!empty($thoiSu)):
                $first = array_shift($thoiSu); ?>
                <div class="col-span-7">
                    <img src="<?= ($first['thumbnail_url'] ?? '') ?>"
                        class="w-full h-[400px] object-cover rounded-2xl mb-6 shadow-md">
                    <div class="flex items-center gap-3 mt-3 mb-4 text-sm text-gray-600">
                        <span
                            class="text-[14px] font-barlow font-bold uppercase text-[#b90c17]"><?= htmlspecialchars($first['category_name'] ?? '') ?></span>

                    </div>
                    <h3 class="text-3xl font-serif font-bold hover:text-[#b90c17] transition cursor-pointer">
                        <?= htmlspecialchars($first['title'] ?? '') ?>
                    </h3>

                    <p class="text-gray-500 mt-4 font-light"><?= htmlspecialchars($first['summary'] ?? '') ?></p>
                    <div class="flex items-center gap-2 mt-3 text-[12px] font-bold uppercase text-gray-400">
                        <span><?= isset($first['published_at']) ? date('d/m/Y', strtotime($first['published_at'])) : '' ?></span>
                        <span class="text-[20px] leading-none select-none">·</span>
                        <span><?= htmlspecialchars($first['author_name'] ?? '') ?></span>
                    </div>
                </div>
                <div class="col-span-5 space-y-8">
                    <?php foreach ($thoiSu as $p): ?>
                        <div class="border-b border-gray-100 pb-6 last:border-b-0">
                            <span
                                class="text-[12px] font-barlow font-bold uppercase text-[#b90c17] block mb-2"><?= htmlspecialchars($p['category_name'] ?? '') ?></span>
                            <h4
                                class="font-serif font-bold text-xl hover:text-[#b90c17] cursor-pointer transition line-clamp-2">
                                <?= htmlspecialchars($p['title'] ?? '') ?>
                            </h4>
                            <p class="text-gray-500 mt-4 font-light line-clamp-3"><?= htmlspecialchars($p['summary'] ?? '') ?>
                            </p>
                            <div class="flex items-center gap-2 mt-3 text-[11px] font-bold uppercase text-gray-400">
                                <span><?= isset($p['published_at']) ? date('d/m/Y', strtotime($p['published_at'])) : '' ?></span>
                                <span class="text-[20px] leading-none select-none">·</span>
                                <span><?= htmlspecialchars($p['author_name'] ?? '') ?></span>
                            </div>
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
            <a href="?page=category&name=Kinh tế"
                class="text-[#b90c17] text-[14px] font-barlow font-bold uppercase tracking-widest hover:opacity-70 transition">Xem
                thêm</a>
        </div>

        <div class="grid grid-cols-12 gap-12">
            <?php if (!empty($kinhTe)):
                $first = array_shift($kinhTe); ?>
                <div class="col-span-7">
                    <img src="<?= ($first['thumbnail_url'] ?? '') ?>"
                        class="w-full h-[400px] object-cover rounded-2xl mb-6 shadow-md">
                    <div class="flex items-center gap-3 mt-3 mb-4 text-sm text-gray-600">
                        <span
                            class="text-[14px] font-barlow font-bold uppercase text-[#b90c17]"><?= htmlspecialchars($first['category_name'] ?? '') ?></span>

                    </div>
                    <h3 class="text-3xl font-serif font-bold hover:text-[#b90c17] transition cursor-pointer">
                        <?= htmlspecialchars($first['title'] ?? '') ?>
                    </h3>

                    <p class="text-gray-500 mt-4 font-light"><?= htmlspecialchars($first['summary'] ?? '') ?></p>
                    <div class="flex items-center gap-2 mt-3 text-[12px] font-bold uppercase text-gray-400">
                        <span><?= isset($first['published_at']) ? date('d/m/Y', strtotime($first['published_at'])) : '' ?></span>
                        <span class="text-[20px] leading-none select-none">·</span>
                        <span><?= htmlspecialchars($first['author_name'] ?? '') ?></span>
                    </div>
                </div>
                <div class="col-span-5 space-y-8">
                    <?php foreach ($kinhTe as $p): ?>
                        <div class="border-b border-gray-100 pb-6 last:border-b-0">
                            <span
                                class="text-[12px] font-barlow font-bold uppercase text-[#b90c17] block mb-2"><?= htmlspecialchars($p['category_name'] ?? '') ?></span>
                            <h4
                                class="font-serif font-bold text-xl hover:text-[#b90c17] cursor-pointer transition line-clamp-2">
                                <?= htmlspecialchars($p['title'] ?? '') ?>
                            </h4>
                            <p class="text-gray-500 mt-4 font-light line-clamp-3"><?= htmlspecialchars($p['summary'] ?? '') ?>
                            </p>
                            <div class="flex items-center gap-2 mt-3 text-[11px] font-bold uppercase text-gray-400">
                                <span><?= isset($p['published_at']) ? date('d/m/Y', strtotime($p['published_at'])) : '' ?></span>
                                <span class="text-[20px] leading-none select-none">·</span>
                                <span><?= htmlspecialchars($p['author_name'] ?? '') ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>



</main>

<?php include __DIR__ . '/../Partials/Client/Footer.php'; ?>