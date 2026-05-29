/**
 * clientPostCreate.js
 * Đặt tại: Public/Client/Js/clientPostCreate.js
 * Yêu cầu: jQuery 3.x
 */

$(function () {

    /* ════════════════════════════════
       1. THUMBNAIL UPLOAD & PREVIEW
    ════════════════════════════════ */

    const $zone = $('#pcThumbnailZone');

    // Click vào zone → trigger input file
    $zone.on('click', function () {
        $('#pcThumbnailInput').trigger('click');
    });

    $('#pcThumbnailInput').on('change', function () {
        const file = this.files[0];
        if (!file) return;
        _previewThumb(file);
    });

    // Drag & drop
    $zone.on('dragover', function (e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });

    $zone.on('dragleave', function () {
        $(this).removeClass('dragover');
    });

    $zone.on('drop', function (e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        const file = e.originalEvent.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('pcThumbnailInput').files = dt.files;
            _previewThumb(file);
        }
    });

    function _previewThumb(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#pcUploadPlaceholder').hide();
            $('#pcThumbnailPreview').attr('src', e.target.result).show();
            $('#pcRemoveThumbBtn').show();
        };
        reader.readAsDataURL(file);
    }

    window.pcRemoveThumbnail = function () {
        $('#pcThumbnailPreview').hide().attr('src', '');
        $('#pcUploadPlaceholder').show();
        $('#pcRemoveThumbBtn').hide();
        $('#pcThumbnailInput').val('');
    };


    /* ════════════════════════════════
       2. DANH MỤC CHA → CON
    ════════════════════════════════ */

    $('#pcParentCat').on('change', function () {
        const parentId = $(this).val();
        const $child   = $('#pcChildCat');

        $child.find('option').not(':first').hide();
        $child.val('');

        if (parentId) {
            const $matching = $child.find('option[data-parent="' + parentId + '"]');
            if ($matching.length) {
                $matching.show();
                $child.prop('disabled', false).css('opacity', '1');
            } else {
                $child.prop('disabled', true).css('opacity', '0.6');
            }
        } else {
            $child.prop('disabled', true).css('opacity', '0.6');
        }
    });


    /* ════════════════════════════════
       3. TAGS
    ════════════════════════════════ */

    let tags = [];

    function _renderTags() {
        const $list = $('#pcTagsList');
        $list.empty();
        tags.forEach(function (tag, i) {
            $list.append(
                $('<span class="tag-chip"></span>')
                    .text('#' + tag + ' ')
                    .append(
                        $('<button type="button">×</button>').on('click', function (e) {
                            e.stopPropagation();
                            tags.splice(i, 1);
                            _renderTags();
                            _syncTags();
                        })
                    )
            );
        });
    }

    function _syncTags() {
        $('#pcTagsHidden').empty();
        tags.forEach(function (tag) {
            $('#pcTagsHidden').append(
                $('<input type="hidden" name="tags[]">').val(tag)
            );
        });
    }

    function _addTag(value) {
        const tag = value.trim().replace(/,/g, '').toLowerCase();
        if (tag && !tags.includes(tag) && tags.length < 10) {
            tags.push(tag);
            _renderTags();
            _syncTags();
        }
    }

    // Nút "+ Thêm tag" → focus vào input
    $('#pcAddTagBtn').on('click', function (e) {
        e.preventDefault();
        $('#pcTagInput').show().focus();
        $(this).hide();
    });

    $('#pcTagInput').on('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            _addTag($(this).val());
            $(this).val('');
        }
        if (e.key === 'Backspace' && $(this).val() === '' && tags.length) {
            tags.pop();
            _renderTags();
            _syncTags();
        }
        if (e.key === 'Escape') {
            $(this).hide().val('');
            $('#pcAddTagBtn').show();
        }
    });

    $('#pcTagInput').on('blur', function () {
        if ($(this).val().trim()) {
            _addTag($(this).val());
            $(this).val('');
        }
        if (tags.length === 0) {
            $(this).hide();
            $('#pcAddTagBtn').show();
        }
    });


    /* ════════════════════════════════
       4. CONTENT EDITOR — đếm từ
    ════════════════════════════════ */

    $('#pcContentEditor').on('input', function () {
        const text  = $(this).text().trim();
        const words = text ? text.split(/\s+/).length : 0;
        $('#pcWordCount').text(words);
        $('#pcContentInput').val($(this).html());
    });


    /* ════════════════════════════════
       5. FORM SUBMIT — sync editors
    ════════════════════════════════ */

    $('#pcPostForm').on('submit', function () {
        $('#pcContentInput').val($('#pcContentEditor').html());
    });


    /* ════════════════════════════════
       6. AUTO-DISMISS ALERTS
    ════════════════════════════════ */

    setTimeout(function () {
        $('.pc-alert').fadeOut(400);
    }, 4000);

});


/* ════════════════════════════════
   7. FORMAT HELPERS (global)
════════════════════════════════ */

function pcFormat(cmd, val) {
    document.getElementById('pcContentEditor').focus();
    if (cmd === 'createLink') {
        const url = prompt('Nhập URL:');
        if (url) document.execCommand('createLink', false, url);
    } else {
        document.execCommand(cmd, false, val || null);
    }
    $('#pcContentInput').val($('#pcContentEditor').html());
}

function pcInsertImage() {
    const url = prompt('Nhập URL ảnh:');
    if (!url) return;
    document.getElementById('pcContentEditor').focus();
    document.execCommand(
        'insertHTML', false,
        '<img src="' + url + '" style="max-width:100%;border-radius:8px;margin:12px 0;" alt="">'
    );
    $('#pcContentInput').val($('#pcContentEditor').html());
}