const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

togglePassword.addEventListener("click", () => {
    const isHidden = password.getAttribute("type") === "password";

    password.setAttribute("type", isHidden ? "text" : "password");

    togglePassword.classList.toggle("fa-eye",       isHidden);
    togglePassword.classList.toggle("fa-eye-slash", !isHidden);
});