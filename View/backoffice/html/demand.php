<?php
require_once "../../../config.php";
$config = new config();
$pdo = $config->getConnexion();

// Hard-coded event requests
$requests = [
    [
        'id' => 1,
        'titre' => 'Summer Neon Party',
        'description' => 'A crazy summer party with neon lights and techno music.',
        'date_event' => '2025-09-10',
        'lieu' => 'Neon Club',
        'organisateur_id' => 2,
        'image' => 'summer_party.jpg',
    ],
    [
        'id' => 2,
        'titre' => 'Cyberpunk Festival',
        'description' => 'Techno vibes all night, bring your best costumes!',
        'date_event' => '2025-09-20',
        'lieu' => 'City Center',
        'organisateur_id' => 3,
        'image' => 'cyberpunk_fest.png',
    ],
];
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

<h1>Inbox - Event Requests</h1>

<div class="requests-container">
    <?php foreach ($requests as $req): ?>
        <div class="request-card">
            <h2><?= htmlspecialchars($req['titre']) ?></h2>
            <p><strong>Description:</strong> <?= htmlspecialchars($req['description']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($req['date_event']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($req['lieu']) ?></p>
            <p><strong>Organizer ID:</strong> <?= htmlspecialchars($req['organisateur_id']) ?></p>
            <p><strong>Image:</strong> <?= htmlspecialchars($req['image']) ?></p>

            <form method="POST" action="process_request.php">
                <?php foreach ($req as $key => $value): ?>
                    <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($value) ?>">
                <?php endforeach; ?>
                <button type="submit" name="action" value="accept" class="btn btn-accept">Accept</button>
                <button type="submit" name="action" value="refuse" class="btn btn-refuse">Refuse</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
