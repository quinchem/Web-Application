function goToPage(page) {
    const url = new URL(window.location.href);
    url.searchParams.set('page', 'admin_user_posts');
    url.searchParams.set('p', page);
    window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded', function () {

    // Highlight đúng trang theo URL param 'p'
    const params   = new URLSearchParams(window.location.search);
    const currentP = parseInt(params.get('p')) || 1;

    document.querySelectorAll('.pagination button').forEach(btn => {
        const pageNum = parseInt(btn.textContent.trim());
        if (!isNaN(pageNum) && pageNum === currentP) {
            btn.classList.add('current');
        } else {
            btn.classList.remove('current');
        }
    });

    // ─── Custom Category Dropdown ───────────────────────────────────
    const dropdown    = document.getElementById('categoryDropdown');
    const trigger     = document.getElementById('categoryTrigger');
    const menu        = document.getElementById('categoryMenu');
    const label       = document.getElementById('categoryLabel');
    const hiddenInput = document.getElementById('categoryValue');
    const catReset    = document.getElementById('catReset');

    if (!dropdown || !menu || !trigger) return;

    // Restore label nếu đang có filter
    const preVal = hiddenInput.value;
    if (preVal) {
        const preChild = dropdown.querySelector(`.cat-child[data-value="${preVal}"]`);
        if (preChild) {
            label.textContent = preChild.dataset.label;
            preChild.classList.add('selected');
        }
    }

    // Toggle mở/đóng menu chính
   trigger.addEventListener('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();
    const isOpen = menu.style.display === 'block';
    
    // Đóng tất cả children trước khi mở lại
    if (!isOpen) {
        dropdown.querySelectorAll('.cat-children').forEach(c => {
            c.style.display = 'none';
        });
        dropdown.querySelectorAll('.cat-parent-label i').forEach(i => {
            i.style.transform = '';
        });
    }
    
    menu.style.display = isOpen ? 'none' : 'block';
    trigger.querySelector('i').style.transform = isOpen ? '' : 'rotate(180deg)';
});

    // Click danh mục cha → expand/collapse con
    dropdown.querySelectorAll('.cat-parent-label').forEach(parentLabel => {
        parentLabel.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            const parent   = parentLabel.closest('.cat-parent');
            const children = parent.querySelector('.cat-children');
            const chevron  = parentLabel.querySelector('i');
            const isOpen   = children.style.display === 'block';

            // Đóng tất cả cha khác
            dropdown.querySelectorAll('.cat-parent').forEach(p => {
                p.querySelector('.cat-children').style.display = 'none';
                p.querySelector('.cat-parent-label i').style.transform = '';
            });

            // Mở cha hiện tại nếu chưa mở
            if (!isOpen) {
                children.style.display = 'block';
                chevron.style.transform = 'rotate(90deg)';
            }
        });
    });

    // Click danh mục con → chọn & đóng
    dropdown.querySelectorAll('.cat-child').forEach(child => {
        child.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            dropdown.querySelectorAll('.cat-child').forEach(c => c.classList.remove('selected'));
            child.classList.add('selected');
            hiddenInput.value = child.dataset.value;
            label.textContent = child.dataset.label;
            menu.style.display = 'none';
            trigger.querySelector('i').style.transform = '';
        });
    });

    // Reset
    catReset.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        dropdown.querySelectorAll('.cat-child').forEach(c => c.classList.remove('selected'));
        hiddenInput.value = '';
        label.textContent = 'Danh mục';
        menu.style.display = 'none';
        trigger.querySelector('i').style.transform = '';
    });

    // Click ngoài → đóng
    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target)) {
            menu.style.display = 'none';
            trigger.querySelector('i').style.transform = '';
        }
    });

});