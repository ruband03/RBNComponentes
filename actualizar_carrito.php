<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php';

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['UserID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST['producto_id'];
    $action = $_POST['action'];

    if ($action == 'increment') {
        $stmt = $conn->prepare("SELECT Cantidad FROM Carrito WHERE UserID = ? AND ProductoID = ?");
        $stmt->execute([$user_id, $producto_id]);
        $cantidad = $stmt->fetchColumn();
        if ($cantidad < 30) {
            $stmt = $conn->prepare("UPDATE Carrito SET Cantidad = Cantidad + 1 WHERE UserID = ? AND ProductoID = ?");
            $stmt->execute([$user_id, $producto_id]);
        }
    } elseif ($action == 'decrement') {
        $stmt = $conn->prepare("UPDATE Carrito SET Cantidad = Cantidad - 1 WHERE UserID = ? AND ProductoID = ? AND Cantidad > 1");
        $stmt->execute([$user_id, $producto_id]);
    } elseif ($action == 'remove') {
        $stmt = $conn->prepare("DELETE FROM Carrito WHERE UserID = ? AND ProductoID = ?");
        $stmt->execute([$user_id, $producto_id]);
    }

    header("Location: ver_carrito.php");
    exit();
}
