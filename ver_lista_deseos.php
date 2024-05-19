<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

$userId = $_SESSION['UserID'];

// Obtener la lista de deseos del usuario
$stmt = $conn->prepare("SELECT ListaDeseosID FROM listadeseos WHERE UsuarioID = ?");
$stmt->execute([$userId]);
$listaDeseos = $stmt->fetch(PDO::FETCH_ASSOC);

$productos = [];
if ($listaDeseos) {
    $listaDeseosId = $listaDeseos['ListaDeseosID'];

    // Obtener los productos en la lista de deseos
    $stmt = $conn->prepare("SELECT p.ProductoID, p.Nombre, p.Precio, p.ImagenURL 
                            FROM listadeseosproducto ldp 
                            JOIN producto p ON ldp.ProductoID = p.ProductoID 
                            WHERE ldp.ListaDeseosID = ?");
    $stmt->execute([$listaDeseosId]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lista de Deseos</title>
<link rel="stylesheet" href="css/ver_lista_deseos.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon">
</head>
<body>
<?php include 'header.php'; ?>

<section class="wishlist-section">
    <div class="container">
        <h2>Mi Lista de Deseos</h2>
        <?php if (empty($productos)): ?>
            <p>No hay productos en la lista de deseos por el momento.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($productos as $producto): ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($producto['ImagenURL']); ?>" alt="<?= htmlspecialchars($producto['Nombre']); ?>">
                    <h4><?= htmlspecialchars($producto['Nombre']); ?></h4>
                    <p class="price"><?= number_format($producto['Precio'], 2); ?>€</p>
                    <a href="detalles_producto.php?id=<?= $producto['ProductoID'] ?>" class="btn">Ver Detalles</a>
                    <form action="añadir_al_carrito.php" method="post">
                        <input type="hidden" name="producto_id" value="<?= $producto['ProductoID'] ?>">
                        <input type="hidden" name="cantidad" value="1">
                        <button type="submit" class="btn">Añadir al Carrito</button>
                    </form>
                    <form action="eliminar_de_lista_deseos.php" method="post" class="wishlist-form">
                        <input type="hidden" name="producto_id" value="<?= $producto['ProductoID'] ?>">
                        <button type="submit" class="wishlist-btn">&#9733;</button> <!-- Estrella llena -->
                    </form>
                </div>
                <?php endforeach; ?>
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
