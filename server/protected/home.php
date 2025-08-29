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
            $ftp_server = "ftp";
            $ftp_user   = "user";
            $ftp_pass   = "pass";

            $conn_id = ftp_connect($ftp_server);
            if (!$conn_id) {
                $ftp_error = "No se pudo conectar al servidor FTP";
            } elseif (!ftp_login($conn_id, $ftp_user, $ftp_pass)) {
                $ftp_error = "Error de autenticación FTP";
                ftp_close($conn_id);
            } else {
                ftp_pasv($conn_id, true);

                $extension = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
                $tmp_file  = $_FILES['attachment']['tmp_name'];

                $remote_dir = "/$user_id";
                // Crear directorio del usuario si no existe
                if (!@ftp_chdir($conn_id, $remote_dir)) {
                    if (!ftp_mkdir($conn_id, $remote_dir)) {
                        $ftp_error = "No se pudo crear el directorio en FTP";
                    } else {
                        ftp_chdir($conn_id, $remote_dir);
                    }
                } else {
                    ftp_chdir($conn_id, $remote_dir);
                }

                $remote_file = "{$note_id}.$extension";
                if (!ftp_put($conn_id, $remote_file, $tmp_file, FTP_BINARY)) {
                    $ftp_error = "Error al subir el archivo al FTP";
                }

                ftp_close($conn_id);
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