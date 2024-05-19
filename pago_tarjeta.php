<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID']) || !isset($_SESSION['envio']) || !isset($_SESSION['metodo_pago'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Procesar los datos de la tarjeta de crédito
    // En un entorno real, aquí se integrarían servicios de pago seguros como Stripe o PayPal

    header('Location: resumen_pedido.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pago con Tarjeta de Crédito</title>
<link rel="stylesheet" href="css/pago_tarjeta.css">
<link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon">
</head>
<body>
<?php include 'header.php'; ?>

<section class="credit-card-section">
    <div class="container">
        <h2>Pago con Tarjeta de Crédito</h2>
        <form method="post" action="pago_tarjeta.php">
            <div class="form-group">
                <label for="numero_tarjeta">Número de Tarjeta:</label>
                <input type="text" id="numero_tarjeta" name="numero_tarjeta" required>
            </div>
            <div class="form-group">
                <label for="fecha_expiracion">Fecha de Expiración (MM/AA):</label>
                <input type="text" id="fecha_expiracion" name="fecha_expiracion" required>
            </div>
            <div class="form-group">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required>
            </div>
            <button type="submit" class="btn">Pagar</button>
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
