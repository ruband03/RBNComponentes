<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID']) || !isset($_SESSION['envio']) || !isset($_SESSION['metodo_pago'])) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

$userid = $_SESSION['UserID'];
$envio = $_SESSION['envio'];
$metodoPago = $_SESSION['metodo_pago'];
$costeEnvio = $_SESSION['coste_envio'];

// Obtener el nombre de la comunidad autónoma
$comunidadID = $envio['comunidad'];
$stmt = $conn->prepare("SELECT Nombre FROM comunidades WHERE ComunidadID = ?");
$stmt->execute([$comunidadID]);
$comunidad = $stmt->fetch(PDO::FETCH_ASSOC)['Nombre'];

$stmt = $conn->prepare("SELECT c.Cantidad, p.Precio FROM Carrito c JOIN Producto p ON c.ProductoID = p.ProductoID WHERE c.UserID = ?");
$stmt->execute([$userid]);
$carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($carrito as $producto) {
    $total += $producto['Cantidad'] * $producto['Precio'];
}

$total += $costeEnvio;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resumen de Pedido</title>
<link rel="stylesheet" href="css/resumen_pedido.css">
</head>
<body>
<?php include 'header.php'; ?>

<section class="resumen-section">
    <div class="container">
        <h2>Resumen de Pedido</h2>
        <p><strong>Dirección:</strong> <?= htmlspecialchars($envio['direccion']) ?></p>
        <p><strong>Ciudad:</strong> <?= htmlspecialchars($envio['ciudad']) ?></p>
        <p><strong>Comunidad Autónoma:</strong> <?= htmlspecialchars($comunidad) ?></p>
        <p><strong>Provincia:</strong> <?= htmlspecialchars($envio['provincia']) ?></p>
        <p><strong>Código Postal:</strong> <?= htmlspecialchars($envio['codigo_postal']) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($envio['telefono']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($envio['email']) ?></p>
        <p><strong>Método de Pago:</strong> <?= htmlspecialchars($metodoPago) ?></p>
        <p><strong>Coste de Envío:</strong> <?= number_format($costeEnvio, 2) ?>€</p>
        <p><strong>Total:</strong> <?= number_format($total, 2) ?>€</p>

        <form method="post" action="procesar_pedido.php">
            <button type="submit" class="btn">Confirmar Pedido</button>
        </form>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; 2024 RBNComponentes. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
</html>
