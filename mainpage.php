<?php
require_once 'conectaBBDD.php';
include 'header.php'; 

if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
}

// Seleccionar productos aleatoriamente de la base de datos
$stmt = $conn->prepare("SELECT * FROM Producto ORDER BY RAND() LIMIT 3");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="stylesheet" href="css/mainpage.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon"> 
</head>
<body>
<section class="hero">
    <div class="container">
        <h2>Bienvenido a RBNComponentes</h2>
        <p>¡Aquí encontrarás los mejores componentes de ordenador, al mejor precio!</p>
        <a href="products.php" class="btn">Explorar Productos</a>
    </div>
</section>

<section class="featured-products">
    <div class="container">
        <h2>Productos Destacados</h2>
        <div class="product-grid">
            <?php foreach ($productos as $producto): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($producto['ImagenURL']); ?>" alt="<?= htmlspecialchars($producto['Nombre']); ?>">
                <h3><?= htmlspecialchars($producto['Nombre']); ?></h3>
                <p class="price">€<?= number_format($producto['Precio'], 2); ?></p>
                <a href="detalles_producto.php?id=<?= $producto['ProductoID']; ?>" class="btn">Ver Detalles</a>
                <form action="añadir_al_carrito.php" method="post" class="inline-form">
                    <input type="hidden" name="producto_id" value="<?= $producto['ProductoID']; ?>">
                    <input type="hidden" name="cantidad" value="1">
                    <button type="submit" class="btn">Añadir al Carrito</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; 2024 RBNComponentes. Todos los derechos reservados.</p>
    </div>
</footer>

</body>
</html>
