<?php
require_once __DIR__ . '/../../Partials/Client/Header.php';

$successMsg = $_SESSION['success'] ?? null;
$errorMsg   = $_SESSION['error']   ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$catParents  = [];
$catChildren = [];
foreach (($categories ?? []) as $cat) {
    if (empty($cat['parent_id'])) {
        $catParents[$cat['category_id']] = $cat;
    } else {
        $catChildren[$cat['parent_id']][] = $cat;
    }
}

$postId         = $post['post_id']       ?? '';
$postTitle      = $post['title']         ?? '';
$postSummary    = $post['summary']       ?? '';
$postContent    = $post['content']       ?? '';
$postThumb      = $post['thumbnail_URL'] ?? '';
$postCategoryId = $post['category_id']   ?? '';
$postPublishAt  = $post['published_at']  ?? '';
$postStatus     = $post['status']        ?? 'draft';

$currentParentId = null;
foreach ($catChildren as $parentId => $children) {
    foreach ($children as $child) {
        if ((string)$child['category_id'] === (string)$postCategoryId) {
            $currentParentId = $parentId;
            break 2;
        }
    }
}
if ($currentParentId === null && isset($catParents[$postCategoryId])) {
    $currentParentId = $postCategoryId;
    $postCategoryId  = '';
}

$publishAtFormatted = '';
if (!empty($postPublishAt)) {
    $ts = strtotime($postPublishAt);
    if ($ts !== false) {
        $publishAtFormatted = date('Y-m-d\TH:i', $ts);
    }
}

$currentTags = $tags ?? [];

