document.addEventListener('click', function (e) {
    const categoryDropdown = document.querySelector('.result-category-dropdown');

    const dropdownBtn = e.target.closest('.result-category-btn');

    if (dropdownBtn) {
        e.preventDefault();
        e.stopPropagation();

        if (categoryDropdown) {
            categoryDropdown.classList.toggle('active');
        }

        return;
    }

    const parentBtn = e.target.closest('.result-parent-btn');

    if (parentBtn) {
        e.preventDefault();
        e.stopPropagation();

        const parentItem = parentBtn.closest('.result-category-parent');

        if (parentItem) {
            parentItem.classList.toggle('active');
        }

        return;
    }

    const categoryOption = e.target.closest('.result-category-option, .result-category-clear');

    if (categoryOption) {
        e.preventDefault();
        e.stopPropagation();

        const categoryValue = categoryOption.dataset.category || '';
        const categoryLabel = categoryOption.dataset.label || 'Danh mục';

        const input = document.querySelector('#resultCategoryInput');
        const label = document.querySelector('#resultCategoryLabel');
        const filterForm = document.querySelector('#resultFilterForm');

        if (input) {
            input.value = categoryValue;
        }

        if (label) {
            label.textContent = categoryLabel;
        }

        if (categoryDropdown) {
            categoryDropdown.classList.remove('active');
        }

        document.querySelectorAll('.result-category-parent.active').forEach(function (parent) {
            parent.classList.remove('active');
        });

        if (filterForm) {
            filterForm.submit();
        }

        return;
    }

    const insideDropdown = e.target.closest('.result-category-dropdown');

    if (!insideDropdown && categoryDropdown) {
        categoryDropdown.classList.remove('active');

        document.querySelectorAll('.result-category-parent.active').forEach(function (parent) {
            parent.classList.remove('active');
        });
    }
});

document.addEventListener('change', function (e) {
    const filterInput = e.target.closest(
        '#resultFilterForm input[type="radio"], #resultFilterForm input[type="date"]'
    );

    if (filterInput) {
        const filterForm = document.querySelector('#resultFilterForm');

        if (filterForm) {
            filterForm.submit();
        }
    }
});

document.addEventListener('input', function (e) {
    const authorInput = e.target.closest('#resultFilterForm input[name="author"]');

    if (!authorInput) {
        return;
    }

    clearTimeout(window.resultAuthorTypingTimer);

    window.resultAuthorTypingTimer = setTimeout(function () {
        const filterForm = document.querySelector('#resultFilterForm');

        if (filterForm) {
            filterForm.submit();
        }
    }, 600);
});