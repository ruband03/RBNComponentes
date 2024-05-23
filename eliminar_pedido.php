<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'])) {
    $pedidoId = (int)$_POST['pedido_id'];
    
    if (!isset($_SESSION['eliminados'])) {
        $_SESSION['eliminados'] = [];
    }
    
    if (!in_array($pedidoId, $_SESSION['eliminados'])) {
        $_SESSION['eliminados'][] = $pedidoId;
    }

    echo 'OK';
}
?>
