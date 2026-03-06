document.getElementById('loginForm').addEventListener('submit', function (e) {
    const user = document.getElementById('username').value;
    const pass = document.getElementById('password').value;
    const errorMsg = document.getElementById('error-msg');

    if (user.trim() === "" || pass.trim() === "") {
        e.preventDefault(); // Detiene el envío del formulario
        errorMsg.innerText = "Por favor, completa todos los campos.";
    }
});