function editStatusLabel(string $status): string
{
    return match ($status) {
        'approved' => 'Đã đăng',
        'pending'  => 'Chờ duyệt',
        'rejected' => 'Từ chối',
        'draft'    => 'Bản nháp',
        'hidden'   => 'Đã ẩn',
        default    => 'Bản nháp',
    };
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800;900&family=Montserrat:ital,wght@0,400;0,500;0,700;1,400&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,700;0,6..72,800;1,6..72,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="Public/Client/Css/ClientPostCreate.css?v=<?= time() ?>">

<main id="page-post-create">
    <div class="container my-5">
        <div class="row g-4">

            <div class="col-md-3">
                <?php
                $activeMenuTarget = 'my_posts';
                include __DIR__ . '/../../Partials/Client/Client_menu.php';
                ?>
            </div>

            <div class="col-md-9">
                <div class="create-post-main">

                    <div class="create-post-heading">
                        <h1>Chỉnh sửa bài viết</h1>
                        <div class="subtitle">Cập nhật nội dung bài đăng của bạn</div>
                    </div>

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

                    <div class="post-count-label" id="pcDraftLabel">
                        <?= htmlspecialchars($postTitle ?: 'Chỉnh sửa bài viết') ?>
                    </div>

                    <form method="POST"
                          action="index.php?page=client_store_post"
                          enctype="multipart/form-data"
                          id="pcPostForm">

                        <input type="hidden" name="post_id" id="pcPostId"
                               value="<?= htmlspecialchars($postId) ?>">

                        <!-- CARD 1: TIÊU ĐỀ + TÓM TẮT -->
                        <div class="pc-card">
                            <input type="text"
                                   name="title"
                                   id="pcTitleInput"
                                   class="pc-title-input"
                                   placeholder="Nhập tiêu đề bài viết..."
                                   autocomplete="off"
                                   value="<?= htmlspecialchars($postTitle) ?>"
                                   required>

                            <div class="pc-title-divider"></div>

                            <div class="pc-summary-label">Tóm tắt nội dung</div>
                            <textarea name="summary"
                                      id="pcSummaryInput"
                                      class="pc-summary-textarea"
                                      placeholder="Nhập một đoạn tóm tắt ngắn..."
                                      rows="3"><?= htmlspecialchars($postSummary) ?></textarea>
                        </div>

                        <!-- CARD 2: DANH MỤC + TAG + TRẠNG THÁI -->
                        <div class="pc-card">
                            <div class="pc-meta-row">
                                <div class="pc-meta-group">
                                    <label for="pcParentCat">Danh mục</label>
                                    <div class="pc-select-wrap">
                                        <select name="parent_category" id="pcParentCat" class="pc-select">
                                            <option value="">Chọn danh mục</option>
                                            <?php foreach ($catParents as $parentId => $parent): ?>
                                                <option value="<?= htmlspecialchars($parentId) ?>"
                                                    <?= (string)$parentId === (string)$currentParentId ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($parent['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="pc-meta-group">
                                    <label for="pcChildCat">Danh mục con</label>
                                    <div class="pc-select-wrap">
                                        <select name="category_id" id="pcChildCat" class="pc-select"
                                                <?= empty($currentParentId) ? 'disabled' : '' ?>>
                                            <option value="">Chọn danh mục con</option>
                                            <?php foreach ($catParents as $parentId => $parent): ?>
                                                <?php if (!empty($catChildren[$parentId])): ?>
                                                    <?php foreach ($catChildren[$parentId] as $child): ?>
                                                        <?php
                                                        $isHidden   = (string)$parentId !== (string)$currentParentId;
                                                        $isSelected = (string)$child['category_id'] === (string)$postCategoryId;
                                                        ?>
                                                        <option value="<?= htmlspecialchars($child['category_id']) ?>"
                                                                data-parent="<?= htmlspecialchars($parentId) ?>"
                                                                <?= $isSelected ? 'selected' : '' ?>
                                                                <?= $isHidden   ? 'style="display:none;"' : '' ?>>
                                                            <?= htmlspecialchars($child['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Tags -->
                            <div class="pc-tag-row">
                                <div class="pc-field-title">Tag bài viết</div>
                                <div class="pc-tag-label-group">
                                    <span id="pcTagsList"></span>
                                    <input type="text" id="pcTagInput" class="pc-tag-input-inline"
                                           placeholder="Nhập tag..." style="display:none;">
                                </div>
                                <button type="button" id="pcAddTagBtn" class="pc-add-tag-btn">+ Thêm tag</button>
                            </div>
                            <div id="pcTagsHidden">
                                <?php foreach ($currentTags as $tag): ?>
                                    <input type="hidden" name="tags[]" value="<?= htmlspecialchars($tag) ?>">
                                <?php endforeach; ?>
                            </div>

                            <!-- Ngày xuất bản -->
                            <div class="pc-meta-row" style="margin-top:16px;">
                                <div class="pc-meta-group">
                                    <label>Ngày xuất bản</label>
                                    <div class="date-input-wrap" style="display:flex;align-items:center;gap:8px;">
                                        <i class="fa-regular fa-calendar"></i>
                                        <input type="datetime-local" name="publish_at" class="pc-select"
                                               style="flex:1;" value="<?= htmlspecialchars($publishAtFormatted) ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="pc-status-row">
                                <span class="pc-field-title">Trạng thái</span>
                                <span class="pc-status-badge <?= $postStatus === 'pending' ? 'pending' : '' ?>"
                                      id="pcStatusBadge">
                                    <?= editStatusLabel($postStatus) ?>
                                </span>
                            </div>
                        </div>

                        <!-- CARD 3: ẢNH ĐẠI DIỆN -->
                        <div class="pc-card">
                            <div class="pc-card-label">
                                <i class="fa-regular fa-image"></i> Ảnh đại diện bài viết
                            </div>
                            <div class="pc-thumbnail-zone" id="pcThumbnailZone">
                                <input type="file" name="thumbnail" id="pcThumbnailInput" accept="image/*" hidden>
                                <div class="pc-upload-placeholder" id="pcUploadPlaceholder"
                                     style="<?= !empty($postThumb) ? 'display:none;' : '' ?>">
                                    <div class="upload-icon"><i class="fa-regular fa-file-image"></i></div>
                                    <p>Tải ảnh lên hoặc kéo thả vào đây</p>
                                    <small>Kích thước khuyến dùng 1200×630px</small>
                                </div>
                                <img id="pcThumbnailPreview"
                                     src="<?= htmlspecialchars($postThumb) ?>"
                                     alt=""
                                     style="<?= !empty($postThumb) ? '' : 'display:none;' ?>">
                            </div>
                            <button type="button" id="pcRemoveThumbBtn" class="pc-remove-thumb-btn"
                                    style="<?= !empty($postThumb) ? '' : 'display:none;' ?>">
                                <i class="fa-solid fa-xmark me-1"></i> Xoá ảnh
                            </button>
                            <input type="hidden" name="existing_thumbnail" id="pcExistingThumb"
                                   value="<?= htmlspecialchars($postThumb) ?>">
                        </div>

                        <!-- CARD 4: NỘI DUNG -->
                        <div class="pc-card">
                            <div class="pc-content-toolbar">
                                <button type="button" onclick="pcFormat('bold')"        title="In đậm"><b>B</b></button>
                                <button type="button" onclick="pcFormat('italic')"       title="In nghiêng"><i>I</i></button>
                                <button type="button" onclick="pcFormat('underline')"    title="Gạch chân"><u>T</u></button>
                                <button type="button" onclick="pcFormat('formatBlock','blockquote')"
                                        title="Trích dẫn" style="font-size:.75rem;">99</button>
                                <div class="tb-sep"></div>
                                <button type="button" onclick="pcFormat('insertUnorderedList')"
                                        title="Danh sách"><i class="fa-solid fa-list-ul"></i></button>
                                <div class="tb-sep"></div>
                                <button type="button" onclick="pcFormat('createLink')"   title="Chèn link"><i class="fa-solid fa-link"></i></button>
                                <button type="button" onclick="pcInsertImage()"          title="Chèn ảnh"><i class="fa-regular fa-image"></i></button>
                                <button type="button" onclick="pcFormat('justifyFull')"  title="Căn đều"><i class="fa-solid fa-align-justify"></i></button>
                            </div>

                            <!-- QUAN TRỌNG: editor để trống, JS sẽ điền nội dung an toàn -->
                            <div class="pc-editor"
                                 id="pcContentEditor"
                                 contenteditable="true"
                                 data-placeholder="Bắt đầu kể câu chuyện của bạn tại đây..."></div>

                            <!-- QUAN TRỌNG: textarea cũng để trống, JS sync khi submit -->
                            <textarea name="content" id="pcContentInput" hidden></textarea>

                            <div class="pc-word-count">
                                <span id="pcWordCount">0</span> từ
                            </div>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="pc-action-bar">
                            <a href="index.php?page=client_profile&tab=my_posts" class="pc-btn-cancel">
                                Quay lại
                            </a>
                            <button type="submit" name="action" value="draft"
                                    class="pc-btn-draft" id="pcSaveDraftBtn">
                                Lưu thay đổi
                            </button>
                            <button type="submit" name="action" value="publish"
                                    class="pc-btn-publish" id="pcPublishBtn">
                                Gửi duyệt lại
                            </button>
                        </div>

                    </form>

                </div><!-- /create-post-main -->
            </div><!-- /col-md-9 -->
        </div><!-- /row -->
    </div><!-- /container -->
</main>

<!-- ══ TOAST ══ -->
<div id="pcToast" class="pc-toast" style="display:none;">
    <i class="fa-solid fa-circle-check me-2"></i>
    <span id="pcToastMsg"></span>
</div>

<!-- ══ MODAL GỬI DUYỆT ══ -->
<div id="pcPublishModal" class="pc-modal-overlay" style="display:none;">
    <div class="pc-modal-box">
        <div class="pc-modal-icon"><i class="fa-solid fa-paper-plane"></i></div>
        <h3>Gửi bài duyệt?</h3>
        <p>Bài viết sẽ được gửi đến admin để xét duyệt lại. Bạn không thể chỉnh sửa sau khi gửi.</p>
        <div class="pc-modal-actions">
            <button type="button" id="pcModalCancel"  class="pc-btn-cancel">Huỷ</button>
            <button type="button" id="pcModalConfirm" class="pc-btn-publish">Xác nhận gửi</button>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Truyền dữ liệu PHP sang JS SAU KHI jQuery đã load -->
<script>
    window.PC_EDIT_TAGS    = <?= json_encode(array_values($currentTags)) ?>;
    window.PC_EDIT_MODE    = true;
    window.PC_POST_CONTENT = <?= json_encode($postContent) ?>;
</script>

<script src="Public/Client/Js/ClientPostEdit.js?v=<?= time() ?>"></script>

<?php include __DIR__ . '/../../Partials/Client/Footer.php'; ?>