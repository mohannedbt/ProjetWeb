<?php
require_once "../../../Controller/EvenementController.php";
require_once "../../../config.php";

$config = new config();
$pdo = $config->getConnexion();
$eventController = new EvenementController($pdo);
$events=$eventController->getAll(); // Fetch all events

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Neon Techno Event Shower</title>
<!-- Google Fonts: Techno / Cyberpunk -->
<link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
/* BODY & BACKGROUND */
body {
    margin: 0;
    font-family: 'Orbitron', 'Audiowide', sans-serif;
    background: url('https://media.istockphoto.com/id/530437857/photo/beautiful-european-night-club-interior.jpg?s=612x612&w=0&k=20&c=2Xpq2Z1o9rOLsQ9-B6rHPI7FAtH3LSCMqKHKUDs8zUg=') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    text-align: center;
    -webkit-font-smoothing: antialiased;
    text-rendering: optimizeLegibility;
}

/* URL BAR */
.url-bar {
    padding: 20px;
    background: rgba(0,0,0,0.6);
    font-size: 1.4em;
    border-bottom: 2px solid #00ffff;
}

.url-bar a {
    color: #00ffff;
    text-decoration: none;
    font-weight: bold;
}

.url-bar a:hover {
    color: #ff00ff;
}

/* TITLE */
h1 {
    margin-top: 40px;
    font-family: 'Audiowide', 'Orbitron', sans-serif;
    font-size: 4.2em;
    color: #ff00ff;
    text-shadow: 0 0 4px #ff00ff, 0 0 12px #ff00ff;
}

/* EVENTS CONTAINER */
.events-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 50px 20px;
    gap: 40px;
}

/* EVENT CARD */
.event-card {
    background: rgba(0,0,0,0.85);
    border-radius: 20px;
    width: 360px;
    padding: 25px;
    box-shadow: 0 0 12px #00ffff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}

.event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 0 18px #00ffff, 0 0 30px #ff00ff;
}

/* EVENT IMAGE */
.event-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 15px;
    margin-bottom: 15px;
    border: 2px solid #ff00ff;
}

/* EVENT TITLE */
.event-card h2 {
    font-size: 2em;
    color: #00ffff;
    text-shadow: 0 0 3px #00ffff;
    margin-bottom: 15px;
}

/* EVENT DETAILS */
.event-details {
    font-size: 1.1em;
    line-height: 1.6;
    text-align: left;
    margin-bottom: 20px;
}

.event-details p {
    margin: 8px 0;
}

.event-details strong {
    color: #ff00ff;
}

/* BUTTONS */
.btn {
    display: inline-block;
    padding: 12px 25px;
    margin: 5px;
    border-radius: 15px;
    text-decoration: none;
    font-weight: bold;
    font-family: 'Orbitron', 'Audiowide', sans-serif;
    font-size: 1.1em;
    color: #000;
    transition: all 0.3s ease;
}

.btn-modify {
    background: linear-gradient(45deg, #ffff00, #ffea00);
    box-shadow: 0 0 12px #ffff00, 0 0 25px #ffea00;
}

.btn-modify:hover {
    filter: brightness(1.3);
}

.btn-delete {
    background: linear-gradient(45deg, #ff0000, #ff4c4c);
    box-shadow: 0 0 12px #ff0000, 0 0 25px #ff4c4c;
}

.btn-delete:hover {
    filter: brightness(1.3);
}

/* FOOTER */
footer {
    text-align: center;
    padding: 25px;
    font-size: 1em;
    color: #fff;
}
.event-card {
    position: relative; /* Add this */
    background: rgba(0,0,0,0.85);
    border-radius: 20px;
    width: 360px;
    padding: 25px;
    box-shadow: 0 0 12px #00ffff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}

</style>
</head>
<body>
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



<h1>Upcoming Events</h1>
<div class="events-container">
    <?php if (!empty($events)): ?>
        <?php foreach ($events as $event): ?>
            <div class="event-card">
                <?php
                    $imagePath = $event['image'] 
                        ? "../../../organizator_images/" . $event['organisateur_id'] . "/" . $event['image'] 
                        : "https://via.placeholder.com/350x180.png?text=Event";
                ?>
                <img src="<?= htmlspecialchars($imagePath) ?>" alt="Event Image">

                <!-- Pending Badge -->
                <?php if ($event['status'] === 'en_attente'): ?>
                    <div style="position:absolute; top:10px; right:10px; background:#ff00ff; color:#000; padding:5px 10px; border-radius:10px; font-weight:bold; z-index:10;">
                        Pending - <a href="demand.php" style="color:#00ffff; text-decoration:underline;">See Inbox</a>
                    </div>
                <?php endif; ?>

                <!-- Event Title -->
                <h2><?= htmlspecialchars($event['titre']) ?></h2>

                <!-- Event Details -->
                <div class="event-details">
                    <p><strong>Description:</strong> <?= htmlspecialchars($event['description']) ?></p>
                    <p><strong>Date:</strong> <?= htmlspecialchars($event['date_event']) ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($event['lieu']) ?></p>
                    <p><strong>Organizer:</strong> <?= htmlspecialchars($event['organisateur_id']) ?></p>
                </div>

                <!-- Buttons -->
                <a href="modify_event.php?id=<?= $event['id'] ?>" class="btn btn-modify">Modify</a>
                <a href="delete_event.php?id=<?= $event['id'] ?>" class="btn btn-delete">Delete</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color:#ff00ff; text-align:center;">No events found.</p>
    <?php endif; ?>
</div>


<footer>
    &copy; <?= date('Y') ?> Neon Techno Events. All rights reserved.
</footer>

</body>
</html>
