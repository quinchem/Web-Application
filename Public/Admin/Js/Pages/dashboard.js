// Public/Admin/Js/Pages/dashboard.js

let postChart = null;
let statusChart = null;

$(document).ready(function () {

        // Flatpickr cho 2 ô date
    const fpFrom = flatpickr('#fromDate', {
        locale: 'vn',
        dateFormat: 'd/m/Y',          // hiển thị DD/MM/YYYY
        allowInput: true,
        onChange: function(selectedDates, dateStr) {
            if (fpTo && selectedDates[0]) {
                fpTo.set('minDate', selectedDates[0]);
            }
        }
    });
    const fpTo = flatpickr('#toDate', {
        locale: 'vn',
        dateFormat: 'd/m/Y',
        allowInput: true,
        onChange: function(selectedDates, dateStr) {
            if (fpFrom && selectedDates[0]) {
                fpFrom.set('maxDate', selectedDates[0]);
            }
        }
    });

    // ── CATEGORY DROPDOWN ──────────────────────────
$(document).on('click', '#dashCategoryTrigger', function(e) {
    e.stopPropagation();
    $('#dashCategoryDropdown').toggleClass('open');
});

$(document).on('click', '.dash-cat-parent-label', function(e) {
    e.stopPropagation();
    $(this).closest('.dash-cat-parent').toggleClass('open');
});

$(document).on('click', '.dash-cat-child', function(e) {
    e.stopPropagation();
    const value = $(this).data('value');
    const label = $(this).data('label');
    $('#categoryFilter').val(value);
    $('#dashCategoryLabel').text(label);
    $('.dash-cat-child').removeClass('active');
    $(this).addClass('active');
    $('#dashCategoryDropdown').removeClass('open');
});

$(document).on('click', '.dash-cat-reset', function(e) {
    e.stopPropagation();
    $('#categoryFilter').val('');
    $('#dashCategoryLabel').text('Danh mục');
    $('.dash-cat-child').removeClass('active');
    $('#dashCategoryDropdown').removeClass('open');
});

// Đóng dropdown khi click ngoài
$(document).on('click', function(e) {
    if (!$(e.target).closest('#dashCategoryDropdown').length) {
        $('#dashCategoryDropdown').removeClass('open');
    }
});

    loadDashboard();

    $('#filterBtn').click(function () {

        loadDashboard();
    });
});


function formatDateDMY(dateStr) {
    if (!dateStr) return dateStr;
    const parts = dateStr.split('-');
    if (parts.length !== 3) return dateStr;
    return parts[2] + '/' + parts[1] + '/' + parts[0];
}
// =========================
// LOAD DASHBOARD
// =========================

function loadDashboard()
{

        // Convert DD/MM/YYYY → YYYY-MM-DD để gửi server
    function toISO(dmy) {
        if (!dmy) return '';
        const parts = dmy.split('/');
        if (parts.length !== 3) return dmy;
        return parts[2] + '-' + parts[1] + '-' + parts[0];
    }

    $.ajax({

        url: 'Admin_index.php?page=dashboard_ajax',

        type: 'POST',

        dataType: 'json',

        data: {

            fromDate: $('#fromDate').val(),

            toDate: $('#toDate').val(),

            category: $('#categoryFilter').val(),

            author: $('#authorFilter').val()
        },

        success: function (response)
        {
            console.log(response);

            loadKPI(response.kpi);

            loadChart(response.chart);

            loadStatusChart(response.status);

            loadTopPosts(response.topPosts);
        },

        error: function (xhr)
        {
            console.log(xhr.responseText);

            alert('Lỗi load dashboard');
        }
    });
}


// =========================
// KPI
// =========================


