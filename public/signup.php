<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stay | Signup</title>
    <link rel="shortcut icon" href="assets/img/icons/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/__reset.css">
    <link rel="stylesheet" href="css/signup.css">
</head>

<body>
    <main>
        <?php
        //AGREGA CONEXIÓN DB
        include "../server/db/db.php";
        $message = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($conn)) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            try {
                $check = $conn->query("SELECT COUNT(*) FROM users WHERE email='$email'")->fetchColumn();
                if ($check > 0) {
                    $message = "El usuario ya existe con este correo.";
                } else {
                    $conn->exec("INSERT INTO users (email, password) VALUES ('$email', '$hashed_password')");
                    $message = "Usuario registrado exitosamente.";
                }
            } catch (Exception $e) {
                $message = "No se pudo registrar al usuario, intenta más tarde.";
                error_log($e->getMessage());
            }
        }
        ?>
        <form action="signup.php" method="POST" class="signup-form">
            <fieldset>
                <legend>
                    <h1>
                        Registro de Usuario
                    </h1>
                </legend>
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <?php if ($message) echo "<small>$message</small>"; ?>
                <div class="btn-group">
                    <a href="/" class="primary-btn">Volver</a>
                    <button type="submit" class="secondary-btn">Registrarse</button>
                </div>
            </fieldset>
        </form>
    </main>
</body>

</html>