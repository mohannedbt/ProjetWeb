<?php
require_once "../../../config.php";
require_once "../../../Controller/EvenementController.php";
require '../../../vendor/autoload.php'; // Composer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = new config();
$pdo = $config->getConnexion();
$eventController = new EvenementController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $eventId = $_POST['id']; // Event ID

    if ($action === 'accept') {
        $eventController->updateStatus($eventId, 'approuve');
        sendMailToOrganizer($pdo, $eventId, 'accept');
        $message = "✅ Event approved successfully!";
        $color = "#00ff00";
    }

    if ($action === 'refuse') {
        $eventController->updateStatus($eventId, 'refuse');
        sendMailToOrganizer($pdo, $eventId, 'refuse');
        $message = "❌ Event refused!";
        $color = "#ff0000";
    }
}

// -------------------------
// Send email function
// -------------------------
function sendMailToOrganizer($pdo, $eventId, $status) {
    // Get event & organizer info
    $stmt = $pdo->prepare("
        SELECT e.titre, u.email, u.nom 
        FROM evenement e 
        JOIN utilisateur u ON e.organisateur_id = u.id 
        WHERE e.id = ?
    ");
    $stmt->execute([$eventId]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) return;

    $toEmail = $event['email'];
    $toName = $event['nom'];
    $eventTitle = $event['titre'];

    $subject = ($status === 'accept') ? "Your Event Was Approved ✅" : "Your Event Was Refused ❌";
     $body = "
    <html>
    <head>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Audiowide&family=Orbitron:wght@400;700&display=swap');
            body {
                background-color: #111;
                color: #fff;
                font-family: 'Orbitron', 'Audiowide', sans-serif;
                text-align: center;
                padding: 30px;
            }
            h1 {
                color: #ff00ff;
                text-shadow: 0 0 4px #ff00ff, 0 0 12px #ff00ff;
            }
            p {
                font-size: 1.2em;
                color: white;
            }
            .neon-box {
                background: rgba(0,0,0,0.85);
                border: 2px solid #00ffff;
                border-radius: 20px;
                padding: 20px;
                display: inline-block;
                box-shadow: 0 0 12px #00ffff, 0 0 25px #ff00ff;
            }
            a.button {
                display: inline-block;
                margin-top: 20px;
                padding: 12px 25px;
                border-radius: 15px;
                text-decoration: none;
                font-weight: bold;
                font-family: 'Orbitron', 'Audiowide', sans-serif;
                font-size: 1.1em;
                color: #000;
                background: linear-gradient(45deg, #ffff00, #ffea00);
                box-shadow: 0 0 12px #ffff00, 0 0 25px #ffea00;
            }
            a.button:hover {
                filter: brightness(1.3);
            }
        </style>
    </head>
    <body>
        <div class='neon-box'>
            <h1>".($status === 'accept' ? '✅ Événement approuvé !' : '❌ Événement refusé !')."</h1>
            <p>Bonjour <strong>{$toName}</strong>,</p>
            <p>Votre événement <strong>{$eventTitle}</strong> a ".($status === 'accept' ? 'été approuvé 🎉' : 'été refusé ❌')."</p>
            <p>Merci d’utiliser notre plateforme Neon Techno Events.</p>
            <a class='button' href='https://yourwebsite.com' target='_blank'>Voir la plateforme</a>
        </div>
    </body>
    </html>
    ";

    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // change if using another provider
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mohannedbentaleb8@gmail.com'; // your email
        $mail->Password   = 'empv ycsz cgpj txos';   // Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('youremail@gmail.com', 'Neon Events');
        $mail->addAddress($toEmail, $toName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
}
?>
<?php if(isset($message)): ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Processing...</title>
<style>
body {
    font-family: 'Orbitron','Audiowide', sans-serif;
    background: #111;
    color: <?= $color ?? '#fff' ?>;
    text-align: center;
    padding-top: 100px;
}
</style>
<script>
// Redirect back to inbox after 2 seconds
setTimeout(() => {
    window.location.href = 'demand.php';
}, 2000); // 2 seconds
</script>
</head>
<body>
    <h1><?= $message ?></h1>
</body>
</html>
<?php endif; ?>