function loadKPI(kpi) {
    $('#totalPosts').text(Number(kpi.totalPosts  || 0).toLocaleString('vi-VN'));
    $('#pendingPosts').text(Number(kpi.pendingPosts || 0).toLocaleString('vi-VN'));
    $('#totalAuthors').text(Number(kpi.totalAuthors || 0).toLocaleString('vi-VN'));
    $('#totalViews').text(Number(kpi.totalViews  || 0).toLocaleString('vi-VN'));

    // Đổi label nếu có filter date
    if (kpi.hasDateFilter) {
        const from = kpi.fromDate ? formatDateDMY(kpi.fromDate) : null;
        const to   = kpi.toDate   ? formatDateDMY(kpi.toDate)   : null;

        let label = 'LƯỢT XEM';
        if (from && to)   label = `LƯỢT XEM (${from} - ${to})`;
        else if (from)    label = `LƯỢT XEM (từ ${from})`;
        else if (to)      label = `LƯỢT XEM (đến ${to})`;

        $('#totalViews').closest('.stat-card').find('p').text(label);
    } else {
        $('#totalViews').closest('.stat-card').find('p').text('TỔNG LƯỢT XEM');
    }
}


// =========================
// CHART BÀI VIẾT
// =========================

function loadChart(chartResponse)
{
    if (window.postChartInstance) {
        window.postChartInstance.destroy();
        window.postChartInstance = null;
    }

    const $wrapper = $('#postChart').closest('.chart-wrapper');
    $wrapper.find('.empty-state').remove();

    const groupBy  = chartResponse.groupBy ?? 'day';
    const chartData = chartResponse.data  ?? [];

    if (!chartData || chartData.length === 0) {
        $wrapper.append(`
            <div class="empty-state">
                <i class="fa-regular fa-chart-bar"></i>
                <p>Không có dữ liệu bài viết trong khoảng thời gian này</p>
            </div>
        `);
        return;
    }

    // Format label theo groupBy
    const labels = chartData.map(item => {
        const raw = item.post_date;

        if (groupBy === 'year') {
            return raw; // "2024"
        }

        if (groupBy === 'month') {
            // raw = "2025-03" → "Th.03/2025"
            const parts = raw.split('-');
            return `Th.${parts[1]}/${parts[0]}`;
        }

        // day: raw = "2025-03-01" → "01/03"
        return formatDateDMY(raw).slice(0, 5); // lấy DD/MM
    });

    const totals = chartData.map(item => parseInt(item.total));

    window.postChartInstance = new Chart(
        document.getElementById('postChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label     : 'Bài viết',
                data      : totals,
                tension   : 0.4,
                borderColor     : '#2563eb',
                backgroundColor : 'rgba(37, 99, 235, 0.08)',
                pointBackgroundColor: '#2563eb',
                pointBorderColor    : '#fff',
                pointBorderWidth    : 2,
                pointRadius         : 4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color     : '#2563eb',
                        font      : { weight: '700', size: 13 },
                        boxWidth  : 14,
                        boxHeight : 14,
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            if (Number.isInteger(value)) return value;
                        }
                    }
                }
            }
        }
    });
}


// =========================
// CHART STATUS
// =========================

