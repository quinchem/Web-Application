<link rel="stylesheet" href="/Web-Application/Public/Admin/Css/ReviewPost.css">
<script src="/Web-Application/Public/Admin/Js/ReviewPost.js" defer></script>

<div class="modal-overlay" id="reviewModal">
    <div class="modal-box">

        <!-- HEADER -->
        <div class="modal-header d-flex align-items-center justify-content-between">
            <div class="modal-label">DUYỆT BÀI VIẾT</div>
            <button class="modal-close" onclick="closeReviewModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="modal-body">

            <p class="modal-section-label">BÀI VIẾT ĐANG XÉT DUYỆT</p>
            <div class="modal-title" id="modal-post-title"></div>

            <p class="modal-section-label">QUYẾT ĐỊNH PHÊ DUYỆT</p>
            <div class="modal-decision d-grid gap-3 mb-4">
                <button class="decision-btn approve selected" onclick="selectDecision('approved')">
                    <i class="fa-regular fa-circle-check"></i> Duyệt
                </button>
                <button class="decision-btn reject" onclick="selectDecision('rejected')">
                    <i class="fa-regular fa-circle-xmark"></i> Chưa duyệt
                </button>
            </div>

            <p class="modal-section-label">LÝ DO & GHI CHÚ</p>
            <textarea
                class="modal-textarea w-100 mb-4"
                id="modal-reason"
                placeholder="Nhập lý do chưa duyệt cho tác giả..."
            ></textarea>

        </div>

        <!-- HIDDEN INPUTS -->
        <input type="hidden" id="modal-post-id">
        <input type="hidden" id="modal-decision" value="approved">

        <!-- FOOTER -->
        <div class="modal-footer d-flex justify-content-end align-items-center gap-4">
            <button class="modal-cancel" onclick="closeReviewModal()">Hủy</button>
            <button class="modal-confirm" onclick="submitReview()">Xác nhận</button>
        </div>

    </div>
</div>