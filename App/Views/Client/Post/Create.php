<?php
/**
 * App/Views/Client/Post/Create.php
 * Trang soạn thảo bài viết — layout: sidebar profile trái + form chính phải
 */
require_once __DIR__ . '/../../Partials/Client/Header.php';

$defaultAvatar   = 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
$usernameSession = $_SESSION['user_name'] ?? '';
$avatar          = $_SESSION['avatar']    ?? $defaultAvatar;

// Flash messages
$successMsg = $_SESSION['success'] ?? null;
$errorMsg   = $_SESSION['error']   ?? null;
unset($_SESSION['success'], $_SESSION['error']);

// Phân loại danh mục cha / con
$catParents  = [];
$catChildren = [];
foreach (($categories ?? []) as $cat) {
    if (empty($cat['parent_id'])) {
        $catParents[$cat['category_id']] = $cat;
    } else {
        $catChildren[$cat['parent_id']][] = $cat;
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800;900&family=Montserrat:ital,wght@0,400;0,500;0,700;1,400&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,700;0,6..72,800;1,6..72,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="Public/Client/Css/clientPostCreate.css">

<main id="page-post-create">
    <div class="container">

        <div class="create-post-wrapper">

            <!-- ══════════════════════════════
                 SIDEBAR TRÁI — PROFILE NAV
            ══════════════════════════════ -->
            <aside class="profile-sidebar">

                <!-- Avatar + tên -->
                <div class="sidebar-avatar-block">
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar">
                    <div class="username"><?= htmlspecialchars($usernameSession ?: 'Tác giả') ?></div>
                </div>

                <!-- Nav links -->
                <nav class="sidebar-nav">
                    <a href="index.php?page=my_posts" class="active">
                        <i class="fa-regular fa-file-lines"></i>
                        Bài viết của tôi
                    </a>
                    <a href="index.php?page=profile">
                        <i class="fa-regular fa-user"></i>
                        Thông tin tài khoản
                    </a>
                    <a href="index.php?page=change_password">
                        <i class="fa-solid fa-rotate-left"></i>
                        Đổi mật khẩu
                    </a>
                    <a href="index.php?page=saved_posts">
                        <i class="fa-regular fa-bookmark"></i>
                        Bài viết đã lưu
                    </a>
                </nav>

            </aside><!-- /sidebar -->


            <!-- ══════════════════════════════
                 NỘI DUNG CHÍNH
            ══════════════════════════════ -->
            <div class="create-post-main">

                <!-- Tiêu đề trang -->
                <div class="create-post-heading">
                    <h1>Soạn thảo bài viết</h1>
                    <div class="subtitle">Soạn thảo nội dung bài đăng</div>
                </div>

                <!-- Flash messages -->
                <?php if ($successMsg): ?>
                    <div class="pc-alert pc-alert-success mt-3">
                        <i class="fa-solid fa-circle-check"></i>
                        <?= htmlspecialchars($successMsg) ?>
                    </div>
                <?php endif; ?>
                <?php if ($errorMsg): ?>
                    <div class="pc-alert pc-alert-error mt-3">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?= htmlspecialchars($errorMsg) ?>
                    </div>
                <?php endif; ?>

                <!-- Label đếm bài -->
                <div class="post-count-label">Bài đăng nhập (1)</div>

                <form method="POST" action="index.php?page=client_store_post"
                      enctype="multipart/form-data" id="pcPostForm">

                    <!-- ── CARD 1: TIÊU ĐỀ + TÓM TẮT ── -->
                    <div class="pc-card">

                        <!-- Tiêu đề -->
                        <input type="text"
                               name="title"
                               id="pcTitleInput"
                               class="pc-title-input"
                               placeholder="Nhập tiêu đề bài viết..."
                               autocomplete="off"
                               required>

                        <div class="pc-title-divider"></div>

                        <!-- Tóm tắt -->
                        <div class="pc-summary-label">Tóm tắt nội dung</div>
                        <textarea name="summary"
                                  id="pcSummaryInput"
                                  class="pc-summary-textarea"
                                  placeholder="Nhập một đoạn tóm tắt ngắn để thu hút người đọc..."
                                  rows="3"></textarea>

                    </div><!-- /card tiêu đề -->


                    <!-- ── CARD 2: DANH MỤC + TAG + TRẠNG THÁI ── -->
                    <div class="pc-card">

                        <!-- Danh mục cha + con (2 cột) -->
                        <div class="pc-meta-row">

                            <div class="pc-meta-group">
                                <label for="pcParentCat">Danh mục</label>
                                <div class="pc-select-wrap">
                                    <select name="parent_category"
                                            id="pcParentCat"
                                            class="pc-select">
                                        <option value="">Chọn danh mục</option>
                                        <?php foreach ($catParents as $parentId => $parent): ?>
                                            <option value="<?= htmlspecialchars($parentId) ?>">
                                                <?= htmlspecialchars($parent['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="pc-meta-group">
                                <label for="pcChildCat">Danh mục con</label>
                                <div class="pc-select-wrap">
                                    <select name="category_id"
                                            id="pcChildCat"
                                            class="pc-select"
                                            disabled>
                                        <option value="">Chọn danh mục con</option>
                                        <?php foreach ($catParents as $parentId => $parent): ?>
                                            <?php if (!empty($catChildren[$parentId])): ?>
                                                <?php foreach ($catChildren[$parentId] as $child): ?>
                                                    <option value="<?= htmlspecialchars($child['category_id']) ?>"
                                                            data-parent="<?= htmlspecialchars($parentId) ?>"
                                                            style="display:none;">
                                                        <?= htmlspecialchars($child['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                        </div><!-- /pc-meta-row -->

                        <!-- Tags -->
                        <div class="pc-tag-row">
                            <div class="pc-field-title">Tag bài viết</div>
                            <div class="pc-tag-label-group">
                                <span id="pcTagsList"></span>
                                <input type="text"
                                       id="pcTagInput"
                                       class="pc-tag-input-inline"
                                       placeholder="Nhập tag..."
                                       style="display:none;">
                            </div>
                            <button type="button" id="pcAddTagBtn" class="pc-add-tag-btn">
                                + Thêm tag
                            </button>
                        </div>
                        <div id="pcTagsHidden"></div>

                        <!-- Trạng thái -->
                        <div class="pc-status-row">
                            <span class="pc-field-title">Trạng thái</span>
                            <span class="pc-status-badge" id="pcStatusBadge">Bản nháp</span>
                        </div>

                    </div><!-- /card meta -->


                    <!-- ── CARD 3: ẢNH ĐẠI DIỆN ── -->
                    <div class="pc-card">
                        <div class="pc-card-label">
                            <i class="fa-regular fa-image"></i>
                            Ảnh đại diện bài viết
                        </div>

                        <div class="pc-thumbnail-zone" id="pcThumbnailZone">
                            <input type="file"
                                   name="thumbnail"
                                   id="pcThumbnailInput"
                                   accept="image/*"
                                   hidden>

                            <div class="pc-upload-placeholder" id="pcUploadPlaceholder">
                                <div class="upload-icon">
                                    <i class="fa-regular fa-file-image"></i>
                                </div>
                                <p>Tải ảnh lên hoặc kéo thả vào đây</p>
                                <small>Kích thước khuyến dùng 1200×630px</small>
                            </div>

                            <img id="pcThumbnailPreview" src="" alt="" style="display:none;">
                        </div>

                        <button type="button"
                                id="pcRemoveThumbBtn"
                                class="pc-remove-thumb-btn"
                                onclick="pcRemoveThumbnail()">
                            <i class="fa-solid fa-xmark me-1"></i> Xoá ảnh
                        </button>
                    </div><!-- /card ảnh -->


                    <!-- ── CARD 4: NỘI DUNG ── -->
                    <div class="pc-card">

                        <!-- Toolbar -->
                        <div class="pc-content-toolbar">
                            <button type="button" onclick="pcFormat('bold')"
                                    title="In đậm"><b>B</b></button>
                            <button type="button" onclick="pcFormat('italic')"
                                    title="In nghiêng"><i>I</i></button>
                            <button type="button" onclick="pcFormat('underline')"
                                    title="Gạch chân"><u>T</u></button>
                            <button type="button" onclick="pcFormat('formatBlock','blockquote')"
                                    title="Trích dẫn" style="font-size:.75rem;">99</button>
                            <div class="tb-sep"></div>
                            <button type="button" onclick="pcFormat('insertUnorderedList')"
                                    title="Danh sách"><i class="fa-solid fa-list-ul"></i></button>
                            <div class="tb-sep"></div>
                            <button type="button" onclick="pcFormat('createLink')"
                                    title="Chèn link"><i class="fa-solid fa-link"></i></button>
                            <button type="button" onclick="pcInsertImage()"
                                    title="Chèn ảnh"><i class="fa-regular fa-image"></i></button>
                            <button type="button" onclick="pcFormat('justifyFull')"
                                    title="Căn đều"><i class="fa-solid fa-align-justify"></i></button>
                        </div>

                        <!-- Vùng soạn thảo -->
                        <div class="pc-editor"
                             id="pcContentEditor"
                             contenteditable="true"
                             data-placeholder="Bắt đầu kể câu chuyện của bạn tại đây..."></div>

                        <textarea name="content" id="pcContentInput" hidden></textarea>

                        <div class="pc-word-count">
                            <span id="pcWordCount">0</span> từ
                        </div>

                    </div><!-- /card nội dung -->


                    <!-- ── ACTION BUTTONS ── -->
                    <div class="pc-action-bar">
                        <a href="index.php?page=my_posts"
                           class="pc-btn-cancel">Huỷ bản nháp</a>

                        <button type="submit"
                                name="action"
                                value="draft"
                                class="pc-btn-draft"
                                onclick="document.getElementById('pcStatusBadge').textContent='Bản nháp'">
                            Lưu bản nháp
                        </button>

                        <button type="submit"
                                name="action"
                                value="publish"
                                class="pc-btn-publish"
                                onclick="document.getElementById('pcStatusBadge').textContent='Chờ duyệt'">
                            Đăng bài
                        </button>
                    </div>

                </form><!-- /pcPostForm -->

            </div><!-- /create-post-main -->

        </div><!-- /create-post-wrapper -->

    </div><!-- /container -->
</main>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="Public/Client/Js/clientPostCreate.js"></script>

<?php include __DIR__ . '/../../Partials/Client/Footer.php'; ?>