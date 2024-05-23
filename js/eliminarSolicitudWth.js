function eliminarSolicitud(solicitudId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta solicitud?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'eliminar_solicitud.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    var row = document.getElementById('solicitud-' + solicitudId);
                    if (row) {
                        row.remove();
                    }
                    alert(response.message);
                } else {
                    alert('Error: ' + response.message);
                }
            }
        };
        xhr.send('solicitud_id=' + solicitudId);
    }
}