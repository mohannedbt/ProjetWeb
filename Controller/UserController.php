<?php
class UserController {
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    // Fetch all users
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM utilisateur");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch single user by ID
    public function get($id){
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add a new user
    public function add($data){
        $stmt = $this->pdo->prepare("INSERT INTO utilisateur (nom,email,mot_de_passe,role) VALUES (?,?,?,?)");
        $hashedPassword = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        return $stmt->execute([$data['nom'], $data['email'], $hashedPassword, $data['role']]);
    }

    // Update user
    public function update($id, $data){
        if(!empty($data['mot_de_passe'])){
            $hashedPassword = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE utilisateur SET nom=?, email=?, mot_de_passe=?, role=? WHERE id=?");
            return $stmt->execute([$data['nom'], $data['email'], $hashedPassword, $data['role'], $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE utilisateur SET nom=?, email=?, role=? WHERE id=?");
            return $stmt->execute([$data['nom'], $data['email'], $data['role'], $id]);
        }
    }

    // Delete user
    public function delete($id){
        $stmt = $this->pdo->prepare("DELETE FROM utilisateur WHERE id=?");
        return $stmt->execute([$id]);
    }
}
?>
