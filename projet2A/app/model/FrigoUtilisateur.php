<?php
class FrigoUtilisateur {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function getAll(): array {
        return $this->pdo->query("
            SELECT f.*, 
                   COALESCE(p.nom, f.nom_custom) AS nom,
                   p.prix,
                   CASE
                     WHEN f.date_expiration < CURDATE() THEN 'perime'
                     WHEN f.date_expiration <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) THEN 'bientot_perime'
                     ELSE 'frais'
                   END AS etat
            FROM frigo_utilisateur f
            LEFT JOIN frigo_produit p ON f.produit_id = p.id
            ORDER BY f.date_expiration ASC
        ")->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare("
            SELECT f.*, COALESCE(p.nom, f.nom_custom) AS nom, p.prix
            FROM frigo_utilisateur f
            LEFT JOIN frigo_produit p ON f.produit_id = p.id
            WHERE f.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function ajouter(array $data): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO frigo_utilisateur 
            (produit_id, nom_custom, quantite, date_expiration, seuil_alerte)
            VALUES (:produit_id, :nom_custom, :quantite, :date_expiration, :seuil_alerte)
        ");
        return $stmt->execute($data);
    }

    public function modifierQuantite(int $id, int $qte): bool {
        $stmt = $this->pdo->prepare("
            UPDATE frigo_utilisateur SET quantite = :qte WHERE id = :id
        ");
        return $stmt->execute([':qte' => $qte, ':id' => $id]);
    }

    public function verifierSeuil(int $id): array|false {
        $stmt = $this->pdo->prepare("
            SELECT f.*, COALESCE(p.nom, f.nom_custom) AS nom, p.prix
            FROM frigo_utilisateur f
            LEFT JOIN frigo_produit p ON f.produit_id = p.id
            WHERE f.id = :id AND f.quantite <= f.seuil_alerte
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function supprimerDuFrigo(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM frigo_utilisateur WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function ajouterAuPanier(int $id): array|false {
        $stmt = $this->pdo->prepare("
            SELECT f.*, COALESCE(p.nom, f.nom_custom) AS nom, p.prix
            FROM frigo_utilisateur f
            LEFT JOIN frigo_produit p ON f.produit_id = p.id
            WHERE f.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}