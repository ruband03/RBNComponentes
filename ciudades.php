<?php
require_once 'conectaBBDD.php';

if (isset($_GET['comunidad_id'])) {
    $comunidad_id = $_GET['comunidad_id'];

    $stmt = $conn->prepare("SELECT Nombre FROM ciudades WHERE ComunidadID = ?");
    $stmt->execute([$comunidad_id]);
    $ciudades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($ciudades);
}
?>
