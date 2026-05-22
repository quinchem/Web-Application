/**
 * Public/Client/Js/ProfileTab.js
 * Chỉ làm duy nhất nhiệm vụ tải động Ajax các tab nội dung Hồ sơ cá nhân
 */

document.addEventListener("DOMContentLoaded", function () {
    const menuItems = document.querySelectorAll("#account-menu .list-group-item");
    const contentArea = document.getElementById("dynamic-content");

    if (!menuItems.length || !contentArea) return;

    // Hàm gọi Ajax tải nội dung trang con
    function loadTabContent(targetPage) {
        contentArea.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        `;

        fetch(`index.php?page=client_account_sub_page&action=${targetPage}`)
            .then(response => {
                if (!response.ok) throw new Error("Lỗi kết nối.");
                return response.text();
            })
            .then(htmlContent => {
                contentArea.innerHTML = htmlContent;
            })
            .catch(error => {
                console.error(error);
                contentArea.innerHTML = `<div class="alert alert-danger">Không thể tải nội dung.</div>`;
            });
    }

    // Lắng nghe sự kiện click chuyển mục Menu dọc bên trái
    menuItems.forEach(item => {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            if (this.classList.contains("active")) return;

            menuItems.forEach(i => i.classList.remove("active"));
            this.classList.add("active");

            const targetPage = this.getAttribute("data-target");
            if (targetPage) {
                loadTabContent(targetPage);
            }
        });
    });

    // Tự động tải mục đầu tiên khi vừa vào trang
    const initialActive = document.querySelector("#account-menu .list-group-item.active");
    if (initialActive) {
        loadTabContent(initialActive.getAttribute("data-target"));
    }
});