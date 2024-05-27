<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

$userId = $_SESSION['UserID'];
$productoId = $_POST['producto_id'];


$stmt = $conn->prepare("SELECT ListaDeseosID FROM listadeseos WHERE UsuarioID = ?");
$stmt->execute([$userId]);
$listaDeseos = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listaDeseos) {
    $stmt = $conn->prepare("INSERT INTO listadeseos (UsuarioID) VALUES (?)");
    $stmt->execute([$userId]);
    $listaDeseosId = $conn->lastInsertId();
} else {
    $listaDeseosId = $listaDeseos['ListaDeseosID'];
}


$stmt = $conn->prepare("INSERT INTO listadeseosproducto (ListaDeseosID, ProductoID) VALUES (?, ?)");
$stmt->execute([$listaDeseosId, $productoId]);

$_SESSION['message'] = "Producto añadido a la lista de deseos.";
header('Location: products.php');
exit();
?>
