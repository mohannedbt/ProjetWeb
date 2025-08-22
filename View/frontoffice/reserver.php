<?php
session_start();
require_once __DIR__ . "/../../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connexion PDO
$conn = config::getConnexion();

$utilisateur_id = $_SESSION['user_id'];
$evenement_id = $_GET['id'] ?? null;

if (!$evenement_id) {
    die("<p style='color:red;'>Événement invalide.</p>");
}

// Vérifier si l'utilisateur a déjà réservé
$check = $conn->prepare("SELECT * FROM participation WHERE utilisateur_id=? AND evenement_id=?");
$check->execute([$utilisateur_id, $evenement_id]);

if ($check->rowCount() == 0) {
    // Ajouter la réservation
    $insert = $conn->prepare("INSERT INTO participation (utilisateur_id, evenement_id, statut) VALUES (?, ?, ?)");
    $insert->execute([$utilisateur_id, $evenement_id, 'non paye']);
    $message="<p style='color:green;'>Réservation effectuée avec succès !</p>";
} else {
    $message= "<p style='color:red;'>Vous avez déjà réservé cet événement.</p>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Orbitron', sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .message-box {
            background: rgba(0, 0, 0, 0.7);
            border: 2px solid #0ff;
            box-shadow: 0 0 20px #0ff, 0 0 40px #0ff inset;
            padding: 40px 60px;
            border-radius: 15px;
            text-align: center;
            font-size: 24px;
            color: <?= $color ?>;
            text-shadow: 0 0 5px <?= $color ?>, 0 0 10px <?= $color ?>, 0 0 20px <?= $color ?>;
            animation: glow 1.5s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { text-shadow: 0 0 5px <?= $color ?>, 0 0 10px <?= $color ?>, 0 0 20px <?= $color ?>; }
            to { text-shadow: 0 0 10px <?= $color ?>, 0 0 20px <?= $color ?>, 0 0 30px <?= $color ?>; }
        }

        .btn-back {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            color: #111;
            background-color: #0ff;
            box-shadow: 0 0 10px #0ff, 0 0 20px #0ff;
            transition: 0.3s;
        }

        .btn-back:hover {
            background-color: #111;
            color: #0ff;
            box-shadow: 0 0 20px #0ff, 0 0 40px #0ff;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <?php echo $message?>
        <br>
        <a href="index.php" class="btn-back">⬅ Retour aux événements</a>
        <a href="export_pdf.php" class="btn-back" style="margin-left: 20px;">⬅ Exporter en PDF</a>

    </div>
</body>
</html>