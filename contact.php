<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php';
include 'header.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contacto</title>
<link rel="stylesheet" href="css/contact.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="shortcut icon" href="logos/favicon.ico" type="image/x-icon">
</head>
<body>
<section class="contact-section">
    <div class="container">
        <h2>Contacto</h2>
        <p>Para cualquier consulta, no dudes en contactarnos:</p>
        <ul>
            <li>Email: contacto@rbncomponentes.com</li>
            <li>Teléfono: +34 900 123 456</li>
            <li>Dirección: Calle Tomás Bretón, Zaragoza 50005</li>
        </ul>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; 2024 RBNComponentes. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
</html>
