<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php'; 
?> 

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Producto</title>
    <link rel="stylesheet" href="css/añadir_producto.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>

    <h1>Añadir Producto</h1>
    <form action="procesar_producto.php" method="post" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        
        <label for="categoria">Categoría:</label>
        <select id="categoria" name="categoria" required>
            <?php
            $categorias = ['Placa Base', 'RAM', 'Disco Duro', 'Ratón', 'Monitor', 'Teclado', 'Gráfica', 'Fuente de Alimentación', 'Procesador'];
            foreach ($categorias as $categoria) {
                echo "<option value=\"$categoria\">$categoria</option>";
            }
            ?>
        </select>
        
        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" required>
        
        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen">
        
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>
        
        <button type="submit">Guardar Producto</button>
    </form>
</body>

<footer>
    <div class="container">
        <p>&copy; 2024 RBNComponentes. Todos los derechos reservados.</p>
    </div>
</footer>

</html>
