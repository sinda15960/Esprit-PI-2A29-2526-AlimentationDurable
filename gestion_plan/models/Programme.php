<?php
require_once __DIR__ . '/../config.php';

class Programme {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    public function getAll() {
        $sql = "SELECT p.*, o.titre AS objectif_titre 
                FROM programme p 
                LEFT JOIN objectif o ON p.objectif_id = o.id 
                ORDER BY p.date_creation DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    // NOUVELLE METHODE : Récupérer les programmes par objectif
    public function getByObjectif($objectif_id) {
        $sql = "SELECT p.*, o.titre AS objectif_titre 
                FROM programme p 
                LEFT JOIN objectif o ON p.objectif_id = o.id 
                WHERE p.objectif_id = ?
                ORDER BY p.date_creation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$objectif_id]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT p.*, o.titre AS objectif_titre 
                FROM programme p 
                LEFT JOIN objectif o ON p.objectif_id = o.id 
                WHERE p.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO programme (nom, description, duree_semaines, niveau, objectif_id)
                VALUES (:nom, :description, :duree_semaines, :niveau, :objectif_id)";
        return $this->pdo->prepare($sql)->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE programme SET nom=:nom, description=:description,
                duree_semaines=:duree_semaines, niveau=:niveau, objectif_id=:objectif_id
                WHERE id=:id";
        $data['id'] = $id;
        return $this->pdo->prepare($sql)->execute($data);
    }

    public function delete($id) {
        return $this->pdo->prepare("DELETE FROM programme WHERE id = ?")->execute([$id]);
    }
}