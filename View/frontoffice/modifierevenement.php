<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controller/EvenementController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'organisateur') {
    header("Location: login.php");
    exit();
}

$conn = config::getConnexion();
$model = new EvenementController($conn);

$organisateur_id = $_SESSION['user_id'];

// Récupérer l'événement à modifier
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Événement introuvable.";
    exit();
}

$stmt = $conn->prepare("SELECT * FROM evenement WHERE id=? AND organisateur_id=?");
$stmt->execute([$id, $organisateur_id]);
$event = $stmt->fetch();

if (!$event) {
    echo "Aucun événement trouvé.";
    exit();
}

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = [
        'titre' => $_POST['titre'],
        'description' => $_POST['description'],
        'date_event' => $_POST['date_event'],
        'lieu' => $_POST['lieu'],
        'organisateur_id' => $organisateur_id,
        'current_image' => $event['image']
    ];

    $file = $_FILES['image'] ?? null;

    $model->update($id, $data, $file);

    echo "<p style='color:green;'>Événement modifié avec succès !</p>";
    echo "<a href='organisateur.php'>⬅ Retour à mes événements</a>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Modifier l'événement</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
    body {
       background: url("zebra.jpg") no-repeat center center fixed;
  background-size: 100% 100%;
        font-family: 'Orbitron', sans-serif;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }
    .container {
        background: rgba(0,0,0,0.85);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 20px #0ff, 0 0 40px #f0f;
        width: 400px;
    }
    h2 {
        text-align: center;
        color: #0ff;
        text-shadow: 0 0 5px #0ff, 0 0 10px #0ff;
    }
    label {
        display: block;
        margin-top: 15px;
        margin-bottom: 5px;
        font-weight: bold;
        color: #0ff;
        text-shadow: 0 0 3px #0ff;
    }
    input[type="text"],
    input[type="date"],
    textarea {
        width: 100%;
        padding: 8px;
        border: 2px solid #0ff;
        border-radius: 8px;
        background: #111;
        color: #fff;
        font-family: 'Orbitron', sans-serif;
        outline: none;
        transition: 0.3s;
    }
    input[type="text"]:focus,
    input[type="date"]:focus,
    textarea:focus {
        border-color: #f0f;
        box-shadow: 0 0 5px #f0f, 0 0 10px #0ff;
    }
    textarea {
        resize: none;
        height: 80px;
    }
    button[type="submit"],
    button[type="button"] {
        margin-top: 20px;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        background: #0ff;
        color: #111;
        font-weight: bold;
        cursor: pointer;
        width: 48%;
        box-shadow: 0 0 10px #0ff, 0 0 20px #0ff;
        transition: 0.3s;
    }
    button[type="submit"]:hover,
    button[type="button"]:hover {
        background: #111;
        color: #0ff;
        box-shadow: 0 0 15px #f0f, 0 0 30px #0ff;
    }
    .buttons {
        display: flex;
        justify-content: space-between;
    }
    img {
        margin-top: 10px;
        border: 2px solid #0ff;
        border-radius: 8px;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Modifier l'événement</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Titre :</label>
        <input type="text" name="titre" value="<?= htmlspecialchars($event['titre']); ?>" required>

        <label>Description :</label>
        <textarea name="description" required><?= htmlspecialchars($event['description']); ?></textarea>

        <label>Date :</label>
        <input type="date" name="date_event" value="<?= htmlspecialchars($event['date_event']); ?>" required>

        <label>Lieu :</label>
        <input type="text" name="lieu" value="<?= htmlspecialchars($event['lieu']); ?>" required>

        <label>Image actuelle :</label>
        <?php if (!empty($event['image'])): ?>
            <img src="../organizator_images/<?= $organisateur_id ?>/<?= htmlspecialchars($event['image']); ?>" width="100">
        <?php endif; ?>

        <label>Nouvelle image (optionnel) :</label>
        <input type="file" name="image">

        <div class="buttons">
            <button type="submit">Enregistrer</button>
            <a href="organisateur.php"><button type="button" style="width:120px">Annuler</button></a>
        </div>
    </form>
</div>
</body>
</html