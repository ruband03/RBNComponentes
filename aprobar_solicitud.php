<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['solicitud_id'])) {
    $solicitudID = $_POST['solicitud_id'];

    // Obtener el UserID del solicitante
    $stmt = $conn->prepare("SELECT UserID FROM adminrequests WHERE RequestID = ?");
    $stmt->execute([$solicitudID]);
    $solicitante = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($solicitante) {
        $userID = $solicitante['UserID'];

        // No permitir que el Super Admin sea modificado
        $stmt = $conn->prepare("SELECT EsSuperAdmin FROM Usuario WHERE UserID = ?");
        $stmt->execute([$userID]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario['EsSuperAdmin']) {
            $_SESSION['message'] = "No se puede modificar al Administrador Máximo.";
            header('Location: whitelist.php');
            exit();
        }

        // Actualizar el usuario a administrador
        $stmt = $conn->prepare("UPDATE Usuario SET EsAdministrador = 1 WHERE UserID = ?");
        $stmt->execute([$userID]);

        // Eliminar la solicitud
        $stmt = $conn->prepare("DELETE FROM adminrequests WHERE RequestID = ?");
        $stmt->execute([$solicitudID]);

        $_SESSION['message'] = "Solicitud aceptada. El usuario es ahora administrador.";
    } else {
        $_SESSION['message'] = "Solicitud no encontrada.";
    }

    header('Location: whitelist.php');
    exit();
}
?>
