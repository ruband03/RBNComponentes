<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php';

$categorias = ['Monitor', 'Ratón', 'Teclado', 'RAM', 'SSD', 'Disco Duro', 'Placa Base', 'Gráfica', 'Fuente de Alimentación', 'Procesador'];
$categoriaSeleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$ordenSeleccionado = isset($_GET['orden']) ? $_GET['orden'] : '';

$query = "SELECT * FROM Producto";
$params = [];

if ($categoriaSeleccionada && in_array($categoriaSeleccionada, $categorias)) {
    $query .= " WHERE Categoría = ?";
    $params[] = $categoriaSeleccionada;
}

if ($ordenSeleccionado) {
    if ($ordenSeleccionado == 'precio_asc') {
        $query .= " ORDER BY Precio ASC";
    } elseif ($ordenSeleccionado == 'precio_desc') {
        $query .= " ORDER BY Precio DESC";
    } else {
        $query .= " ORDER BY Categoría, Nombre";
    }
} else {
    $query .= " ORDER BY Categoría, Nombre";
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener la lista de deseos del usuario
$listaDeseos = [];
if (isset($_SESSION['UserID'])) {
    $userId = $_SESSION['UserID'];
    $stmt = $conn->prepare("SELECT ProductoID FROM listadeseosproducto WHERE ListaDeseosID = (SELECT ListaDeseosID FROM listadeseos WHERE UsuarioID = ?)");
    $stmt->execute([$userId]);
    $listaDeseos = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Productos</title>
<link rel="stylesheet" href="css/products.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>

<section class="products-section">
    <div class="container">
        <h2>Nuestros Productos</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert">
                <?= $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <div class="filter">
            <form action="products.php" method="get">
                <select name="categoria" onchange="this.form.submit()">
                    <option value="">Todas las Categorías</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria ?>" <?= $categoria == $categoriaSeleccionada ? 'selected' : '' ?>><?= $categoria ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="orden" onchange="this.form.submit()">
                    <option value="">Ordenar por</option>
                    <option value="precio_asc" <?= $ordenSeleccionado == 'precio_asc' ? 'selected' : '' ?>>Precio: Menor a Mayor</option>
                    <option value="precio_desc" <?= $ordenSeleccionado == 'precio_desc' ? 'selected' : '' ?>>Precio: Mayor a Menor</option>
                </select>
            </form>
        </div>
        <div class="product-grid">
            <?php foreach ($productos as $producto): ?>
            <div class="product-card">
                <img src="<?= $producto['ImagenURL']; ?>" alt="<?= htmlspecialchars($producto['Nombre']); ?>">
                <h4><?= htmlspecialchars($producto['Nombre']); ?></h4>
                <p class="price"><?= number_format($producto['Precio'], 2); ?>€</p>
                <a href="detalles_producto.php?id=<?= $producto['ProductoID'] ?>" class="btn">Ver Detalles</a>
                <form action="añadir_al_carrito.php" method="post">
                    <input type="hidden" name="producto_id" value="<?= $producto['ProductoID'] ?>">
                    <input type="hidden" name="cantidad" value="1">
                    <button type="submit" class="btn">Añadir al Carrito</button>
                </form>
                <?php if (in_array($producto['ProductoID'], $listaDeseos)): ?>
                    <form action="eliminar_de_lista_deseos.php" method="post" class="wishlist-form">
                        <input type="hidden" name="producto_id" value="<?= $producto['ProductoID'] ?>">
                        <button type="submit" class="wishlist-btn">&#9733;</button> <!-- Estrella llena -->
                    </form>
                <?php else: ?>
                    <form action="añadir_a_lista_deseos.php" method="post" class="wishlist-form">
                        <input type="hidden" name="producto_id" value="<?= $producto['ProductoID'] ?>">
                        <button type="submit" class="wishlist-btn">&#9734;</button> <!-- Estrella vacía -->
                    </form>
                <?php endif; ?>
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
