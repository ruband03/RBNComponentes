<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitud_id'])) {
    $solicitudId = $_POST['solicitud_id'];

    $stmt = $conn->prepare("DELETE FROM adminrequests WHERE RequestID = ?");
    $stmt->execute([$solicitudId]);

    echo json_encode(['success' => true, 'message' => 'Solicitud de whitelist eliminada correctamente.']);
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar la solicitud.']);
    exit();
}
?>
