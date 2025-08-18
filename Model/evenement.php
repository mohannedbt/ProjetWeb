<?php
class evenement
{
    private $id;
    private $titre;
    private $description;
    private $date_event;
    private $lieu;
    private $organisateur_id;
    private $image;
    private $date_creation;

    // Getters
    public function getId() { return $this->id; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getDateEvent() { return $this->date_event; }
    public function getLieu() { return $this->lieu; }
    public function getOrganisateurId() { return $this->organisateur_id; }
    public function getImage() { return $this->image; }
    public function getDateCreation() { return $this->date_creation; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setTitre($titre) { $this->titre = $titre; }
    public function setDescription($description) { $this->description = $description; }
    public function setDateEvent($date_event) { $this->date_event = $date_event; }
    public function setLieu($lieu) { $this->lieu = $lieu; }
    public function setOrganisateurId($organisateur_id) { $this->organisateur_id = $organisateur_id; }
    public function setImage($image) { $this->image = $image; }
    public function setDateCreation($date_creation) { $this->date_creation = $date_creation; }
}
?>
