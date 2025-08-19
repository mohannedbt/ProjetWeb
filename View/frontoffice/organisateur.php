<?php
session_start();
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'organisateur') {
    header("Location: login.php");
    exit();
}

$organisateur_id = $_SESSION['user_id'];

// Récupérer la connexion PDO
$conn = config::getConnexion();

// Récupérer les événements de cet organisateur
$sql = "SELECT * FROM evenement WHERE organisateur_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$organisateur_id]);
$events = $stmt->fetchAll();

?>


<h2>Mes événements</h2>
<ul style="
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 0;
    list-style: none;
">
<?php foreach($events as $event): ?>

    
    <li style="
        width: calc(20% - 20px); /* 5 cartes par ligne */
        min-width: 220px;
        background: #111;
        color: #fff;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 0 15px #0ff, 0 0 30px #0ff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: left;
    " 
    onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 25px #0ff, 0 0 50px #0ff';" 
    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 0 15px #0ff, 0 0 30px #0ff';">

        <!-- Infos de l'événement -->
        <div>
            <strong><?= $event['titre']; ?></strong><br>
            📅 <?= $event['date_event']; ?><br>
            📍 <?= $event['lieu']; ?>
        </div>

        <!-- Bouton -->
        <a href="modifierevenement.php?id=<?= $event['id']; ?>">
            <button style="
                margin-top: 10px;
                padding: 8px 12px;
                border: none;
                border-radius: 8px;
                background: #0ff;
                color: #111;
                font-weight: bold;
                cursor: pointer;
                box-shadow: 0 0 10px #0ff, 0 0 20px #0ff;
                transition: background 0.3s ease, color 0.3s ease;
                width: 100%;
            " 
            onmouseover="this.style.background='#111'; this.style.color='#0ff';" 
            onmouseout="this.style.background='#0ff'; this.style.color='#111';">
                Modifier
            </button>
        </a>
    </li>
    








<?php endforeach; ?>
</ul>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes événements</title>
   <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Lobster&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

  <style>
    /* Arrière-plan animé */
    body {
  margin: 0;
  padding: 0;
  font-family: 'Roboto', Arial, sans-serif; /* police par défaut */
  background: url("event.avif") no-repeat center center fixed;
  background-size: 100% 100%;
  animation: gradientBG 15s ease infinite;
  color: #fff;
  text-align: center;
}

h2 {
  font-size: 2em;
  margin: 20px 0;
  font-family: 'Orbitron', sans-serif; /* style futuriste pour les titres */
  color: #fff;
  text-shadow: 0 0 5px #0ff, 0 0 10px #0ff, 0 0 20px #0ff;
}

li {
  font-family: 'Roboto', sans-serif; /* texte normal */
}

form label {
  font-family: 'Roboto', sans-serif;
}

button {
  font-family: 'Orbitron', sans-serif; /* effet futuriste pour le bouton */
}


    li:hover {
      transform: scale(1.05);
      box-shadow: 0 0 20px #f0f, 0 0 40px #0ff inset;
    }

    /* Formulaire */
    form {
      margin: 30px auto;
      padding: 20px;
      width: 50%;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 20px;
      box-shadow: 0 0 15px #0ff, 0 0 25px #f0f;
      text-align: left;
    }

    form label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: none;
      border-radius: 10px;
      background: #111;
      color: #0ff;
      font-size: 1em;
      box-shadow: 0 0 5px #0ff inset;
    }

    input:focus, textarea:focus {
      outline: none;
      box-shadow: 0 0 10px #0ff inset;
    }

    button {
      margin-top: 40px;
      padding: 15px 50px;
      border: none;
      border-radius: 30px;
      background: #0ff;
      color: #111;
      font-size: 1em;
      font-weight: bold;
      cursor: pointer;
      box-shadow: 0 0 10px #0ff, 0 0 20px #0ff;
      transition: 0.3s ease;
    }

    button:hover {
      background: #f0f;
      color: #fff;
      box-shadow: 0 0 20px #f0f, 0 0 40px #0ff;
      transform: scale(1.1);
    }
  </style>
</head>
<body>

  <h2>Proposer un événement</h2>
  <form method="post" action="proposer_evenement.php" enctype="multipart/form-data">
    <label for="titre">Titre:</label>
    <input type="text" name="titre" id="titre" required>

    <label for="description">Description:</label>
    <textarea name="description" id="description"></textarea>

    <label for="date_event">Date:</label>
    <input type="date" name="date_event" id="date_event" required>

    <label for="lieu">Lieu:</label>
    <input type="text" name="lieu" id="lieu">

    <label for="image">Image:</label>
    <input type="file" name="image" id="image">

    <div style="text-align:center;">
  <button type="submit">Proposer</button>
</div>
  </form>

</body>
</html>
