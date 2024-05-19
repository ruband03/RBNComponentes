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

// Configuración de la paginación
$pedidosPorPagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina > 1) ? ($pagina * $pedidosPorPagina) - $pedidosPorPagina : 0;

// Obtener el total de pedidos
$totalPedidos = $conn->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$paginas = ceil($totalPedidos / $pedidosPorPagina);

// Obtener los pedidos con límite y offset para paginación
$stmt = $conn->prepare("SELECT p.PedidoID, p.UserID, p.Total, p.FechaPedido, p.Estado, u.Username FROM pedidos p JOIN Usuario u ON p.UserID = u.UserID LIMIT :inicio, :pedidosPorPagina");
$stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindParam(':pedidosPorPagina', $pedidosPorPagina, PDO::PARAM_INT);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
    <h1>Gestionar Pedidos</h1>
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
            <tr>
                <td><?= htmlspecialchars($pedido['PedidoID']) ?></td>
                <td><?= htmlspecialchars($pedido['Username']) ?></td>
                <td>€<?= number_format($pedido['Total'], 2) ?></td>
                <td><?= htmlspecialchars($pedido['FechaPedido']) ?></td>
                <td><?= htmlspecialchars($pedido['Estado']) ?></td>
                <td>
                    <?php if ($pedido['Estado'] == 'Entregado'): ?>
                        <span>Este pedido ya ha sido entregado.</span>
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
            <a href="?pagina=<?= $i ?>" class="btn <?= $i == $pagina ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>
</body>
</html>
