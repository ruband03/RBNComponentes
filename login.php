<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "rbncomponentes";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; 
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT UserID, Contraseña, EsAdministrador, Mensaje FROM Usuario WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userid, $hashed_password, $esAdministrador, $mensaje);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['UserID'] = $userid;
            $_SESSION['esAdministrador'] = $esAdministrador;
            
            if (!empty($mensaje)) {
                $_SESSION['message'] = $mensaje;
                $stmt = $conn->prepare("UPDATE Usuario SET Mensaje = NULL WHERE UserID = ?");
                $stmt->bind_param("i", $userid);
                $stmt->execute();
            }
            
            header("Location: mainpage.php");
            exit;
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inicio de Sesión</title>
<link rel="stylesheet" href="css/login.css">
<link rel="shortcut icon" href="../logos/RBNComponentes.ico" type="image/x-icon">
</head>
<body>
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert">
        <?= $_SESSION['message']; ?>
        <?php unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>
<div class="login-form">
  <h2>Iniciar Sesión</h2>
  <form action="login.php" method="post">
    <input type="text" name="username" placeholder="Nombre de usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Iniciar Sesión</button>
    <p class="register-text">¿No te has registrado? <a href="register.php">Regístrate Aquí</a></p>
  </form>
</div>

</body>
</html>
