<?php
require_once "../../../config.php";
$config = new config();
$pdo = $config->getConnexion();

// Fetch event by id
if (!isset($_GET['id'])) {
    die("Event ID is missing.");
}
$eventId = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM evenement WHERE id = :id");
$stmt->execute(['id' => $eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Event not found.");
}

// Handle update form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $date_event = $_POST['date_event'];
    $lieu = $_POST['lieu'];
    $organisateur_id = $_POST['organisateur_id'];

    // Image handling
    $imageName = $event['image']; // keep old by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . "/../../../organizator_images/$organisateur_id/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Delete old image if exists
        if ($event['image'] && file_exists($uploadDir . $event['image'])) {
            unlink($uploadDir . $event['image']);
        }

        $imageName = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
    }

    $update = $pdo->prepare("
        UPDATE evenement 
        SET titre = :titre, description = :description, date_event = :date_event, lieu = :lieu, organisateur_id = :organisateur_id, image = :image 
        WHERE id = :id
    ");
    $update->execute([
        'titre' => $titre,
        'description' => $description,
        'date_event' => $date_event,
        'lieu' => $lieu,
        'organisateur_id' => $organisateur_id,
        'image' => $imageName,
        'id' => $eventId
    ]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Modify Event</title>
<link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
body {
    margin: 0;
    font-family: 'Orbitron', 'Audiowide', sans-serif;
    background: url('https://media.istockphoto.com/id/530437857/photo/beautiful-european-night-club-interior.jpg?s=612x612&w=0&k=20&c=2Xpq2Z1o9rOLsQ9-B6rHPI7FAtH3LSCMqKHKUDs8zUg=') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    text-align: center;
}
.container {
    max-width: 600px;
    margin: 60px auto;
    background: rgba(0,0,0,0.85);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 0 15px #00ffff, 0 0 25px #ff00ff;
}
/* Hide default file input */
.file-input {
    display: none;
}

/* Styled label acting as button */
.file-label {
    display: inline-block;
    padding: 10px 25px;
    margin-top: 10px;
    border-radius: 15px;
    background: linear-gradient(45deg, #ff00ff, #ff69b4);
    color: #fff;
    font-family: 'Orbitron', 'Audiowide', sans-serif;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 0 12px #ff00ff, 0 0 25px #ff69b4;
    transition: all 0.3s ease;
    font-size: 1em;
    text-align: center;
}

.file-label:hover {
    filter: brightness(1.3);
    transform: translateY(-2px);
}

h1 {
    font-size: 3em;
    color: #ff00ff;
    text-shadow: 0 0 6px #ff00ff, 0 0 12px #ff00ff;
    margin-bottom: 25px;
}
label {
    display: block;
    font-weight: bold;
    margin: 12px 0 5px;
    text-align: left;
    color: #00ffff;
}
input[type="text"], input[type="date"], textarea {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: none;
    font-size: 1em;
    margin-bottom: 15px;
    background: #111;
    color: #fff;
    box-shadow: 0 0 8px #00ffff;
}
input[type="file"] {
    margin: 10px 0;
    color: #fff;
}
.preview {
    margin: 15px 0;
}
.preview img {
    max-width: 250px;
    border-radius: 15px;
    border: 2px solid #ff00ff;
    box-shadow: 0 0 10px #ff00ff, 0 0 15px #00ffff;
}
.btn {
    display: inline-block;
    padding: 12px 25px;
    margin: 12px 8px;
    border-radius: 15px;
    text-decoration: none;
    font-weight: bold;
    font-size: 1.2em;
    font-family: 'Orbitron', 'Audiowide', sans-serif;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}
.btn-save {
    background: linear-gradient(45deg, #00ffcc, #00ffff);
    box-shadow: 0 0 12px #00ffff, 0 0 20px #00ffcc;
    color: #000;
}
.btn-save:hover {
    filter: brightness(1.3);
}
.btn-cancel {
    background: linear-gradient(45deg, #ff0000, #ff4c4c);
    box-shadow: 0 0 12px #ff0000, 0 0 20px #ff4c4c;
    color: #fff;
}
.btn-cancel:hover {
    filter: brightness(1.3);
}
</style>
</head>
<body>

<div class="container">
    <h1>Modify Event</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="titre" value="<?= htmlspecialchars($event['titre']) ?>" required>

        <label>Description:</label>
        <textarea name="description" rows="4" required><?= htmlspecialchars($event['description']) ?></textarea>

        <label>Date:</label>
        <input type="date" name="date_event" value="<?= htmlspecialchars($event['date_event']) ?>" required>

        <label>Location:</label>
        <input type="text" name="lieu" value="<?= htmlspecialchars($event['lieu']) ?>" required>

        <label>Organizer ID:</label>
        <input type="text" name="organisateur_id" value="<?= htmlspecialchars($event['organisateur_id']) ?>" required>

     <label for="image" class="file-label">Choisir un fichier</label>
<input type="file" name="image" id="image" class="file-input">
        <div class="preview">
            <?php if ($event['image']): ?>
                <img src="../../../organizator_images/<?= $event['organisateur_id'] ?>/<?= $event['image'] ?>" alt="Event Image">
            <?php else: ?>
                <p>No image uploaded.</p>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-save">💾 Save</button>
        <a href="index.php" class="btn btn-cancel">✖ Cancel</a>
    </form>
</div>

</body>
</html>
