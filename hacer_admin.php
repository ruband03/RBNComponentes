<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'conectaBBDD.php';

if (!isset($_SESSION['esAdministrador']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    $stmt = $conn->prepare("SELECT Username FROM Usuario WHERE UserID = ?");
    $stmt->execute([$userId]);
    $targetUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($targetUser && $targetUser['Username'] !== 'Rubenandia85') {
        $stmt = $conn->prepare("UPDATE Usuario SET EsAdministrador = TRUE WHERE UserID = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    header('Location: administrar_usuarios.php');
} else {
    header('Location: administrar_usuarios.php');
}
?>
