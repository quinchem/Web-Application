/**
 * Public/Client/Js/ProfileEdit.js
 */
document.addEventListener("DOMContentLoaded", function () {
    
    // ==========================================
    // 1. BẮT SỰ KIỆN CLICK TOÀN CỤC (EVENT DELEGATION)
    // ==========================================
    document.addEventListener("click", function (e) {
        
        // ---> Xử lý click nút "Chỉnh sửa" thông tin form
        const btnEdit = e.target.closest('#btnEnableEdit');
        if (btnEdit) {
            e.preventDefault();

            const inputUsername = document.getElementById("inputUsername");
            const inputFullname = document.getElementById("inputFullname");

            if (inputUsername) {
                inputUsername.disabled = false;
                inputUsername.readOnly = false;
            }
            if (inputFullname) {
                inputFullname.disabled = false;
                inputFullname.readOnly = false;
            }
            
            document.getElementsByName("gender").forEach(radio => {
                radio.disabled = false;
            });

            const btnSubmitForm = document.getElementById("btnSubmitForm");
            if (btnSubmitForm) {
                btnSubmitForm.disabled = false;
            }

            btnEdit.disabled = true;
        }

        // ---> Xử lý click nút "TẢI ẢNH MỚI"
        const btnUpload = e.target.closest('#btnUploadAvatar');
        if (btnUpload) {
            e.preventDefault();
            const avatarInput = document.getElementById('avatarInput');
            if (avatarInput) {
                avatarInput.click(); 
            }
        }

        // ---> Xử lý click nút "GỠ BỎ"
        const btnRemove = e.target.closest('#btnRemoveAvatar');
        if (btnRemove) {
            e.preventDefault();
            
            const avatarInput = document.getElementById("avatarInput");
            const avatarPreview = document.getElementById("avatarPreview");
            const removeAvatarFlag = document.getElementById("removeAvatarFlag");
            const btnSubmitForm = document.getElementById("btnSubmitForm");

            // Xóa file tạm đã chọn trong input trước đó
            if (avatarInput) avatarInput.value = ""; 
            
            // Thay đổi ảnh hiển thị tức thì thành hình mặc định
            if (avatarPreview) {
                avatarPreview.src = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
            }
            
            // BẬT CỜ BÁO LỆNH XÓA ẢNH (Gửi dữ liệu là 1 về server)
            if (removeAvatarFlag) {
                removeAvatarFlag.value = "1";
            }
            
            // Sáng nút Lưu lên để gửi form lên hệ thống
            if (btnSubmitForm) {
                btnSubmitForm.disabled = false;
            }
        }
    });

    // ==========================================
    // 2. BẮT SỰ KIỆN CHANGE (KHI CHỌN FILE)
    // ==========================================
    document.addEventListener("change", function(e) {
        
        if (e.target && e.target.id === "avatarInput") {
            if (e.target.files && e.target.files[0]) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    const avatarPreview = document.getElementById('avatarPreview');
                    if (avatarPreview) {
                        avatarPreview.src = event.target.result; 
                    }
                }
                reader.readAsDataURL(e.target.files[0]);
                
                // TẮT CỜ LỆNH XÓA VỀ "0" vì người dùng đã đổi ý tải lên file ảnh mới
                const removeAvatarFlag = document.getElementById('removeAvatarFlag');
                if (removeAvatarFlag) {
                    removeAvatarFlag.value = "0";
                }

                const btnSubmitForm = document.getElementById("btnSubmitForm");
                if (btnSubmitForm) {
                    btnSubmitForm.disabled = false;
                }
            }
        }
    });
    const profileForm = document.getElementById("profileForm");
    if (profileForm) {
        profileForm.addEventListener("submit", function () {
            // Lấy tất cả thẻ input trong form và gỡ bỏ disabled
            const inputs = profileForm.querySelectorAll("input");
            inputs.forEach(input => input.disabled = false);
        });
    }
});