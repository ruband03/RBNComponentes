<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php';
include 'header.php';

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['UserID'];

$stmt = $conn->prepare("SELECT Producto.Nombre, Producto.Precio, Carrito.Cantidad, Producto.ProductoID FROM Carrito JOIN Producto ON Carrito.ProductoID = Producto.ProductoID WHERE Carrito.UserID = ?");
$stmt->execute([$user_id]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="css/ver_carrito.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Carrito de Compras</h1>
    <?php if (empty($productos)): ?>
        <p>Actualmente, no tienes productos en el carrito, <a href="products.php">haz click aquí</a> para empezar a añadir productos.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= htmlspecialchars($producto['Nombre']) ?></td>
                    <td>€<?= number_format($producto['Precio'], 2) ?></td>
                    <td>
                        <form action="actualizar_carrito.php" method="post" class="inline-form">
                            <input type="hidden" name="producto_id" value="<?= $producto['ProductoID'] ?>">
                            <button type="submit" name="action" value="decrement" class="quantity-btn" <?= $producto['Cantidad'] <= 1 ? 'disabled' : '' ?>>-</button>
                            <?= $producto['Cantidad'] ?>
                            <button type="submit" name="action" value="increment" class="quantity-btn" <?= $producto['Cantidad'] >= 30 ? 'disabled' : '' ?>>+</button>
                        </form>
                    </td>
                    <td>€<?= number_format($producto['Precio'] * $producto['Cantidad'], 2) ?></td>
                    <td>
                        <form action="actualizar_carrito.php" method="post" class="inline-form">
                            <input type="hidden" name="producto_id" value="<?= $producto['ProductoID'] ?>">
                            <button type="submit" name="action" value="remove" class="remove-btn">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form action="envio.php" method="get">
            <button type="submit" class="btn">Proceder al Pago</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
