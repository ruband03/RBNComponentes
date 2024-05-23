<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
}

$currentFile = basename($_SERVER['PHP_SELF']);
$cartCount = 0;
$username = '';

if (isset($_SESSION['UserID'])) {
    require_once 'conectaBBDD.php';
    $user_id = $_SESSION['UserID'];
    
    $stmt = $conn->prepare("SELECT SUM(Cantidad) as total FROM Carrito WHERE UserID = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $cartCount = $result['total'] ? $result['total'] : 0;

    // Obtener la imagen de perfil y el nombre de usuario
    $stmt = $conn->prepare("SELECT ProfileImage, Username, EsAdministrador FROM Usuario WHERE UserID = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $profileImage = $user['ProfileImage'];
    $username = $user['Username'];
    $esAdministrador = $user['EsAdministrador'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RBNComponentes</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon">
</head>
<body>

<header>
    <div class="container">
        <div class="title">
            <a href="mainpage.php">
                <img src="logos/logo.png" alt="Logo de RBNComponentes">
            </a>
            <h1>RBNComponentes</h1>
        </div>
        <nav>
            <ul class="main-nav">
                <?php if (isset($_SESSION['esAdministrador']) && $_SESSION['esAdministrador']): ?>
                    <?php if ($currentFile != 'ver_usuarios.php'): ?>
                        <li><a href="ver_usuarios.php">Ver Usuarios</a></li>
                    <?php endif; ?>
                    <?php if ($currentFile != 'añadir_producto.php'): ?>
                        <li><a href="añadir_producto.php">Añadir Productos</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($currentFile != 'mainpage.php'): ?>
                    <li><a href="mainpage.php">Inicio</a></li>
                <?php endif; ?>
                <?php if ($currentFile != 'products.php'): ?>
                    <li><a href="products.php">Productos</a></li>
                <?php endif; ?>
                <?php if ($currentFile != 'about.php'): ?>
                    <li><a href="about.php">¿Dónde nos encontramos?</a></li>
                <?php endif; ?>
                <?php if ($currentFile != 'faq.php'): ?>
                    <li><a href="faq.php">FAQ</a></li>
                <?php endif; ?>
                <?php if ($currentFile != 'contact.php'): ?>
                    <li><a href="contact.php">Contacto</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['UserID']) && !$esAdministrador): ?>
                    <?php if ($currentFile != 'solicitar_admin.php'): ?>
                        <li><a href="solicitar_admin.php">Solicitar ser Administrador</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
        <?php if (isset($_SESSION['UserID'])): ?>
        <div class="user-profile">
            <div class="profile-image" id="profile-menu">
                <img style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;" src="<?= htmlspecialchars($profileImage) ?>" alt="Imagen de perfil">
                <p><?= htmlspecialchars($username) ?></p>
            </div>
            <ul class="dropdown-menu" id="dropdown-menu">
                <?php if ($esAdministrador): ?>
                    <li><a href="whitelist.php">Whitelist</a></li>
                    <li><a href="gestionar_pedidos.php">Gestionar Pedidos</a></li>
                <?php endif; ?>
                <li><a href="profile.php">Mi Perfil</a></li>
                <li><a href="ver_carrito.php">Carrito <span class="cart-count">(<?= $cartCount ?>)</span></a></li>
                <li><a href="ver_lista_deseos.php">Lista de Deseos</a></li>
                <li><a href="ver_estado_pedido.php">Ver Estado de mi Pedido</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</header>

<script src="js/dropdownMenu.js"></script>
</body>
</html>
