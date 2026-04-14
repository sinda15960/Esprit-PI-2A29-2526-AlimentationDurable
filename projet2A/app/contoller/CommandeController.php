<?php
class CommandeController {
    private Commande $model;
    private Produit $produitModel;

    public function __construct() {
        $this->model        = new Commande();
        $this->produitModel = new Produit();
    }

    public function panier(): void {
        $panier = $_SESSION['panier'] ?? [];
        $total  = array_sum(array_map(fn($i) => $i['prix'] * $i['quantite'], $panier));
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
        header('Location: /frigo/index.php?controller=commande&action=panier');
        exit;
    }

    public function retirerPanier(): void {
        $id = $_GET['id'] ?? null;
        if ($id !== null && isset($_SESSION['panier'][$id])) {
            unset($_SESSION['panier'][$id]);
        }
        header('Location: /frigo/index.php?controller=commande&action=panier');
        exit;
    }

    public function ajouterPanier(): void {
        $id  = (int)($_POST['produit_id'] ?? 0);
        $qte = (int)($_POST['quantite']   ?? 1);
        $produit = $this->produitModel->getById($id);
        if (!$produit || $qte < 1) {
            header('Location: /frigo/index.php?controller=categorie&action=index');
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
        header('Location: /frigo/index.php?controller=commande&action=panier');
        exit;
    }

    public function checkout(): void {
        $historique = $this->model->getHistorique();
        require 'app/view/commandes/checkout.php';
    }

    public function confirmer(): void {
        $errors = $this->model->valider($_POST);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /frigo/index.php?controller=commande&action=checkout');
            exit;
        }
        $panier = $_SESSION['panier'];
        $total  = array_sum(array_map(fn($i) => $i['prix'] * $i['quantite'], $panier));
        $tel    = preg_replace('/\s+/', '', $_POST['telephone']);

        $commandeId = $this->model->create([
            ':nom_client'       => htmlspecialchars(trim($_POST['nom_client'])),
            ':telephone'        => $tel,
            ':adresse'          => htmlspecialchars(trim($_POST['adresse'])),
            ':methode_paiement' => $_POST['methode_paiement'],
            ':total'            => $total
        ]);

        foreach ($panier as $id => $item) {
            if (is_numeric($id)) {
                $this->model->addProduit($commandeId, (int)$id, $item['quantite'], $item['prix']);
            }
        }

        unset($_SESSION['panier']);
        $_SESSION['success'] = "Commande #$commandeId confirmée ! Merci.";
        header('Location: /frigo/index.php?controller=commande&action=checkout');
        exit;
    }

    public function annuler(): void {
        unset($_SESSION['panier']);
        header('Location: /frigo/index.php?controller=categorie&action=index');
        exit;
    }

    public function index(): void {
        $historique = $this->model->getHistorique();
        require 'app/view/commandes/admin.php';
    }
public function updateCommande(): void {
    $id = (int)($_POST['id'] ?? 0);
    $tel = preg_replace('/\s+/', '', $_POST['telephone'] ?? '');
    if (!preg_match('/^\d{8}$/', $tel)) {
        $_SESSION['errors'] = ["Téléphone invalide (8 chiffres)."];
        header('Location: /frigo/index.php?controller=commande&action=index');
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
    $_SESSION['success'] = "Commande modifiée avec succès.";
    header('Location: /frigo/index.php?controller=commande&action=index');
    exit;
}

public function deleteCommande(): void {
    $id = (int)($_GET['id'] ?? 0);
    $this->model->deleteCommande($id);
    header('Location: /frigo/index.php?controller=commande&action=index');
    exit;
}
public function modifierQuantiteFrigo(): void {
    $id  = (int)($_POST['frigo_id'] ?? 0);
    $qte = (int)($_POST['quantite'] ?? 1);

    // Validation
    if ($qte < 0) {
        $_SESSION['errors'] = ["La quantité ne peut pas être négative."];
        header('Location: /frigo/index.php?controller=produit&action=frigo');
        exit;
    }

    // Mettre à jour la quantité
    $this->frigoModel->modifierQuantite($id, $qte);

    // Vérifier si quantité <= seuil → ajouter au panier automatiquement
    $item = $this->frigoModel->verifierSeuil($id);
    if ($item) {
        $pid = $item['produit_id'] ?? 'custom_' . $id;
        if (!isset($_SESSION['panier'][$pid])) {
            $_SESSION['panier'][$pid] = [
                'nom'      => $item['nom'],
                'prix'     => $item['prix'] ?? 0,
                'quantite' => 1
            ];
            $_SESSION['success'] = "
                Quantité faible pour {$item['nom']} — 
                ajouté automatiquement au panier !
            ";
        }
    } else {
        $_SESSION['success'] = "Quantité mise à jour.";
    }

    header('Location: /frigo/index.php?controller=produit&action=frigo');
    exit;
}
}