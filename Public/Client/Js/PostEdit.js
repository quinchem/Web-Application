$(function () {

    /* ════════════════════════════════
       INIT — fill dữ liệu từ server
    ════════════════════════════════ */

    const EDIT = window.PC_EDIT_DATA || {};

    // Fill nội dung vào contenteditable editor
    if (EDIT.content) {
        const editor = document.getElementById('pcContentEditor');
        if (editor) {
            editor.innerHTML = EDIT.content;
            // Đếm từ ban đầu
            const words = editor.innerText.trim().split(/\s+/).filter(Boolean).length;
            $('#pcWordCount').text(words);
        }
        $('#pcContentInput').val(EDIT.content);
    }

    let tags = Array.isArray(EDIT.tags) ? EDIT.tags.filter(Boolean) : [];
    _renderTags();

    /* ════════════════════════════════
       0. HELPERS: TOAST & MODAL
    ════════════════════════════════ */

    function showToast(msg, isError) {
        const $t = $('#pcToast');
        $t.find('#pcToastMsg').text(msg);
        $t.toggleClass('pc-toast--error', !!isError)
            .css('display', 'flex')
            .addClass('show');

        clearTimeout(window._pcToastTimer);
        window._pcToastTimer = setTimeout(function () {
            $t.removeClass('show');
            setTimeout(function () { $t.hide(); }, 300);
        }, 3000);
    }

    function showModal(selector) { $(selector).css('display', 'flex'); }
    function hideModal(selector) { $(selector).hide(); }


    /* ════════════════════════════════
   1. THUMBNAIL UPLOAD
    ════════════════════════════════ */

    const $zone        = $('#pcThumbnailZone');
    const $input       = $('#pcThumbnailInput');
    const $preview     = $('#pcThumbnailPreview');
    const $placeholder = $('#pcUploadPlaceholder');
    const $removeBtn   = $('#pcRemoveThumbBtn');

    function _previewFile(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $placeholder.hide();
            $preview.attr('src', e.target.result).show();
            $removeBtn.show();
            $('#pcExistingThumbnail').val('');
        };
        reader.readAsDataURL(file);
    }

    // Gắn onclick trực tiếp lên zone — ghi đè mọi handler cũ
    document.getElementById('pcThumbnailZone').onclick = function (e) {
        if (e.target.id === 'pcRemoveThumbBtn' ||
            e.target.closest && e.target.closest('#pcRemoveThumbBtn')) return;
        document.getElementById('pcThumbnailInput').click();
    };

    // Click thẳng vào ảnh preview cũng mở picker
    document.getElementById('pcThumbnailPreview').onclick = function (e) {
        e.stopPropagation();
        document.getElementById('pcThumbnailInput').click();
    };

    // Kéo thả
    $zone.on('dragover', function (e) {
        e.preventDefault();
        $(this).addClass('dragover');
    }).on('dragleave', function () {
        $(this).removeClass('dragover');
    }).on('drop', function (e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        const file = e.originalEvent.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            $input[0].files = dt.files;
            _previewFile(file);
        }
    });

    // Chọn file → preview
    $input.on('change', function () {
        const file = this.files[0];
        if (file) _previewFile(file);
    });

    // Xoá ảnh
    $removeBtn.on('click', function (e) {
        e.stopPropagation();
        $preview.hide().attr('src', '');
        $placeholder.show();
        $removeBtn.hide();
        $input.val('');
        $('#pcExistingThumbnail').val('');
    });

    /* ════════════════════════════════
       2. DANH MỤC CHA → CON
    ════════════════════════════════ */

    // Khởi tạo: nếu đã có danh mục cha, show các option con tương ứng
    (function initCategory() {
        const parentId = $('#pcParentCat').val();
        if (!parentId) return;

        const $child = $('#pcChildCat');
        $child.find('option').not(':first').each(function () {
            $(this).toggle($(this).data('parent') == parentId);
        });
        $child.prop('disabled', false).css('opacity', '1');
    })();

    $('#pcParentCat').on('change', function () {
        const parentId = $(this).val();
        const $child = $('#pcChildCat');

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

        if (tags.length === 0 && !$('#pcTagInput').is(':visible')) {
            $('#pcAddTagBtn').show();
        }
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

    _syncTags();

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
    }).on('blur', function () {
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
        const text = $(this).text().trim();
        const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
        $('#pcWordCount').text(words);
        $('#pcContentInput').val($(this).html());
    });


    /* ════════════════════════════════
       5. LABEL ĐỔI THEO TIÊU ĐỀ
    ════════════════════════════════ */

    $('#pcTitleInput').on('input', function () {
        const val = $(this).val().trim();
        $('#pcDraftLabel').text(val || 'Bài viết nháp');
    });


    /* ════════════════════════════════
       6. NÚT LƯU THAY ĐỔI (AJAX)
          → Luôn là UPDATE vì post_id đã có
    ════════════════════════════════ */

    $('#pcSaveDraftBtn').on('click', function (e) {
        e.preventDefault();

        // Sync editor
        $('#pcContentInput').val($('#pcContentEditor').html());

        const $btn = $(this).prop('disabled', true).text('Đang lưu...');

        const formData = new FormData(document.getElementById('pcPostForm'));
        formData.set('action', 'draft');

        $.ajax({
            url: 'index.php?page=client_store_post',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    showToast('✓ Đã lưu thay đổi thành công!');
                    $('#pcStatusBadge')
                        .text('Bản nháp')
                        .removeClass('approved pending rejected')
                        .addClass('');
                } else {
                    showToast(res.message || 'Có lỗi xảy ra, vui lòng thử lại.', true);
                }
            },
            error: function () {
                showToast('Không thể kết nối máy chủ.', true);
            },
            complete: function () {
                $btn.prop('disabled', false).text('Lưu thay đổi');
            }
        });
    });


    /* ════════════════════════════════
       7. NÚT GỬI DUYỆT → MODAL XÁC NHẬN
    ════════════════════════════════ */

    $('#pcPublishBtn').on('click', function (e) {
        e.preventDefault();
        $('#pcContentInput').val($('#pcContentEditor').html());

        const title = $('#pcTitleInput').val().trim();
        const summary = $('#pcSummaryInput').val().trim();
        const categoryId = $('#pcChildCat').val() || $('#pcParentCat').val();
        const content = $('#pcContentEditor').text().trim();

        if (!title) { showToast('Vui lòng nhập tiêu đề bài viết.', true); return; }
        if (!summary) { showToast('Vui lòng nhập tóm tắt nội dung.', true); return; }
        if (!categoryId) { showToast('Vui lòng chọn danh mục bài viết.', true); return; }
        if (!content) { showToast('Vui lòng nhập nội dung bài viết.', true); return; }

        showModal('#pcPublishModal');
    });

    $('#pcModalCancel').on('click', function () { hideModal('#pcPublishModal'); });

    $('#pcModalConfirm').on('click', function () {
        hideModal('#pcPublishModal');

        const $btn = $(this).prop('disabled', true).text('Đang gửi...');

        $('#pcContentInput').val($('#pcContentEditor').html());

        const formData = new FormData(document.getElementById('pcPostForm'));
        formData.set('action', 'publish');

        $.ajax({
            url: 'index.php?page=client_store_post',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    showToast('✓ Bài viết đã được gửi đến admin để duyệt!');
                    $('#pcStatusBadge')
                        .text('Chờ duyệt')
                        .removeClass('approved rejected')
                        .addClass('pending');
                    setTimeout(function () {
                        window.location.href = 'index.php?page=client_profile&tab=my_posts';
                    }, 1800);
                } else {
                    showToast(res.message || 'Có lỗi xảy ra, vui lòng thử lại.', true);
                }
            },
            error: function () {
                showToast('Không thể kết nối máy chủ.', true);
            },
            complete: function () {
                $btn.prop('disabled', false).text('Xác nhận gửi');
            }
        });
    });


    /* ════════════════════════════════
       8. NÚT HUỶ CHỈNH SỬA
          → Luôn hỏi confirm vì bài đã tồn tại
    ════════════════════════════════ */

    $('#pcCancelBtn').on('click', function () {
        showModal('#pcCancelModal');
    });

    $('#pcCancelModalNo').on('click', function () { hideModal('#pcCancelModal'); });

    $('#pcCancelModalYes').on('click', function () {
        hideModal('#pcCancelModal');
        window.location.href = 'index.php?page=client_profile&tab=my_posts';
    });

    // Nút Quay lại (arrow) — hỏi confirm nếu form đã bị chỉnh
    let formDirty = false;

    $('#pcPostForm').on('input change', function () { formDirty = true; });

    $('#pcBackBtn').on('click', function (e) {
        e.preventDefault();
        if (formDirty) {
            showModal('#pcCancelModal');
        } else {
            window.location.href = 'index.php?page=client_profile&tab=my_posts';
        }
    });


    /* ════════════════════════════════
       9. AUTO-DISMISS FLASH ALERTS
    ════════════════════════════════ */

    setTimeout(function () { $('.pc-alert').fadeOut(400); }, 4000);

});


