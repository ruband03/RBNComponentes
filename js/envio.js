document.addEventListener('DOMContentLoaded', function () {
    const comunidadSelect = document.getElementById('comunidad');
    const provinciaSelect = document.getElementById('provincia');

    comunidadSelect.addEventListener('change', function () {
        const comunidadID = comunidadSelect.value;

        fetch('get_provincias.php?comunidad_id=' + comunidadID)
            .then(response => response.json())
            .then(data => {
                provinciaSelect.innerHTML = '';
                data.forEach(provincia => {
                    const option = document.createElement('option');
                    option.value = provincia.ProvinciaID;
                    option.textContent = provincia.Nombre;
                    provinciaSelect.appendChild(option);
                });
            });
    });
});
