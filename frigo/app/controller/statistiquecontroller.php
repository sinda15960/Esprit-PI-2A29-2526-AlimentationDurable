<?php
class StatistiqueController {
    private Statistique $statistique;
    private PDO $pdo;

    public function __construct() {
        $this->statistique = new Statistique();
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function index(): void {
        $statsGlobales      = $this->statistique->getStatsGlobales();
        $caJournalier       = $this->statistique->getCAparJour(30);
        $topProduits        = $this->statistique->getTopProduits(10);
        $ventesParCategorie = $this->statistique->getVentesParCategorie();
        $ventesParPaiement  = $this->statistique->getVentesParPaiement();
        $tauxConversion     = $this->statistique->getTauxConversion();
        $commandesParStatut = $this->statistique->getCommandesParStatut();

        require 'app/view/statistiques/index.php';
    }

    public function exportCSV(): void {
        $type = $_GET['type'] ?? 'ca';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=stats_' . $type . '_' . date('Y-m-d') . '.csv');
        $output = fopen('php://output', 'w');

        if ($type === 'ca') {
            $data = $this->statistique->getCAparJour(90);
            fputcsv($output, ['Date', 'CA (TND)', 'Nb commandes']);
            foreach ($data as $row) {
                fputcsv($output, [$row['jour'], number_format($row['ca'], 2), $row['nb_commandes']]);
            }
        } elseif ($type === 'produits') {
            $data = $this->statistique->getTopProduits(50);
            fputcsv($output, ['Produit', 'Prix', 'Qté vendue', 'Revenu']);
            foreach ($data as $row) {
                fputcsv($output, [$row['nom'], number_format($row['prix'], 2), $row['total_vendu'], number_format($row['revenue'], 2)]);
            }
        } elseif ($type === 'paiement') {
            $data = $this->statistique->getVentesParPaiement();
            fputcsv($output, ['Méthode de paiement', 'Nombre de commandes', 'Total (TND)']);
            foreach ($data as $row) {
                fputcsv($output, [$row['methode_paiement'], $row['nb_commandes'], number_format($row['total'], 2)]);
            }
        }
        fclose($output);
        exit;
    }
}
?>