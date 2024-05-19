<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'conectaBBDD.php';
include 'header.php';

if (!isset($_SESSION['esAdministrador']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

$result = $conn->query("SELECT UserID, Username, EsAdministrador FROM Usuario");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="css/administrar_usuario.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon"> 
</head>
<body>
<h1>Administrar Usuarios</h1>
<table border="1">
    <tr>
        <th>Username</th>
        <th>Es Administrador</th>
        <th>Acción</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['Username']) ?></td>
        <td><?= $row['EsAdministrador'] ? 'Sí' : 'No' ?></td>
        <td>
            <?php if (!$row['EsAdministrador']): ?>
            <a href="hacer_admin.php?user_id=<?= $row['UserID'] ?>">Hacer Admin</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<footer>
    <div class="container">
        <p>&copy; 2024 RBNComponentes. Todos los derechos reservados.</p>
    </div>
</footer>

</body>
</html>
