<?php
class Categorie {
    private $conn;
    private $table = "categories";

    // Propriétés
    private $idCategorie;
    private $nom;
    private $description;
    private $icon;
    private $couleur;
    private $created_at;

    // Constructeur
    public function __construct($db) {
        $this->conn = $db;
    }

    // Destructeur
    public function __destruct() {
        $this->conn = null;
    }

    // ==================== GETTERS ====================
    public function getIdCategorie() { return $this->idCategorie; }
    public function getNom() { return $this->nom; }
    public function getDescription() { return $this->description; }
    public function getIcon() { return $this->icon; }
    public function getCouleur() { return $this->couleur; }
    public function getCreatedAt() { return $this->created_at; }
    public function getTable() { return $this->table; }
    public function getConnection() { return $this->conn; }

    // ==================== SETTERS ====================
    public function setIdCategorie($idCategorie) { $this->idCategorie = $idCategorie; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setDescription($description) { $this->description = $description; }
    public function setIcon($icon) { $this->icon = $icon; }
    public function setCouleur($couleur) { $this->couleur = $couleur; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
}
?>