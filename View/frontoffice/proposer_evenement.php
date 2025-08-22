<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controller/EvenementController.php';

// Récupérer la connexion PDO
$conn = config::getConnexion();
$model = new EvenementController($conn);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'organisateur') {
    header("Location: login.php");
    exit();
}

$organisateur_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre       = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $date_event  = $_POST['date_event'] ?? '';
    $lieu        = $_POST['lieu'] ?? '';

    $errors = [];

    // Contrôles de saisie
   // Vérifier que tous les champs sont remplis
if (empty($titre) || empty($description) || empty($date_event) || empty($lieu)) {
    $errors[] = "Tous les champs doivent être remplis.";
}

// Vérif titre : lettres uniquement, max 15
if (empty($titre) || strlen($titre) > 15 || !preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $titre)) {
    $errors[] = "Le titre doit contenir uniquement des lettres, au maximum 15 caractères et ne pas être vide.";
}

// Vérif description : lettres uniquement, max 50
if (empty($description) || strlen($description) > 50 || !preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $description)) {
    $errors[] = "La description doit contenir uniquement des lettres, au maximum 50 caractères et ne pas être vide.";
}

// Vérif date (pas dans le passé)
if (!empty($date_event) && strtotime($date_event) < strtotime(date("Y-m-d"))) {
    $errors[] = "La date de l'événement ne peut pas être dans le passé.";
}

// Vérif lieu : lettres uniquement, max 10
if (empty($lieu) || strlen($lieu) > 10 || !preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $lieu)) {
    $errors[] = "Le lieu doit contenir uniquement des lettres, au maximum 10 caractères et ne pas être vide.";
}

// Vérif image : uniquement JPG/PNG
if (!empty($_FILES['image']['name'])) {
    $allowed = ['image/jpeg', 'image/png'];
    if (!in_array($_FILES['image']['type'], $allowed)) {
        $errors[] = "Seules les images JPG et PNG sont autorisées.";
    }
}

    if (!empty($errors)) {
        foreach ($errors as $e) {
            echo "<p style='color:red;'>$e</p>";
        }
    } else {
        // Préparer les données pour le modèle
        $data = [
            'titre' => $titre,
            'description' => $description,
            'date_event' => $date_event,
            'lieu' => $lieu,
            'organisateur_id' => $organisateur_id
        ];

        $file = $_FILES['image'] ?? null;

        // Appel de la fonction add du modèle
        $model->add($data, $file);

        echo "<p style='color:green;'>Votre événement a été proposé avec succès !</p>";
    }
}
echo '<div style="margin-top:20px;"><a href="organisateur.php" 
       style="text-decoration:none;">
       <button type="button" 
               style="
                   padding: 10px 30px; 
                   border:none; 
                   border-radius:10px; 
                   background:#0ff; 
                   color:#111; 
                   font-weight:bold; 
                   cursor:pointer; 
                   box-shadow: 0 0 10px #0ff, 0 0 20px #f0f;
                   transition:0.3s;
               "
               onmouseover="this.style.background=\'#f0f\'; this.style.color=\'#fff\';" 
               onmouseout="this.style.background=\'#0ff\'; this.style.color=\'#111\';">
           Retour
       </button>
     </a></div>';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Process Request</title>
<link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Orbitron','Audiowide', sans-serif;
    background: #111;
    color: <?= $color ?? '#fff' ?>;
    text-align: center;
    padding-top: 100px;
}
a {
    color: #00ffff;
    text-decoration: none;
    font-weight: bold;
}
a:hover {
    color: #ff00ff;
}
</style>
</head>
<body>
    <h1><?= $message ?? '' ?></h1>
   
</body>
</html>
