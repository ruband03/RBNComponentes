<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $provincia = $_POST['provincia'];
    $comunidad = $_POST['comunidad'];
    $codigoPostal = $_POST['codigo_postal'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    $envio_normal = 13.01;
    $envio_especial = 14.64;
    $comunidades_especiales = [4, 5, 18, 19];

    $gastos_envio = in_array($comunidad, $comunidades_especiales) ? $envio_especial : $envio_normal;

    $_SESSION['envio'] = [
        'direccion' => $direccion,
        'ciudad' => $ciudad,
        'provincia' => $provincia,
        'comunidad' => $comunidad,
        'codigo_postal' => $codigoPostal,
        'telefono' => $telefono,
        'email' => $email,
        'gastos_envio' => $gastos_envio
    ];

    header('Location: metodo_pago.php');
    exit();
}

$comunidades = $conn->query("SELECT ComunidadID, Nombre FROM comunidades")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Datos de Envío</title>
<link rel="stylesheet" href="css/envio.css">
<link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon">
<script src="js/envio.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<section class="envio-section">
    <div class="container">
        <h2>Datos de Envío</h2>
        <form method="post" action="envio.php">
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required>
            </div>
            <div class="form-group">
                <label for="ciudad">Ciudad:</label>
                <input type="text" id="ciudad" name="ciudad" required>
            </div>
            <div class="form-group">
                <label for="comunidad">Comunidad Autónoma:</label>
                <select id="comunidad" name="comunidad" required>
                    <option value="">Seleccione una comunidad</option>
                    <?php foreach ($comunidades as $comunidad): ?>
                        <option value="<?= htmlspecialchars($comunidad['ComunidadID']) ?>"><?= htmlspecialchars($comunidad['Nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="provincia">Provincia:</label>
                <select id="provincia" name="provincia" required>
                    <option value="">Seleccione una comunidad autónoma primero</option>
                </select>
            </div>
            <div class="form-group">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" id="codigo_postal" name="codigo_postal" required>
            </div>
            <div class="form-group">
                <label for="telefono">Número de Teléfono:</label>
                <input type="text" id="telefono" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="btn">Continuar</button>
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
