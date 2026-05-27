// ── Xóa inline color trong summaryEditor khi load ──
document.querySelectorAll('#summaryEditor, #summaryEditor *').forEach(el => {
    el.style.removeProperty('color');
    el.removeAttribute('color');
});

// ── Sync contenteditable → hidden textarea trước submit ──
document.getElementById('editPostForm').addEventListener('submit', function () {
    document.getElementById('summaryHidden').value = document.getElementById('summaryEditor').innerHTML;
    document.getElementById('contentHidden').value  = document.getElementById('contentEditor').innerHTML;
});

// ── Toolbar commands ──
function epFmt(cmd, val) {
    document.execCommand(cmd, false, val || null);
}
function epInsertLink() {
    const url = prompt('Nhập URL liên kết:');
    if (url) document.execCommand('createLink', false, url);
}
function epInsertImage() {
    const url = prompt('Nhập URL ảnh:');
    if (url) document.execCommand('insertImage', false, url);
}

// ── Thumbnail upload preview ──
document.getElementById('thumbnailArea').addEventListener('click', function () {
    document.getElementById('thumbnailInput').click();
});
document.getElementById('thumbnailInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (e) {
        const area = document.getElementById('thumbnailArea');
        // Xóa placeholder nếu còn
        const placeholder = document.getElementById('thumbnailPlaceholder');
        if (placeholder) placeholder.remove();
        // Cập nhật hoặc tạo preview
        let preview = document.getElementById('thumbnailPreview');
        if (!preview) {
            preview = document.createElement('img');
            preview.id = 'thumbnailPreview';
            preview.className = 'ep-thumbnail-preview';
            preview.alt = 'Thumbnail';
            area.insertBefore(preview, area.querySelector('input'));
        }
        preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
});

// ── Tags ──
function epAddTag(e) {
    if (e.key !== 'Enter') return;
    e.preventDefault();
    const input = document.getElementById('tagInput');
    const val   = input.value.trim();
    if (!val) return;

    const chip = document.createElement('span');
    chip.className = 'ep-tag-chip';
    chip.innerHTML = `${htmlEsc(val)}<input type="hidden" name="tags[]" value="${htmlEsc(val)}"><i class="fa-solid fa-xmark" onclick="epRemoveTag(this)"></i>`;
    document.getElementById('tagsWrapper').insertBefore(chip, input);
    input.value = '';
}
function epRemoveTag(el) {
    el.closest('.ep-tag-chip').remove();
}
function htmlEsc(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Filter sub-categories theo parent đang chọn ──
function epFilterSubCats() {
    const parentId = document.getElementById('parentCatSelect').value;
    const opts     = document.querySelectorAll('#subCatSelect option');
    let firstVisible = null;

    opts.forEach(opt => {
        const show = opt.dataset.parent === parentId;
        opt.style.display = show ? '' : 'none';
        if (show && !firstVisible) firstVisible = opt;
    });

    if (firstVisible) {
        document.getElementById('subCatSelect').value = firstVisible.value;
    }
}

// Chạy ngay khi load để ẩn sub-cat không thuộc parent hiện tại
epFilterSubCats();