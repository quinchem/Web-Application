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
        // Sync hidden textarea
        $('#pcContentInput').val(EDIT.content);
    }

    // Fill tags ban đầu từ server
    let tags = Array.isArray(EDIT.tags) ? EDIT.tags.filter(Boolean) : [];
    _renderTags();
    // Không cần _syncTags() vì PHP đã render hidden inputs,
    // chỉ cần sync lại khi user thay đổi


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
       1. THUMBNAIL UPLOAD & CROP
    ════════════════════════════════ */

    const $zone        = $('#pcThumbnailZone');
    const $input       = $('#pcThumbnailInput');
    const $preview     = $('#pcThumbnailPreview');
    const $placeholder = $('#pcUploadPlaceholder');
    const $removeBtn   = $('#pcRemoveThumbBtn');

    let cropperInstance = null;

    $zone.on('click', function (e) {
        if ($(e.target).closest('#pcRemoveThumbBtn').length) return;
        if ($(e.target).is('#pcThumbnailInput')) return;
        e.preventDefault(); e.stopPropagation();
        $input[0].click();
    });

    $input.on('change', function () {
        const file = this.files[0];
        if (file) _openCropper(file);
    });

    $zone.on('dragover', function (e) {
        e.preventDefault(); $(this).addClass('dragover');
    }).on('dragleave', function () {
        $(this).removeClass('dragover');
    }).on('drop', function (e) {
        e.preventDefault(); $(this).removeClass('dragover');
        const file = e.originalEvent.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            $input[0].files = dt.files;
            _openCropper(file);
        }
    });

    function _openCropper(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#pcCropImage').attr('src', e.target.result);
            $('#pcCropModal').css('display', 'flex');

            if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }

            cropperInstance = new Cropper(document.getElementById('pcCropImage'), {
                aspectRatio: NaN,
                viewMode: 1,
                movable: true,
                zoomable: true,
                scalable: true,
                cropBoxResizable: true,
                rotatable: false,
            });
        };
        reader.readAsDataURL(file);
    }

    $('#pcCropConfirm').on('click', function () {
        if (!cropperInstance) return;
        cropperInstance.getCroppedCanvas().toBlob(function (blob) {
            const croppedFile = new File([blob], 'thumbnail.jpg', { type: 'image/jpeg' });
            const dt = new DataTransfer();
            dt.items.add(croppedFile);
            $input[0].files = dt.files;

            const url = URL.createObjectURL(blob);
            $placeholder.hide();
            $preview.attr('src', url).show();
            $removeBtn.show();

            // Xoá existing_thumbnail vì user đã upload ảnh mới
            $('#pcExistingThumbnail').val('');

            $('#pcCropModal').hide();
            cropperInstance.destroy();
            cropperInstance = null;
        }, 'image/jpeg', 0.92);
    });

    $('#pcCropCancel').on('click', function () {
        $('#pcCropModal').hide();
        if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
        $input.val('');
    });

    $removeBtn.on('click', function (e) {
        e.stopPropagation();
        $preview.hide().attr('src', '');
        $placeholder.show();
        $removeBtn.hide();
        $input.val('');
        // Đánh dấu đã xoá thumbnail cũ
        $('#pcExistingThumbnail').val('');
    });

    // Click ảnh preview → mở lại cropper
    $preview.on('click', function (e) {
        e.stopPropagation();
        const src = $preview.attr('src');
        if (!src) return;

        $('#pcCropImage').attr('src', src);
        $('#pcCropModal').css('display', 'flex');

        if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }

        cropperInstance = new Cropper(document.getElementById('pcCropImage'), {
            aspectRatio: NaN, viewMode: 1,
            movable: true, zoomable: true, scalable: true,
            cropBoxResizable: true, rotatable: false,
        });
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

        // Hiện nút "Thêm tag" nếu không có tag nào và input đang ẩn
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

    // Sync tags hidden inputs ngay từ đầu để đồng bộ với mảng tags JS
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

        const title      = $('#pcTitleInput').val().trim();
        const summary    = $('#pcSummaryInput').val().trim();
        const categoryId = $('#pcChildCat').val() || $('#pcParentCat').val();
        const content    = $('#pcContentEditor').text().trim();

        if (!title)      { showToast('Vui lòng nhập tiêu đề bài viết.', true); return; }
        if (!summary)    { showToast('Vui lòng nhập tóm tắt nội dung.', true); return; }
        if (!categoryId) { showToast('Vui lòng chọn danh mục bài viết.', true); return; }
        if (!content)    { showToast('Vui lòng nhập nội dung bài viết.', true); return; }

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