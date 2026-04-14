<?php
require_once __DIR__ . '/../Model/Traitement.php';
require_once __DIR__ . '/../Model/Allergie.php';

class TraitementController {
    
    /**
     * Récupérer tous les traitements
     */
    public function getAllTraitements() {
        $traitements = Traitement::findAll();
        $result = [];
        foreach ($traitements as $traitement) {
            $result[] = $traitement->toArray();
        }
        return $result;
    }
    
    /**
     * Récupérer un traitement par ID d'allergie
     */
    public function getTraitementByAllergieId($allergie_id) {
        $traitement = Traitement::findByAllergieId($allergie_id);
        if ($traitement) {
            return $traitement->toArray();
        }
        return null;
    }
    
    /**
     * NOUVELLE MÉTHODE : Afficher les traitements par allergie (jointure)
     * Pour le formulaire de recherche
     */
    public function afficherTraitementsParAllergie($idAllergie) {
        $db = Database::getInstance()->getConnection();
        
        // Requête avec jointure pour récupérer les traitements avec le nom de l'allergie
        $sql = "SELECT t.*, a.nom as allergie_nom, a.categorie, a.gravite as allergie_gravite
                FROM traitements t 
                JOIN allergies a ON t.allergie_id = a.id 
                WHERE a.id = :id";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $idAllergie]);
        return $stmt->fetchAll();
    }
    
    /**
     * NOUVELLE MÉTHODE : Récupérer tous les traitements avec détails de l'allergie
     * Pour affichage avec jointure complète
     */
    public function getAllTraitementsWithAllergies() {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT t.*, a.nom as allergie_nom, a.categorie, a.description as allergie_description, a.gravite as allergie_gravite
                FROM traitements t 
                JOIN allergies a ON t.allergie_id = a.id 
                ORDER BY a.nom";
        
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * NOUVELLE MÉTHODE : Rechercher des traitements par catégorie d'allergie
     */
    public function getTraitementsByCategorie($categorie) {
        return Traitement::findByCategorie($categorie);
    }
    
    /**
     * Ajouter un traitement
     */
    public function addTraitement($data) {
        // Vérifier si l'allergie existe
        $allergie = Allergie::findById($data['allergie_id']);
        if (!$allergie) {
            return ['success' => false, 'message' => 'Allergie non trouvée'];
        }
        
        $traitement = new Traitement(
            $data['allergie_id'],
            $data['conseil'],
            $data['interdits'],
            $data['medicaments'] ?? null,
            $data['duree'] ?? null,
            $data['niveau_urgence']
        );
        
        if ($traitement->save()) {
            return ['success' => true, 'id' => $traitement->getId(), 'message' => 'Traitement ajouté avec succès'];
        }
        return ['success' => false, 'message' => 'Erreur lors de l\'ajout'];
    }
    
    /**
     * Ajouter un traitement par nom d'allergie (pour BackOffice)
     */
    public function addTraitementByAllergieNom($data) {
        // Chercher l'allergie par son nom
        $allergie = Allergie::findByNom($data['allergie_nom']);
        if (!$allergie) {
            return ['success' => false, 'message' => 'Allergie non trouvée: ' . $data['allergie_nom']];
        }
        
        $traitement = new Traitement(
            $allergie->getId(),
            $data['conseil'],
            $data['interdits'],
            $data['medicaments'] ?? null,
            $data['duree'] ?? null,
            $data['niveau_urgence']
        );
        
        if ($traitement->save()) {
            return ['success' => true, 'id' => $traitement->getId(), 'message' => 'Traitement ajouté avec succès'];
        }
        return ['success' => false, 'message' => 'Erreur lors de l\'ajout'];
    }
    
    /**
     * Modifier un traitement
     */
    public function updateTraitement($id, $data) {
        $traitement = Traitement::findById($id);
        if (!$traitement) {
            return ['success' => false, 'message' => 'Traitement non trouvé'];
        }
        
        $traitement->setConseil($data['conseil']);
        $traitement->setInterdits($data['interdits']);
        $traitement->setMedicaments($data['medicaments'] ?? null);
        $traitement->setDuree($data['duree'] ?? null);
        $traitement->setNiveauUrgence($data['niveau_urgence']);
        
        if ($traitement->save()) {
            return ['success' => true, 'message' => 'Traitement modifié avec succès'];
        }
        return ['success' => false, 'message' => 'Erreur lors de la modification'];
    }
    
    /**
     * Supprimer un traitement
     */
    public function deleteTraitement($id) {
        $traitement = Traitement::findById($id);
        if (!$traitement) {
            return ['success' => false, 'message' => 'Traitement non trouvé'];
        }
        
        if ($traitement->delete()) {
            return ['success' => true, 'message' => 'Traitement supprimé avec succès'];
        }
        return ['success' => false, 'message' => 'Erreur lors de la suppression'];
    }
    
    /**
     * Vérifier si un traitement existe pour une allergie
     */
    public function traitementExiste($allergie_id) {
        $traitement = Traitement::findByAllergieId($allergie_id);
        return $traitement !== null;
    }
    
    /**
     * Compter le nombre total de traitements
     */
    public function countTraitements() {
        $traitements = $this->getAllTraitements();
        return count($traitements);
    }
}
?>