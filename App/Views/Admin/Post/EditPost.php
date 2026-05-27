<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa bài viết - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800;900&family=Newsreader:opsz,wght@6..72,400;6..72,700;6..72,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/PostAdmin.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/EditPost.css">
    <link rel="stylesheet" href="Public/Admin/Css/Pages/Profile.css">
    <style>
        html, body { height: 100%; overflow: hidden; }
        .admin-layout { height: 100vh; overflow: hidden; }
        .sidebar { position: sticky; top: 0; height: 100vh; overflow-y: auto; flex-shrink: 0; }
        .main-content { height: 100vh; overflow-y: auto; flex: 1; }
        .profile-wrapper { padding: 16px 18px; }
        .admin-profile { padding: 14px 16px; gap: 12px; border-radius: 14px; }
        .admin-profile img { width: 40px; height: 40px; }
        .profile-info strong { font-size: 14px; margin-bottom: 6px; }
        .profile-info p { font-size: 11px; gap: 7px; }
        .profile-info i { font-size: 13px; }
    </style>
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
                <span class="active">CHỈNH SỬA BÀI VIẾT</span>
            </div>
        </div>

        <section class="content-inner">
            <div class="page-header">
                <h1>CHỈNH SỬA BÀI VIẾT</h1>
            </div>

            <?php if (!empty($_GET['success'])): ?>
                <div class="alert alert-success mb-3" style="border-radius:12px;font-weight:600;">
                    ✅ Lưu thay đổi thành công!
                </div>
            <?php endif; ?>

            <?php
            // Tách parent/child từ $categories
            $allParents  = [];
            $allChildren = [];
            foreach (($categories ?? []) as $cat) {
                if (empty($cat['parent_id'])) {
                    $allParents[$cat['category_id']] = $cat;
                } else {
                    $allChildren[] = $cat;
                }
            }

            // Xác định parent hiện tại của bài viết
            $currentCatId    = $post['category_id'] ?? '';
            $currentParentId = '';
            foreach ($allChildren as $child) {
                if ((string)$child['category_id'] === (string)$currentCatId) {
                    $currentParentId = $child['parent_id'];
                    break;
                }
            }
            // Nếu không tìm thấy trong children → chính nó là parent
            if (!$currentParentId && isset($allParents[$currentCatId])) {
                $currentParentId = $currentCatId;
            }
            ?>

            <form method="POST" action="Admin_index.php?page=update_post" enctype="multipart/form-data" id="editPostForm">
                <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['post_id'] ?? '') ?>">

                <!-- ── TIÊU ĐỀ ── -->
                <div class="ep-card">
                    <label class="ep-section-label">TIÊU ĐỀ BÀI VIẾT</label>
                    <input type="text" name="title" class="ep-title-input"
                           value="<?= htmlspecialchars($post['title'] ?? '') ?>"
                           placeholder="Nhập tiêu đề bài viết...">
                </div>

                <!-- ── TÓM TẮT ── -->
                <div class="ep-card">
                    <div class="ep-toolbar">
                        <button type="button" onclick="epFmt('bold')" title="Bold"><b>B</b></button>
                        <button type="button" onclick="epFmt('italic')" title="Italic"><i>I</i></button>
                        <button type="button" onclick="epFmt('formatBlock','h1')" title="H1">H1</button>
                        <button type="button" onclick="epFmt('insertHTML','&ldquo;&rdquo;')" title="Quote">99</button>
                        <button type="button" onclick="epInsertLink()" title="Link"><i class="fa-solid fa-link"></i></button>
                        <button type="button" onclick="epFmt('insertUnorderedList')" title="List"><i class="fa-solid fa-list"></i></button>
                    </div>
                    <div class="ep-summary-label">TÓM TẮT NỘI DUNG</div>
                    <div class="ep-summary-editor" id="summaryEditor"
     contenteditable="true"><?= preg_replace('/\s*color\s*:\s*[^;"\'>]+[;]?/i', '', $post['summary'] ?? '') ?></div>
                    <textarea name="summary" id="summaryHidden" style="display:none"></textarea>
                </div>

                <!-- ── ẢNH ĐẠI DIỆN ── -->
                <div class="ep-card">
                    <label class="ep-section-label">
                        <i class="fa-regular fa-image"></i> ẢNH ĐẠI DIỆN BÀI VIẾT
                    </label>
                    <div class="ep-thumbnail-area" id="thumbnailArea">
                        <?php if (!empty($post['thumbnail_URL'])): ?>
                            <img src="<?= htmlspecialchars($post['thumbnail_URL']) ?>"
                                 id="thumbnailPreview" class="ep-thumbnail-preview" alt="Thumbnail">
                        <?php else: ?>
                            <div class="ep-thumbnail-placeholder" id="thumbnailPlaceholder">
                                <i class="fa-regular fa-image"></i>
                                <span>Nhấp để chọn ảnh đại diện</span>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*" style="display:none">
                    </div>
                </div>

                <!-- ── NỘI DUNG CHÍNH ── -->
                <div class="ep-card">
                    <div class="ep-toolbar">
                        <button type="button" onclick="epFmt('bold')" title="Bold"><b>B</b></button>
                        <button type="button" onclick="epFmt('italic')" title="Italic"><i>I</i></button>
                        <button type="button" onclick="epFmt('formatBlock','h1')" title="H1">H1</button>
                        <button type="button" onclick="epFmt('insertHTML','&ldquo;&rdquo;')" title="Quote">99</button>
                        <button type="button" onclick="epInsertLink()" title="Link"><i class="fa-solid fa-link"></i></button>
                        <button type="button" onclick="epFmt('insertUnorderedList')" title="List"><i class="fa-solid fa-list"></i></button>
                        <button type="button" onclick="epInsertImage()" title="Chèn ảnh"><i class="fa-regular fa-image"></i></button>
                    </div>
                    <div class="ep-content-editor" id="contentEditor"
                         contenteditable="true"><?= $post['content'] ?? '' ?></div>
                    <textarea name="content" id="contentHidden" style="display:none"></textarea>
                </div>

                <!-- ── META: DANH MỤC + TAGS + NGÀY ── -->
                <div class="ep-card ep-meta-card">
                    <div class="ep-meta-grid">

                        <!-- Danh mục cha -->
                        <div class="ep-meta-group">
                            <label class="ep-meta-label">DANH MỤC</label>
                            <div class="ep-select-wrapper">
                                <select name="category_id" id="parentCatSelect" class="ep-select"
                                        onchange="epFilterSubCats()">
                                    <?php foreach ($allParents as $pId => $parent): ?>
                                        <option value="<?= htmlspecialchars($pId) ?>"
                                            <?= (string)$pId === (string)$currentParentId ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($parent['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fa-solid fa-chevron-down ep-select-icon"></i>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="ep-meta-group">
                            <label class="ep-meta-label">TAGS BÀI VIẾT</label>
                            <div class="ep-tags-wrapper" id="tagsWrapper">
                                <?php foreach (($tags ?? []) as $tag): 
                                    $tagVal = is_array($tag) ? ($tag['slug'] ?? $tag['name'] ?? '') : $tag;
                                ?>
                                    <span class="ep-tag-chip">
                                        <?= htmlspecialchars($tagVal) ?>
                                        <input type="hidden" name="tags[]" value="<?= htmlspecialchars($tagVal) ?>">
                                        <i class="fa-solid fa-xmark" onclick="epRemoveTag(this)"></i>
                                    </span>
                                <?php endforeach; ?>
                                <input type="text" class="ep-tag-input" id="tagInput"
                                       placeholder="+ THÊM TAG"
                                       onkeydown="epAddTag(event)">
                            </div>
                        </div>

                        <!-- Danh mục con -->
                        <div class="ep-meta-group">
                            <label class="ep-meta-label">DANH MỤC CON</label>
                            <div class="ep-select-wrapper">
                                <select name="sub_category_id" id="subCatSelect" class="ep-select">
                                    <?php foreach ($allChildren as $child): ?>
                                        <option value="<?= htmlspecialchars($child['category_id']) ?>"
                                                data-parent="<?= htmlspecialchars($child['parent_id']) ?>"
                                            <?= (string)$child['category_id'] === (string)$currentCatId ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($child['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fa-solid fa-chevron-down ep-select-icon"></i>
                            </div>
                        </div>

                        <!-- Ngày xuất bản -->
                        <div class="ep-meta-group">
                            <label class="ep-meta-label">NGÀY XUẤT BẢN</label>
                            <div class="ep-date-wrapper">
                                <i class="fa-regular fa-calendar"></i>
                                <input type="datetime-local" name="published_at" class="ep-date-input"
                                       value="<?= !empty($post['published_at'])
                                           ? date('Y-m-d\TH:i', strtotime($post['published_at']))
                                           : date('Y-m-d\TH:i') ?>">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ── FOOTER ACTIONS ── -->
                <div class="ep-footer-actions">
                    <a href="Admin_index.php?page=admin_posts" class="ep-btn-cancel">HỦY BỎ</a>
                    <button type="submit" name="action" value="save" class="ep-btn-submit">
                        LƯU THAY ĐỔI
                    </button>
                </div>

            </form>

            <?php require_once __DIR__ . '/../../Partials/Admin/Footer.php'; ?>
        </section>
    </main>
</div>

<script src="Public/Admin/Js/Pages/EditPost.js?v=<?= time() ?>"></script>
<script src="Public/Admin/Js/Pages/Profile.js?v=<?= time() ?>" defer></script>
</body>
</html>