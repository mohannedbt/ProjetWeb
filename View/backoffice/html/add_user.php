<?php
require_once "../../../config.php";
require_once "../../../Controller/UserController.php";

$config = new config();
$pdo = $config->getConnexion();
$userController = new UserController($pdo);

$errors = [];
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $role = $_POST['role'] ?? '';

    // ✅ Validations
    if(strlen($nom) < 3 || strlen($nom) > 50){
        $errors[] = "Le nom doit être entre 3 et 50 caractères.";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Adresse e-mail invalide.";
    }

    if(strlen($mot_de_passe) < 6){
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    $valid_roles = ['admin', 'organisateur', 'participant'];
    if(!in_array($role, $valid_roles)){
        $errors[] = "Rôle invalide.";
    }

    // ✅ If no errors → add user
    if(empty($errors)){
        $data = [
            'nom' => $nom,
            'email' => $email,
            'mot_de_passe' => $mot_de_passe,
            'role' => $role
        ];
        if($userController->add($data)){
            $success = "✅ Utilisateur ajouté avec succès!";
            $nom = $email = $mot_de_passe = $role = '';
        } else {
            $errors[] = "❌ Échec de l'ajout de l'utilisateur.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajouter Utilisateur</title>
<link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
body { margin:0; font-family: 'Orbitron','Audiowide'; background: url('https://media.istockphoto.com/id/530437857/photo/beautiful-european-night-club-interior.jpg?s=612x612&w=0&k=20&c=2Xpq2Z1o9rOLsQ9-B6rHPI7FAtH3LSCMqKHKUDs8zUg=') no-repeat center center fixed; background-size: cover; color: #fff; display:flex; justify-content:center; align-items:flex-start; min-height:100vh; padding-top:50px; }

form { background: rgba(0,0,0,0.85); padding: 40px; border-radius:20px; box-shadow:0 0 20px #ff00ff,0 0 30px #00ffff; width:360px; position:relative; }

.input-group { position:relative; margin-bottom:25px; }
.input-group input,
.input-group select { width:100%; padding:12px 12px 12px 10px; background:black; border:2px solid #00ffff; border-radius:10px; color:#fff; font-size:1em; outline:none; -webkit-appearance:none; }
.input-group label { position:absolute; top:50%; left:12px; transform:translateY(-50%); color:#ff00ff; pointer-events:none; transition:0.3s; background: rgba(0,0,0,0.85); padding:0 5px; }
.input-group input:focus + label,
.input-group input:not(:placeholder-shown) + label,
.input-group select:focus + label,
.input-group select:not([value=""]) + label { top:-10px; font-size:0.8em; color:#00ffff; }

button { width:100%; padding:12px; font-family: 'Orbitron', sans-serif; font-weight:bold; font-size:1em; border:none; border-radius:12px; background: linear-gradient(45deg,#00ff00,#7fff00); color:#000; cursor:pointer; transition: all 0.3s ease; }
button:hover { filter: brightness(1.3); }

.message { text-align:center; margin-bottom:15px; font-weight:bold; color:#00ff00; }
.error { text-align:center; margin-bottom:10px; font-weight:bold; color:#ff0000; }
* { box-sizing: border-box; }
</style>
</head>
<body>

<form method="POST" novalidate>
    <?php if($success): ?><div class="message"><?= $success ?></div><?php endif; ?>
    <?php foreach($errors as $err): ?><div class="error"><?= $err ?></div><?php endforeach; ?>

    <div class="input-group">
        <input type="text" name="nom" value="<?= htmlspecialchars($nom ?? '') ?>" placeholder=" " required>
        <label>Nom</label>
    </div>

    <div class="input-group">
        <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" placeholder=" " required>
        <label>Email</label>
    </div>

    <div class="input-group">
        <input type="password" name="mot_de_passe" placeholder=" " required>
        <label>Mot de passe</label>
    </div>

    <div class="input-group">
        <select name="role" required>
            <option value="" disabled <?= empty($role)?'selected':'' ?>>Sélectionner un rôle</option>
            <option value="participant" <?= ($role ?? '')==='participant'?'selected':'' ?>>Participant</option>
            <option value="organisateur" <?= ($role ?? '')==='organisateur'?'selected':'' ?>>Organisateur</option>
            <option value="admin" <?= ($role ?? '')==='admin'?'selected':'' ?>>Admin</option>
        </select>
        <label>Rôle</label>
    </div>

    <button type="submit">Ajouter l'utilisateur</button>
</form>

</body>
</html>
s