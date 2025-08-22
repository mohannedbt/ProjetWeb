<?php
session_start();
require_once __DIR__ . '/../../config.php';

// Connexion à la BDD
$pdo = Config::getConnexion();

// Vérifier si un id est passé
if (!isset($_GET['id'])) {
    die("Événement non trouvé !");
}

$event_id = (int)$_GET['id'];

// Récupérer les détails de l'événement + organisateur
$stmt = $pdo->prepare("
    SELECT e.*, u.nom AS organisateur_nom 
    FROM evenement e 
    JOIN utilisateur u ON e.organisateur_id = u.id 
    WHERE e.id = ?
");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Événement introuvable !");
}

// Vérifier si participant connecté
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'participant') {
    die("⚠️ Vous devez être connecté en tant que participant pour réserver !");
}

$user_id = $_SESSION['user_id'];

// Gérer la réservation
$message = "";
if (isset($_POST['reserver'])) {
    // Vérifier si déjà inscrit
    $check = $pdo->prepare("SELECT * FROM participation WHERE utilisateur_id = ? AND evenement_id = ?");
    $check->execute([$user_id, $event_id]);

    if ($check->rowCount() > 0) {
        $message = "⚠️ Vous avez déjà réservé cet événement.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO participation (utilisateur_id, evenement_id, statut) VALUES (?, ?, 'non paye')");
        $stmt->execute([$user_id, $event_id]);
        $message = "✅ Votre réservation a été enregistrée !";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de l'événement</title>
    <!-- Import de Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Lobster&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: url("event.avif") no-repeat center center fixed; /* ton image */
            background-size: cover;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .event-container {
            background: rgba(15, 15, 15, 0.85);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 25px #0ff, 0 0 50px #f0f;
            text-align: left;
            width: 600px;
            animation: glowPulse 2s infinite alternate;
        }

        @keyframes glowPulse {
            from { box-shadow: 0 0 25px #0ff, 0 0 50px #f0f; }
            to   { box-shadow: 0 0 40px #ff00ff, 0 0 70px #00ffff; }
        }

        h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.2em;
            margin-bottom: 25px;
            color: #00ffff;
            text-align: center;
            text-shadow: 0 0 10px #00ffff, 0 0 20px #ff00ff;
        }

        p {
            margin: 12px 0;
            font-size: 1.15em;
            line-height: 1.5;
            color: #ddd;
        }

        strong {
            color: #ff00ff;
            font-family: 'Orbitron', sans-serif;
        }

        form {
            margin-top: 25px;
            text-align: center;
        }

        button {
            padding: 12px 25px;
            font-size: 1.1em;
            font-family: 'Orbitron', sans-serif;
            color: #fff;
            background: linear-gradient(45deg, #ff00ff, #00ffff);
            border: none;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 0 15px #ff00ff, 0 0 30px #00ffff;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        button:hover {
            transform: scale(1.1);
            box-shadow: 0 0 30px #ff00ff, 0 0 60px #00ffff;
        }

        .message {
            margin-top: 20px;
            font-size: 1.2em;
            color: #0ff;
            text-align: center;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 10px #0ff, 0 0 20px #f0f;
        }
        .content {
    font-family: 'lobster', sans-serif;
    color: white; /* cyan néon */
    font-size: 1.1em;
}

    </style>
</head>
<body>
    <div class="event-container">
        <?php
    echo "<h2>" . htmlspecialchars($event['titre']) . "</h2>";
    echo "<p><strong>Description:</strong> <span class='content'>" . htmlspecialchars($event['description']) . "</span></p>";
    echo "<p><strong>Date:</strong> <span class='content'>" . htmlspecialchars($event['date_event']) . "</span></p>";
    echo "<p><strong>Lieu:</strong> <span class='content'>" . htmlspecialchars($event['lieu']) . "</span></p>";
    echo "<p><strong>Organisateur:</strong> <span class='content'>" . htmlspecialchars($event['organisateur_nom']) . "</span></p>";
?>

        <form method="post">
   <a href="reserver.php?id=<?= $event['id']; ?>">Réserver</a>



        </form>
        <?php
            if (isset($_POST['payer'])) {
                echo "<p class='message'>Votre participation a été enregistrée !</p>";
            }
        ?>
    </div>
</body>
</html>
