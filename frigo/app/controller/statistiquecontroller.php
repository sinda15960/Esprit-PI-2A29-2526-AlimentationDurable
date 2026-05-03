<?php
class StatistiqueController {
    private Statistique $statistique;
    private PDO $pdo;

    public function __construct() {
        $this->statistique = new Statistique();
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function index(): void {
        // Vérifier qu'on est en mode backoffice
        $mode = $_GET['mode'] ?? 'front';
        if ($mode !== 'back') {
            header('Location: /frigo/index.php?mode=back&controller=statistique&action=index');
            exit;
        }

        $statsGlobales = $this->statistique->getStatsGlobales();
        $caJournalier = $this->statistique->getCAparJour(30);
        $topProduits = $this->statistique->getTopProduits(10);
        $ventesParCategorie = $this->statistique->getVentesParCategorie();
        $ventesParPaiement = $this->statistique->getVentesParPaiement();
        $heuresAchats = $this->statistique->getHeuresAchats();
        $tauxConversion = $this->statistique->getTauxConversion();

        require 'app/view/statistiques/index.php';
    }

    // Exporter les stats en CSV
    public function exportCSV(): void {
        $type = $_GET['type'] ?? 'ca';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=statistiques_' . $type . '_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        
        if ($type === 'ca') {
            $data = $this->statistique->getCAparJour(90);
            fputcsv($output, ['Date', 'Chiffre d\'affaires', 'Nombre de commandes']);
            foreach ($data as $row) {
                fputcsv($output, [$row['jour'], $row['ca'], $row['nb_commandes']]);
            }
        } elseif ($type === 'produits') {
            $data = $this->statistique->getTopProduits(50);
            fputcsv($output, ['Produit', 'Prix', 'Quantité vendue', 'Revenu']);
            foreach ($data as $row) {
                fputcsv($output, [$row['nom'], $row['prix'], $row['total_vendu'], $row['revenue']]);
            }
        }
        
        fclose($output);
        exit;
    }
}