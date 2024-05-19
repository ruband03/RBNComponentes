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

$userid = $_SESSION['UserID'];

try {
    $stmt = $conn->prepare("SELECT Username, Email, Nombre, Idioma, ProfileImage FROM Usuario WHERE UserID = :userid");
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mi Perfil | RBNComponentes</title>
<link rel="stylesheet" href="css/profile.css">
</head>
<body>

<section class="profile-section">
    <div class="container">
        <h2>Mi Perfil</h2>
        <?php if ($result): ?>
            <?php if ($result['ProfileImage']): ?>
                <div class="profile-image">
                    <img style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;" src="<?= htmlspecialchars($result['ProfileImage']) ?>" alt="Imagen de perfil">
                </div>
            <?php else: ?>
                <p>Añade tu foto de perfil</p>
                <form method="post" action="update_profile.php" enctype="multipart/form-data">
                    <label for="profile_image">Subir imagen de perfil:</label>
                    <input type="file" id="profile_image" name="profile_image" required>
                    <button type="submit" class="btn">Actualizar Imagen</button>
                </form>
            <?php endif; ?>
            <p>Usuario: <?= htmlspecialchars($result['Username']); ?></p>
            <form method="post" action="update_profile.php" class="profile-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username">Nombre de usuario:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($result['Username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($result['Email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="name">Nombre completo:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($result['Nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="idioma">Idioma:</label>
                    <select id="idioma" name="idioma" required>
                        <option value="es" <?= $result['Idioma'] == 'es' ? 'selected' : '' ?>>Español</option>
                        <option value="en" <?= $result['Idioma'] == 'en' ? 'selected' : '' ?>>Inglés</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña Actual:</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="new_password">Nueva Contraseña:</label>
                    <input type="password" id="new_password" name="new_password">
                </div>
                <div class="form-group">
                    <label for="profile_image">Cambiar imagen de perfil:</label>
                    <input type="file" id="profile_image" name="profile_image">
                </div>
                <div class="form-group">
                    <button type="submit">Actualizar Datos</button>
                    <button type="button" onclick="confirmDelete()">Eliminar Perfil</button>
                </div>
                <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
                    <div class="success-message">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                        Perfil Actualizado Correctamente
                    </div>
                <?php elseif (isset($_GET['error'])): ?>
                    <div class="error-message">
                        <svg class="error-x" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                        Ha habido un error al actualizar el campo
                    </div>
                <?php endif; ?>
            </form>
        <?php else: ?>
            <p>No se encontraron datos del usuario.</p>
        <?php endif; ?>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; 2024 RBNComponentes. Todos los derechos reservados.</p>
    </div>
</footer>
<script src="js/confirmDelete.js"></script>

</body>
</html>
