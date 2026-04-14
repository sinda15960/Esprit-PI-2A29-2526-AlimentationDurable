<?php
require_once __DIR__ . '/../config.php';

class Objectif {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    public function getAll() {
        $sql = "SELECT * FROM objectif WHERE is_personal = 0 ORDER BY date_creation DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getPersonalByUser($user_id) {
        $sql = "SELECT * FROM objectif WHERE is_personal = 1 AND user_id = ? ORDER BY date_creation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM objectif WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO objectif (titre, type_objectif, description, maladies, preferences, calories_min, calories_max, is_personal)
                VALUES (:titre, :type_objectif, :description, :maladies, :preferences, :calories_min, :calories_max, 0)";
        return $this->pdo->prepare($sql)->execute([
            ':titre'         => $data['titre'],
            ':type_objectif' => $data['type_objectif'],
            ':description'   => $data['description'],
            ':maladies'      => $data['maladies'],
            ':preferences'   => $data['preferences'],
            ':calories_min'  => $data['calories_min'],
            ':calories_max'  => $data['calories_max'],
        ]);
    }

    public function createPersonal($data) {
        $sql = "INSERT INTO objectif (titre, description, poids_actuel, poids_cible, taille, age, 
                                      etat_sante, date_debut, date_fin_prevue, user_id, is_personal)
                VALUES (:titre, :description, :poids_actuel, :poids_cible, :taille, :age, 
                        :etat_sante, :date_debut, :date_fin_prevue, :user_id, 1)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':titre'           => $data['titre'],
            ':description'     => $data['description'],
            ':poids_actuel'    => $data['poids_actuel'],
            ':poids_cible'     => $data['poids_cible'],
            ':taille'          => $data['taille'],
            ':age'             => $data['age'],
            ':etat_sante'      => $data['etat_sante'],
            ':date_debut'      => $data['date_debut'],
            ':date_fin_prevue' => $data['date_fin_prevue'],
            ':user_id'         => $data['user_id'],
        ]);
    }

    public function updatePersonal($id, $data) {
        $sql = "UPDATE objectif SET 
                    titre = :titre, 
                    description = :description,
                    poids_actuel = :poids_actuel,
                    poids_cible = :poids_cible,
                    taille = :taille,
                    age = :age,
                    etat_sante = :etat_sante,
                    date_debut = :date_debut,
                    date_fin_prevue = :date_fin_prevue
                WHERE id = :id AND user_id = :user_id AND is_personal = 1";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':titre'           => $data['titre'],
            ':description'     => $data['description'],
            ':poids_actuel'    => $data['poids_actuel'],
            ':poids_cible'     => $data['poids_cible'],
            ':taille'          => $data['taille'],
            ':age'             => $data['age'],
            ':etat_sante'      => $data['etat_sante'],
            ':date_debut'      => $data['date_debut'],
            ':date_fin_prevue' => $data['date_fin_prevue'],
            ':id'              => $id,
            ':user_id'         => $data['user_id'],
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE objectif SET 
                    titre = :titre, 
                    type_objectif = :type_objectif,
                    description = :description, 
                    maladies = :maladies,
                    preferences = :preferences, 
                    calories_min = :calories_min, 
                    calories_max = :calories_max
                WHERE id = :id AND is_personal = 0";
        return $this->pdo->prepare($sql)->execute([
            ':titre'         => $data['titre'],
            ':type_objectif' => $data['type_objectif'],
            ':description'   => $data['description'],
            ':maladies'      => $data['maladies'],
            ':preferences'   => $data['preferences'],
            ':calories_min'  => $data['calories_min'],
            ':calories_max'  => $data['calories_max'],
            ':id'            => $id,
        ]);
    }

    public function delete($id) {
        return $this->pdo->prepare("DELETE FROM objectif WHERE id = ?")->execute([$id]);
    }

    public function deletePersonal($id, $user_id) {
        $sql = "DELETE FROM objectif WHERE id = ? AND user_id = ? AND is_personal = 1";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id, $user_id]);
    }
}
?>