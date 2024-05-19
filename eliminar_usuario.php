<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php';

if (!isset($_SESSION['UserID']) || !isset($_SESSION['esAdministrador']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $currentUserId = $_SESSION['UserID'];

    // Verificar si el usuario es SuperAdmin
    $stmt = $conn->prepare("SELECT Username FROM Usuario WHERE UserID = ?");
    $stmt->execute([$currentUserId]);
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
    $isSuperAdmin = $currentUser['Username'] === 'Rubenandia85';

    // Verificar el rol del usuario a eliminar
    $stmt = $conn->prepare("SELECT EsAdministrador, Username FROM Usuario WHERE UserID = ?");
    $stmt->execute([$userId]);
    $targetUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($targetUser) {
        if ($targetUser['EsAdministrador'] && !$isSuperAdmin) {
            $_SESSION['message'] = "No tienes permiso para eliminar a este usuario.";
        } elseif ($targetUser['Username'] === 'Rubenandia85') {
            $_SESSION['message'] = "No puedes eliminar al SuperAdmin.";
        } else {
            // Iniciar una transacción
            $conn->beginTransaction();

            try {
                // Eliminar registros relacionados en la tabla Valoración
                $stmt = $conn->prepare("DELETE FROM Valoración WHERE UsuarioID = ?");
                $stmt->bindParam(1, $userId);
                $stmt->execute();

                // Eliminar registros relacionados en la tabla Notificación
                $stmt = $conn->prepare("DELETE FROM Notificación WHERE UsuarioID = ?");
                $stmt->bindParam(1, $userId);
                $stmt->execute();

                // Eliminar registros relacionados en la tabla ListaDeseos
                $stmt = $conn->prepare("DELETE FROM ListaDeseosProducto WHERE ListaDeseosID IN (SELECT ListaDeseosID FROM ListaDeseos WHERE UsuarioID = ?)");
                $stmt->bindParam(1, $userId);
                $stmt->execute();

                $stmt = $conn->prepare("DELETE FROM ListaDeseos WHERE UsuarioID = ?");
                $stmt->bindParam(1, $userId);
                $stmt->execute();

                // Eliminar registros relacionados en la tabla HistorialBusqueda
                $stmt = $conn->prepare("DELETE FROM HistorialBusqueda WHERE UsuarioID = ?");
                $stmt->bindParam(1, $userId);
                $stmt->execute();

                // Eliminar registros relacionados en la tabla Compra
                $stmt = $conn->prepare("DELETE FROM DetalleCompra WHERE CompraID IN (SELECT CompraID FROM Compra WHERE UserID = ?)");
                $stmt->bindParam(1, $userId);
                $stmt->execute();

                $stmt = $conn->prepare("DELETE FROM Compra WHERE UserID = ?");
                $stmt->bindParam(1, $userId);
                $stmt->execute();

                // Eliminar registros relacionados en la tabla Carrito
                $stmt = $conn->prepare("DELETE FROM Carrito WHERE UserID = ?");
                $stmt->bindParam(1, $userId);
                $stmt->execute();

                // Finalmente, eliminar el usuario
                $stmt = $conn->prepare("DELETE FROM Usuario WHERE UserID = ?");
                $stmt->bindParam(1, $userId);
                $stmt->execute();

                // Confirmar la transacción
                $conn->commit();

                $_SESSION['message'] = "Usuario y todos sus datos relacionados eliminados correctamente.";
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                $conn->rollBack();
                $_SESSION['message'] = "Error al eliminar el usuario: " . $e->getMessage();
            }
        }
    } else {
        $_SESSION['message'] = "Usuario no encontrado.";
    }

    // Redirigir de vuelta a la página de ver usuarios
    header('Location: ver_usuarios.php');
    exit();
}
?>