function loadStatusChart(statusData)
{
        const $card    = $('#statusChart').closest('.dashboard-card');
    const $wrapper = $('#statusChart').closest('.chart-wrapper');

    $wrapper.find('.empty-state').remove();
    $card.find('.status-legend').remove();

    if (!statusData || statusData.length === 0) {
        if (window.statusChartInstance) {
            window.statusChartInstance.destroy();
            window.statusChartInstance = null;
        }
        $wrapper.append(`
            <div class="empty-state">
                <i class="fa-regular fa-chart-pie"></i>
                <p>Không có dữ liệu trạng thái</p>
            </div>
        `);
        return;
    }

    const labelMap = {
        'approved' : 'Đã duyệt',
        'pending'  : 'Chờ duyệt',
        'draft'    : 'Bản nháp',
        'rejected' : 'Từ chối',
        'hidden'   : 'Ẩn'
    };

    const colorMap = {
        'approved' : '#1D9E75',
        'pending'  : '#E24B4A',
        'draft'    : '#378ADD',
        'rejected' : '#EF9F27',
        'hidden'   : '#888780'
    };

    const labels     = statusData.map(item => labelMap[item.status] ?? item.status);
    const totals     = statusData.map(item => parseInt(item.total));
    const colors     = statusData.map(item => colorMap[item.status] ?? '#aaa');
    const grandTotal = totals.reduce((a, b) => a + b, 0);

    if(window.statusChartInstance){
        window.statusChartInstance.destroy();
    }

    window.statusChartInstance = new Chart(
        document.getElementById('statusChart'), {

        type: 'pie',

        data: {
            labels: labels,
            datasets: [{
                data: totals,
                backgroundColor: colors,
                borderWidth: 0
            }]
        },

            options: {
                responsive: true,
                maintainAspectRatio: true,

                cutout: '35%',          // ← tạo lỗ giữa

                layout: { padding: 0 },

                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const pct = grandTotal > 0
                                    ? Math.round(ctx.raw / grandTotal * 100) : 0;
                                return ` ${ctx.label}: ${ctx.raw} bài (${pct}%)`;
                            }
                        }
                    }
                }
            }
    });

    // Re-render legend với dot + background
    let legendHtml = '';

    statusData.forEach(item => {
        const pct   = grandTotal > 0
            ? Math.round(item.total / grandTotal * 100) : 0;
        const label = labelMap[item.status] ?? item.status;
        const color = colorMap[item.status] ?? '#aaa';

            legendHtml += `
                <div class="status-legend-item">
                    <span class="legend-dot" style="background:${color}"></span>
                    <span class="legend-label">${label}</span>
                    <span class="legend-count">${Number(item.total).toLocaleString('vi-VN')}</span>
                    <span class="legend-pct">${pct}%</span>
                </div>
            `;  
    });


    $card.find('.status-legend').remove();

    $('#statusChart')
        .closest('.chart-wrapper')
        .after(`<div class="status-legend">${legendHtml}</div>`);
}


// =========================
// TOP POSTS
// =========================

function loadTopPosts(posts)
{
        if (!posts || posts.length === 0) {
        $('#topPostsBody').html(`
            <div class="empty-state">
                <i class="fa-regular fa-newspaper"></i>
                <p>Không có bài viết nổi bật trong khoảng thời gian này</p>
            </div>
        `);
        return;
    }

    let rows = '';

    posts.forEach((post, index) => {

        const rank       = String(index + 1).padStart(2, '0');
        const isTop      = index === 0;
        const badgeBg    = isTop ? '#fef2f2' : '#f0f2f5';
        const badgeColor = isTop ? '#e52328'  : '#9fb0bc';

        rows += `
            <tr class="top-post-row">

                <td class="col-rank">
                    <div class="rank-badge"
                        style="background:${badgeBg};color:${badgeColor}">
                        ${rank}
                    </div>
                </td>

                <td class="col-title" colspan="4">
                    <h4 class="post-title-text">
                        ${post.title}
                    </h4>
                </td>

            </tr>

            <tr class="top-post-meta-row">

                <td class="col-rank"></td>

                <td class="col-category">
                    <span class="category-tag">
                        ${post.category_name ?? ''}
                    </span>
                </td>

                <td class="col-views">
                    <span class="meta-views">
                        <i class="fa-regular fa-eye"></i>
                        ${Number(post.view_count || 0)
                            .toLocaleString('vi-VN')} lượt xem
                    </span>
                </td>

                <td class="col-likes">
                    <span class="meta-stat">
                        <i class="fa-regular fa-heart"></i>
                        ${Number(post.likes_count || 0)
                            .toLocaleString('vi-VN')} lượt thích
                    </span>
                </td>

                <td class="col-comments">
                    <span class="meta-stat">
                        <i class="fa-regular fa-comment"></i>
                        ${Number(post.comments_count || 0)
                            .toLocaleString('vi-VN')} bình luận
                    </span>
                </td>

            </tr>

            <tr class="top-post-spacer">
                <td colspan="5"></td>
            </tr>
        `;
    });

    $('#topPostsBody').html(
        `<table class="top-posts-table">${rows}</table>`
    );
}