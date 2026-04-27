<?php
class FavoriController {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function ajouter(): void {
        $id = (int)($_GET['produit_id'] ?? 0);
        $redirect = $_GET['redirect'] ?? 'categorie&action=index';
        
        if ($id > 0) {
            $stmt = $this->pdo->prepare(
                "SELECT COUNT(*) FROM favori WHERE produit_id = :id"
            );
            $stmt->execute([':id' => $id]);
            if ($stmt->fetchColumn() == 0) {
                $stmt = $this->pdo->prepare(
                    "INSERT INTO favori (produit_id) VALUES (:id)"
                );
                $stmt->execute([':id' => $id]);
                $_SESSION['success'] = "Produit ajouté aux favoris !";
            } else {
                $_SESSION['errors'] = ["Ce produit est déjà dans vos favoris."];
            }
        }
        header("Location: /frigo/index.php?mode=front&controller=$redirect");
        exit;
    }

    public function supprimer(): void {
        $id = (int)($_GET['produit_id'] ?? 0);
        $redirect = $_GET['redirect'] ?? 'categorie&action=index';
        
        if ($id > 0) {
            $stmt = $this->pdo->prepare(
                "DELETE FROM favori WHERE produit_id = :id"
            );
            $stmt->execute([':id' => $id]);
            $_SESSION['success'] = "Retiré des favoris.";
        }
        header("Location: /frigo/index.php?mode=front&controller=$redirect");
        exit;
    }

    public function ajouterAuPanier(): void {
        $id   = (int)($_GET['produit_id'] ?? 0);
        $stmt = $this->pdo->prepare("SELECT * FROM produit WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $produit = $stmt->fetch();

        if ($produit) {
            if (!isset($_SESSION['panier'][$id])) {
                $_SESSION['panier'][$id] = [
                    'nom'      => $produit['nom'],
                    'prix'     => $produit['prix'],
                    'quantite' => 1
                ];
            } else {
                $_SESSION['panier'][$id]['quantite']++;
            }
            $_SESSION['success'] = "{$produit['nom']} ajouté au panier !";
        }
        header('Location: /frigo/index.php?mode=front&controller=categorie&action=index');
        exit;
    }
}