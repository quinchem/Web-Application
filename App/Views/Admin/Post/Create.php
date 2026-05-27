<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Soạn thảo bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Briem+Hand:wght@400..700&family=Newsreader:opsz,wght@6..72,400;6..72,700;6..72,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/PostCreate.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/Profile.css">
    <style>
        html, body { height: 100%; overflow: hidden; margin: 0; padding: 0; }

        /* Override Bootstrap — bắt buộc dùng !important */
        .admin-layout {
            display: flex !important;
            height: 100vh !important;
            overflow: hidden !important;
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            flex: 0 0 300px !important;   /* không co, không giãn, cố định 300px */
            width: 300px !important;
            max-width: 300px !important;
        }
        .main-content {
            flex: 1 1 0% !important;      /* chiếm hết phần còn lại */
            min-width: 0 !important;       /* quan trọng: cho phép co lại */
            max-width: calc(100% - 300px) !important;
            height: 100vh;
            overflow-y: auto;
            padding: 0 !important;
            margin: 0 !important;
        }
        .content-inner {
            padding: 38px 48px !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        .profile-wrapper { padding: 16px 18px; }
        .admin-profile { padding: 14px 16px; gap: 12px; border-radius: 14px; }
        .admin-profile img { width: 40px; height: 40px; }
        .profile-info strong { font-size: 14px; margin-bottom: 6px; }
        .profile-info p { font-size: 11px; gap: 7px; }
        .profile-info i { font-size: 13px; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="Public/Admin/Js/Pages/PostCreate.js?v=<?= time() ?>" defer></script>
    <script src="Public/Admin/Js/Pages/Profile.js?v=<?= time() ?>" defer></script>
</head>
<body>
<div class="admin-layout">
    <?php require_once __DIR__ . '/../../Partials/Admin/Sidebar.php'; ?>

    <main class="main-content">
        <div class="topbar">
            <div class="breadcrumb">
                <a href="Admin_index.php?page=admin_posts">QUẢN LÝ BÀI VIẾT</a>
                <span>></span>
                <a href="Admin_index.php?page=admin_posts">BÀI VIẾT QUẢN TRỊ VIÊN</a>
                <span>></span>
                <span class="active">SOẠN THẢO BÀI VIẾT</span>
            </div>
        </div>

        <section class="content-inner">
            <h1>SOẠN THẢO BÀI VIẾT</h1>

            <form method="POST" action="Admin_index.php?page=store_post" enctype="multipart/form-data" id="postForm">

                <!-- TIÊU ĐỀ -->
                <div class="editor-card">
                    <div class="field-label">TIÊU ĐỀ BÀI VIẾT</div>
                    <input type="text" name="title" class="title-input"
                        placeholder="Nhập tiêu đề bài viết tại đây..." required>
                </div>

                <!-- TÓM TẮT -->
                <div class="editor-card">
                    <div class="editor-toolbar">
                        <button type="button" onclick="formatText('bold')"><b>B</b></button>
                        <button type="button" onclick="formatText('italic')"><i>I</i></button>
                        <button type="button" onclick="formatText('h1')">H1</button>
                        <button type="button" onclick="formatText('quote')">99</button>
                        <button type="button" onclick="formatText('link')"><i class="fa-solid fa-link"></i></button>
                        <button type="button" onclick="formatText('list')"><i class="fa-solid fa-list"></i></button>
                    </div>
                    <div class="field-label">TÓM TẮT NỘI DUNG</div>
                    <div class="editor-area" id="summaryEditor" contenteditable="true"
                        data-placeholder="Nhập tóm tắt nội dung bài viết..."></div>
                    <textarea name="summary" id="summaryInput" hidden></textarea>
                </div>

                <!-- ẢNH ĐẠI DIỆN -->
                <div class="editor-card">
                    <div class="field-label"><i class="fa-regular fa-image"></i> ẢNH ĐẠI DIỆN BÀI VIẾT</div>
                    <div class="thumbnail-upload" id="thumbnailZone">
                        <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*" hidden>
                        <div class="upload-placeholder" id="uploadPlaceholder"
                            onclick="document.getElementById('thumbnailInput').click()">
                            <i class="fa-regular fa-file-image"></i>
                            <p>Tải ảnh lên hoặc kéo thả vào đây</p>
                            <small>Kích thước khuyến dùng 1200x630px</small>
                        </div>
                        <img id="thumbnailPreview" src="" alt="" style="display:none;">
                    </div>
                </div>

                <!-- NỘI DUNG -->
                <div class="editor-card">
                    <div class="editor-toolbar">
                        <button type="button" onclick="formatContent('bold')"><b>B</b></button>
                        <button type="button" onclick="formatContent('italic')"><i>I</i></button>
                        <button type="button" onclick="formatContent('h1')">H1</button>
                        <button type="button" onclick="formatContent('quote')">99</button>
                        <button type="button" onclick="formatContent('link')"><i class="fa-solid fa-link"></i></button>
                        <button type="button" onclick="formatContent('list')"><i class="fa-solid fa-list"></i></button>
                        <button type="button" onclick="formatContent('image')"><i class="fa-regular fa-image"></i></button>
                    </div>
                    <div class="editor-area content-editor" id="contentEditor" contenteditable="true"
                        data-placeholder="Nhập nội dung bài viết chi tiết tại đây..."></div>
                    <textarea name="content" id="contentInput" hidden></textarea>
                </div>

                <!-- META: DANH MỤC, TAGS, NGÀY -->
                <?php
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
                <div class="meta-card">
                    <div class="meta-row">
                        <div class="meta-group">
                            <label>DANH MỤC</label>
                            <select name="parent_category" id="parentCatSelect" class="meta-select">
                                <option value="">Chọn danh mục</option>
                                <?php foreach ($catParents as $parentId => $parent): ?>
                                    <option value="<?= htmlspecialchars($parentId) ?>">
                                        <?= htmlspecialchars($parent['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="meta-group">
                            <label>TAGS BÀI VIẾT</label>
                            <div class="tags-input-wrapper">
                                <div class="tags-list" id="tagsList"></div>
                                <input type="text" id="tagInput" placeholder="+ THÊM TAG" class="tag-input-field">
                            </div>
                            <div id="tagsHidden"></div>
                        </div>
                    </div>

                    <div class="meta-row">
                        <div class="meta-group">
                            <label>DANH MỤC CON</label>
                            <select name="category_id" id="childCatSelect" class="meta-select" disabled style="opacity:0.5">
                                <option value="">Chọn danh mục con</option>
                                <?php foreach ($catParents as $parentId => $parent): ?>
                                    <?php if (!empty($catChildren[$parentId])): ?>
                                        <?php foreach ($catChildren[$parentId] as $child): ?>
                                            <option value="<?= htmlspecialchars($child['category_id']) ?>"
                                                    data-parent="<?= htmlspecialchars($parentId) ?>">
                                                <?= htmlspecialchars($child['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="meta-group">
                            <label>NGÀY XUẤT BẢN</label>
                            <div class="date-input-wrap">
                                <i class="fa-regular fa-calendar"></i>
                                <input type="datetime-local" name="publish_at" class="meta-date">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="action-bar">
                    <a href="Admin_index.php?page=admin_posts" class="btn-cancel">HỦY BỎ</a>
                    <button type="submit" name="action" value="draft" class="btn-draft">LƯU NHÁP</button>
                    <button type="submit" name="action" value="publish" class="btn-publish">ĐĂNG BÀI</button>
                </div>

            </form>

            <?php require_once __DIR__ . '/../../Partials/Admin/Footer.php'; ?>
        </section>
    </main>
</div>
</body>
</html>