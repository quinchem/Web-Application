// Public/Client/Js/Current_day.js

document.addEventListener('DOMContentLoaded', function() {
    function updateCurrentDate() {
        const dateEl = document.getElementById('current-date');
        
        // Kiểm tra nếu tìm thấy thẻ trên giao diện thì mới chạy tiếp
        if (dateEl) {
            const now = new Date();
            const days = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
            const dayName = days[now.getDay()];
            const day = now.getDate();
            const month = now.getMonth() + 1;
            const year = now.getFullYear();

            dateEl.innerHTML = `${dayName}, ngày ${day} tháng ${month} năm ${year}`;
        }
    }s

    updateCurrentDate();
});