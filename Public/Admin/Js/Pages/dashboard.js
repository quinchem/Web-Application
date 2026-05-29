// Public/Admin/Js/Pages/dashboard.js

let postChart = null;
let statusChart = null;

$(document).ready(function () {

    loadDashboard();

    $('#filterBtn').click(function () {

        loadDashboard();
    });
});


// =========================
// LOAD DASHBOARD
// =========================

function loadDashboard()
{
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


function loadKPI(kpi)
{
    $('#totalPosts').text(
        Number(kpi.totalPosts || 0)
        .toLocaleString('vi-VN')
    );

    $('#pendingPosts').text(
        Number(kpi.pendingPosts || 0)
        .toLocaleString('vi-VN')
    );

    $('#totalAuthors').text(
        Number(kpi.totalAuthors || 0)
        .toLocaleString('vi-VN')
    );

    $('#totalViews').text(
        Number(kpi.totalViews || 0)
        .toLocaleString('vi-VN')
    );
}


// =========================
// CHART BÀI VIẾT
// =========================

function loadChart(chartData)
{
    const labels = chartData.map(
        item => item.post_date
    );

    const totals = chartData.map(
        item => item.total
    );

    if(window.postChartInstance){

        window.postChartInstance.destroy();
    }

    const ctx =
    document.getElementById('postChart');

    window.postChartInstance =
    new Chart(ctx, {

        type: 'line',

        data: {

            labels: labels,

            datasets: [{

                label: 'Bài viết',

                data: totals,

                tension: 0.4
            }]
        }
    });
}


// =========================
// CHART STATUS
// =========================

function loadStatusChart(statusData)
{
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
                                return ` ${ctx.label}: ${pct}%`;
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
                <span class="legend-dot"
                    style="background:${color}">
                </span>
                <span class="legend-label">${label}</span>
                <span class="legend-pct">${pct}%</span>
            </div>
        `;
    });

    const $card = $('#statusChart').closest('.dashboard-card');

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