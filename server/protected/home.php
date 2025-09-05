<?php

if (isset($_POST['delete_note_id'])) {
    $note_id = $_POST['delete_note_id'];
    $user_id = $_SESSION['user_id'];

    $userDir = __DIR__ . "/ftp/$user_id";
    $files = glob("$userDir/{$note_id}.*");
    foreach ($files as $file) {
        unlink($file);
    }

    $stmt = $conn->prepare("DELETE FROM notes WHERE id = :id AND user_id = :user_id");
    $stmt->execute([':id' => $note_id, ':user_id' => $user_id]);

    header("Location: home.php");
    exit;
}
$ftp_error = "";
$log_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $user_id = $_SESSION['user_id'];

    if ($title && $description) {
        $stmt = $conn->prepare("INSERT INTO notes (title, description, user_id) VALUES (:title, :description, :user_id)");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':user_id' => $user_id
        ]);
        $note_id = $conn->lastInsertId();

        if (!empty($_FILES['attachment']['name'])) {

            $sftp_server = "sftp"; // nombre del servicio SFTP en docker-compose
            $sftp_user   = "user";
            $sftp_pass   = "pass";

            $extension = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
            $tmp_file  = $_FILES['attachment']['tmp_name'];

            // Conexión SSH y SFTP
            $connection = ssh2_connect($sftp_server, 22);
            if (!$connection) {
                $ftp_error = "No se pudo conectar al servidor SFTP";
            } elseif (!ssh2_auth_password($connection, $sftp_user, $sftp_pass)) {
                $ftp_error = "Error de autenticación SFTP";
            } else {
                $sftp = ssh2_sftp($connection);

                // Carpeta del usuario
                $remote_user_dir = "/home/user/upload/$user_id";
                if (!file_exists("ssh2.sftp://$sftp$remote_user_dir")) {
                    mkdir("ssh2.sftp://$sftp$remote_user_dir", 0777, true);
                }

                // Carpeta de la nota
                $remote_note_dir = "$remote_user_dir/$note_id";
                if (!file_exists("ssh2.sftp://$sftp$remote_note_dir")) {
                    mkdir("ssh2.sftp://$sftp$remote_note_dir", 0777, true);
                }

                // Subir archivo
                $remote_file = "$remote_note_dir/{$note_id}.$extension";
                if (!file_put_contents("ssh2.sftp://$sftp$remote_file", file_get_contents($tmp_file))) {
                    $ftp_error = "Error al subir el archivo al SFTP";
                }
            }

            if (!empty($ftp_error)) {
                $log_msg = "[LOG SFTP] Error detectado:\n";
                $log_msg .= "Usuario: $sftp_user\n";
                $log_msg .= "Directorio remoto: $remote_note_dir\n";
                $log_msg .= "Archivo: {$note_id}.$extension\n";
                $log_msg .= "Mensaje: $ftp_error\n";
                error_log($log_msg);
            }
        }


        header("Location: home.php");
        exit;
    }
}



$notes = [];
$stmt = $conn->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY id DESC");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="shortcut icon" href="assets/img/icons/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/__reset.css">
    <link rel="stylesheet" href="home.php?css=home.css">
</head>

<body>
    <main>
        <div class="title-container">
            <h1>Bienvenido</h1>
            <div class="group-btn">
                <button class="tertiary-btn create-btn">Crear nota</button>
                <a href="logout.php" class="secondary-btn">Salir</a>
            </div>
        </div>
        <section>
            <?php foreach ($notes as $note): ?>
                <article>
                    <h2><?= htmlspecialchars($note['title']) ?></h2>
                    <p><?= nl2br(htmlspecialchars($note['description'])) ?></p>
                    <?php
                    $userDir = __DIR__ . "/ftp/{$note['user_id']}";
                    $files = glob("$userDir/{$note['id']}.*");
                    if (!empty($files)) {
                        $filePath = $files[0];
                        $fileName = basename($filePath);
                        $fileUrl = "ftp/{$note['user_id']}/$fileName";
                    }
                    ?>
                    <div class="footer-note">
                        <?php if (!empty($files)): ?>
                            <a href="<?= htmlspecialchars($fileUrl) ?>" class="primary-btn" download>Ver adjunto</a>
                        <?php else: ?>
                            <span class="primary-btn disabled">Sin adjunto</span>
                        <?php endif; ?>
                        <form action="home.php" method="POST"
                            style="display:inline">
                            <input type="hidden" name="delete_note_id" value="<?= $note['id'] ?>">
                            <button type="submit" class="cancel-btn">Eliminar</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
    <div class="modal-create hidden">
        <form action="home.php" method="POST" enctype="multipart/form-data">
            <h2>Crear nota</h2>
            <input type="text" name="title" placeholder="Título" required>
            <textarea name="description" placeholder="Descripción" required rows="6"></textarea>
            <div class="attach-file-container">
                <label for="">Adjuntar archivo (Máx. 5Mb)</label>
                <input type="file" name="attachment" id="attach-btn">
            </div>
            <?php
            echo "$ftp_error";
            ?>
            <div class="group-btn">
                <button class="cancel-btn">Cancelar</button>
                <button type="submit" class="secondary-btn">Crear nota</button>
            </div>
        </form>
    </div>
    <footer>
        <a href="https://jeffersonmejia.github.io/portfolio-app/" target="blank">
            Jefferson Mejía @jeffersonmejiach
        </a>
    </footer>
    <script src="home.php?js=home.js"></script>
</body>

</html>