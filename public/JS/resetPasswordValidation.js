document.addEventListener("DOMContentLoaded", () => {

    const password = document.querySelector('input[name="new_password"]');
    const repeatPassword = document.querySelector('input[name="repeat_new_password"]');
    const form = document.querySelector("form");

    if (!form) return;

    const errorBox = document.createElement("div");

    errorBox.style.color = "#dc2626";
    errorBox.style.background = "#fee2e2";
    errorBox.style.padding = "12px";
    errorBox.style.borderRadius = "10px";
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

        if (
            password.value.trim() === "" ||
            repeatPassword.value.trim() === ""
        ) {
            showError("Debes rellenar todos los campos.");
            return false;
        }

        if (password.value.length < 6) {
            showError("La contraseña debe tener al menos 6 caracteres.");
            return false;
        }

        if (password.value !== repeatPassword.value) {
            showError("Las contraseñas no coinciden.");
            return false;
        }

        hideError();
        return true;
    }

    [password, repeatPassword].forEach(input => {
        input.addEventListener("input", validate);
    });

    form.addEventListener("submit", (e) => {
        if (!validate()) {
            e.preventDefault();
        }
    });

});
