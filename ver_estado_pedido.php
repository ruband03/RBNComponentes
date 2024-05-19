<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

$userid = $_SESSION['UserID'];
$pedidosPorPagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina > 1) ? ($pagina * $pedidosPorPagina) - $pedidosPorPagina : 0;

$totalPedidos = $conn->prepare("SELECT COUNT(*) FROM pedidos WHERE UserID = ?");
$totalPedidos->execute([$userid]);
$totalPedidos = $totalPedidos->fetchColumn();
$paginas = ceil($totalPedidos / $pedidosPorPagina);

$stmt = $conn->prepare("SELECT * FROM pedidos WHERE UserID = :userid LIMIT :inicio, :pedidosPorPagina");
$stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
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
<title>Estado de mi Pedido</title>
<link rel="stylesheet" href="css/ver_estado_pedido.css">
<link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon">
</head>
<body>
<?php include 'header.php'; ?>

<section class="estado-pedido-section">
    <div class="container">
        <h2>Estado de mi Pedido</h2>
        <?php if (empty($pedidos)): ?>
            <p>Ahora mismo, no tienes pedidos activos.</p>
        <?php else: ?>
            <?php foreach ($pedidos as $pedido): ?>
                <div class="pedido">
                    <h3>Pedido ID: <?= htmlspecialchars($pedido['PedidoID']) ?></h3>
                    <p><strong>Dirección:</strong> <?= htmlspecialchars($pedido['Direccion']) ?></p>
                    <p><strong>Ciudad:</strong> <?= htmlspecialchars($pedido['Ciudad']) ?></p>
                    <p><strong>Provincia:</strong> <?= htmlspecialchars($pedido['Provincia']) ?></p>
                    <p><strong>Comunidad Autónoma:</strong> <?= htmlspecialchars($pedido['Comunidad']) ?></p>
                    <p><strong>Código Postal:</strong> <?= htmlspecialchars($pedido['CodigoPostal']) ?></p>
                    <p><strong>Teléfono:</strong> <?= htmlspecialchars($pedido['Telefono']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($pedido['Email']) ?></p>
                    <p><strong>Metodo de Pago:</strong> <?= htmlspecialchars($pedido['MetodoPago']) ?></p>
                    <p><strong>Coste de Envío:</strong> <?= number_format($pedido['CosteEnvio'], 2) ?>€</p>
                    <p><strong>Total:</strong> <?= number_format($pedido['Total'], 2) ?>€</p>
                    <p><strong>Estado:</strong> <?= htmlspecialchars($pedido['Estado']) ?></p>
                    <h4>Productos</h4>
                    <ul>
                        <?php
                        $stmt2 = $conn->prepare("SELECT dp.Cantidad, dp.Precio, p.Nombre FROM detallepedido dp JOIN producto p ON dp.ProductoID = p.ProductoID WHERE dp.PedidoID = ?");
                        $stmt2->execute([$pedido['PedidoID']]);
                        $productos = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($productos as $producto):
                        ?>
                            <li><?= htmlspecialchars($producto['Nombre']) ?> - <?= htmlspecialchars($producto['Cantidad']) ?> x €<?= number_format($producto['Precio'], 2) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
            <div class="paginacion">
                <?php for ($i = 1; $i <= $paginas; $i++): ?>
                    <a href="?pagina=<?= $i ?>" class="btn"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; 2024 RBNComponentes. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
</html>
