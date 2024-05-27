<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID']) || !isset($_SESSION['esAdministrador']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

$adminUserID = $_SESSION['UserID'];

$pedidosPorPagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina > 1) ? ($pagina * $pedidosPorPagina) - $pedidosPorPagina : 0;

$estadoSeleccionado = isset($_GET['estado']) ? $_GET['estado'] : '';

if (!isset($_SESSION['eliminados'])) {
    $_SESSION['eliminados'] = [];
}

$sqlTotal = "SELECT COUNT(*) FROM pedidos";
$sqlPedidos = "SELECT p.PedidoID, p.UserID, p.Total, p.FechaPedido, p.Estado, u.Username FROM pedidos p JOIN Usuario u ON p.UserID = u.UserID";

$filters = [];
if ($estadoSeleccionado) {
    $filters[] = "Estado = :estado";
}
if (!empty($_SESSION['eliminados'])) {
    $filters[] = "PedidoID NOT IN (" . implode(',', array_map('intval', $_SESSION['eliminados'])) . ")";
}

if ($filters) {
    $sqlTotal .= " WHERE " . implode(' AND ', $filters);
    $sqlPedidos .= " WHERE " . implode(' AND ', $filters);
}

$sqlPedidos .= " LIMIT :inicio, :pedidosPorPagina";

$stmtTotal = $conn->prepare($sqlTotal);
$stmtPedidos = $conn->prepare($sqlPedidos);

if ($estadoSeleccionado) {
    $stmtTotal->bindParam(':estado', $estadoSeleccionado, PDO::PARAM_STR);
    $stmtPedidos->bindParam(':estado', $estadoSeleccionado, PDO::PARAM_STR);
}

$stmtTotal->execute();
$totalPedidos = $stmtTotal->fetchColumn();
$paginas = ceil($totalPedidos / $pedidosPorPagina);

$stmtPedidos->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$stmtPedidos->bindParam(':pedidosPorPagina', $pedidosPorPagina, PDO::PARAM_INT);
$stmtPedidos->execute();
$pedidos = $stmtPedidos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Pedidos</title>
    <link rel="stylesheet" href="css/gestionar_pedidos.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="js/gestionar_pedidos.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
    <h1>Gestionar Pedidos</h1>
    <form method="GET" action="gestionar_pedidos.php" id="filtroForm" class="filter-form">
        <label for="estado">Filtrar por estado:</label>
        <select name="estado" id="estado">
            <option value="">Todos</option>
            <option value="Tramitando Pedido" <?= $estadoSeleccionado == 'Tramitando Pedido' ? 'selected' : '' ?>>Tramitando Pedido</option>
            <option value="Pedido en el Almacén" <?= $estadoSeleccionado == 'Pedido en el Almacén' ? 'selected' : '' ?>>Pedido en el Almacén</option>
            <option value="En Camino" <?= $estadoSeleccionado == 'En Camino' ? 'selected' : '' ?>>En Camino</option>
            <option value="Entregado" <?= $estadoSeleccionado == 'Entregado' ? 'selected' : '' ?>>Entregado</option>
        </select>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Usuario</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
            <tr id="pedido-<?= htmlspecialchars($pedido['PedidoID']) ?>">
                <td><?= htmlspecialchars($pedido['PedidoID']) ?></td>
                <td><?= htmlspecialchars($pedido['Username']) ?></td>
                <td>€<?= number_format($pedido['Total'], 2) ?></td>
                <td><?= htmlspecialchars($pedido['FechaPedido']) ?></td>
                <td><?= htmlspecialchars($pedido['Estado']) ?></td>
                <td>
                    <?php if ($pedido['Estado'] == 'Entregado'): ?>
                        <span>Este pedido ya ha sido entregado.</span>
                        <button class="btn delete-btn" data-pedido-id="<?= $pedido['PedidoID'] ?>"><i class="fas fa-trash-alt"></i></button>
                    <?php else: ?>
                        <form action="actualizar_estado_pedido.php" method="post">
                            <input type="hidden" name="pedido_id" value="<?= $pedido['PedidoID'] ?>">
                            <select name="nuevo_estado">
                                <option value="Tramitando Pedido" <?= $pedido['Estado'] == 'Tramitando Pedido' ? 'selected' : '' ?>>Tramitando Pedido</option>
                                <option value="Pedido en el Almacén" <?= $pedido['Estado'] == 'Pedido en el Almacén' ? 'selected' : '' ?>>Pedido en el Almacén</option>
                                <option value="En Camino" <?= $pedido['Estado'] == 'En Camino' ? 'selected' : '' ?>>En Camino</option>
                                <option value="Entregado" <?= $pedido['Estado'] == 'Entregado' ? 'selected' : '' ?>>Entregado</option>
                            </select>
                            <button type="submit" class="btn">Actualizar</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginacion">
        <?php for ($i = 1; $i <= $paginas; $i++): ?>
            <a href="?pagina=<?= $i ?>&estado=<?= htmlspecialchars($estadoSeleccionado) ?>" class="btn <?= $i == $pagina ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>
</body>
</html>
