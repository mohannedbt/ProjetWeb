<?php
class EvenementController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add new event
    public function add($data, $file) {
        $imageName = null;

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $organisateurId = $data['organisateur_id'];
            $uploadDir = __DIR__ . "/../organizator_images/" . $organisateurId . "/";

            // Create dir if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = basename($file['name']);
            move_uploaded_file($file['tmp_name'], $uploadDir . $imageName);
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO evenement (titre, description, date, lieu, image, organisateur_id) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['titre'],
            $data['description'],
            $data['date'],
            $data['lieu'],
            $imageName, // Save only file name
            $data['organisateur_id']
        ]);

        return "done";
    }

    // Modify event
    public function update($id, $data, $file) {
        $imageName = $data['current_image']; // Keep current by default

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
            SET titre=?, description=?, date=?, lieu=?, image=?, organisateur_id=? 
            WHERE id=?
        ");
        $stmt->execute([
            $data['titre'],
            $data['description'],
            $data['date'],
            $data['lieu'],
            $imageName, // Save only file name
            $data['organisateur_id'],
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
         if ($stmt->rowCount() > 0) {
        return "done";  // Deleted successfully
    } else {
        return "not_found"; // No row found with this ID
    }

        
    }

    // Get all events
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM evenement");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

// Additional methods can be added here as needed
}
