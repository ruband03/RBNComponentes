<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'conectaBBDD.php';  

if (!isset($_SESSION['UserID'], $_POST['password'])) {
    header('Location: login.php');
    exit();
}

$userid = $_SESSION['UserID'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT Contraseña FROM Usuario WHERE UserID = ?");
$stmt->bindParam(1, $userid);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (password_verify($password, $user['Contraseña'])) {

    $deleteStmt = $conn->prepare("DELETE FROM Usuario WHERE UserID = ?");
    $deleteStmt->bindParam(1, $userid);
    $deleteStmt->execute();
    
    $_SESSION = array();
    session_destroy();
    header('Location: login.php');
    exit();
} else {
    echo "Contraseña incorrecta.";
}
?>
