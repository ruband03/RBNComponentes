<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'conectaBBDD.php';
    $user_id = $_SESSION['UserID'];
    $motivo = $_POST['motivo'];

    $stmt = $conn->prepare("INSERT INTO AdminRequests (UserID, Motivo) VALUES (?, ?)");
    $stmt->execute([$user_id, $motivo]);

    $_SESSION['message'] = "Solicitud enviada correctamente.";
    header('Location: mainpage.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar ser Administrador</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
    <h2>Solicitar ser Administrador</h2>
    <form method="post" action="solicitar_admin.php">
        <div class="form-group">
            <label for="motivo">Motivos para ser Administrador:</label>
            <textarea id="motivo" name="motivo" required></textarea>
        </div>
        <button type="submit">Enviar Solicitud</button>
    </form>
</div>

</body>
</html>
