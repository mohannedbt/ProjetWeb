<?php
require_once "../../../config.php";
require_once "../../../Controller/EvenementController.php";

$config = new config();
$pdo = $config->getConnexion();
$eventController = new EvenementController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $eventId = $_POST['id']; // Event ID

    // Get uploaded file if any
    $file = $_FILES['image'] ?? null;

    if ($action === 'accept') {
        // Update event status to "approuve" (approved)
        $eventController->updateStatus($eventId, 'approuve');

        $message = "✅ Event approved successfully!";
        $color = "#00ff00";
    }

    if ($action === 'refuse') {
        // Update event status to "refuse"
        $eventController->updateStatus($eventId, 'refuse');

        $message = "❌ Event refused!";
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
    <p><a href="demand.php">Back to Inbox</a></p>
</body>
</html>
