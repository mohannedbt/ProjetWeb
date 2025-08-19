<?php
require_once "../../../config.php";
require_once "../../../Controller/UserController.php";

$config = new config();
$pdo = $config->getConnexion();
$userController = new UserController($pdo);

$id = $_GET['id'] ?? null;
if(!$id) { die("User ID missing"); }

$user = $userController->get($id);
$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $data = [
        'nom' => $_POST['nom'],
        'email' => $_POST['email'],
        'mot_de_passe' => $_POST['mot_de_passe'], // leave blank to keep old password
        'role' => $_POST['role']
    ];
    if($userController->update($id,$data)){
        $message = "✅ User updated successfully!";
        $user = $userController->get($id);
    } else {
        $message = "❌ Failed to update user!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit User</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
body { font-family:'Orbitron'; background:#111; color:#fff; margin:0; }
.navbar { width:100%; position:sticky; top:0; background:rgba(17,17,17,0.9); padding:15px 30px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 0 15px #b200ff; z-index:1000; }
.nav-left a { color:#b200ff; text-decoration:none; margin-right:20px; font-weight:bold; }
.nav-left a:hover { color:#ff00ff; }
.nav-logo { font-size:1.8em; color:#b200ff; font-weight:bold; }
.form-container { max-width:500px; margin:50px auto; background:rgba(17,17,17,0.85); padding:30px; border-radius:15px; box-shadow:0 0 15px #b200ff; }
input, select { width:100%; padding:10px; margin:10px 0; border-radius:10px; border:none; }
button { padding:10px 20px; border-radius:10px; border:none; font-weight:bold; background:linear-gradient(45deg,#ffff00,#ffea00); color:#000; cursor:pointer; transition:0.3s; }
button:hover { filter:brightness(1.3); }
.message { text-align:center; margin-bottom:15px; font-weight:bold; }
</style>
</head>
<body>

<div class="navbar">
    <div class="nav-left">
        <a href="users.php">Back to Users</a>
    </div>
    <div class="nav-logo">Edit User</div>
</div>

<div class="form-container">
    <?php if($message) echo "<div class='message'>$message</div>"; ?>
    <form method="POST">
        <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <input type="password" name="mot_de_passe" placeholder="New Password (leave blank to keep old)">
        <select name="role" required>
            <option value="participant" <?= $user['role']=='participant'?'selected':'' ?>>Participant</option>
            <option value="organisateur" <?= $user['role']=='organisateur'?'selected':'' ?>>Organisateur</option>
            <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
        </select>
        <button type="submit">Update User</button>
    </form>
</div>

</body>
</html>
