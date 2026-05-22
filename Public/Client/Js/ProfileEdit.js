/**
 * Public/Client/Js/ProfileEdit.js
 * Quản lý tương tác, kích hoạt form nhập liệu trong giao diện Chỉnh sửa hồ sơ (edit.php)
 */

document.addEventListener("DOMContentLoaded", function () {
    
    // Sử dụng ủy quyền sự kiện (Event Delegation) toàn cục
    // Giúp bắt trúng nút "Chỉnh sửa" ngay khi nó được Ajax tải động về màn hình
    document.addEventListener("click", function (e) {
        if (e.target && e.target.id === "btnEnableEdit") {
            e.preventDefault();

            // 1. Mở khóa hai ô nhập liệu Họ tên và Email
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
            
            // 2. Mở khóa các nút tròn chọn giới tính (Radio buttons)
            const radios = document.getElementsByName("gender");
            radios.forEach(radio => {
                radio.disabled = false;
            });

            // 3. Kích hoạt sáng nút LƯU lên để cho phép gửi dữ liệu form
            const btnSubmitForm = document.getElementById("btnSubmitForm");
            if (btnSubmitForm) {
                btnSubmitForm.disabled = false;
            }

            // 4. Khóa và làm mờ chính nó (nút Chỉnh sửa) để tránh người dùng bấm lại nhiều lần
            e.target.disabled = true;
            e.target.style.opacity = "0.5";
        }
    });
});