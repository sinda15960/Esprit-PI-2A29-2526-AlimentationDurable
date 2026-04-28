<?php
require_once __DIR__ . '/../Config/Database.php';

class ExportController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Export PDF (HTML pour impression)
    public function exportPDF() {
        $allergies = $this->db->query("SELECT * FROM allergies ORDER BY nom")->fetchAll();
        
        $html = '<h1>📋 Liste des allergies - NutriFlow AI</h1>';
        $html .= '<table>';
        $html .= '<thead><tr><th>Nom</th><th>Catégorie</th><th>Gravité</th><th>Description</th></tr></thead>';
        $html .= '<tbody>';
        
        foreach ($allergies as $a) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($a['nom']) . '</td>';
            $html .= '<td>' . htmlspecialchars($a['categorie']) . '</td>';
            $html .= '<td>' . htmlspecialchars($a['gravite']) . '</td>';
            $html .= '<td>' . htmlspecialchars(substr($a['description'], 0, 100)) . '...</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
        $html .= '<div class="footer">Généré le ' . date('d/m/Y H:i:s') . ' - NutriFlow AI</div>';
        
        return $html;
    }
    
    // Export Excel (CSV)
    public function exportExcel() {
        $allergies = $this->db->query("SELECT * FROM allergies ORDER BY nom")->fetchAll();
        
        $output = "Nom;Catégorie;Gravité;Description;Symptômes;Déclencheurs\n";
        
        foreach ($allergies as $a) {
            $output .= '"' . str_replace('"', '""', $a['nom']) . '";';
            $output .= '"' . str_replace('"', '""', $a['categorie']) . '";';
            $output .= '"' . str_replace('"', '""', $a['gravite']) . '";';
            $output .= '"' . str_replace('"', '""', substr($a['description'], 0, 150)) . '";';
            $output .= '"' . str_replace('"', '""', substr($a['symptomes'], 0, 100)) . '";';
            $output .= '"' . str_replace('"', '""', substr($a['declencheurs'], 0, 100)) . "\"\n";
        }
        
        return $output;
    }
}
?>