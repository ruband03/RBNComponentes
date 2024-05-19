<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

$userid = $_SESSION['UserID'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $envio = [
        'direccion' => $_POST['direccion'],
        'ciudad' => $_POST['ciudad'],
        'comunidad' => $_POST['comunidad'],
        'codigo_postal' => $_POST['codigo_postal'],
        'telefono' => $_POST['telefono'],
        'email' => $_POST['email']
    ];
    $_SESSION['envio'] = $envio;

    // Calcular el coste de envío
    $costeEnvio = 1.69; // Coste base de envío
    if (in_array($envio['comunidad'], ['Islas Baleares', 'Canarias', 'Ceuta', 'Melilla'])) {
        $costeEnvio = 5.40;
    }
    $_SESSION['coste_envio'] = $costeEnvio;

    header('Location: metodo_pago.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dirección de Envío</title>
<link rel="stylesheet" href="css/checkout.css">
<link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon">
</head>
<body>
<?php include 'header.php'; ?>

<section class="checkout-section">
    <div class="container">
        <h2>Dirección de Envío</h2>
        <form method="post" action="checkout.php">
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
                <input type="text" id="comunidad" name="comunidad" required>
            </div>
            <div class="form-group">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" id="codigo_postal" name="codigo_postal" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
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
