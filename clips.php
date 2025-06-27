<?php
$uploadDir = __DIR__ . '/upload/';
$maxFileSize = 50 * 1024 * 1024; // 50MB

$errors = [];
$success = '';

// Gestione upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            if ($file['size'] <= $maxFileSize) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/webm', 'video/ogg'];
                if (in_array($file['type'], $allowedTypes)) {
                    $fileName = basename($file['name']);
                    $targetPath = $uploadDir . $fileName;
                    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                        $success = "File caricato con successo!";
                    } else {
                        $errors[] = "Errore nel salvataggio del file.";
                    }
                } else {
                    $errors[] = "Formato file non consentito.";
                }
            } else {
                $errors[] = "File troppo grande. Max 50MB.";
            }
        } else {
            $errors[] = "Errore nel caricamento file.";
        }
    } else {
        $errors[] = "Nessun file selezionato.";
    }
}

// Prendi lista file caricati
$files = array_diff(scandir($uploadDir), ['.', '..']);

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carica e Visualizza Clip</title>
    <link rel="stylesheet" href="css/stile_home.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="js/menu.js" defer></script>
</head>
<body>

<div class="menu-container">
    <button class="menu-button" onclick="toggleMenu()">☰ Menu</button>
    <div id="menu" class="menu">
        <ul>
            <li><a href="home">Home</a></li>
            <li><a href="clips">Clips</a></li>
            <li><a href="gioco">Gioco</a></li>
            <li><a href="nosense">NoSense</a></li>
            <li><a href="chat">Chat</a></li>
        </ul>
    </div>
</div>

<header class="hero">
    <div class="hero-content">
        <h1>Carica e Visualizza Clip e Video</h1>
        <p>Carica le tue foto e video, e guarda tutte le clip sotto.</p>
    </div>
</header>

<main class="features" style="flex-direction: column; align-items: center;">

    <!-- Messaggi -->
    <?php if ($success): ?>
        <p style="color: #0f0; margin-bottom: 20px;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <?php if ($errors): ?>
        <ul style="color: #f44; margin-bottom: 20px;">
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Form Upload -->
    <section class="card" style="width: 400px; margin-bottom: 40px;">
        <form method="post" enctype="multipart/form-data">
            <label for="file" style="font-weight: bold;">Seleziona immagine o video (max 50MB):</label><br><br>
            <input type="file" name="file" id="file" accept="image/*,video/*" required /><br><br>
            <button type="submit" class="btn">Carica</button>
        </form>
    </section>

    <!-- Visualizza clip -->
    <section style="width: 90%; max-width: 900px;">
        <h2 style="color: white; margin-bottom: 20px;">Clip Caricate</h2>
        <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
            <?php foreach ($files as $file): 
                $filePath = 'upload/' . $file;
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg','jpeg','png','gif','webp'])): ?>
                    <img src="<?= htmlspecialchars($filePath) ?>" alt="immagine" style="max-width: 200px; border-radius: 15px; box-shadow: 0 0 10px #000;" />
                <?php elseif (in_array($ext, ['mp4','webm','ogg'])): ?>
                    <video controls style="max-width: 200px; border-radius: 15px; box-shadow: 0 0 10px #000;">
                        <source src="<?= htmlspecialchars($filePath) ?>" type="video/<?= $ext ?>" />
                        Il tuo browser non supporta il video.
                    </video>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<footer>
    <p>&copy; 2025 GheshinMerDact. Tutti i diritti riservati.</p>
</footer>

</body>
</html>