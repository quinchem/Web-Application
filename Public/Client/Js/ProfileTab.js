/**
 * Public/Client/Js/ProfileTab.js
 * Chỉ làm duy nhất nhiệm vụ tải động Ajax các tab nội dung Hồ sơ cá nhân
 * Đã nâng cấp: Giữ trạng thái Tab trên URL khi F5 hoặc Back/Forward trình duyệt
 */

document.addEventListener("DOMContentLoaded", function () {
    const menuItems = document.querySelectorAll("#account-menu .list-group-item");
    const contentArea = document.getElementById("dynamic-content");

    if (!menuItems.length || !contentArea) return;

    // 1. ĐỌC THAM SỐ 'tab' TỪ URL KHI VỪA VÀO TRANG 
    const urlParams = new URLSearchParams(window.location.search);
    const activeTabFromUrl = urlParams.get('tab');

    // Hàm gọi Ajax tải nội dung trang con (Đã thêm tham số shouldPushState)
    function loadTabContent(targetPage, shouldPushState = true) {
        
        // CẬP NHẬT THANH URL TRÌNH DUYỆT (Không làm tải lại trang)
        if (shouldPushState) {
            const newUrl = `index.php?page=client_profile&tab=${targetPage}`;
            history.pushState({ tab: targetPage }, '', newUrl);
        }

        // ĐỒNG BỘ CLASS ACTIVE CHO MENU TRÁI: Tìm đúng mục data-target để bật đèn
        menuItems.forEach(i => {
            i.classList.remove("active");
            if (i.getAttribute("data-target") === targetPage) {
                i.classList.add("active");
            }
        });

        // Hiển thị hiệu ứng loading spinner của bạn
        contentArea.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        `;

       let fetchUrl = `index.php?page=client_account_sub_page&action=${targetPage}`;

if (targetPage === 'saved') {
    const currentParams = new URLSearchParams(window.location.search);
    const savedPage = currentParams.get('saved_page') || 1;

    fetchUrl = `index.php?page=client_saved_posts_page&saved_page=${savedPage}`;
}

fetch(fetchUrl)
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
            
            const targetPage = this.getAttribute("data-target");
            if (targetPage) {
                // Truyền true để khi click chuột thì URL trên thanh địa chỉ thay đổi theo
                loadTabContent(targetPage, true); 
            }
        });
    });

    // 2. TỰ ĐỘNG TẢI TAB KHI KHỞI ĐỘNG (Xử lý thông minh khi nhấn F5)
    if (activeTabFromUrl) {
        // Nếu trên thanh địa chỉ có sẵn dạng ?tab=doi-mat-khau thì ưu tiên mở tab đó luôn
        loadTabContent(activeTabFromUrl, false); // false vì URL đã đổi sẵn rồi
    } else {
        // Nếu URL trống (vừa ấn vào Profile), quay về lấy mục active mặc định ban đầu của bạn
        const initialActive = document.querySelector("#account-menu .list-group-item.active");
        if (initialActive) {
            loadTabContent(initialActive.getAttribute("data-target"), false);
        }
    }

    // 3. XỬ LÝ KHI NGƯỜI DÙNG BẤM NÚT QUAY LẠI (BACK) HOẶC TIẾP TỤC (FORWARD) CỦA TRÌNH DUYỆT
    window.addEventListener("popstate", function (e) {
        if (e.state && e.state.tab) {
            loadTabContent(e.state.tab, false);
        }
    });
});