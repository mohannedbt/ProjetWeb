<?php
require_once "../../../config.php";
require_once "../../../Controller/UserController.php";

$config = new config();
$pdo = $config->getConnexion();
$userController = new UserController($pdo);
$users = $userController->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
<style>
body { font-family:'Orbitron';    background: url('https://media.istockphoto.com/id/530437857/photo/beautiful-european-night-club-interior.jpg?s=612x612&w=0&k=20&c=2Xpq2Z1o9rOLsQ9-B6rHPI7FAtH3LSCMqKHKUDs8zUg=') ;  background-size: cover;color:#fff; margin:0; }
.navbar { width:100%; position:sticky; top:0; background:rgba(17,17,17,0.9); padding:15px 30px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 0 15px #b200ff; z-index:1000; }
.nav-left a { color:#b200ff; text-decoration:none; margin-right:20px; font-weight:bold; }
.nav-left a:hover { color:#ff00ff; }
.nav-logo { font-size:1.8em; color:#b200ff; font-weight:bold; }
.table-container { padding:30px; }
table { width:100%; border-collapse:collapse; }
th, td { padding:12px; border-bottom:1px solid #444; text-align:left; }
th { color:#ff00ff; }
tr:hover { background:rgba(255,0,255,0.1); }
.btn { padding:8px 15px; border-radius:10px; font-weight:bold; text-decoration:none; margin-right:5px; transition:0.3s; color:#000; }
.btn-edit { background:linear-gradient(45deg,#ffff00,#ffea00); }
.btn-edit:hover { filter:brightness(1.3); }
.btn-delete { background:linear-gradient(45deg,#ff0000,#ff4c4c); }
.btn-delete:hover { filter:brightness(1.3); }
.btn-add { background:linear-gradient(45deg,#00ff00,#7fff00); display:inline-block; margin-bottom:20px; }
.btn-add:hover { filter:brightness(1.3); }
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
        <a href="utilisateurs.php">Manage Users</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="nav-logo">Admin Dashboard</div>
</div>


<div class="table-container">
    <a href="add_user.php" class="btn btn-add">+ Add User</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['nom']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                    <a href="modify_utilisateur.php?id=<?= $user['id'] ?>" class="btn btn-edit">Edit</a>
                    <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
