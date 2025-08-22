<?php
require_once "../../../config.php";
require_once "../../../Controller/EvenementController.php";

$config = new config();
$pdo = $config->getConnexion();
$eventController = new EvenementController($pdo);

$message = '';
$errors = [];

// Fetch event by id
if (!isset($_GET['id'])) {
    die("Event ID is missing.");
}
$eventId = intval($_GET['id']);
$event = $eventController->getById($eventId);

if (!$event) {
    die("Event not found.");
}

// Handle update form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $date_event = $_POST['date_event'];
    $lieu = trim($_POST['lieu']);
    $organisateur_id = trim($_POST['organisateur_id']);
    $file = $_FILES['image'] ?? null;

    // --- Validation ---
    if (strlen($titre) < 3 || strlen($titre) > 100) {
        $errors[] = "❌ Title must be between 3 and 100 characters.";
    }
    if (strlen($description) < 10) {
        $errors[] = "❌ Description must be at least 10 characters.";
    }
    if (empty($date_event)) {
        $errors[] = "❌ Date is required.";
    }
    if (strlen($lieu) < 3) {
        $errors[] = "❌ Location must be at least 3 characters.";
    }
    if (!is_numeric($organisateur_id)) {
        $errors[] = "❌ Organizer ID must be numeric.";
    }

    // Image validation (if new uploaded)
    if ($file && $file['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = "❌ Only JPG, PNG, GIF files are allowed.";
        }
        if ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = "❌ File size must not exceed 2MB.";
        }
    }

    if (empty($errors)) {
        $data = [
            'titre' => $titre,
            'description' => $description,
            'date' => $date_event,
            'lieu' => $lieu,
            'organisateur_id' => $organisateur_id,
            'current_image' => $event['image'] ?? null
        ];

        if ($eventController->update($eventId, $data, $file)) {
            $message = "✅ Event updated successfully!";
            // refresh event data
            $event = $eventController->getById($eventId);
        } else {
            $message = "❌ Failed to update event!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Modify Event</title>
<link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
body {
    margin: 0;
    font-family: 'Orbitron', 'Audiowide', sans-serif;
    background: url('https://media.istockphoto.com/id/530437857/photo/beautiful-european-night-club-interior.jpg?s=612x612&w=0&k=20&c=2Xpq2Z1o9rOLsQ9-B6rHPI7FAtH3LSCMqKHKUDs8zUg=') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    text-align: center;
}
.container {
    max-width: 600px;
    margin: 60px auto;
    background: rgba(0,0,0,0.85);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 0 15px #00ffff, 0 0 25px #ff00ff;
}
.message { font-weight: bold; margin-bottom: 15px; }
.error { color: #ff4c4c; margin: 5px 0; }
.success { color: #00ff7f; margin: 5px 0; }

/* Hide default file input */
.file-input { display: none; }
.file-label {
    display: inline-block;
    padding: 10px 25px;
    margin-top: 10px;
    border-radius: 15px;
    background: linear-gradient(45deg, #ff00ff, #ff69b4);
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 0 12px #ff00ff, 0 0 25px #ff69b4;
    transition: all 0.3s ease;
}
.file-label:hover { filter: brightness(1.3); transform: translateY(-2px); }

h1 { font-size: 3em; color: #ff00ff; margin-bottom: 25px; text-shadow: 0 0 12px #ff00ff; }
label { display: block; font-weight: bold; margin: 12px 0 5px; text-align: left; color: #00ffff; }
input[type="text"], input[type="date"], textarea {
    width: 100%; padding: 12px; border-radius: 12px; border: none;
    font-size: 1em; margin-bottom: 15px; background: #111; color: #fff;
    box-shadow: 0 0 8px #00ffff;
}
.preview { margin: 15px 0; }
.preview img {
    max-width: 250px; border-radius: 15px;
    border: 2px solid #ff00ff;
    box-shadow: 0 0 10px #ff00ff, 0 0 15px #00ffff;
}
.btn {
    display: inline-block; padding: 12px 25px; margin: 12px 8px;
    border-radius: 15px; font-weight: bold; font-size: 1.2em;
    transition: all 0.3s ease; border: none; cursor: pointer;
}
.btn-save {
    background: linear-gradient(45deg, #00ffcc, #00ffff);
    box-shadow: 0 0 12px #00ffff, 0 0 20px #00ffcc; color: #000;
}
.btn-save:hover { filter: brightness(1.3); }
.btn-cancel {
    background: linear-gradient(45deg, #ff0000, #ff4c4c);
    box-shadow: 0 0 12px #ff0000, 0 0 20px #ff4c4c; color: #fff;
}
.btn-cancel:hover { filter: brightness(1.3); }

/* Navbar */
.navbar {
    width: 100%; top: 0; left: 0; position: sticky;
    background: rgba(17,17,17,0.9);
    padding: 15px 30px; display: flex;
    justify-content: space-between; align-items: center;
    box-shadow: 0 0 15px #b200ff; z-index: 1000;
}
.nav-left { display: flex; gap: 20px; }
.nav-left a { color: #b200ff; text-decoration: none; font-weight: bold; transition: 0.3s; }
.nav-left a:hover { color: #ff00ff; }
.nav-logo { font-size: 1.8em; color: #b200ff; font-weight: bold; }
* {
    box-sizing: border-box; /* Include padding/border in width calculations */
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

<div class="container">
    <h1>Modify Event</h1>

    <!-- Messages -->
    <?php if ($message): ?>
        <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>
    <?php foreach ($errors as $e): ?>
        <div class="error"><?= $e ?></div>
    <?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="titre" value="<?= htmlspecialchars($event['titre']) ?>" required>

        <label>Description:</label>
        <textarea name="description" rows="4" required><?= htmlspecialchars($event['description']) ?></textarea>

        <label>Date:</label>
        <input type="date" name="date_event" value="<?= htmlspecialchars($event['date_event']) ?>" required>

        <label>Location:</label>
        <input type="text" name="lieu" value="<?= htmlspecialchars($event['lieu']) ?>" required>

        <label>Organizer ID:</label>
        <input type="text" name="organisateur_id" value="<?= htmlspecialchars($event['organisateur_id']) ?>" required>

        <label for="image" class="file-label">Choose an image</label>
        <input type="file" name="image" id="image" class="file-input" accept="image/*">

        <div class="preview">
            <img id="preview-img"
                 src="<?= $event['image'] ? "../../../organizator_images/{$event['organisateur_id']}/{$event['image']}" : 'https://via.placeholder.com/250x150?text=No+Image' ?>"
                 alt="Event Image">
        </div>

        <button type="submit" class="btn btn-save">💾 Save</button>
        <a href="event.php" class="btn btn-cancel">✖ Cancel</a>
    </form>
</div>

<script>
document.getElementById("image").addEventListener("change", function(e){
    const file = e.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(event){
            document.getElementById("preview-img").setAttribute("src", event.target.result);
        };
        reader.readAsDataURL(file);
    }
});
</script>

</body>
</html>
