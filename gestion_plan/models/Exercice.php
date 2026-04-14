<?php
require_once __DIR__ . '/../config.php';

class Exercice {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    // Tous les exercices (pour admin)
    public function getAll() {
        $sql = "SELECT e.*, p.nom AS programme_nom 
                FROM exercice e 
                LEFT JOIN programme p ON e.programme_id = p.id
                ORDER BY e.programme_id, e.ordre";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Exercices par programme officiel
    public function getByProgramme($programme_id) {
        $sql = "SELECT e.*, p.nom AS programme_nom 
                FROM exercice e
                LEFT JOIN programme p ON e.programme_id = p.id
                WHERE e.programme_id = ?
                ORDER BY e.ordre";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$programme_id]);
        return $stmt->fetchAll();
    }

    // Tous les exercices (pour affichage front)
    public function getAllForAjout() {
        $sql = "SELECT e.*, p.nom AS programme_nom 
                FROM exercice e
                LEFT JOIN programme p ON e.programme_id = p.id
                ORDER BY p.nom, e.ordre";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT e.*, p.nom AS programme_nom 
                FROM exercice e 
                LEFT JOIN programme p ON e.programme_id = p.id 
                WHERE e.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ─── BACK OFFICE ONLY ─────────────────────────────────
    public function create($data) {
        $sql = "INSERT INTO exercice 
                    (nom, description, ordre, duree_minutes, video_url, programme_id, statut)
                VALUES 
                    (:nom, :description, :ordre, :duree_minutes, :video_url, :programme_id, 'en_attente')";
        return $this->pdo->prepare($sql)->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE exercice SET nom=:nom, description=:description,
                ordre=:ordre, duree_minutes=:duree_minutes,
                video_url=:video_url, statut=:statut, programme_id=:programme_id
                WHERE id=:id";
        $data['id'] = $id;
        return $this->pdo->prepare($sql)->execute($data);
    }

    public function delete($id) {
        return $this->pdo->prepare("DELETE FROM exercice WHERE id = ?")->execute([$id]);
    }
}
?>