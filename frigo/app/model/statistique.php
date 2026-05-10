<?php
class Statistique {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function getCAparJour(int $jours = 30): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                DATE(date_commande) as jour,
                COALESCE(SUM(total), 0) as ca,
                COUNT(*) as nb_commandes
            FROM frigo_commande
            WHERE date_commande >= DATE_SUB(CURDATE(), INTERVAL :jours DAY)
            GROUP BY DATE(date_commande)
            ORDER BY jour ASC
        ");
        $stmt->execute([':jours' => $jours]);
        return $stmt->fetchAll();
    }

    public function getTopProduits(int $limite = 10): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                p.id, 
                p.nom, 
                p.prix,
                COALESCE(SUM(cp.quantite), 0) as total_vendu,
                COALESCE(SUM(cp.quantite * cp.prix_unitaire), 0) as revenue
            FROM frigo_produit p
            LEFT JOIN frigo_commande_produit cp ON cp.produit_id = p.id
            LEFT JOIN frigo_commande c ON cp.commande_id = c.id
            GROUP BY p.id
            ORDER BY total_vendu DESC
            LIMIT :limite
        ");
        $stmt->execute([':limite' => $limite]);
        return $stmt->fetchAll();
    }

    public function getVentesParCategorie(): array {
        $stmt = $this->pdo->query("
            SELECT 
                COALESCE(c.nom, 'Sans catégorie') as categorie,
                COUNT(cp.id) as nb_ventes,
                COALESCE(SUM(cp.quantite * cp.prix_unitaire), 0) as revenue
            FROM frigo_commande_produit cp
            JOIN frigo_produit p ON cp.produit_id = p.id
            LEFT JOIN frigo_categorie c ON p.categorie_id = c.id
            GROUP BY c.id
            ORDER BY revenue DESC
        ");
        return $stmt->fetchAll();
    }

    public function getStatsGlobales(): array {
        $stmt = $this->pdo->query("
            SELECT 
                (SELECT COUNT(*) FROM frigo_commande) as total_commandes,
                (SELECT COALESCE(SUM(total), 0) FROM frigo_commande) as ca_total,
                (SELECT COALESCE(AVG(total), 0) FROM frigo_commande) as panier_moyen,
                (SELECT COUNT(*) FROM frigo_commande WHERE statut = 'en_attente') as commandes_attente,
                (SELECT COUNT(*) FROM frigo_commande WHERE statut = 'confirmee') as commandes_confirmees,
                (SELECT COUNT(*) FROM frigo_commande WHERE statut = 'annulee') as commandes_annulees,
                (SELECT COUNT(*) FROM frigo_produit WHERE quantite < 5) as stocks_faibles,
                (SELECT COUNT(*) FROM frigo_produit WHERE date_expiration IS NOT NULL AND date_expiration < CURDATE()) as produits_perimes,
                (SELECT COUNT(*) FROM frigo_utilisateur f 
                 WHERE f.date_expiration IS NOT NULL
                 AND f.date_expiration <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
                 AND f.date_expiration >= CURDATE()) as alerte_expiration
        ");
        return $stmt->fetch();
    }

    public function getVentesParPaiement(): array {
        $stmt = $this->pdo->query("
            SELECT 
                methode_paiement,
                COUNT(*) as nb_commandes,
                COALESCE(SUM(total), 0) as total
            FROM frigo_commande
            GROUP BY methode_paiement
        ");
        return $stmt->fetchAll();
    }

    public function getHeuresAchats(): array {
        $stmt = $this->pdo->query("
            SELECT 
                HOUR(date_commande) as heure,
                COUNT(*) as nb_commandes
            FROM frigo_commande
            GROUP BY HOUR(date_commande)
            ORDER BY heure ASC
        ");
        return $stmt->fetchAll();
    }

    public function getTauxConversion(): float {
        $stmt = $this->pdo->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN statut = 'confirmee' THEN 1 ELSE 0 END) as confirmees
            FROM frigo_commande
        ");
        $result = $stmt->fetch();
        $total = $result['total'];
        if ($total == 0) return 0;
        return round(($result['confirmees'] / $total) * 100, 2);
    }

    // Nouvelle méthode pour les commandes par statut
    public function getCommandesParStatut(): array {
        $stmt = $this->pdo->query("
            SELECT 
                statut,
                COUNT(*) as nb_commandes,
                COALESCE(SUM(total), 0) as total
            FROM frigo_commande
            GROUP BY statut
        ");
        return $stmt->fetchAll();
    }
}
?>