<?php
require_once __DIR__ . '/utils/notes.php';

if (isset($_POST['delete_note_id'])) {
    delete_note($conn, (int)$_POST['delete_note_id'], (int)$_SESSION['user_id']);
    header("Location: home.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $user_id = (int)$_SESSION['user_id'];

    if ($title && $description) {
        create_note($conn, $title, $description, $user_id, $_FILES['attachment']);
        header("Location: home.php");
        exit;
    }
}

$notes = get_notes($conn, (int)$_SESSION['user_id']);
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
                    $sftp_server = "stay-app-sftp-1";
                    $sftp_user   = "user";
                    $sftp_pass   = "pass";

                    [$sftp, $error] = sftp_connect_server($sftp_server, $sftp_user, $sftp_pass);

                    $remote_user_dir = "/upload/{$note['user_id']}";
                    $files = [];

                    if ($sftp) {
                        $sftp_fd = intval($sftp);
                        $handle = @opendir("ssh2.sftp://$sftp_fd$remote_user_dir");
                        if ($handle) {
                            while (($entry = readdir($handle)) !== false) {
                                if (preg_match("/^{$note['id']}\./", $entry)) {
                                    $files[] = $entry;
                                }
                            }
                            closedir($handle);
                        }
                    }

                    if (!empty($files)) {
                        $fileName = $files[0];
                        $fileUrl  = "utils/download.php?user_id={$note['user_id']}&file={$fileName}";
                    }
                    ?>
                    <div class="footer-note">
                        <?php if (!empty($files)): ?>
                            <a href="utils/download.php?user_id=<?= $note['user_id'] ?>&file=<?= urlencode($fileName) ?>" class="primary-btn" download>Descargar adjunto</a>

                        <?php else: ?>
                            <span class="primary-btn disabled">Sin adjunto</span>
                        <?php endif; ?>
                        <form action="home.php" method="POST" style="display:inline">
                            <input type="hidden" name="delete_note_id" value="<?= $note['id'] ?>">
                            <button type="submit" class="cancel-btn">Delete</button>
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