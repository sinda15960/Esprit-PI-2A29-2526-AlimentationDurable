<?php
class Commande {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO commande (nom_client, telephone, adresse, methode_paiement, total)
            VALUES (:nom_client, :telephone, :adresse, :methode_paiement, :total)
        ");
        $stmt->execute($data);
        return (int)$this->pdo->lastInsertId();
    }

    public function addProduit(int $commandeId, int $produitId, int $qte, float $prix): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO commande_produit (commande_id, produit_id, quantite, prix_unitaire)
            VALUES (:commande_id, :produit_id, :quantite, :prix_unitaire)
        ");
        $stmt->execute([
            ':commande_id'  => $commandeId,
            ':produit_id'   => $produitId,
            ':quantite'     => $qte,
            ':prix_unitaire'=> $prix
        ]);
    }

    public function getHistorique(): array {
        return $this->pdo->query("
            SELECT c.*, GROUP_CONCAT(p.nom SEPARATOR ', ') AS produits_noms
            FROM commande c
            LEFT JOIN commande_produit cp ON c.id = cp.commande_id
            LEFT JOIN produit p ON cp.produit_id = p.id
            GROUP BY c.id
            ORDER BY c.date_commande DESC
            LIMIT 10
        ")->fetchAll();
    }

    public function valider(array $data): array {
        $errors = [];
        if (empty(trim($data['nom_client'] ?? ''))) {
            $errors[] = "Le nom est obligatoire.";
        }
        // Validation téléphone : exactement 8 chiffres
        $tel = preg_replace('/\s+/', '', $data['telephone'] ?? '');
        if (!preg_match('/^\d{8}$/', $tel)) {
            $errors[] = "Le numéro de téléphone doit contenir exactement 8 chiffres.";
        }
        if (empty(trim($data['adresse'] ?? ''))) {
            $errors[] = "L'adresse est obligatoire.";
        }
        $methodes = ['especes', 'carte', 'virement'];
        if (!in_array($data['methode_paiement'] ?? '', $methodes, true)) {
            $errors[] = "Méthode de paiement invalide.";
        }
        if (empty($_SESSION['panier'])) {
            $errors[] = "Votre panier est vide.";
        }
        return $errors;
    }
public function getAll(): array {
    return $this->pdo->query("
        SELECT * FROM commande ORDER BY date_commande DESC
    ")->fetchAll();
}

public function updateCommande(int $id, array $data): bool {
    $data[':id'] = $id;
    $stmt = $this->pdo->prepare("
        UPDATE commande 
        SET nom_client=:nom_client, telephone=:telephone,
            adresse=:adresse, methode_paiement=:methode_paiement,
            total=:total, statut=:statut
        WHERE id=:id
    ");
    return $stmt->execute($data);
}

public function deleteCommande(int $id): bool {
    $stmt = $this->pdo->prepare("DELETE FROM commande WHERE id = :id");
    return $stmt->execute([':id' => $id]);
}
}