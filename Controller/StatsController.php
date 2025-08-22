<?php
require_once "../../../config.php";

class StatsController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // count of pending events
    public function getPendingEvents() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM evenement WHERE status = 'en_attente'");
        return $stmt->fetchColumn();
    }

    // approved events
    public function getApprovedEvents() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM evenement WHERE status = 'approuve'");
        return $stmt->fetchColumn();
    }

    // distinct organizers (from evenement.organisateur_id)
    public function getOrganizers() {
        $stmt = $this->pdo->query("SELECT COUNT(DISTINCT organisateur_id) FROM evenement");
        return $stmt->fetchColumn();
    }

    // upcoming events (today or later)
    public function getUpcomingEvents() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM evenement WHERE date_event >= CURDATE()");
        return $stmt->fetchColumn();
    }

    // events per month (for bar chart)
    public function getEventsPerMonth() {
        $stmt = $this->pdo->query("
            SELECT MONTH(date_event) as month, COUNT(*) as total 
            FROM evenement 
            GROUP BY MONTH(date_event)
        ");
        $data = array_fill(1, 12, 0); // default 0 for each month
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[(int)$row['month']] = (int)$row['total'];
        }
        return $data;
    }

    // events by organizer (for doughnut chart)
    public function getEventsByOrganizer() {
        $stmt = $this->pdo->query("
            SELECT u.nom, COUNT(e.id) as total
            FROM utilisateur u
            INNER JOIN evenement e ON u.id = e.organisateur_id
            GROUP BY u.nom
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // participations per event (for line chart maybe)
    public function getParticipationsPerEvent() {
        $stmt = $this->pdo->query("
            SELECT e.titre as event_name, COUNT(p.id) as total
            FROM evenement e
            LEFT JOIN participation p ON e.id = p.evenement_id
            GROUP BY e.titre
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
}
