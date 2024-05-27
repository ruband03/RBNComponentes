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
    $cantidad = (int)$_POST['cantidad'];

    $stmt = $conn->prepare("SELECT * FROM Carrito WHERE UserID = ? AND ProductoID = ?");
    $stmt->execute([$user_id, $producto_id]);
    $producto_en_carrito = $stmt->fetch();

    if ($producto_en_carrito) {
        $stmt = $conn->prepare("UPDATE Carrito SET Cantidad = Cantidad + ? WHERE UserID = ? AND ProductoID = ?");
        $stmt->execute([$cantidad, $user_id, $producto_id]);
    } else {

        $stmt = $conn->prepare("INSERT INTO Carrito (UserID, ProductoID, Cantidad) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $producto_id, $cantidad]);
    }

    $_SESSION['message'] = "Producto añadido al carrito correctamente.";
    header("Location: products.php");
    exit();
}
?>
