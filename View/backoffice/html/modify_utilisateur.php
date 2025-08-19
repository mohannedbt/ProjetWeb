<?php
require_once "../../../config.php";
require_once "../../../Controller/UserController.php";

$config = new config();
$pdo = $config->getConnexion();
$userController = new UserController($pdo);

// Get user ID
$userId = $_GET['id'] ?? null;
if (!$userId) die("No user ID provided.");

$user = $userController->getUserById($userId);
if (!$user) die("User not found.");

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $role = $_POST['role'];

    // Inline PHP validations
    if (strlen($nom) < 3 || strlen($nom) > 50) {
        $errors[] = "Le nom doit être entre 3 et 50 caractères.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Adresse e-mail invalide.";
    }

    $valid_roles = ['admin', 'organisateur', 'participant'];
    if (!in_array($role, $valid_roles)) {
        $errors[] = "Rôle invalide.";
    }

    if (empty($errors)) {
        $data = [
            'nom' => $nom,
            'email' => $email,
            'role' => $role
        ];
        // Only update password if provided
        if (!empty($mot_de_passe)) {
            $data['mot_de_passe'] = $mot_de_passe;
        }
        $userController->updateUser($userId, $data);
        $success = "✅ Utilisateur mis à jour avec succès!";
        $user = $userController->getUserById($userId); // Refresh data
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modifier Utilisateur</title>
<link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
body { margin:0; font-family: 'Orbitron','Audiowide'; background: url('https://media.istockphoto.com/id/530437857/photo/beautiful-european-night-club-interior.jpg?s=612x612&w=0&k=20&c=2Xpq2Z1o9rOLsQ9-B6rHPI7FAtH3LSCMqKHKUDs8zUg=') no-repeat center center fixed; background-size: cover; color: #fff; display:flex; justify-content:center; align-items:flex-start; min-height:100vh; padding-top:50px; }

form { background: rgba(0,0,0,0.85); padding: 40px; border-radius:20px; box-shadow:0 0 20px #ff00ff,0 0 30px #00ffff; width:360px; position:relative; }

.input-group { position:relative; margin-bottom:25px; }
.input-group input,
.input-group select { width:100%; padding:12px 12px 12px 10px; background:transparent; border:2px solid #00ffff; border-radius:10px; color:#fff; font-size:1em; outline:none; -webkit-appearance:none; }
.input-group label { position:absolute; top:50%; left:12px; transform:translateY(-50%); color:#ff00ff; pointer-events:none; transition:0.3s; background: rgba(0,0,0,0.85); padding:0 5px; }
.input-group input:focus + label,
.input-group input:not(:placeholder-shown) + label,
.input-group select:focus + label,
.input-group select:not([value=""]) + label { top:-10px; font-size:0.8em; color:#00ffff; }

button { width:100%; padding:12px; font-family: 'Orbitron', sans-serif; font-weight:bold; font-size:1em; border:none; border-radius:12px; background: linear-gradient(45deg,#00ff00,#7fff00); color:#000; cursor:pointer; transition: all 0.3s ease; }
button:hover { filter: brightness(1.3); }

.message { text-align:center; margin-bottom:15px; font-weight:bold; color:#00ff00; }
.error { text-align:center; margin-bottom:10px; font-weight:bold; color:#ff0000; }
</style>
</head>
<body>

<form method="POST" novalidate>
    <?php if ($success): ?><div class="message"><?= $success ?></div><?php endif; ?>
    <?php foreach ($errors as $err): ?><div class="error"><?= $err ?></div><?php endforeach; ?>

    <div class="input-group">
        <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" placeholder=" ">
        <label>Nom</label>
    </div>

    <div class="input-group">
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" placeholder=" ">
        <label>Email</label>
    </div>

    <div class="input-group">
        <input type="password" name="mot_de_passe" placeholder=" ">
        <label>Mot de passe (laisser vide pour conserver)</label>
    </div>

    <div class="input-group">
        <select name="role" required>
            <option value="" disabled>Select role</option>
            <option value="admin" <?= $user['role']=='admin' ? 'selected':'' ?>>Admin</option>
            <option value="organisateur" <?= $user['role']=='organisateur' ? 'selected':'' ?>>Organisateur</option>
            <option value="participant" <?= $user['role']=='participant' ? 'selected':'' ?>>Participant</option>
        </select>
        <label>Rôle</label>
    </div>

    <button type="submit">Mettre à jour l'utilisateur</button>
</form>

</body>
</html>
