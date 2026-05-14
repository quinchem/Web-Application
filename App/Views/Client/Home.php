<?php include __DIR__ . '/../Partials/Client/Header.php'; ?>

<main class="max-w-6xl mx-auto bg-white min-h-screen p-10 my-8 rounded-3xl">
    
    <?php if (isset($heroPost) && $heroPost): ?>
    <section class="relative h-[500px] rounded-3xl overflow-hidden mb-16 group shadow-lg">
        <img src="<?= $heroPost['thumbnail_URL'] ?>" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-[#003049] via-transparent"></div>
        <div class="absolute bottom-0 p-12 text-white">
            <span class="bg-[#b90c17] px-3 py-1 text-[9px] font-bold uppercase tracking-widest mb-4 inline-block">Tiêu điểm</span>
            <h1 class="text-5xl font-serif font-bold mb-6 leading-tight"><?= htmlspecialchars($heroPost['title']) ?></h1>
            <p class="text-white/80 text-lg max-w-3xl font-light line-clamp-2"><?= htmlspecialchars($heroPost['summary']) ?></p>
        </div>
    </section>
    <?php endif; ?>

    <section class="mb-20">
        <div class="flex justify-between items-end border-b-2 border-[#003049] mb-12 pb-3">
            <h2 class="text-3xl font-serif font-bold text-[#003049]">Thời sự</h2>
            <a href="#" class="text-[#b90c17] text-[11px] font-bold uppercase tracking-widest">Xem thêm</a>
        </div>
        
        <div class="grid grid-cols-12 gap-12">
            <?php if (!empty($thoiSu)): 
                $first = array_shift($thoiSu); ?>
                <div class="col-span-7">
                    <img src="<?= $first['thumbnail_URL'] ?>" class="w-full h-[400px] object-cover rounded-2xl mb-6 shadow-md">
                    <h3 class="text-3xl font-serif font-bold"><?= htmlspecialchars($first['title']) ?></h3>
                    <p class="text-gray-500 mt-4 font-light"><?= htmlspecialchars($first['summary']) ?></p>
                </div>
                <div class="col-span-5 space-y-8">
                    <?php foreach ($thoiSu as $p): ?>
                        <div class="border-b border-gray-100 pb-6">
                            <h4 class="font-serif font-bold text-xl hover:text-[#b90c17] cursor-pointer transition"><?= htmlspecialchars($p['title']) ?></h4>
                            <span class="text-[10px] text-gray-400 font-bold uppercase mt-2 block"><?= date('d/m/Y', strtotime($p['published_at'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../Partials/Client/Footer.php'; ?>