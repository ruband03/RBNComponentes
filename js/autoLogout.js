let inactivityTime = function () {
    let time;
    window.onload = resetTimer;
    window.onmousemove = resetTimer;
    window.onmousedown = resetTimer; // Catches touchscreen presses as well
    window.ontouchstart = resetTimer;
    window.onclick = resetTimer; // Catches touchpad clicks as well
    window.onkeypress = resetTimer;

    function logout() {
        alert("Se ha cerrado tu sesión debido a inactividad.");
        window.location.href = 'logout.php'; // Redirigir a la página de logout
    }

    function resetTimer() {
        clearTimeout(time);
        time = setTimeout(logout, 120000); // Tiempo en milisegundos (2 minutos)
    }
};

window.onload = function() {
    inactivityTime();
};
