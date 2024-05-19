<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RBNComponentes";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Configurar la expiración de la sesión
    $inactivityLimit = 1200; // Tiempo de inactividad en segundos (2 minutos)

    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $inactivityLimit) {
        // Tiempo de inactividad excedido, destruir la sesión y establecer mensaje de inactividad
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['message'] = "Se ha cerrado tu sesión debido a inactividad.";
        header("Location: login.php");
        exit();
    }
    $_SESSION['LAST_ACTIVITY'] = time(); // actualizar el tiempo de la última actividad

} catch (PDOException $e) {
    die("Error en la conexión a la base de datos: " . $e->getMessage());
}
?>