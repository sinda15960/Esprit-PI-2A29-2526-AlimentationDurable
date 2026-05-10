<?php
class Produit {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("
            SELECT p.*, c.nom AS categorie_nom,
            CASE
                WHEN p.date_expiration < CURDATE() THEN 'perime'
                WHEN p.date_expiration <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) THEN 'bientot_perime'
                ELSE 'frais'
            END AS etat
            FROM frigo_produit p
            LEFT JOIN frigo_categorie c ON p.categorie_id = c.id
            ORDER BY p.date_expiration ASC
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM frigo_produit WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO frigo_produit (nom, description, prix, quantite, date_expiration, categorie_id, image)
            VALUES (:nom, :description, :prix, :quantite, :date_expiration, :categorie_id, :image)
        ");
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool {
        $data[':id'] = $id;
        $stmt = $this->pdo->prepare("
            UPDATE frigo_produit SET nom=:nom, description=:description, prix=:prix,
            quantite=:quantite, date_expiration=:date_expiration,
            categorie_id=:categorie_id, image=:image
            WHERE id=:id
        ");
        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM frigo_produit WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function ajouterAuFrigo(int $id, int $qte): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE frigo_produit SET quantite = quantite + :qte WHERE id = :id"
        );
        return $stmt->execute([':qte' => $qte, ':id' => $id]);
    }
}