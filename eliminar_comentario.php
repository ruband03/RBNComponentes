<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php';

if (!isset($_SESSION['UserID']) || !isset($_POST['valoracion_id'])) {
    header('Location: login.php');
    exit();
}

$valoracionID = $_POST['valoracion_id'];

$stmt = $conn->prepare("SELECT UsuarioID, ProductoID FROM Valoración WHERE ValoraciónID = ?");
$stmt->execute([$valoracionID]);
$comentario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comentario || ($_SESSION['UserID'] != $comentario['UsuarioID'] && !$_SESSION['esAdministrador'])) {
    header('Location: detalles_producto.php');
    exit();
}

$stmt = $conn->prepare("DELETE FROM Valoración WHERE ValoraciónID = ?");
$stmt->execute([$valoracionID]);

$_SESSION['message'] = "Comentario eliminado correctamente.";
header("Location: detalles_producto.php?id=" . $comentario['ProductoID']);
exit();
?>
