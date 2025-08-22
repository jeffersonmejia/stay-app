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
        <?php include "../server/db.php";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            //POSIBLE INYECCIÓN MYSQL, REVISAR
            $query = "INSERT INTO users (email, password) VALUES ('$email', '$hashed_password')";
            if (mysqli_query($conn, $query)) {
                echo "Usuario registrado exitosamente.";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
        ?>
            <form action="signup.php" method="POST" class="signup-form">
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <div class="btn-group">
                    <button type="submit" class="primary-btn">Registrarse</button>
                    <a href="/" class="secondary-btn">Volver</a>
                </div>
            </form>
        <?php
        }
        ?>
    </main>
</body>

</html>