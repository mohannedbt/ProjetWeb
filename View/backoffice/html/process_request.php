<?php
require_once "../../../config.php";
$config = new config();
$pdo = $config->getConnexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'accept') {
        // Insert event into 'evenement' table
        $stmt = $pdo->prepare("INSERT INTO evenement (titre, description, date_event, lieu, organisateur_id, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['titre'],
            $_POST['description'],
            $_POST['date_event'],
            $_POST['lieu'],
            $_POST['organisateur_id'],
            $_POST['image']
        ]);
        $message = "✅ Event added successfully!";
        $color = "#00ff00";
    }

    if ($action === 'refuse') {
        $message = "❌ Event request refused!";
        $color = "#ff0000";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Process Request</title>
<link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
body { font-family: 'Orbitron','Audiowide', sans-serif; background: #111; color: <?= $color ?>; text-align: center; padding-top: 100px; }
a { color: #00ffff; text-decoration: none; font-weight: bold; }
a:hover { color: #ff00ff; }
</style>
</head>
<body>
    <h1><?= $message ?? '' ?></h1>
    <p><a href="event.php">Back to Inbox</a></p>
</body>
</html>
