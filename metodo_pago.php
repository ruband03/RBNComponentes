<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID']) || !isset($_SESSION['envio'])) {
    header('Location: login.php');
    exit();
}

$envio = $_SESSION['envio'];
$provincia = $envio['provincia'];

if ($provincia == 'Las Palmas' || $provincia == 'Santa Cruz de Tenerife' || $provincia == 'Baleares' || $provincia == 'Ceuta' || $provincia == 'Melilla') {
    $costeEnvio = 5.40;
} else {
    $costeEnvio = 1.69;
}

$_SESSION['coste_envio'] = $costeEnvio;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $metodoPago = $_POST['metodo_pago'];
    $_SESSION['metodo_pago'] = $metodoPago;

    if ($metodoPago == 'tarjeta') {
        header('Location: pago_tarjeta.php');
    } else {
        header('Location: resumen_pedido.php');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Método de Pago</title>
<link rel="stylesheet" href="css/metodo_pago.css">
</head>
<body>
<?php include 'header.php'; ?>

<section class="payment-method-section">
    <div class="container">
        <h2>Elige tu Método de Pago Preferido</h2>
        <form method="post" action="metodo_pago.php">
            <div class="form-group">
                <input type="radio" id="tarjeta" name="metodo_pago" value="tarjeta" required>
                <label for="tarjeta">Tarjeta de Crédito</label>
            </div>
            <div class="form-group">
                <input type="radio" id="contrareembolso" name="metodo_pago" value="contrareembolso" required>
                <label for="contrareembolso">Contra Reembolso</label>
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
