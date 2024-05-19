<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php';

if (!isset($_SESSION['UserID']) || !isset($_SESSION['esAdministrador']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $name = $_POST['name'];

    $stmt = $conn->prepare("UPDATE Usuario SET Username = ?, Email = ?, Nombre = ? WHERE UserID = ?");
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $email);
    $stmt->bindParam(3, $name);
    $stmt->bindParam(4, $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Perfil del usuario actualizado correctamente.";
    } else {
        $_SESSION['message'] = "Error al actualizar el perfil del usuario.";
    }

    header("Location: ver_usuarios.php");
    exit();
}
?>
