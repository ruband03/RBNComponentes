<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID']) || !isset($_SESSION['esAdministrador']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id']) && isset($_POST['nuevo_estado'])) {
    $pedido_id = $_POST['pedido_id'];
    $nuevo_estado = $_POST['nuevo_estado'];

    $stmt = $conn->prepare("UPDATE pedidos SET Estado = ? WHERE PedidoID = ?");
    $stmt->execute([$nuevo_estado, $pedido_id]);

    $_SESSION['message'] = "Estado del pedido actualizado correctamente.";
    header('Location: gestionar_pedidos.php');
    exit();
} else {
    $_SESSION['message'] = "Datos del formulario incompletos.";
    header('Location: gestionar_pedidos.php');
    exit();
}
?>
