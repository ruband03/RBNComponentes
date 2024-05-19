<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'conectaBBDD.php';

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

$userid = $_SESSION['UserID'];

$username = isset($_POST['username']) ? $_POST['username'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$name = isset($_POST['name']) ? $_POST['name'] : null;
$current_password = isset($_POST['password']) ? $_POST['password'] : null;
$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : null;
$idioma = isset($_POST['idioma']) ? $_POST['idioma'] : null;
$profile_image = null;

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $profile_image = $target_dir . basename($_FILES["profile_image"]["name"]);
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $profile_image)) {
        echo "The file ". htmlspecialchars(basename($_FILES["profile_image"]["name"])). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$stmt = $conn->prepare("SELECT Contraseña FROM Usuario WHERE UserID = ?");
$stmt->bindParam(1, $userid);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($current_password && !password_verify($current_password, $user['Contraseña'])) {
    header('Location: profile.php?error=password_mismatch');
    exit();
}

$query = "UPDATE Usuario SET ";
$params = [];
if ($username) { $query .= "Username = ?, "; $params[] = $username; }
if ($email) { $query .= "Email = ?, "; $params[] = $email; }
if ($name) { $query .= "Nombre = ?, "; $params[] = $name; }
if ($idioma) { $query .= "Idioma = ?, "; $params[] = $idioma; }
if ($new_password) {
    $query .= "Contraseña = ?, ";
    $params[] = password_hash($new_password, PASSWORD_DEFAULT);
}
if ($profile_image) {
    $query .= "ProfileImage = ?, ";
    $params[] = $profile_image;
}

$query = rtrim($query, ", ");
$query .= " WHERE UserID = ?";
$params[] = $userid;

$stmt = $conn->prepare($query);
$updated = $stmt->execute($params);

if ($updated) {
    header('Location: profile.php?success=true');
} else {
    header('Location: profile.php?error=update_failed');
}
exit();
?>
