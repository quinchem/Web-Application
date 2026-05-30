
$(function () {

    /* ════════════════════════════════
       0. HELPERS
    ════════════════════════════════ */
    function showToast(msg, isError) {
        const $t = $('#pcToast');
        $t.find('#pcToastMsg').text(msg);
        $t.toggleClass('pc-toast--error', !!isError).css('display', 'flex').addClass('show');
        clearTimeout(window._pcToastTimer);
        window._pcToastTimer = setTimeout(function () {
            $t.removeClass('show');
            setTimeout(function () { $t.hide(); }, 300);
        }, 3000);
    }
    function showModal(sel) { $(sel).css('display', 'flex'); }
    function hideModal(sel) { $(sel).hide(); }


    /* ════════════════════════════════
       1. KHỞI TẠO NỘI DUNG EDITOR
          Đọc từ window.PC_POST_CONTENT do PHP truyền qua json_encode
          → an toàn, không vỡ HTML
    ════════════════════════════════ */
    (function initEditor() {
        const content = window.PC_POST_CONTENT || '';
        const $editor = $('#pcContentEditor');
        if ($editor.length && content) {
            $editor.html(content);
            // Sync vào textarea ngay
            $('#pcContentInput').val(content);
            // Đếm từ ban đầu
            const text = $editor.text().trim();
            $('#pcWordCount').text(text ? text.split(/\s+/).length : 0);
        }
    })();


    /* ════════════════════════════════
       2. THUMBNAIL
    ════════════════════════════════ */
    const $zone        = $('#pcThumbnailZone');
    const $input       = $('#pcThumbnailInput');
    const $preview     = $('#pcThumbnailPreview');
    const $placeholder = $('#pcUploadPlaceholder');
    const $removeBtn   = $('#pcRemoveThumbBtn');

    $zone.on('click', function (e) {
        if ($(e.target).closest('#pcRemoveThumbBtn').length) return;
        if ($(e.target).is('#pcThumbnailInput')) return;
        e.preventDefault(); e.stopPropagation();
        $input[0].click();
    });
    $input.on('change', function () {
        if (this.files[0]) _previewThumb(this.files[0]);
    });
    $zone.on('dragover', function (e) {
        e.preventDefault(); $(this).addClass('dragover');
    }).on('dragleave', function () {
        $(this).removeClass('dragover');
    }).on('drop', function (e) {
        e.preventDefault(); $(this).removeClass('dragover');
        const file = e.originalEvent.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer(); dt.items.add(file);
            $input[0].files = dt.files;
            _previewThumb(file);
        }
    });
    function _previewThumb(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $placeholder.hide();
            $preview.attr('src', e.target.result).show();
            $removeBtn.show();
        };
        reader.readAsDataURL(file);
    }
    $removeBtn.on('click', function (e) {
        e.stopPropagation();
        $preview.hide().attr('src', '');
        $placeholder.show();
        $removeBtn.hide();
        $input.val('');
        $('#pcExistingThumb').val(''); // báo server xoá ảnh
    });


    /* ════════════════════════════════
       3. DANH MỤC CHA → CON
    ════════════════════════════════ */
    function _refreshChildCat(parentId, selectedChildId) {
        const $child = $('#pcChildCat');
        $child.find('option').not(':first').hide();
        $child.val('');
        if (parentId) {
            const $match = $child.find('option[data-parent="' + parentId + '"]');
            if ($match.length) {
                $match.show();
                $child.prop('disabled', false).css('opacity', '1');
                if (selectedChildId) $child.val(selectedChildId);
            } else {
                $child.prop('disabled', true).css('opacity', '0.6');
            }
        } else {
            $child.prop('disabled', true).css('opacity', '0.6');
        }
    }

    // Pre-fill khi load
    (function initCategoryState() {
        const parentId = $('#pcParentCat').val();
        // Lấy option đang có selected attr (PHP render)
        const childId  = $('#pcChildCat option[selected]').val() || $('#pcChildCat').val();
        if (parentId) _refreshChildCat(parentId, childId);
    })();

    $('#pcParentCat').on('change', function () {
        _refreshChildCat($(this).val(), null);
    });


    /* ════════════════════════════════
       4. TAGS
    ════════════════════════════════ */
    let tags = (Array.isArray(window.PC_EDIT_TAGS) ? window.PC_EDIT_TAGS : []).slice();

    function _renderTags() {
        const $list = $('#pcTagsList');
        $list.empty();
        tags.forEach(function (tag, i) {
            $list.append(
                $('<span class="tag-chip"></span>').text('#' + tag + ' ').append(
                    $('<button type="button">×</button>').on('click', function (e) {
                        e.stopPropagation();
                        tags.splice(i, 1);
                        _renderTags(); _syncTags();
                    })
                )
            );
        });
    }
    function _syncTags() {
        $('#pcTagsHidden').empty();
        tags.forEach(function (tag) {
            $('#pcTagsHidden').append($('<input type="hidden" name="tags[]">').val(tag));
        });
    }
    function _addTag(value) {
        const tag = value.trim().replace(/,/g, '').toLowerCase();
        if (tag && !tags.includes(tag) && tags.length < 10) {
            tags.push(tag); _renderTags(); _syncTags();
        }
    }

    _renderTags(); _syncTags(); // render tags sẵn có khi load

    $('#pcAddTagBtn').on('click', function (e) {
        e.preventDefault(); $('#pcTagInput').show().focus(); $(this).hide();
    });
    $('#pcTagInput').on('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ',') { e.preventDefault(); _addTag($(this).val()); $(this).val(''); }
        if (e.key === 'Backspace' && !$(this).val() && tags.length) { tags.pop(); _renderTags(); _syncTags(); }
        if (e.key === 'Escape') { $(this).hide().val(''); $('#pcAddTagBtn').show(); }
    }).on('blur', function () {
        if ($(this).val().trim()) { _addTag($(this).val()); $(this).val(''); }
        if (!tags.length) { $(this).hide(); $('#pcAddTagBtn').show(); }
    });


    /* ════════════════════════════════
       5. CONTENT EDITOR — đếm từ & sync
    ════════════════════════════════ */
    $('#pcContentEditor').on('input', function () {
        const text = $(this).text().trim();
        $('#pcWordCount').text(text ? text.split(/\s+/).length : 0);
        $('#pcContentInput').val($(this).html());
    });


    /* ════════════════════════════════
       6. LABEL TIÊU ĐỀ
    ════════════════════════════════ */
    $('#pcTitleInput').on('input', function () {
        $('#pcDraftLabel').text($(this).val().trim() || 'Chỉnh sửa bài viết');
    });


    /* ════════════════════════════════
       7. LƯU THAY ĐỔI (draft)
    ════════════════════════════════ */
    $('#pcSaveDraftBtn').on('click', function (e) {
        e.preventDefault();
        $('#pcContentInput').val($('#pcContentEditor').html());

        const $btn = $(this).prop('disabled', true).text('Đang lưu...');
        const formData = new FormData(document.getElementById('pcPostForm'));
        formData.set('action', 'draft');

        $.ajax({
            url: 'index.php?page=client_store_post',
            method: 'POST', data: formData,
            processData: false, contentType: false, dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#pcStatusBadge').text('Bản nháp').removeClass().addClass('pc-status-badge');
                    showToast('✓ Đã lưu thay đổi thành công!');
                } else {
                    showToast(res.message || 'Có lỗi xảy ra.', true);
                }
            },
            error: function () { showToast('Không thể kết nối máy chủ.', true); },
            complete: function () { $btn.prop('disabled', false).text('Lưu thay đổi'); }
        });
    });


    /* ════════════════════════════════
       8. GỬI DUYỆT LẠI
    ════════════════════════════════ */
    $('#pcPublishBtn').on('click', function (e) {
        e.preventDefault();
        $('#pcContentInput').val($('#pcContentEditor').html());

        if (!$('#pcTitleInput').val().trim())                        { showToast('Vui lòng nhập tiêu đề.', true);    return; }
        if (!$('#pcSummaryInput').val().trim())                      { showToast('Vui lòng nhập tóm tắt.', true);    return; }
        if (!($('#pcChildCat').val() || $('#pcParentCat').val()))     { showToast('Vui lòng chọn danh mục.', true);   return; }
        if (!$('#pcContentEditor').text().trim())                    { showToast('Vui lòng nhập nội dung.', true);   return; }

        showModal('#pcPublishModal');
    });
    $('#pcModalCancel').on('click', function () { hideModal('#pcPublishModal'); });
    $('#pcModalConfirm').on('click', function () {
        hideModal('#pcPublishModal');
        const $btn = $(this).prop('disabled', true).text('Đang gửi...');
        const formData = new FormData(document.getElementById('pcPostForm'));
        formData.set('action', 'publish');

        $.ajax({
            url: 'index.php?page=client_store_post',
            method: 'POST', data: formData,
            processData: false, contentType: false, dataType: 'json',
            success: function (res) {
                if (res.success) {
                    showToast('✓ Bài viết đã được gửi để duyệt!');
                    $('#pcStatusBadge').text('Chờ duyệt').removeClass().addClass('pc-status-badge pending');
                    setTimeout(function () {
                        window.location.href = 'index.php?page=client_profile&tab=my_posts';
                    }, 1800);
                } else {
                    showToast(res.message || 'Có lỗi xảy ra.', true);
                }
            },
            error: function () { showToast('Không thể kết nối máy chủ.', true); },
            complete: function () { $btn.prop('disabled', false).text('Xác nhận gửi'); }
        });
    });


    /* ════════════════════════════════
       9. AUTO-DISMISS FLASH
    ════════════════════════════════ */
    setTimeout(function () { $('.pc-alert').fadeOut(400); }, 4000);
});


/* ════════════════════════════════
   GLOBAL FORMAT HELPERS
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
    document.execCommand('insertHTML', false,
        '<img src="' + url + '" style="max-width:100%;border-radius:8px;margin:12px 0;" alt="">');
    $('#pcContentInput').val($('#pcContentEditor').html());
}