document.addEventListener('click', function (e) {
    const pageBtn = e.target.closest('.js-my-post-page');

    if (pageBtn) {
        e.preventDefault();

        const page = pageBtn.dataset.page;
        loadMyPostsPage(page);
    }
});

document.addEventListener('submit', function (e) {
    const filterForm = e.target.closest('.js-my-post-filter');

    if (filterForm) {
        e.preventDefault();

        loadMyPostsPage(1);
    }
});

function getMyPostsFilterParams() {
    const form = document.querySelector('.js-my-post-filter');
    const params = new URLSearchParams();

    if (!form) {
        return params;
    }

    const formData = new FormData(form);

    params.set('keyword', formData.get('keyword') || '');
    params.set('category', formData.get('category') || '');
    params.set('status', formData.get('status') || '');
    params.set('date', formData.get('date') || '');

    return params;
}

function loadMyPostsPage(page) {
    const contentBox = document.querySelector('#dynamic-content');

    if (!contentBox) {
        console.error('Không tìm thấy #dynamic-content');
        return;
    }

    const params = getMyPostsFilterParams();
    params.set('my_post_page', page);

    contentBox.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    `;

    fetch('index.php?page=client_my_posts_page&' + params.toString())
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Không tải được danh sách bài viết');
            }

            return response.text();
        })
        .then(function (html) {
            contentBox.innerHTML = html;

            const newUrl =
                'index.php?page=client_profile&tab=my_posts&' +
                params.toString();

            window.history.pushState(
                { tab: 'my_posts', my_post_page: page },
                '',
                newUrl
            );
        })
        .catch(function (error) {
            console.error(error);

            contentBox.innerHTML = `
                <div class="alert alert-danger">
                    Không thể tải danh sách bài viết của bạn.
                </div>
            `;
        });
}