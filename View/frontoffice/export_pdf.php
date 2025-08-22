<?php
session_start();
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../vendor/fpdf/fpdf.php"; // si tu utilises composer

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = config::getConnexion();
$utilisateur_id = $_SESSION['user_id'];

// Récupérer toutes les réservations de l'utilisateur avec infos événements
$stmt = $conn->prepare("
    SELECT p.id as reservation_id, p.date_inscription, p.statut, 
           e.titre, e.description, e.date_event, e.lieu
    FROM participation p
    JOIN evenement e ON p.evenement_id = e.id
    WHERE p.utilisateur_id = ?
");
$stmt->execute([$utilisateur_id]);
$reservations = $stmt->fetchAll();

if (!$reservations) {
    die("Aucune réservation trouvée.");
}

// Créer le PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->SetTextColor(0,255,255); // couleur néon bleu

$pdf->Cell(0,10,"Mes Réservations",0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(0,0,0); // fond noir pour effet néon

foreach ($reservations as $r) {
    $pdf->Cell(0,8,"Titre : ".$r['titre'],0,1,true);
    $pdf->Cell(0,8,"Description : ".$r['description'],0,1,true);
    $pdf->Cell(0,8,"Date de l'événement : ".$r['date_event'],0,1,true);
    $pdf->Cell(0,8,"Lieu : ".$r['lieu'],0,1,true);
    $pdf->Cell(0,8,"Date inscription : ".$r['date_inscription'],0,1,true);
    $pdf->Cell(0,8,"Statut : ".$r['statut'],0,1,true);
    $pdf->Ln(5);
}

// Générer le PDF
$pdf->Output("D","Mes_reservations.pdf");
