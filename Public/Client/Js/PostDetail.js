async function handleLike() {

    if (!isLoggedIn) {
        requireLogin();
        return;
    }

    if (isLikeProcessing) return;

    isLikeProcessing = true;

    try {

        const response = await fetch(
            `index.php?page=api_like&post_id=${currentPostId}`
        );

        const data = await response.json();

        const btn = document.getElementById('btn-like');

        const icon = btn.querySelector('i');

        const text = btn.querySelector('span');

        if (data.action === 'liked') {

            btn.classList.add('active-like');

            btn.classList.remove('btn-light', 'text-muted');

            btn.style.background = '#003049';

            btn.style.color = '#fff';

            icon.classList.remove('fa-regular');

            icon.classList.add('fa-solid');

            text.innerText = 'ĐÃ THÍCH';

        } else {

            btn.classList.remove('active-like');

            btn.classList.add('btn-light', 'text-muted');

            btn.style.background = '';

            btn.style.color = '';

            icon.classList.remove('fa-solid');

            icon.classList.add('fa-regular');

            text.innerText = 'THÍCH';
        }

    } catch (error) {

        console.error(error);

    } finally {

        isLikeProcessing = false;
    }
}
async function handleSave() {

    if (!isLoggedIn) {
        requireLogin();
        return;
    }

    if (isSaveProcessing) return;

    isSaveProcessing = true;

    try {

        const response = await fetch(
            `index.php?page=api_save&post_id=${currentPostId}`
        );

        const data = await response.json();

        const btn = document.getElementById('btn-save');

        const icon = btn.querySelector('i');

        const text = btn.querySelector('span');

        if (data.action === 'saved') {

            btn.classList.add('active-save');

            btn.classList.remove('btn-light', 'text-muted');

            btn.style.background = '#B90C17';

            btn.style.color = '#fff';

            icon.classList.remove('fa-regular');

            icon.classList.add('fa-solid');

            text.innerText = 'ĐÃ LƯU';

        } else {

            btn.classList.remove('active-save');

            btn.classList.add('btn-light', 'text-muted');

            btn.style.background = '';

            btn.style.color = '';

            icon.classList.remove('fa-solid');

            icon.classList.add('fa-regular');

            text.innerText = 'LƯU';
        }

    } catch (error) {

        console.error(error);

    } finally {

        isSaveProcessing = false;
    }
}