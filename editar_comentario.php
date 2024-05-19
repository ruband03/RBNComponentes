<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php';

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

$valoracionID = null;
$comentario = null;

// Si la solicitud es GET, se debe mostrar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['valoracion_id'])) {
    $valoracionID = $_GET['valoracion_id'];
    
    // Verificar si el usuario es el autor del comentario o un administrador
    $stmt = $conn->prepare("SELECT UsuarioID, ProductoID, Comentario, Puntuación FROM Valoración WHERE ValoraciónID = ?");
    $stmt->execute([$valoracionID]);
    $comentario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comentario || ($_SESSION['UserID'] != $comentario['UsuarioID'] && !$_SESSION['esAdministrador'])) {
        header('Location: detalles_producto.php');
        exit();
    }
}

// Si la solicitud es POST, se debe procesar la actualización del comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valoracion_id'])) {
    $valoracionID = $_POST['valoracion_id'];
    $comentarioActualizado = $_POST['comentario'];
    $puntuacionActualizada = $_POST['puntuacion'];

    // Verificar si el usuario es el autor del comentario o un administrador
    $stmt = $conn->prepare("SELECT UsuarioID, ProductoID FROM Valoración WHERE ValoraciónID = ?");
    $stmt->execute([$valoracionID]);
    $comentario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($comentario && ($_SESSION['UserID'] == $comentario['UsuarioID'] || $_SESSION['esAdministrador'])) {
        $stmt = $conn->prepare("UPDATE Valoración SET Comentario = ?, Puntuación = ? WHERE ValoraciónID = ?");
        $stmt->execute([$comentarioActualizado, $puntuacionActualizada, $valoracionID]);

        $_SESSION['message'] = "Comentario actualizado correctamente.";
        header("Location: detalles_producto.php?id=" . $comentario['ProductoID']);
        exit();
    } else {
        header('Location: detalles_producto.php');
        exit();
    }
}

// Si no hay valoracionID o comentario, se redirige
if (!$valoracionID || !$comentario) {
    header('Location: detalles_producto.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Comentario</title>
<link rel="stylesheet" href="css/editar_comentario.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon"> 
</head>
<body>
<?php include 'header.php'; ?>

<section class="edit-comment">
    <div class="container">
        <h2>Editar Comentario</h2>
        <form action="editar_comentario.php" method="post">
            <input type="hidden" name="valoracion_id" value="<?= $valoracionID ?>">
            <textarea name="comentario" required><?= htmlspecialchars($comentario['Comentario']) ?></textarea>
            <label for="puntuacion">Puntuación:</label>
            <select name="puntuacion" id="puntuacion">
                <option value="1" <?= $comentario['Puntuación'] == 1 ? 'selected' : '' ?>>1 Estrella</option>
                <option value="2" <?= $comentario['Puntuación'] == 2 ? 'selected' : '' ?>>2 Estrellas</option>
                <option value="3" <?= $comentario['Puntuación'] == 3 ? 'selected' : '' ?>>3 Estrellas</option>
                <option value="4" <?= $comentario['Puntuación'] == 4 ? 'selected' : '' ?>>4 Estrellas</option>
                <option value="5" <?= $comentario['Puntuación'] == 5 ? 'selected' : '' ?>>5 Estrellas</option>
            </select>
            <button type="submit" class="btn">Guardar Cambios</button>
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
