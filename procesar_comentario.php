<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php';

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productoID = $_POST['producto_id'];
    $comentario = $_POST['comentario'];
    $puntuacion = $_POST['puntuacion'];
    $usuarioID = $_SESSION['UserID'];

    // Verificar si el usuario ya ha dejado un comentario para este producto
    $stmt = $conn->prepare("SELECT ValoraciónID FROM Valoración WHERE UsuarioID = ? AND ProductoID = ?");
    $stmt->execute([$usuarioID, $productoID]);
    $existeComentario = $stmt->fetchColumn();

    if ($existeComentario) {
        $_SESSION['message'] = "Ya has dejado un comentario para este producto.";
        header("Location: detalles_producto.php?id=$productoID");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO Valoración (Puntuación, Comentario, Fecha, UsuarioID, ProductoID) VALUES (?, ?, NOW(), ?, ?)");
    $stmt->execute([$puntuacion, $comentario, $usuarioID, $productoID]);

    $_SESSION['message'] = "Comentario añadido correctamente.";
    header("Location: detalles_producto.php?id=$productoID");
    exit();
}
?>
