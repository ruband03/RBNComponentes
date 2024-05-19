<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php';

if (!isset($_SESSION['UserID']) || !isset($_POST['puntuacion']) || !isset($_POST['producto_id'])) {
    header('Location: login.php');
    exit();
}

$userID = $_SESSION['UserID'];
$puntuacion = (int)$_POST['puntuacion'];
$productoID = $_POST['producto_id'];

$stmt = $conn->prepare("INSERT INTO Valoración (Puntuación, Fecha, UsuarioID, ProductoID) VALUES (?, NOW(), ?, ?)");
$stmt->bindParam(1, $puntuacion);
$stmt->bindParam(2, $userID);
$stmt->bindParam(3, $productoID);

if ($stmt->execute()) {
    $_SESSION['message'] = "Valoración añadida correctamente.";
} else {
    $_SESSION['message'] = "Error al añadir la valoración.";
}

header("Location: detalles_producto.php?id=" . $productoID);
exit();
?>
    