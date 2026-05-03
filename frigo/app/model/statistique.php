<?php
class Statistique {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    // Chiffre d'affaires par période
    public function getCAparJour(int $jours = 30): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                DATE(date_commande) as jour,
                SUM(total) as ca,
                COUNT(*) as nb_commandes
            FROM commande
            WHERE statut = 'confirmee'
            AND date_commande >= DATE_SUB(CURDATE(), INTERVAL :jours DAY)
            GROUP BY DATE(date_commande)
            ORDER BY jour ASC
        ");
        $stmt->execute([':jours' => $jours]);
        return $stmt->fetchAll();
    }

    // Top 10 produits les plus vendus
    public function getTopProduits(int $limite = 10): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                p.id, p.nom, p.prix,
                SUM(cp.quantite) as total_vendu,
                SUM(cp.quantite * cp.prix_unitaire) as revenue
            FROM commande_produit cp
            JOIN produit p ON cp.produit_id = p.id
            JOIN commande c ON cp.commande_id = c.id
            WHERE c.statut = 'confirmee'
            GROUP BY p.id
            ORDER BY total_vendu DESC
            LIMIT :limite
        ");
        $stmt->execute([':limite' => $limite]);
        return $stmt->fetchAll();
    }

    // Ventes par catégorie
    public function getVentesParCategorie(): array {
        $stmt = $this->pdo->query("
            SELECT 
                c.nom as categorie,
                COUNT(cp.id) as nb_ventes,
                SUM(cp.quantite * cp.prix_unitaire) as revenue
            FROM commande_produit cp
            JOIN produit p ON cp.produit_id = p.id
            JOIN categorie c ON p.categorie_id = c.id
            JOIN commande cmd ON cp.commande_id = cmd.id
            WHERE cmd.statut = 'confirmee'
            GROUP BY c.id
            ORDER BY revenue DESC
        ");
        return $stmt->fetchAll();
    }

    // Statistiques globales
    public function getStatsGlobales(): array {
        $stmt = $this->pdo->query("
            SELECT 
                (SELECT COUNT(*) FROM commande WHERE statut = 'confirmee') as total_commandes,
                (SELECT SUM(total) FROM commande WHERE statut = 'confirmee') as ca_total,
                (SELECT AVG(total) FROM commande WHERE statut = 'confirmee') as panier_moyen,
                (SELECT COUNT(*) FROM commande WHERE statut = 'en_attente') as commandes_attente,
                (SELECT COUNT(*) FROM produit WHERE quantite < 5) as stocks_faibles,
                (SELECT COUNT(*) FROM produit WHERE date_expiration < CURDATE()) as produits_perimes,
                (SELECT COUNT(*) FROM frigo_utilisateur f 
                 WHERE f.date_expiration <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
                 AND f.date_expiration >= CURDATE()) as alerte_expiration
        ");
        return $stmt->fetch();
    }

    // Ventes par méthode de paiement
    public function getVentesParPaiement(): array {
        $stmt = $this->pdo->query("
            SELECT 
                methode_paiement,
                COUNT(*) as nb_commandes,
                SUM(total) as total
            FROM commande
            WHERE statut = 'confirmee'
            GROUP BY methode_paiement
        ");
        return $stmt->fetchAll();
    }

    // Commandes par heure (pour marketing)
    public function getHeuresAchats(): array {
        $stmt = $this->pdo->query("
            SELECT 
                HOUR(date_commande) as heure,
                COUNT(*) as nb_commandes
            FROM commande
            WHERE statut = 'confirmee'
            GROUP BY HOUR(date_commande)
            ORDER BY heure ASC
        ");
        return $stmt->fetchAll();
    }

    // Taux de conversion (panier -> commande)
    public function getTauxConversion(): float {
        // Estimation : on compte les sessions uniques avec panier non vide
        // Pour simplifier, on utilise les commandes vs toutes les commandes
        $stmt = $this->pdo->query("
            SELECT 
                (SELECT COUNT(*) FROM commande WHERE statut = 'confirmee') as confirmees,
                (SELECT COUNT(*) FROM commande) as total_commandes
        ");
        $result = $stmt->fetch();
        $total = $result['total_commandes'];
        if ($total == 0) return 0;
        return round(($result['confirmees'] / $total) * 100, 2);
    }
}