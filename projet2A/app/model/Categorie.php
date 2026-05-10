<?php
class Categorie {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function getAll(): array {
        return $this->pdo->query("SELECT * FROM frigo_categorie ORDER BY nom ASC")->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM frigo_categorie WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getProduitsParCategorie(int $id): array {
        $stmt = $this->pdo->prepare("
            SELECT * FROM frigo_produit WHERE categorie_id = :id ORDER BY nom ASC
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO frigo_categorie (nom, description, image) 
            VALUES (:nom, :description, :image)
        ");
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool {
        $data[':id'] = $id;
        $stmt = $this->pdo->prepare("
            UPDATE frigo_categorie SET nom=:nom, description=:description, image=:image 
            WHERE id=:id
        ");
        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM frigo_categorie WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}