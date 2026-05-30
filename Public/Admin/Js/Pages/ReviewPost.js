function openReviewModal(postId, postTitle) {
    document.getElementById('modal-post-id').value          = postId;
    document.getElementById('modal-post-title').textContent = postTitle;
    document.getElementById('modal-decision').value         = 'approved';
    document.getElementById('modal-reason').value           = '';

    document.querySelector('.decision-btn.approve').classList.add('selected');
    document.querySelector('.decision-btn.reject').classList.remove('selected');

    document.getElementById('reviewModal').classList.add('active');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.remove('active');
}

function selectDecision(type) {
    document.getElementById('modal-decision').value = type;
    document.querySelector('.decision-btn.approve').classList.toggle('selected', type === 'approved');
    document.querySelector('.decision-btn.reject').classList.toggle('selected',  type === 'rejected');
}

function submitReview() {
    const postId   = document.getElementById('modal-post-id').value;
    const decision = document.getElementById('modal-decision').value;
    const reason   = document.getElementById('modal-reason').value;

    const url = new URL(window.location.href);
    url.searchParams.set('page',     'review_post');
    url.searchParams.set('id',       postId);
    url.searchParams.set('decision', decision);
    url.searchParams.set('reason',   encodeURIComponent(reason));

    window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('reviewModal').addEventListener('click', function(e) {
        if (e.target === this) closeReviewModal();
    });
});