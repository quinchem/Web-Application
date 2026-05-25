document
  .getElementById("forgotForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(
      "http://localhost/Web-Application/Admin_index.php?page=handle_forgot_password",
      { method: "POST", body: formData }
    )
      .then(response => response.text())
      .then(rawText => {
        // ✅ Log raw text để debug — xem có bị lẫn SMTP output không
        console.log("Raw response:", rawText);

        let data;
        try {
          data = JSON.parse(rawText);
        } catch (parseErr) {
          // Response bị lẫn output khác → không parse được
          console.error("JSON parse failed:", parseErr);
          Swal.fire({
            icon: "error",
            title: "Lỗi server",
            text: "Server trả về dữ liệu không hợp lệ, kiểm tra Console để xem chi tiết."
          });
          return;
        }

        if (data.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Thành công",
            text: data.message,
            confirmButtonColor: "#d10016"
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Lỗi",
            text: data.message,
            confirmButtonColor: "#d10016"
          });
        }
      })
      .catch(error => {
        console.error("Fetch error:", error);
        Swal.fire({
          icon: "error",
          title: "Lỗi hệ thống",
          text: "Không thể kết nối đến server!"
        });
      });
  });