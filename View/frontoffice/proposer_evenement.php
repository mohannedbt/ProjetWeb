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
    if (empty($titre) || strlen($titre) > 15) {
        $errors[] = "Le titre doit contenir au maximum 15 caractères.";
    }
    if (empty($description) || strlen($description) > 50) {
        $errors[] = "La description doit contenir au maximum 50 caractères.";
    }
    if (!empty($date_event) && strtotime($date_event) < strtotime(date("Y-m-d"))) {
        $errors[] = "La date de l'événement ne peut pas être dans le passé.";
    }
    if (empty($lieu) || strlen($lieu) > 10) {
        $errors[] = "Le lieu doit contenir au maximum 10 caractères.";
    }
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
