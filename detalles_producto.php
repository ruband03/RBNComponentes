<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php';

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$productoID = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM Producto WHERE ProductoID = ?");
$stmt->bindParam(1, $productoID);
$stmt->execute();
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header('Location: products.php');
    exit();
}

$comentariosPorPagina = 5;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($paginaActual > 1) ? ($paginaActual * $comentariosPorPagina) - $comentariosPorPagina : 0;

$stmt = $conn->prepare("SELECT COUNT(*) FROM Valoración WHERE ProductoID = ?");
$stmt->bindParam(1, $productoID);
$stmt->execute();
$totalComentarios = $stmt->fetchColumn();
$totalPaginas = ceil($totalComentarios / $comentariosPorPagina);

$stmt = $conn->prepare("SELECT v.ValoraciónID, v.Puntuación, v.Comentario, u.Username, u.UserID FROM Valoración v JOIN Usuario u ON v.UsuarioID = u.UserID WHERE ProductoID = ? LIMIT ? OFFSET ?");
$stmt->bindParam(1, $productoID);
$stmt->bindParam(2, $comentariosPorPagina, PDO::PARAM_INT);
$stmt->bindParam(3, $inicio, PDO::PARAM_INT);
$stmt->execute();
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detalles del Producto</title>
<link rel="stylesheet" href="css/detalles_producto.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon">
</head>
<body>
<?php include 'header.php'; ?>

<section class="product-details">
    <div class="container">
        <h2><?= htmlspecialchars($producto['Nombre']); ?></h2>
        <div class="product-detail-card">
            <img src="<?= $producto['ImagenURL']; ?>" alt="<?= htmlspecialchars($producto['Nombre']); ?>">
            <div class="product-info">
                <p><strong>Precio:</strong> €<?= number_format($producto['Precio'], 2); ?></p>
                <p><strong>Categoría:</strong> <?= htmlspecialchars($producto['Categoría']); ?></p>
                <p><?= htmlspecialchars($producto['Descripcion']); ?></p>
                <form action="añadir_al_carrito.php" method="post">
                    <input type="hidden" name="producto_id" value="<?= $productoID ?>">
                    <div class="quantity-control">
                        <button type="button" class="quantity-btn" onclick="changeQuantity(-1, 'cantidad')">-</button>
                        <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="30" readonly>
                        <button type="button" class="quantity-btn" onclick="changeQuantity(1, 'cantidad')">+</button>
                    </div>
                    <button type="submit" class="btn">Añadir al Carrito</button>
                </form>
            </div>
        </div>
        <div class="comments-section">
            <h3>Comentarios y Valoraciones</h3>
            <?php if (isset($_SESSION['UserID'])): ?>
                <form action="procesar_comentario.php" method="post">
                    <input type="hidden" name="producto_id" value="<?= $productoID ?>">
                    <textarea name="comentario" placeholder="Escribe tu comentario aquí..." required></textarea>
                    <label for="puntuacion">Puntuación:</label>
                    <select name="puntuacion" id="puntuacion">
                        <option value="">Ninguna</option>
                        <option value="1">1 Estrella</option>
                        <option value="2">2 Estrellas</option>
                        <option value="3">3 Estrellas</option>
                        <option value="4">4 Estrellas</option>
                        <option value="5">5 Estrellas</option>
                    </select>
                    <button type="submit" class="btn">Enviar</button>
                </form>
            <?php endif; ?>
            <?php if (!empty($comentarios)): ?>
                <?php foreach ($comentarios as $comentario): ?>
                    <div class="comment">
                        <p><strong><?= htmlspecialchars($comentario['Username']); ?></strong></p>
                        <p><?= htmlspecialchars($comentario['Comentario']); ?></p>
                        <?php if ($comentario['Puntuación']): ?>
                            <p>Puntuación: <?= htmlspecialchars($comentario['Puntuación']); ?> Estrellas</p>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['UserID']) && ($_SESSION['UserID'] == $comentario['UserID'] || isset($_SESSION['esAdministrador']) && $_SESSION['esAdministrador'])): ?>
                            <form action="editar_comentario.php" method="get">
                                <input type="hidden" name="valoracion_id" value="<?= $comentario['ValoraciónID'] ?>">
                                <button type="submit" class="btn">Editar</button>
                            </form>
                            <form action="eliminar_comentario.php" method="post" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este comentario?');">
                                <input type="hidden" name="valoracion_id" value="<?= $comentario['ValoraciónID'] ?>">
                                <button type="submit" class="btn">Eliminar</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <a href="?id=<?= $productoID ?>&pagina=<?= $i ?>" class="btn <?= $i == $paginaActual ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </div>
            <?php else: ?>
                <p>No hay comentarios ni valoraciones para este producto.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<footer>
    <div class="container">
        <p>&copy; 2024 RBNComponentes. Todos los derechos reservados.</p>
    </div>
</footer>
<script src="js/changeQuantity.js"></script>
</body>
</html>
