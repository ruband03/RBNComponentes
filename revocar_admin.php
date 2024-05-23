<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $currentUserId = $_SESSION['UserID'];

    // Verificar si el usuario actual es SuperAdmin
    $stmt = $conn->prepare("SELECT Username FROM Usuario WHERE UserID = ?");
    $stmt->execute([$currentUserId]);
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
    $isSuperAdmin = $currentUser['Username'] === 'Rubenandia85';

    if ($isSuperAdmin) {
        // Verificar si el usuario a revocar es administrador y no es el SuperAdmin
        $stmt = $conn->prepare("SELECT EsAdministrador, Username FROM Usuario WHERE UserID = ?");
        $stmt->execute([$userId]);
        $targetUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($targetUser && $targetUser['EsAdministrador'] && $targetUser['Username'] !== 'Rubenandia85') {
            // Revocar permisos de administrador y establecer el mensaje
            $stmt = $conn->prepare("UPDATE Usuario SET EsAdministrador = 0, Mensaje = 'El SuperAdmin ha decidido revocarte los permisos de administrador.' WHERE UserID = ?");
            $stmt->execute([$userId]);

            $_SESSION['message'] = "Permisos de administrador revocados correctamente.";
        } else {
            $_SESSION['message'] = "El usuario no tiene permisos de administrador o es el SuperAdmin.";
        }
    } else {
        $_SESSION['message'] = "No tienes permisos para realizar esta acción.";
    }

    header('Location: ver_usuarios.php');
    exit();
}
?>
