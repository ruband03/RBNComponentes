<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php'; 

if (!isset($_SESSION['esAdministrador']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $precio = (float) $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $imagen = $_FILES['imagen']['name'];
    $ruta_destino = "imgproductos/" . basename($_FILES['imagen']['name']);

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
        $stmt = $conn->prepare("INSERT INTO Producto (Nombre, Categoría, Precio, ImagenURL, Descripcion) VALUES (?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2, $categoria);
        $stmt->bindParam(3, $precio);
        $stmt->bindParam(4, $ruta_destino);
        $stmt->bindParam(5, $descripcion);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Producto añadido correctamente.";
            header("Location: mainpage.php");
            exit;
        } else {
            echo "Error al añadir producto: " . $stmt->errorInfo()[2];
        }
    } else {
        echo "Error al subir la imagen.";
    }
}
?>
