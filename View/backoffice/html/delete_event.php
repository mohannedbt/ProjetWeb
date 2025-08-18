<?php
// Start output buffering
ob_start();

// Check if ID is provided
if (!isset($_GET['id'])) {
    die('No event specified.');
}

require_once "../../../Controller/EvenementController.php";
require_once "../../../config.php";

$config = new config();
$pdo = $config->getConnexion();
$eventController = new EvenementController($pdo);

// Attempt to delete the event
$id = intval($_GET['id']); // sanitize input
$deleted = $eventController->delete(id: $id);

// Clear output buffer
ob_end_clean();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Deleted Successfully</title>
<link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
body {
    margin: 0;
    font-family: 'Orbitron', 'Audiowide', sans-serif;
    background: url('https://media.istockphoto.com/id/115969439/photo/bar-restaurant-and-disco.jpg?s=612x612&w=0&k=20&c=2Qrtdcw4eqUNMfNfjJRUFjElLFnaHVD0n20Xj9fBA5I=') no-repeat center center fixed;
    background-size: cover;
    color: #ff4c4c;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    text-align: center;
}

.container {
    background: rgba(0,0,0,0.75);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 0 20px #ff0000, 0 0 40px #ff4c4c;
}

h1 {
    font-size: 3.5em;
    color: #ff0000;
    text-shadow: 0 0 8px #ff0000, 0 0 20px #ff4c4c;
    margin-bottom: 20px;
}

p {
    font-size: 1.3em;
    color: #fff;
}

/* Countdown text */
#countdown {
    font-size: 1.2em;
    color: #00ffff;
    margin-top: 10px;
}
</style>
<script>
// Delay redirect with countdown
let countdown = 5; // seconds
function updateCountdown() {
    document.getElementById('countdown').innerText = "Redirecting in " + countdown + " seconds...";
    if(countdown === 0) {
        window.location.href = "event.php"; // change to your events page
    } else {
        countdown--;
        setTimeout(updateCountdown, 1000);
    }
}
window.onload = updateCountdown;
</script>
</head>
<body>
<div class="container">
    <?php if($deleted=="done"): ?>
        <?php error_log($deleted); ?>
        <h1>Deleted Successfully!</h1>
        <p>The event has been removed from the system.</p>
        <div id="countdown"></div>
    <?php else: ?>
        <h1>Error!</h1>
        <p>Unable to delete the event.</p>
    <?php endif; ?>
</div>
</body>
</html>
