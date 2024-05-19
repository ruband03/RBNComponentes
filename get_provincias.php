<?php
require_once 'conectaBBDD.php';

if (isset($_GET['comunidad_id'])) {
    $comunidadID = $_GET['comunidad_id'];

    $stmt = $conn->prepare("SELECT ProvinciaID, Nombre FROM provincias WHERE ComunidadID = ?");
    $stmt->execute([$comunidadID]);
    $provincias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($provincias);
}
?>
