<?php
session_start();
require_once __DIR__ . '/../../config.php';
$pdo = config::getConnexion();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        // Rediriger selon le rôle
        if ($user['role'] == 'admin') {
            header("Location: ../backoffice/dashboard.php");
        } else {
            header("Location: ../frontoffice/index.php");
        }
        exit;
    } else {
        $message = "Email ou mot de passe incorrect !";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
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
        .login-container {
            background: rgba(0,0,0,0.8);
            padding: 80px;
            border-radius: 20px;
            box-shadow: 0 0 25px #0ff, 0 0 50px #f0f;
            text-align: center;
            width: 500px;
        }
        h2 {
            font-size: 2em;
            margin-bottom: 50px;
            color: #0ff;
            text-shadow: 0 0 10px #0ff, 0 0 20px #ff00ff;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 10px;
            border: none;
            font-size: 1em;
        }
       button {
    font-family: 'Orbitron', sans-serif; /* Texte du bouton en Orbitron */
    padding: 16px 30px;
    font-size: 1.1em;
    color: #fff;
    background: linear-gradient(45deg, #ff00ff, #00ffff);
    border: none;
    border-radius: 30px;
    cursor: pointer;
    box-shadow: 0 0 15px #ff00ff, 0 0 30px #00ffff;
    transition: transform 0.2s, box-shadow 0.2s;
    width: 100%;
}

button:hover {
    transform: scale(1.05);
    box-shadow: 0 0 30px #ff00ff, 0 0 60px #00ffff;
}

        .message {
            margin-top: 15px;
            font-size: 1em;
            color: #ff33cc;
            text-shadow: 0 0 5px #ff33cc;
        }
        .login-form {
    display: flex;
    flex-direction: column; /* Empile verticalement */
    gap: 15px; /* Espace entre les champs et le bouton */
}

.form-group {
    display: flex;
    flex-direction: column;
}

input, button {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    font-size: 1em;
}
button{
    padding: auto;
    width: 524px;
}
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Connexion</h2>
        <form method="post" action="" class="login-form">
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            </div>
            <div class="form-group">
                <button type="submit" name="login">Se connecter</button>
            </div>
        </form>
        <!-- Lien vers l'inscription -->
      <p style="margin-top: 50px; color: #0ff; text-align: center;">
    Pas de compte ? <a href="register.php" style="color: #ff00ff; text-decoration: none;">Créer un compte</a>
</p>

        <p class="message">
            <?php
            // Ici on affichera le message d'erreur ou succès
            ?>
        </p>
    </div>
</body>


</html>

