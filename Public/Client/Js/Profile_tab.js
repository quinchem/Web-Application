// Public/Client/Js/Profile_tab.js

document.addEventListener("DOMContentLoaded", function () {
    const menuLinks = document.querySelectorAll("#account-menu .list-group-item");
    const contentArea = document.getElementById("dynamic-content");

    if (menuLinks.length > 0 && contentArea) {
        menuLinks.forEach(link => {
            link.addEventListener("click", function (e) {
                e.preventDefault(); // Ngăn hành vi giật trang / reload trang mặc định

                // Đổi class Active giữa các mục menu
                menuLinks.forEach(item => item.classList.remove("active"));
                this.classList.add("active");

                const targetPage = this.getAttribute("data-target");

                // Giao diện Spinner trạng thái chờ phản hồi của Bootstrap
                contentArea.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center py-5 text-muted">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        <span>Đang tải nội dung...</span>
                    </div>
                `;

                // Gọi request xử lý đến hàm loadSubPage trong UserController thông qua Route hệ thống
                fetch(`/client/account-sub-page?action=${targetPage}`)
                    .then(response => {
                        if (!response.ok) throw new Error("Không thể kết nối đến máy chủ Client.");
                        return response.text();
                    })
                    .then(htmlContent => {
                        // Cập nhật lại ruột card bên phải một cách mượt mà
                        contentArea.innerHTML = htmlContent;
                    })
                    .catch(error => {
                        contentArea.innerHTML = `
                            <div class="alert alert-danger m-0" role="alert">
                                Lỗi: ${error.message}
                            </div>
                        `;
                    });
            });
        });
    }
});