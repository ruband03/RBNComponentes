<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID']) || !isset($_SESSION['envio']) || !isset($_SESSION['metodo_pago'])) {
    header('Location: login.php');
    exit();
}

require_once 'conectaBBDD.php';

$userid = $_SESSION['UserID'];
$envio = $_SESSION['envio'];
$metodoPago = $_SESSION['metodo_pago'];
$costeEnvio = $_SESSION['coste_envio'];

$stmt = $conn->prepare("SELECT Producto.ProductoID, Producto.Precio, Carrito.Cantidad FROM Carrito JOIN Producto ON Carrito.ProductoID = Producto.ProductoID WHERE Carrito.UserID = ?");
$stmt->execute([$userid]);
$carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($carrito as $producto) {
    $total += $producto['Cantidad'] * $producto['Precio'];
}

$total += $costeEnvio;

try {
    $conn->beginTransaction();

    $stmt = $conn->prepare("INSERT INTO Pedidos (UserID, Direccion, Ciudad, Comunidad, CodigoPostal, Telefono, Email, MetodoPago, CosteEnvio, Total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $userid,
        $envio['direccion'],
        $envio['ciudad'],
        $envio['comunidad'],
        $envio['codigo_postal'],
        $envio['telefono'],
        $envio['email'],
        $metodoPago,
        $costeEnvio,
        $total
    ]);

    $pedidoID = $conn->lastInsertId();

    $stmt = $conn->prepare("INSERT INTO DetallePedido (PedidoID, ProductoID, Cantidad, Precio) VALUES (?, ?, ?, ?)");
    foreach ($carrito as $producto) {
        $stmt->execute([
            $pedidoID,
            $producto['ProductoID'],
            $producto['Cantidad'],
            $producto['Precio']
        ]);
    }

    $stmt = $conn->prepare("DELETE FROM Carrito WHERE UserID = ?");
    $stmt->execute([$userid]);

    $conn->commit();
    $_SESSION['message'] = "Pedido realizado correctamente.";
    header('Location: mainpage.php');
    exit();
} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['message'] = "Error al procesar el pedido: " . $e->getMessage();
    header('Location: ver_carrito.php');
    exit();
}
?>
