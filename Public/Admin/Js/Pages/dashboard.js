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
        Number(kpi.total_posts || 0)
        .toLocaleString('vi-VN')
    );

    $('#pendingPosts').text(
        Number(kpi.pending_posts || 0)
        .toLocaleString('vi-VN')
    );

    $('#totalAuthors').text(
        Number(kpi.total_authors || 0)
        .toLocaleString('vi-VN')
    );

    $('#totalViews').text(
        Number(kpi.total_views || 0)
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
    const labels = statusData.map(
        item => item.status
    );

    const totals = statusData.map(
        item => item.total
    );

    if(window.statusChartInstance){

        window.statusChartInstance.destroy();
    }

    const ctx =
    document.getElementById('statusChart');

    window.statusChartInstance =
    new Chart(ctx, {

        type: 'doughnut',

        data: {

            labels: labels,

            datasets: [{

                data: totals
            }]
        }
    });
}


// =========================
// TOP POSTS
// =========================

function loadTopPosts(posts)
{
    let html = '';

    posts.forEach(post => {

        html += `

            <tr>

                <td class="post-title">
                    ${post.title}
                </td>

                <td>
                    ${post.category_name}
                </td>

                <td>
                    ${Number(post.view_count)
                        .toLocaleString('vi-VN')}
                </td>

                <td>
                    ${post.likes_count}
                </td>

                <td>
                    ${post.comments_count}
                </td>

            </tr>
        `;
    });

    $('#topPostsBody').html(html);
}