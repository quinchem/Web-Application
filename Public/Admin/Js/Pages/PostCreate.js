// ── Format text (summary editor) — global để onclick HTML gọi được ──
function formatText(cmd) {
    $('#summaryEditor').focus();
    if (cmd === 'bold')   document.execCommand('bold');
    if (cmd === 'italic') document.execCommand('italic');
    if (cmd === 'h1')     document.execCommand('formatBlock', false, 'h2');
    if (cmd === 'quote')  document.execCommand('formatBlock', false, 'blockquote');
    if (cmd === 'list')   document.execCommand('insertUnorderedList');
    if (cmd === 'link') {
        const url = prompt('Nhập URL:');
        if (url) document.execCommand('createLink', false, url);
    }
}

// ── Format content (content editor) — global để onclick HTML gọi được ──
function formatContent(cmd) {
    $('#contentEditor').focus();
    if (cmd === 'bold')   document.execCommand('bold');
    if (cmd === 'italic') document.execCommand('italic');
    if (cmd === 'h1')     document.execCommand('formatBlock', false, 'h2');
    if (cmd === 'quote')  document.execCommand('formatBlock', false, 'blockquote');
    if (cmd === 'list')   document.execCommand('insertUnorderedList');
    if (cmd === 'link') {
        const url = prompt('Nhập URL:');
        if (url) document.execCommand('createLink', false, url);
    }
    if (cmd === 'image') {
        const url = prompt('Nhập URL ảnh:');
        if (url) document.execCommand('insertImage', false, url);
    }
}

$(document).ready(function () {

    // ── Sync contenteditable → hidden textarea trước khi submit ──
    $('#postForm').on('submit', function () {
        $('#summaryInput').val($('#summaryEditor').html());
        $('#contentInput').val($('#contentEditor').html());
    });

    // ── HỦY BỎ: xác nhận trước khi rời trang ──
    $('.btn-cancel').on('click', function (e) {
        e.preventDefault();
        const href = $(this).attr('href');
        if (confirm('Bạn có chắc muốn hủy? Nội dung đang soạn sẽ không được lưu.')) {
            window.location.href = href;
        }
    });

    // ── ĐĂNG BÀI: xác nhận trước khi xuất bản ──
    $('.btn-publish').on('click', function (e) {
        if (!confirm('Bạn có chắc muốn đăng bài này không?')) {
            e.preventDefault();
        }
    });

    // ── Thumbnail preview ──
    $('#thumbnailInput').on('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#thumbnailPreview').attr('src', e.target.result).show();
            $('#uploadPlaceholder').hide();
        };
        reader.readAsDataURL(file);
    });

    // Drag & drop thumbnail
    const $zone = $('#thumbnailZone');
    $zone.on('dragover', function (e) {
        e.preventDefault();
        $(this).css('border-color', '#e52328');
    });
    $zone.on('dragleave', function () {
        $(this).css('border-color', '#d0e8f0');
    });
    $zone.on('drop', function (e) {
        e.preventDefault();
        $(this).css('border-color', '#d0e8f0');
        const file = e.originalEvent.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            $('#thumbnailInput')[0].files = dt.files;
            const reader = new FileReader();
            reader.onload = function (ev) {
                $('#thumbnailPreview').attr('src', ev.target.result).show();
                $('#uploadPlaceholder').hide();
            };
            reader.readAsDataURL(file);
        }
    });

    // ── Tags ──
    let tags = [];

    function renderTags() {
        $('#tagsList').empty();
        $('#tagsHidden').empty();

        $.each(tags, function (i, tag) {
            // Chip hiển thị
            const $chip = $('<span class="tag-chip"></span>');
            const $label = $('<span class="tag-chip__label"></span>').text(tag);
            const $btn   = $('<button type="button" class="tag-chip__remove" aria-label="Xóa tag">×</button>');

            $btn.on('click', function () {
                tags.splice(i, 1);
                renderTags();
            });

            $chip.append($label).append($btn);
            $('#tagsList').append($chip);

            // Hidden input gửi lên server
            $('#tagsHidden').append(
                $('<input type="hidden" name="tags[]">').val(tag)
            );
        });

        // Hiện/ẩn placeholder "+ THÊM TAG"
        if (tags.length > 0) {
            $('#tagInput').attr('placeholder', '+ Thêm tag...');
        } else {
            $('#tagInput').attr('placeholder', '+ THÊM TAG');
        }
    }

    // Thêm tag khi nhấn Enter hoặc dấu phẩy
    $('#tagInput').on('keydown', function (e) {
        if ((e.key === 'Enter' || e.key === ',') && $(this).val().trim()) {
            e.preventDefault();
            addTag($(this).val());
            $(this).val('');
        }
        // Xóa tag cuối khi nhấn Backspace và input đang trống
        if (e.key === 'Backspace' && $(this).val() === '' && tags.length > 0) {
            tags.pop();
            renderTags();
        }
    });

    // Thêm tag khi blur (bỏ focus) nếu có giá trị
    $('#tagInput').on('blur', function () {
        if ($(this).val().trim()) {
            addTag($(this).val());
            $(this).val('');
        }
    });

    function addTag(value) {
        const val = value.trim().replace(/,/g, '');
        if (val && !tags.includes(val)) {
            tags.push(val);
            renderTags();
        }
    }

    // Click vào vùng tags-input-wrapper cũng focus vào input
    $('.tags-input-wrapper').on('click', function () {
        $('#tagInput').focus();
    });

    // ── Category cascade ──
    const allChildOptions = $('#childCatSelect option[data-parent]').clone();

    $('#parentCatSelect').on('change', function () {
        const chosen = $(this).val();
        $('#childCatSelect').html('<option value="">Chọn danh mục con</option>');

        if (chosen) {
            allChildOptions.filter(function () {
                return $(this).data('parent') == chosen;
            }).clone().appendTo('#childCatSelect');

            $('#childCatSelect').prop('disabled', false).css('opacity', '1');
        } else {
            $('#childCatSelect').prop('disabled', true).css('opacity', '0.5');
        }
    });

}); // end document.ready