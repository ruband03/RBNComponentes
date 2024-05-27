function confirmDelete() {
    if (confirm("¿Estás seguro de que quieres hacer esto? Esta acción es irreversible y borrará todo lo asociado a tu cuenta.")) {
        var password = prompt("Por favor, introduce tu contraseña para confirmar:");
        if (password) {

            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'delete_profile.php';  
            var passwordInput = document.createElement('input');
            passwordInput.type = 'hidden';
            passwordInput.name = 'password';
            passwordInput.value = password;

            form.appendChild(passwordInput);

            document.body.appendChild(form);
            form.submit();
        } else {
            alert("Debes introducir tu contraseña para confirmar la eliminación.");
        }
    }
}
