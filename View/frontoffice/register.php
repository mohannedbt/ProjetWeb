<?php
session_start();
require_once __DIR__ . '/../../config.php';
$pdo = config::getConnexion();

if (isset($_POST['register'])) {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $role = $_POST['role'];

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $message = "Cet email est déjà utilisé !";
    } else {
        // Hasher le mot de passe
        $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        // Insérer l'utilisateur
        $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $hashed_password, $role]);

        $message = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
      <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'orbitron', sans-serif;
            background: url('event.avif') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #fff;
        }
        .register-container {
            background: rgba(0,0,0,0.8);
            padding: 70px;
            border-radius: 20px;
            box-shadow: 0 0 25px #0ff, 0 0 50px #f0f;
            text-align: center;
            width: 500px;
        }
        h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #0ff;
            text-shadow: 0 0 10px #0ff, 0 0 20px #ff00ff;
        }
        .register-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select, button {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            font-size: 1em;
        }
        button {
            font-family: 'Orbitron', sans-serif;
            color: #fff;
            background: linear-gradient(45deg, #ff00ff, #00ffff);
            cursor: pointer;
            box-shadow: 0 0 15px #ff00ff, 0 0 30px #00ffff;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 30px #ff00ff, 0 0 60px #00ffff;
        }
        .message {
            margin-top: 15px;
            font-size: 1em;
            color: #0ff;
            text-shadow: 0 0 5px #0ff;
        }
        a {
            color: #ff00ff;
            text-decoration: none;
        }
        button{
    padding: auto;
    width: 520px;
}
select {
    width: 105%;  /* Prend toute la largeur du formulaire */
    padding: 12px;
    border-radius: 10px;
    border: none;
    font-size: 1em;
}

    </style>
</head>
<body>
    <div class="register-container">
        <h2>Créer un compte</h2>
        <form method="post" class="register-form">
            <input type="text" name="nom" placeholder="Nom complet" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <select name="role" required>
                <option value="participant">Participant</option>
                <option value="organisateur">Organisateur</option>
            </select>
            <button type="submit" name="register" style="margin-top: 50px;">S'inscrire</button>

        </form>
        <p class="message">
            <?php if (isset($message)) echo htmlspecialchars($message); ?>
        </p>
       <p style="margin-top: 40px; text-align: center;">
    Déjà un compte ? <a href="login.php" style="color: #ff00ff; text-decoration: none;">Se connecter</a>
</p>


    </div>
</body>
</html>
