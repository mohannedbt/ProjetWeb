<?php
class EvenementController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Get event by ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM evenement WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add new event (with file)
    public function add($data, $file = null) {
        $imageName = null;

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $organisateurId = $data['organisateur_id'];
            $uploadDir = __DIR__ . "/../organizator_images/" . $organisateurId . "/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = basename($file['name']);
            move_uploaded_file($file['tmp_name'], $uploadDir . $imageName);
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO evenement (titre, description, date_event, lieu, image, organisateur_id, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['titre'],
            $data['description'],
            $data['date_event'],
            $data['lieu'],
            $imageName,
            $data['organisateur_id'],
            $data['status'] ?? 'en_attente'
        ]);

        return "done";
    }

    // Modify event
    public function update($id, $data, $file = null) {
        $imageName = $data['current_image'] ?? null; // Keep current by default

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $organisateurId = $data['organisateur_id'];
            $uploadDir = __DIR__ . "/../organizator_images/" . $organisateurId . "/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Remove old image if exists
            if (!empty($imageName) && file_exists($uploadDir . $imageName)) {
                unlink($uploadDir . $imageName);
            }

            $imageName = basename($file['name']);
            move_uploaded_file($file['tmp_name'], $uploadDir . $imageName);
        }

        $stmt = $this->pdo->prepare("
            UPDATE evenement 
            SET titre=?, description=?, date_event=?, lieu=?, image=?, organisateur_id=?, status=? 
            WHERE id=?
        ");
        $stmt->execute([
            $data['titre'],
            $data['description'],
            $data['date_event'], // fixed column name
            $data['lieu'],
            $imageName,
            $data['organisateur_id'],
            $data['status'] ?? 'en_attente',
            $id
        ]);

        return "done";
    }

    // Delete event
    public function delete($id) {
        $stmt = $this->pdo->prepare("SELECT image, organisateur_id FROM evenement WHERE id=?");
        $stmt->execute([$id]);
        $event = $stmt->fetch();

        if ($event && !empty($event['image'])) {
            $filePath = __DIR__ . "/../organizator_images/" . $event['organisateur_id'] . "/" . $event['image'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $stmt = $this->pdo->prepare("DELETE FROM evenement WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0 ? "done" : "not_found";
    }

    // Get all events
    public function getAll() {
        $stmt = $this->pdo->query("SELECT e.*, u.nom AS organisateur_nom FROM evenement e JOIN utilisateur u ON e.organisateur_id = u.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get events by status
    public function getByStatus($status) {
        $stmt = $this->pdo->prepare("
            SELECT e.*, u.nom AS organisateur_nom 
            FROM evenement e 
            JOIN utilisateur u ON e.organisateur_id = u.id 
            WHERE e.status = ?
        ");
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update status of an event
    public function updateStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE evenement SET status=? WHERE id=?");
        $stmt->execute([$status, $id]);
        return "done";
    }
}
