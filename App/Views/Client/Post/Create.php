<form id="postForm" enctype="multipart/form-data">
    <!-- Tiêu đề -->
    <input type="text" name="title" placeholder="Nhập tiêu đề..." required>

    <!-- Tóm tắt -->
    <textarea name="summary" placeholder="Nhập tóm tắt bài viết..." rows="3"></textarea>

    <!-- Danh mục con (category_id) — render từ PHP -->
    <select name="category_id" required>
        <option value="">-- Chọn danh mục --</option>
        <?php
        $grouped = [];
        foreach ($categories as $cat) {
            if (!empty($cat['parent_id'])) {
                $grouped[$cat['parent_id']][] = $cat;
            }
        }
        $parents = array_filter($categories, fn($c) => empty($c['parent_id']));
        foreach ($parents as $parent):
            $children = $grouped[$parent['category_id']] ?? [];
            if (empty($children)) continue;
        ?>
            <optgroup label="<?= htmlspecialchars($parent['name']) ?>">
                <?php foreach ($children as $child): ?>
                    <option value="<?= htmlspecialchars($child['category_id']) ?>">
                        <?= htmlspecialchars($child['name']) ?>
                    </option>
                <?php endforeach; ?>
            </optgroup>
        <?php endforeach; ?>
    </select>

    <!-- Ảnh đại diện -->
    <div id="thumbnailZone" class="upload-placeholder">
        <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*" hidden>
        <img id="thumbnailPreview" style="display:none; width: 100%;">
        <span id="thumbnailHint">Nhấn để chọn ảnh đại diện</span>
    </div>

    <!-- Editor nội dung -->
    <div id="contentEditor" contenteditable="true" class="editor-area"></div>
    <textarea name="content" id="contentInput" hidden></textarea>

    <!-- Nút hành động -->
    <div class="post-actions">
        <button type="button" class="btn-draft"   onclick="submitPost('draft')">Lưu bản nháp</button>
        <button type="button" class="btn-publish" onclick="submitPost('publish')">Đăng bài</button>
    </div>
</form>

<script>
$(document).ready(function () {

    // 1. Preview ảnh thumbnail
    $('#thumbnailZone').click(function () {
        $('#thumbnailInput').click();
    });

    $('#thumbnailInput').change(function () {
        if (!this.files[0]) return;
        let reader = new FileReader();
        reader.onload = function (e) {
            $('#thumbnailPreview').attr('src', e.target.result).show();
            $('#thumbnailHint').hide();
        };
        reader.readAsDataURL(this.files[0]);
    });

    // 2. Submit bằng AJAX
    window.submitPost = function (action) {
        // Đẩy nội dung editor vào textarea ẩn trước khi build FormData
        $('#contentInput').val($('#contentEditor').html());

        let formData = new FormData($('#postForm')[0]);
        formData.append('action', action);

        $.ajax({
            url: 'index.php?page=store_post_client',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                let data;
                try { data = JSON.parse(res); } catch (e) { data = res; }

                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(function () {
                        // Sau khi đăng thành công → về trang quản lý bài viết
                        window.location.href = 'index.php?page=client_profile&tab=my_posts';
                    });
                } else if (data.status === 'unauthorized') {
                    Swal.fire('Chưa đăng nhập', 'Vui lòng đăng nhập để đăng bài.', 'warning');
                } else {
                    Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra, vui lòng thử lại.', 'error');
                }
            },
            error: function (xhr) {
                Swal.fire('Lỗi kết nối', 'Không thể kết nối đến máy chủ. Mã lỗi: ' + xhr.status, 'error');
            }
        });
    };
});
</script>