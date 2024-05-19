<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php';
include 'header.php';

if (!isset($_SESSION['UserID']) || !isset($_SESSION['esAdministrador']) || !$_SESSION['esAdministrador']) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['user_id'])) {
    header('Location: ver_usuarios.php');
    exit();
}

$userId = $_GET['user_id'];

$stmt = $conn->prepare("SELECT Username, Email, Nombre FROM Usuario WHERE UserID = ?");
$stmt->bindParam(1, $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: ver_usuarios.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Usuario</title>
<link rel="stylesheet" href="css/editar_usuario.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon"> 
</head>
<body>
<section class="profile-section">
    <div class="container">
        <h2>Editar Perfil de Usuario</h2>
        <form method="post" action="procesar_editar_usuario.php" class="profile-form">
            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
            <div class="form-group">
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['Username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="name">Nombre completo:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['Nombre']); ?>" required>
            </div>
            <button type="submit" class="btn">Actualizar Datos</button>
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
