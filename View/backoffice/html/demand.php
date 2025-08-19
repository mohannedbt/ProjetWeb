<?php
require_once "../../../config.php";
require_once "../../../Controller/EvenementController.php";
require_once "../../../Controller/UserController.php";

$config = new config();
$pdo = $config->getConnexion();
$eventController = new EvenementController($pdo);
$userController = new UserController($pdo);

// Fetch events that are pending
$requests = $eventController->getByStatus('en_attente');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Organizer Inbox</title>
<link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
body {
    margin: 0;
    font-family: 'Orbitron','Audiowide', sans-serif;
    background: url('https://media.istockphoto.com/id/530437857/photo/beautiful-european-night-club-interior.jpg?s=612x612&w=0&k=20&c=2Xpq2Z1o9rOLsQ9-B6rHPI7FAtH3LSCMqKHKUDs8zUg=') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    text-align: center;
}
h1 { margin-top: 40px; font-size: 3.5em; color: #ff00ff; text-shadow: 0 0 5px #ff00ff, 0 0 15px #ff00ff; }
.requests-container { display: flex; flex-wrap: wrap; justify-content: center; padding: 50px 20px; gap: 30px; }
.request-card {
    background: rgba(0,0,0,0.85);
    border-radius: 20px;
    width: 360px;
    padding: 20px;
    box-shadow: 0 0 12px #00ffff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}
.request-card:hover { transform: translateY(-5px); box-shadow: 0 0 18px #00ffff, 0 0 30px #ff00ff; }
.request-card h2 { font-size: 1.8em; color: #00ffff; margin-bottom: 10px; }
.request-card p { font-size: 1.1em; line-height: 1.5; margin: 8px 0; color: #fff; }
.btn { display: inline-block; padding: 10px 20px; border-radius: 15px; font-family: 'Orbitron','Audiowide'; font-weight: bold; font-size: 1em; margin: 5px; color: #000; text-decoration: none; cursor: pointer; transition: all 0.3s ease; border: none; }
.btn-accept { background: linear-gradient(45deg, #00ff00, #7fff00); box-shadow: 0 0 12px #00ff00, 0 0 25px #7fff00; }
.btn-accept:hover { filter: brightness(1.3); }
.btn-refuse { background: linear-gradient(45deg, #ff0000, #ff4c4c); box-shadow: 0 0 12px #ff0000, 0 0 25px #ff4c4c; }
.btn-refuse:hover { filter: brightness(1.3); }
</style>
</head>
<body>
    <!-- NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <a href="admin.php">Home</a>
        <a href="demand.php">Inbox</a>
        <a href="event.php">Manage Events</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="nav-logo">Admin Dashboard</div>
</div>

<style>
/* Navbar */
.navbar {
    width: 100%;
    top: 0;
    left: 0;
    position: sticky;
    background: rgba(17,17,17,0.9);
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 0 15px #b200ff;
    z-index: 1000;
}

.nav-left {
    display: flex;
    gap: 20px;
}

.nav-left a {
    color: #b200ff;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}
.nav-left a:hover {
    color: #ff00ff;
}

/* Logo / title on right */
.nav-logo {
    font-size: 1.8em;
    color: #b200ff;
    font-weight: bold;
}
* {
    box-sizing: border-box; /* Include padding/border in width calculations */
}
</style>


<h1>Inbox - Event Requests</h1>

<div class="requests-container">
    <?php foreach ($requests as $req): ?>
        <div class="request-card">
            <h2><?= htmlspecialchars($req['titre']) ?></h2>
            <p><strong>Description:</strong> <?= htmlspecialchars($req['description']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($req['date_event']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($req['lieu']) ?></p>
            <p><strong>Organizer:</strong> <?= htmlspecialchars($req['organisateur_nom']) ?></p>

            <?php if (!empty($req['image'])): ?>
                <img src='../../../organizator_images/<?= $req['organisateur_id'] ?>/<?= htmlspecialchars($req['image']) ?>' width='200'>
            <?php endif; ?>

            <form method="POST" action="process_request.php">
                <input type="hidden" name="id" value="<?= $req['id'] ?>">
                <button type="submit" name="action" value="accept" class="btn btn-accept">Accept</button>
                <button type="submit" name="action" value="refuse" class="btn btn-refuse">Refuse</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