/* ════════════════════════════════
   10. FORMAT HELPERS (global)
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

(function () {
    // Tạo hidden file input một lần duy nhất
    const $imgPicker = $('<input type="file" accept="image/*" style="display:none;" id="pcContentImgPicker">');
    $('body').append($imgPicker);

    // Lưu vị trí con trỏ trước khi mở picker
    let savedRange = null;

    window.pcInsertImage = function () {
        // Lưu selection hiện tại
        const sel = window.getSelection();
        if (sel && sel.rangeCount > 0) {
            savedRange = sel.getRangeAt(0).cloneRange();
        }
        $imgPicker.val('').trigger('click');
    };

    $imgPicker.on('change', function () {
        const file = this.files[0];
        if (!file) return;

        // Hiện placeholder ảnh ngay lập tức
        const placeholderId = 'img-uploading-' + Date.now();
        const $editor = $('#pcContentEditor');
        $editor.focus();

        // Khôi phục vị trí con trỏ
        if (savedRange) {
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(savedRange);
        }

        document.execCommand(
            'insertHTML', false,
            '<span id="' + placeholderId + '" style="display:inline-flex;align-items:center;gap:6px;' +
            'background:#f0f4f8;border-radius:6px;padding:8px 14px;font-size:0.82rem;color:#6c7a8d;' +
            'font-family:Barlow,sans-serif;font-weight:600;margin:6px 0;">' +
            '<i class="fa-solid fa-spinner fa-spin"></i> Đang tải ảnh lên...</span>'
        );

        // Upload lên Cloudinary qua server
        const formData = new FormData();
        formData.append('image', file);

        $.ajax({
            url: 'index.php?page=api_upload_image',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                const $placeholder = $('#' + placeholderId);
                if (res.success && res.url) {
                    // Thay placeholder bằng ảnh thật
                    $placeholder.replaceWith(
                        '<img src="' + res.url + '" ' +
                        'style="max-width:100%;border-radius:8px;margin:12px 0;display:block;" alt="">'
                    );
                } else {
                    $placeholder.replaceWith(
                        '<span style="color:#991b1b;font-family:Barlow,sans-serif;font-size:0.82rem;">' +
                        '⚠ Upload thất bại: ' + (res.message || 'Không rõ lỗi') + '</span>'
                    );
                }
            },
            error: function () {
                $('#' + placeholderId).replaceWith(
                    '<span style="color:#991b1b;font-family:Barlow,sans-serif;font-size:0.82rem;">' +
                    '⚠ Không thể kết nối máy chủ.</span>'
                );
            },
            complete: function () {
                // Sync lại textarea hidden
                $('#pcContentInput').val($('#pcContentEditor').html());
            }
        });
    });
})();