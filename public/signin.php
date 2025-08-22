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
        <form action="public/signin.php" method="POST" class="signup-form">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <div class="btn-group">
                <button type="submit" class="primary-btn">Ingresar</button>
                <a href="/" class="secondary-btn">Volver</a>
            </div>
        </form>
    </main>
</body>

</html>