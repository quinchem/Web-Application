document.addEventListener('DOMContentLoaded', function() {
    const togglePasswordBtn = document.getElementById('standaloneTogglePassword');
    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function () {
            const passwordInput = document.getElementById('standalonePasswordInput');
            const eyeIcon = document.getElementById('standaloneEyeIcon');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            
            passwordInput.setAttribute('type', type);
            if (type === 'password') {
                eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
    }
});