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
    $email = $_POST['email'];
    $password = $_POST['password']; 
    $confirm_password = $_POST['confirm_password']; 
    $nombre = $_POST['nombre']; 
    $idioma = $_POST['idioma'];
    $profile_image = null;

    $errors = []; 

    if (!preg_match('/[A-Z]/', $username) || !preg_match('/[0-9]/', $username)) {
        $errors[] = "El nombre de usuario debe contener al menos un número y una letra mayúscula.";
    }

    if (!preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[^a-zA-Z0-9]/', $password) ||
        strlen($password) < 6) {
        $errors[] = "La contraseña debe tener al menos 6 caracteres, incluyendo una mayúscula, un número y un caracter especial.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors; 
        header("Location: register.php"); 
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $profile_image = $target_dir . basename($_FILES["profile_image"]["name"]);
        if (!move_uploaded_file($_FILES["profile_image"]["tmp_name"], $profile_image)) {
            $errors[] = "Error al subir la imagen.";
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO Usuario (Username, Email, Contraseña, Nombre, Idioma, ProfileImage) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $email, $hashed_password, $nombre, $idioma, $profile_image);
        if ($stmt->execute()) {
            echo "Usuario creado con éxito.";
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['errors'] = $errors;
        header("Location: register.php");
        exit;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registro</title>
<link rel="stylesheet" href="css/register.css">
<link rel="shortcut icon" href="../logos/RBNComponentes.ico" type="image/x-icon">
</head>
<body>

<div class="register-form">
  <h2>Registro</h2>
  <?php
  if (!empty($_SESSION['errors'])) {
      echo '<div style="color: red;">';
      foreach ($_SESSION['errors'] as $error) {
          echo "<p>$error</p>";
      }
      echo '</div>';
      unset($_SESSION['errors']); 
  }
  ?>
  <form action="register.php" method="post" enctype="multipart/form-data">
    <input type="text" name="username" placeholder="Nombre de usuario" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <input type="password" name="confirm_password" placeholder="Repite tu contraseña" required>
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <select name="idioma" required>
      <option value="" disabled selected>Idioma</option>
      <option value="es">Español</option>
      <option value="en">Inglés</option>
    </select>
    <label for="profile_image">Subir imagen de perfil:</label>
    <input type="file" id="profile_image" name="profile_image">
    <button type="submit">Registrarse</button>
    <p class="register-text">¿Ya estás registrado? <a href="login.php">Inicia Sesión</a></p>
  </form>
</div>

</body>
</html>
