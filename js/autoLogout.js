let inactivityTime = function () {
    let time;
    window.onload = resetTimer;
    window.onmousemove = resetTimer;
    window.onmousedown = resetTimer; 
    window.ontouchstart = resetTimer;
    window.onclick = resetTimer; 
    window.onkeypress = resetTimer;

    function logout() {
        alert("Se ha cerrado tu sesión debido a inactividad.");
        window.location.href = 'logout.php'; 
    }

    function resetTimer() {
        clearTimeout(time);
        time = setTimeout(logout, 120000); //(2 minutos)
    }
};

window.onload = function() {
    inactivityTime();
};
