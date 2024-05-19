<?php
session_start();
require_once 'conectaBBDD.php';
include 'header.php';

if (!isset($_SESSION['UserID']) || !isset($_SESSION['esAdministrador']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

$adminUserID = $_SESSION['UserID'];

// Verificar si el usuario es SuperAdmin
$stmt = $conn->prepare("SELECT Username FROM Usuario WHERE UserID = ?");
$stmt->execute([$adminUserID]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
$isSuperAdmin = $currentUser['Username'] === 'Rubenandia85';

$usuariosPorPagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina > 1) ? ($pagina * $usuariosPorPagina) - $usuariosPorPagina : 0;

$totalUsuarios = $conn->query("SELECT COUNT(*) FROM Usuario")->fetchColumn();
$paginas = ceil($totalUsuarios / $usuariosPorPagina);

$stmt = $conn->prepare("SELECT * FROM Usuario LIMIT :inicio, :usuariosPorPagina");
$stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindParam(':usuariosPorPagina', $usuariosPorPagina, PDO::PARAM_INT);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Usuarios</title>
    <link rel="stylesheet" href="css/ver_usuarios.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 
</head>
<body>
<div class="container">
    <h1>Listado de Usuarios</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['Username']) ?></td>
                <td><?= htmlspecialchars($usuario['Email']) ?></td>
                <td><?= htmlspecialchars($usuario['Nombre']) ?></td>
                <td><?= $usuario['EsAdministrador'] ? 'Administrador' : 'Usuario' ?></td>
                <td>
                    <?php if ($usuario['UserID'] != $adminUserID): ?>
                        <a href="editar_usuario.php?user_id=<?= $usuario['UserID'] ?>" class="btn">Editar</a>
                    <?php endif; ?>
                    <?php if (!$usuario['EsAdministrador'] || ($isSuperAdmin && $usuario['Username'] !== 'Rubenandia85')): ?>
                        <form action="eliminar_usuario.php" method="post" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a este usuario?');" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $usuario['UserID'] ?>">
                            <button type="submit" class="btn">Eliminar</button>
                        </form>
                    <?php endif; ?>
                    <?php if ($isSuperAdmin && $usuario['EsAdministrador'] && $usuario['Username'] !== 'Rubenandia85'): ?>
                        <form action="revocar_admin.php" method="post" onsubmit="return confirm('¿Estás seguro de que quieres revocar los permisos de administrador a este usuario?');" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $usuario['UserID'] ?>">
                            <button type="submit" class="btn">Revocar Admin</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginacion">
        <?php for ($i = 1; $i <= $paginas; $i++): ?>
            <a href="?pagina=<?= $i ?>" class="btn"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>

<footer>
    <div class="container">
        <p>&copy; 2024 RBNComponentes. Todos los derechos reservados.</p>
    </div>
</footer>

</body>
</html>
