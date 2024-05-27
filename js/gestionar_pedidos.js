document.addEventListener('DOMContentLoaded', function() {
    var estadoSelect = document.getElementById('estado');
    if (estadoSelect) {
        estadoSelect.addEventListener('change', function() {
            document.getElementById('filtroForm').submit();
        });
    }

    var deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var pedidoId = this.getAttribute('data-pedido-id');
            var pedidoRow = document.getElementById('pedido-' + pedidoId);
            if (pedidoRow && confirm('¿Estás seguro de que quieres borrar este pedido?')) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "eliminar_pedido.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        pedidoRow.remove();
                    }
                };
                xhr.send("pedido_id=" + pedidoId);
            }
        });
    });
});
