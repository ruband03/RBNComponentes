<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'conectaBBDD.php'; 
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sobre Nosotros</title>
<link rel="stylesheet" href="css/about.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<section class="about-section">
    <div class="container">
        <h2>Sobre Nosotros</h2>
        <p>RBNComponentes: Nos dedicamos a la venta de componentes de ordenador y periféricos a precios competitivos. Nuestro objetivo es, vender productos de alta calidad, con un trato al cliente excepcional. ¿Te lo vas a perder? ¡Anímate a Comprar!</p>
        <h3>¿Dónde nos encontramos?</h3>
        <p>Estamos ubicados en Calle Tomás Bretón, Zaragoza 50005.</p>
        <div class="full-width-map">
    <iframe src="https://maps.google.com/maps?q=Calle%20Tomás%20Bretón%2C%20Zaragoza%2050005&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
</div>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; 2024 RBNComponentes. Todos los derechos reservados.</p>
    </div>
</footer>

</body>
</html>
