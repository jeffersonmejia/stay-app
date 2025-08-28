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
            <article>
                <h2>Título</h2>
                <p>Descripción</p>
                <a href="#" class="primary-btn">Ver adjunto</a>
            </article>
            <article>
                <h2>Título</h2>
                <p>Descripción</p>
                <a href="#" class="primary-btn">Ver adjunto</a>
            </article>
            <article>
                <h2>Título</h2>
                <p>Descripción</p>
                <a href="#" class="primary-btn">Ver adjunto</a>
            </article>
            <article>
                <h2>Título</h2>
                <p>Descripción</p>
                <a href="#" class="primary-btn">Ver adjunto</a>
            </article>
        </section>
    </main>
    <div class="modal-create hidden">
        <form action="../controller/create_note.php" method="POST">
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