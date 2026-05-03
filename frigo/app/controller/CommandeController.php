<?php
class CommandeController {
    private Commande $model;
    private PDO $pdo;

    public function __construct() {
        $this->model = new Commande();
        $this->pdo   = Database::getInstance()->getPdo();
    }

    // ========== CODE PROMO ==========

    private function verifierCodePromo(string $code): array|false {
        $stmt = $this->pdo->prepare("
            SELECT * FROM code_promo
            WHERE code = :code
            AND actif = 1
            AND (date_expiration IS NULL OR date_expiration >= CURDATE())
        ");
        $stmt->execute([':code' => strtoupper(trim($code))]);
        return $stmt->fetch();
    }

    private function calculerTotal(array $panier, ?array $promo): float {
        $total = array_sum(
            array_map(fn($i) => $i['prix'] * $i['quantite'], $panier)
        );
        if ($promo) {
            if ($promo['type_reduction'] === 'pourcentage') {
                $total = $total - ($total * $promo['reduction'] / 100);
            } else {
                $total = max(0, $total - $promo['reduction']);
            }
        }
        return round($total, 2);
    }

    public function appliquerPromo(): void {
        $code = trim($_POST['code_promo'] ?? '');

        if (strlen($code) < 3) {
            $_SESSION['errors'] = ["Le code promo doit contenir au moins 3 caractères."];
            header('Location: /frigo/index.php?mode=front&controller=commande&action=panier');
            exit;
        }

        $promo = $this->verifierCodePromo($code);
        if ($promo) {
            $_SESSION['promo'] = $promo;
            $message = "Code promo appliqué : " . ($promo['type_reduction'] === 'pourcentage'
                ? "-{$promo['reduction']}% sur le total"
                : "-" . number_format($promo['reduction'], 2) . " TND");
            $_SESSION['success'] = $message;
        } else {
            $_SESSION['errors'] = ["Code promo invalide ou expiré."];
        }
        header('Location: /frigo/index.php?mode=front&controller=commande&action=panier');
        exit;
    }

    public function supprimerPromo(): void {
        unset($_SESSION['promo']);
        header('Location: /frigo/index.php?mode=front&controller=commande&action=panier');
        exit;
    }

    // ========== CODE PROMO DYNAMIQUE (IDÉE 6) ==========

    private function verifierCodePromoAvance(string $code, string $telephone, float $totalPanier): array|false {
        $stmt = $this->pdo->prepare("
            SELECT * FROM code_promo
            WHERE code = :code
            AND actif = 1
            AND (utilisation_max > utilisation_compteur OR utilisation_max = 0)
            AND (date_expiration IS NULL OR date_expiration >= CURDATE())
        ");
        $stmt->execute([':code' => strtoupper(trim($code))]);
        $promo = $stmt->fetch();
        
        if (!$promo) return false;
        
        // Vérification client unique (un seul usage par téléphone)
        if ($promo['client_unique'] == 1 && !empty($telephone)) {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM code_promo_utilisation
                WHERE code_promo_id = :id AND telephone_client = :tel
            ");
            $stmt->execute([':id' => $promo['id'], ':tel' => $telephone]);
            if ($stmt->fetchColumn() > 0) {
                return false;
            }
        }
        
        return $promo;
    }

    public function genererCodeBienvenue(): void {
        $telephone = $_POST['telephone'] ?? '';
        if (empty($telephone)) {
            $_SESSION['errors'] = ["Téléphone requis pour le code de bienvenue."];
            header('Location: /frigo/index.php?mode=front&controller=categorie&action=index');
            exit;
        }
        
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM commande WHERE telephone = :tel");
        $stmt->execute([':tel' => $telephone]);
        $nbCommandes = $stmt->fetchColumn();
        
        if ($nbCommandes == 0) {
            $code = 'BIENVENUE_' . substr(md5($telephone . time()), 0, 6);
            $_SESSION['promo_personnalise'] = $code;
            $_SESSION['success'] = "Nouveau client ! Utilisez le code: $code pour -10% sur votre première commande.";
        }
        header('Location: /frigo/index.php?mode=front&controller=commande&action=panier');
        exit;
    }

    // ========== PANIER ==========

    public function panier(): void {
        $panier = $_SESSION['panier'] ?? [];
        $promo  = $_SESSION['promo'] ?? null;
        $total  = array_sum(
            array_map(fn($i) => $i['prix'] * $i['quantite'], $panier)
        );
        
        // Suggestions de produits complémentaires pour le panier
        $suggestionsComplementaires = [];
        if (!empty($panier)) {
            $idsProduits = array_keys($panier);
            // Nettoyer les IDs (enlever les 'custom_')
            $idsProduits = array_filter($idsProduits, 'is_numeric');
            if (!empty($idsProduits)) {
                $placeholders = implode(',', array_fill(0, count($idsProduits), '?'));
                $stmt = $this->pdo->prepare("
                    SELECT id, nom, prix FROM produit 
                    WHERE id NOT IN ($placeholders)
                    ORDER BY RAND()
                    LIMIT 4
                ");
                $stmt->execute($idsProduits);
                $suggestionsComplementaires = $stmt->fetchAll();
            }
        }
        
        require 'app/view/commandes/panier.php';
    }

    public function modifierPanier(): void {
        $quantites = $_POST['quantites'] ?? [];
        foreach ($quantites as $id => $qte) {
            $qte = (int)$qte;
            if ($qte >= 1 && isset($_SESSION['panier'][$id])) {
                $_SESSION['panier'][$id]['quantite'] = $qte;
            }
        }
        $_SESSION['success'] = "Panier mis à jour !";
        header('Location: /frigo/index.php?mode=front&controller=commande&action=panier');
        exit;
    }

    public function retirerPanier(): void {
        $id = $_GET['id'] ?? null;
        if ($id !== null && isset($_SESSION['panier'][$id])) {
            unset($_SESSION['panier'][$id]);
        }
        header('Location: /frigo/index.php?mode=front&controller=commande&action=panier');
        exit;
    }

    public function ajouterPanier(): void {
        $id      = (int)($_POST['produit_id'] ?? 0);
        $qte     = (int)($_POST['quantite']   ?? 1);
        $stmt    = $this->pdo->prepare("SELECT * FROM produit WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $produit = $stmt->fetch();

        if (!$produit || $qte < 1) {
            header('Location: /frigo/index.php?mode=front&controller=categorie&action=index');
            exit;
        }
        if (!isset($_SESSION['panier'][$id])) {
            $_SESSION['panier'][$id] = [
                'nom'      => $produit['nom'],
                'prix'     => $produit['prix'],
                'quantite' => $qte
            ];
        } else {
            $_SESSION['panier'][$id]['quantite'] += $qte;
        }
        header('Location: /frigo/index.php?mode=front&controller=commande&action=panier');
        exit;
    }

    public function checkout(): void {
        $historique = $this->model->getHistorique();
        $total = array_sum(
            array_map(fn($i) => $i['prix'] * $i['quantite'], $_SESSION['panier'] ?? [])
        );
        require 'app/view/commandes/checkout.php';
    }

    public function confirmer(): void {
        $errors = $this->model->valider($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /frigo/index.php?mode=front&controller=commande&action=checkout');
            exit;
        }

        $panier = $_SESSION['panier'] ?? [];
        $promo  = $_SESSION['promo'] ?? null;
        $total  = $this->calculerTotal($panier, $promo);
        $tel    = preg_replace('/\s+/', '', $_POST['telephone']);
        
        // Sauvegarder le téléphone en session pour les suggestions futures
        $_SESSION['telephone_client'] = $tel;

        $commandeId = $this->model->create([
            ':nom_client'       => htmlspecialchars(trim($_POST['nom_client'])),
            ':telephone'        => $tel,
            ':adresse'          => htmlspecialchars(trim($_POST['adresse'])),
            ':methode_paiement' => $_POST['methode_paiement'],
            ':total'            => $total
        ]);

        foreach ($panier as $id => $item) {
            if (is_numeric($id)) {
                $this->model->addProduit(
                    $commandeId, (int)$id, $item['quantite'], $item['prix']
                );
            }
        }

        // Log de l'utilisation du code promo
        if ($promo && isset($promo['id'])) {
            $stmt = $this->pdo->prepare("
                INSERT INTO code_promo_utilisation (code_promo_id, commande_id, telephone_client, reduction_appliquee)
                VALUES (:promo_id, :commande_id, :tel, :reduction)
            ");
            $reductionCalculee = $total - array_sum(
                array_map(fn($i) => $i['prix'] * $i['quantite'], $panier)
            );
            $stmt->execute([
                ':promo_id' => $promo['id'],
                ':commande_id' => $commandeId,
                ':tel' => $tel,
                ':reduction' => abs($reductionCalculee)
            ]);
            
            // Incrémenter le compteur d'utilisation
            $stmt = $this->pdo->prepare("
                UPDATE code_promo SET utilisation_compteur = utilisation_compteur + 1 WHERE id = :id
            ");
            $stmt->execute([':id' => $promo['id']]);
        }

        unset($_SESSION['panier']);
        unset($_SESSION['promo']);
        $_SESSION['success'] = "Commande #$commandeId confirmée ! Total : " .
            number_format($total, 2) . " TND";
        header('Location: /frigo/index.php?mode=front&controller=commande&action=checkout');
        exit;
    }

    public function annuler(): void {
        unset($_SESSION['panier']);
        header('Location: /frigo/index.php?mode=front&controller=categorie&action=index');
        exit;
    }

    public function index(): void {
        $historique = $this->model->getHistorique();
        $codesPromo = $this->model->getCodesPromo();
        require 'app/view/commandes/admin.php';
    }

    public function updateCommande(): void {
        $id  = (int)($_POST['id'] ?? 0);
        $tel = preg_replace('/\s+/', '', $_POST['telephone'] ?? '');
        if (!preg_match('/^\d{8}$/', $tel)) {
            $_SESSION['errors'] = ["Téléphone invalide (8 chiffres)."];
            header('Location: /frigo/index.php?mode=back&controller=commande&action=index');
            exit;
        }
        $this->model->updateCommande($id, [
            ':nom_client'       => htmlspecialchars(trim($_POST['nom_client'])),
            ':telephone'        => $tel,
            ':adresse'          => htmlspecialchars(trim($_POST['adresse'])),
            ':methode_paiement' => $_POST['methode_paiement'],
            ':total'            => (float)$_POST['total'],
            ':statut'           => $_POST['statut'],
        ]);
        $_SESSION['success'] = "Commande modifiée.";
        header('Location: /frigo/index.php?mode=back&controller=commande&action=index');
        exit;
    }

    public function deleteCommande(): void {
        $id = (int)($_GET['id'] ?? 0);
        $this->model->deleteCommande($id);
        header('Location: /frigo/index.php?mode=back&controller=commande&action=index');
        exit;
    }

    public function ajouterPromo(): void {
        $code = strtoupper(trim($_POST['code'] ?? ''));
        $red  = (float)($_POST['reduction'] ?? 0);
        $type = $_POST['type_reduction'] ?? 'pourcentage';
        $date = trim($_POST['date_expiration'] ?? '');

        $errors = [];
        if (strlen($code) < 3) $errors[] = "Code trop court (min 3 caractères).";
        if (!preg_match('/^[A-Z0-9]+$/', $code)) $errors[] = "Code invalide (lettres et chiffres uniquement).";
        if ($red <= 0) $errors[] = "La réduction doit être positive.";
        if ($type === 'pourcentage' && $red > 100) $errors[] = "Le pourcentage ne peut pas dépasser 100%.";
        if (!empty($date)) {
            $d = DateTime::createFromFormat('Y-m-d', $date);
            if (!$d) $errors[] = "Format de date invalide.";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
        } else {
            try {
                $stmt = $this->pdo->prepare("
                    INSERT INTO code_promo (code, reduction, type_reduction, actif, date_expiration, utilisation_max, client_unique)
                    VALUES (:code, :reduction, :type, 1, :date, :util_max, :client_unique)
                ");
                $stmt->execute([
                    ':code'      => $code,
                    ':reduction' => $red,
                    ':type'      => $type,
                    ':date'      => $date ?: null,
                    ':util_max'  => (int)($_POST['utilisation_max'] ?? 0),
                    ':client_unique' => isset($_POST['client_unique']) ? 1 : 0,
                ]);
                $_SESSION['success'] = "Code promo ajouté !";
            } catch (Exception $e) {
                $_SESSION['errors'] = ["Ce code existe déjà."];
            }
        }
        header('Location: /frigo/index.php?mode=back&controller=commande&action=index');
        exit;
    }

    public function togglePromo(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare("
            UPDATE code_promo SET actif = 1 - actif WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
        header('Location: /frigo/index.php?mode=back&controller=commande&action=index');
        exit;
    }

    public function supprimerCodePromo(): void {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->pdo->prepare("DELETE FROM code_promo WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header('Location: /frigo/index.php?mode=back&controller=commande&action=index');
        exit;
    }
}
?>