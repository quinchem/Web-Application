document.addEventListener("DOMContentLoaded", function () {
  const addBtn = document.querySelector(".add-btn");
  const filterBtn = document.querySelector(".filter-btn");
  const approveBtn = document.querySelector(".approve-btn");

  if (addBtn) {
    addBtn.addEventListener("click", function () {
      alert("Chức năng đăng bài mới sẽ được xử lý ở bước tiếp theo.");
    });
  }

  if (filterBtn) {
    filterBtn.addEventListener("click", function () {
      alert("Chức năng lọc bài viết đang được mô phỏng.");
    });
  }

  if (approveBtn) {
    approveBtn.addEventListener("click", function () {
      alert("Đã duyệt bài viết thành công.");
    });
  }
});