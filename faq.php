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
<title>FAQ</title>
<link rel="stylesheet" href="css/faq.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 
</head>
<body>

<section class="faq-section">
    <div class="container">
        <h2>Preguntas Frecuentes</h2>
        <div class="faq-item">
            <h3>¿Cómo realizo un pedido?</h3>
            <p>Para realizar un pedido, simplemente navega a la sección de productos, selecciona el producto que deseas y entra al carrito y continua el proceso de compra desde ahí.</p>
        </div>

        <br>
        
        <div class="faq-item">
            <h3>¿Qué métodos de pago aceptan?</h3>
            <p>Por el momento aceptamos 2 métodos de pago incluyendo tarjeta de crédito y contrarembolso.</p>
        </div>

        <br>

        <div class="faq-item">
            <h3>¿Qué opciones de envío ofrecen dentro de la península?</h3>
            <p>Actualemente, solo disponemos del envío Standar por el que cobramos 1,69€ de gastos de envío.</p>
        </div>

        <br>

        <div class="faq-item">
            <h3>¿Por qué no realizan envíos a las Islas Canarias y las Islas Baleares?</h3>
            <p> Actualmente no enviamos a las Islas Canarias ni a las Islas Baleares debido a las limitaciones logísticas y los costos elevados de transporte. Estamos trabajando para incluir estas regiones en el futuro.</p>
        </div>

        <br>

        <div class="faq-item">
            <h3>¿Cómo puedo hacer seguimiento a mi pedido?</h3>
            <p>Una vez que tu pedido sea enviado, entra al apartado de Ver Estado de mi Pedido y te saldrá.</p>
        </div>

        <br>

        <div class="faq-item">
            <h3>¿Qué tipo de garantía ofrecen para sus productos?</h3>
            <p>Todos nuestros productos vienen con una garantía de fabricante que cubre defectos de material o de fabricación durante un período de dos años desde la fecha de compra.</p>
        </div>

        <br>

        <div class="faq-item">
            <h3>¿Cuál es su política de devoluciones?</h3>
            <p>Ofrecemos una política de devolución de 30 días. Si no estás completamente satisfecho con tu compra, puedes devolverla en su estado original dentro de los 30 días para un reembolso completo.</p>
        </div>

        <br>

        <div class="faq-item">
            <h3>¿Cómo puedo contactar con atención al cliente si tengo un problema?</h3>
            <p>Puedes contactarnos a través de nuestro formulario de contacto en la página web, por email a servicio@rbncomponentes.com, o por teléfono al +34 656 33 73 68 durante nuestro horario de atención al cliente de lunes a viernes de 9:00 a 18:00.</p>
        </div>

        <br>

        <div class="faq-item">
            <h3>¿Qué sucede si el producto que quiero comprar está agotado?</h3>
            <p>Estamos comenzando en el negocio. A medida que vayamos mejorando, el stock aumentará, por el momento, sentimos no contar con un stock suficiente para cubrir muchos pedidos, seguimos trabajando en ello por lo que si no hay stock, no se podrá comprar el producto =( .</p>
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
