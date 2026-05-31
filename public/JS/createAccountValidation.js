document.addEventListener("DOMContentLoaded", () => {

    const email = document.querySelector('input[name="create_email"]');
    const username = document.querySelector('input[name="create_username"]');
    const password = document.querySelector('input[name="create_password"]');
    const repeatPassword = document.querySelector('input[name="create_repeat_password"]');
    const form = document.querySelector("form");

    // Crear caja de errores
    const errorBox = document.createElement("div");

    errorBox.style.color = "red";
    errorBox.style.background = "#ffe5e5";
    errorBox.style.padding = "10px";
    errorBox.style.borderRadius = "8px";
    errorBox.style.marginBottom = "20px";
    errorBox.style.display = "none";

    form.parentNode.insertBefore(errorBox, form);

    function showError(message) {
        errorBox.innerText = message;
        errorBox.style.display = "block";
    }

    function hideError() {
        errorBox.style.display = "none";
    }

    function validate() {

        // Campos vacíos
        if (
            email.value.trim() === "" ||
            username.value.trim() === "" ||
            password.value.trim() === "" ||
            repeatPassword.value.trim() === ""
        ) {
            showError("Todos los campos son obligatorios.");
            return false;
        }

        // Email válido
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email.value)) {
            showError("El correo electrónico no es válido.");
            return false;
        }

        // Usuario válido
        const usernameRegex = /^[a-zA-Z0-9_]+$/;

        if (!usernameRegex.test(username.value)) {
            showError("El nombre de usuario solo puede contener letras, números y guiones bajos.");
            return false;
        }

        // Longitud password
        if (password.value.length < 6) {
            showError("La contraseña debe tener al menos 6 caracteres.");
            return false;
        }

        // Passwords iguales
        if (password.value !== repeatPassword.value) {
            showError("Las contraseñas no coinciden.");
            return false;
        }

        hideError();
        return true;
    }

    // Validación en tiempo real
    [email, username, password, repeatPassword].forEach(input => {
        input.addEventListener("input", validate);
    });

    // Validación antes de enviar
    form.addEventListener("submit", (e) => {
        if (!validate()) {
            e.preventDefault();
        }
    });

});
