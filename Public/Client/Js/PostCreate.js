$(function () {

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

    function showModal(selector) {
        $(selector).css('display', 'flex');
    }

    function hideModal(selector) {
        $(selector).hide();
    }


    /* ════════════════════════════════
   1. THUMBNAIL UPLOAD & CROP
════════════════════════════════ */

    const $zone = $('#pcThumbnailZone');
    const $input = $('#pcThumbnailInput');
    const $preview = $('#pcThumbnailPreview');
    const $placeholder = $('#pcUploadPlaceholder');
    const $removeBtn = $('#pcRemoveThumbBtn');

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

            if (cropperInstance) {
                cropperInstance.destroy();
                cropperInstance = null;
            }

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

            $('#pcCropModal').hide();
            cropperInstance.destroy();
            cropperInstance = null;
        }, 'image/jpeg', 0.92);
    });

    $('#pcCropCancel').on('click', function () {
        $('#pcCropModal').hide();
        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }
        $input.val('');
    });

    $removeBtn.on('click', function (e) {
        e.stopPropagation();
        $preview.hide().attr('src', '');
        $placeholder.show();
        $removeBtn.hide();
        $input.val('');
    });

    window.pcRemoveThumbnail = function () { $removeBtn.trigger('click'); };
    // Click vào ảnh preview → mở lại cropper với ảnh hiện tại
    $preview.on('click', function (e) {
        e.stopPropagation();
        const src = $preview.attr('src');
        if (!src) return;

        $('#pcCropImage').attr('src', src);
        $('#pcCropModal').css('display', 'flex');

        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }

        cropperInstance = new Cropper(document.getElementById('pcCropImage'), {
            aspectRatio: NaN,
            viewMode: 1,
            movable: true,
            zoomable: true,
            scalable: true,
            cropBoxResizable: true,
            rotatable: false,
        });
    });
    /* ════════════════════════════════
       2. DANH MỤC CHA → CON
    ════════════════════════════════ */

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
        const words = text ? text.split(/\s+/).length : 0;
        $('#pcWordCount').text(words);
        $('#pcContentInput').val($(this).html());
    });


    /* ════════════════════════════════
       5. LABEL ĐỔI THEO TIÊU ĐỀ
    ════════════════════════════════ */

    $('#pcTitleInput').on('input', function () {
        const val = $(this).val().trim();
        $('#pcDraftLabel').text(val ? val : 'Bài viết nháp');
    });


    /* ════════════════════════════════
       6. NÚT LƯU BẢN NHÁP
          - Gửi AJAX → không reload trang
          - Nhận post_id trả về → cập nhật hidden input
          - Hiện toast thành công
    ════════════════════════════════ */

    $('#pcSaveDraftBtn').on('click', function (e) {
        e.preventDefault();

        // Sync editor content
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
                    // Cập nhật post_id để lần lưu sau là UPDATE, không phải INSERT
                    if (res.post_id) {
                        $('#pcPostId').val(res.post_id);
                    }
                    $('#pcStatusBadge').text('Bản nháp').removeClass().addClass('pc-status-badge');
                    showToast('✓ Đã lưu bản nháp thành công!');
                } else {
                    showToast(res.message || 'Có lỗi xảy ra, vui lòng thử lại.', true);
                }
            },
            error: function () {
                showToast('Không thể kết nối máy chủ.', true);
            },
            complete: function () {
                $btn.prop('disabled', false).text('Lưu bản nháp');
            }
        });
    });


    /* ════════════════════════════════
       7. NÚT ĐĂNG BÀI → MODAL XÁC NHẬN
    ════════════════════════════════ */

    $('#pcPublishBtn').on('click', function (e) {
        e.preventDefault();
        $('#pcContentInput').val($('#pcContentEditor').html());

        const title = $('#pcTitleInput').val().trim();
        const summary = $('#pcSummaryInput').val().trim();
        const categoryId = $('#pcChildCat').val() || $('#pcParentCat').val();
        const content = $('#pcContentEditor').text().trim();

        if (!title) {
            showToast('Vui lòng nhập tiêu đề bài viết.', true); return;
        }
        if (!summary) {
            showToast('Vui lòng nhập tóm tắt nội dung.', true); return;
        }
        if (!categoryId) {
            showToast('Vui lòng chọn danh mục bài viết.', true); return;
        }
        if (!content) {
            showToast('Vui lòng nhập nội dung bài viết.', true); return;
        }

        showModal('#pcPublishModal');
    });

    $('#pcModalCancel').on('click', function () {
        hideModal('#pcPublishModal');
    });

    $('#pcModalConfirm').on('click', function () {
        hideModal('#pcPublishModal');

        const $btn = $(this).prop('disabled', true).text('Đang gửi...');

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
                    $('#pcStatusBadge').text('Chờ duyệt')
                        .removeClass().addClass('pc-status-badge pending');
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
       8. NÚT HUỶ BẢN NHÁP
          - Nếu chưa lưu (không có post_id) → về trang my_posts ngay
          - Nếu đã lưu → hiện modal xác nhận xoá
    ════════════════════════════════ */

    $('#pcCancelBtn').on('click', function () {
        const postId = $('#pcPostId').val();
        if (!postId) {
            window.location.href = 'index.php?page=client_profile&tab=my_posts';
            return;
        }
        showModal('#pcCancelModal');
    });

    $('#pcCancelModalNo').on('click', function () {
        hideModal('#pcCancelModal');
    });

    $('#pcCancelModalYes').on('click', function () {
        const postId = $('#pcPostId').val();
        hideModal('#pcCancelModal');

        $.ajax({
            url: 'index.php?page=client_delete_post',
            method: 'POST',
            data: { post_id: postId },
            dataType: 'json',
            success: function () {
                window.location.href = 'index.php?page=client_profile&tab=my_posts';
            },
            error: function () {
                window.location.href = 'index.php?page=client_profile&tab=my_posts';
            }
        });
    });
    /* ════════════════════════════════
   8b. NÚT QUAY LẠI
════════════════════════════════ */
    $('#pcBackBtn').on('click', function (e) {
        e.preventDefault();
        window.location.href = 'index.php?page=client_profile&tab=my_posts';
    });

    /* ════════════════════════════════
       9. AUTO-DISMISS FLASH ALERTS
    ════════════════════════════════ */

    setTimeout(function () {
        $('.pc-alert').fadeOut(400);
    }, 4000);

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