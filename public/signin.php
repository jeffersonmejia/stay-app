<?php
session_start();
include "../server/db/db.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($conn)) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        $stmt = $conn->query("SELECT id, password FROM users WHERE email='$email'");
        $stored = $stmt->fetch(PDO::FETCH_ASSOC);
        $stored_hash = $stored['password'] ?? null;

        if ($stored_hash && password_verify($password, $stored_hash)) {
            $_SESSION['user_id'] = $stored['id'];
            header("Location: home.php");
            exit;
        } else {
            $message = "Correo o contraseña incorrectos.";
        }
    } catch (Exception $e) {
        $message = "No se pudo procesar el ingreso, intenta más tarde.";
        error_log($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stay | Signin</title>
    <link rel="shortcut icon" href="assets/img/icons/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/__reset.css">
    <link rel="stylesheet" href="css/signin.css">
</head>

<body>
    <main>
        <form action="signin.php" method="POST" class="signup-form">
            <fieldset>
                <legend>
                    <h1>Ingreso de Usuario</h1>
                </legend>
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <?php if ($message) echo "<small>$message</small>"; ?>
                <div class="btn-group">
                    <a href="/" class="primary-btn">Volver</a>
                    <button type="submit" class="secondary-btn">Ingresar</button>
                </div>
            </fieldset>
        </form>
    </main>
</body>

</html>