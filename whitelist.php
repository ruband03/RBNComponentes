<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

$solicitudesPorPagina = 10;

$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $solicitudesPorPagina;

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM adminrequests");
$stmt->execute();
$totalSolicitudes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPaginas = ceil($totalSolicitudes / $solicitudesPorPagina);

$stmt = $conn->prepare("SELECT ar.*, u.Username FROM adminrequests ar JOIN Usuario u ON ar.UserID = u.UserID LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $solicitudesPorPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whitelist de Administradores</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/whitelist.css">
    <link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<?php include 'header.php'; ?>

<section class="whitelist-section">
    <div class="container">
        <h2>Whitelist de Administradores</h2>
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Motivo</th>
                    <th>Fecha de Solicitud</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($solicitudes) > 0): ?>
                    <?php foreach ($solicitudes as $solicitud): ?>
                        <tr id="solicitud-<?= $solicitud['RequestID'] ?>">
                            <td><?= htmlspecialchars($solicitud['Username']) ?></td>
                            <td><?= htmlspecialchars($solicitud['Motivo']) ?></td>
                            <td><?= htmlspecialchars($solicitud['RequestDate']) ?></td>
                            <td>
                                <form method="post" action="aprobar_solicitud.php" style="display:inline;">
                                    <input type="hidden" name="solicitud_id" value="<?= $solicitud['RequestID'] ?>">
                                    <button type="submit" class="btn">Aceptar Propuesta</button>
                                </form>
                                <button class="btn delete-btn" onclick="eliminarSolicitud(<?= $solicitud['RequestID'] ?>)"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay solicitudes pendientes.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($paginaActual > 1): ?>
                <a href="whitelist.php?pagina=<?= $paginaActual - 1 ?>">&laquo; Anterior</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="whitelist.php?pagina=<?= $i ?>" class="<?= $i == $paginaActual ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($paginaActual < $totalPaginas): ?>
                <a href="whitelist.php?pagina=<?= $paginaActual + 1 ?>">Siguiente &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; 2024 RBNComponentes. Todos los derechos reservados.</p>
    </div>
</footer>
<script src="js/eliminarSolicitudWth.js" defer></script>
</body>
</html>
