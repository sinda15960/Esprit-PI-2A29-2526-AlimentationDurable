<?php
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/mail_config.php';

class MailController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    // Envoyer un email simple
   // Envoyer un email simple (version de test - écrit dans un fichier)
public function sendMail($to, $subject, $htmlContent, $altContent = '') {
    // Créer le dossier logs s'il n'existe pas
    $logDir = dirname(__DIR__) . '/logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    // Écrire l'email dans un fichier de log
    $logFile = $logDir . '/emails_' . date('Y-m-d') . '.html';
    $logContent = "
    <hr>
    <h3>Email envoyé le " . date('Y-m-d H:i:s') . "</h3>
    <p><strong>À:</strong> $to</p>
    <p><strong>Sujet:</strong> $subject</p>
    <p><strong>Contenu:</strong></p>
    $htmlContent
    <hr>
    ";
    
    file_put_contents($logFile, $logContent, FILE_APPEND);
    
    // Pour le test, on retourne true (simule l'envoi)
    return true;
}
    
    // Générer le rapport hebdomadaire
    public function generateWeeklyReport() {
        // Récupérer les nouvelles recettes de la semaine
        $newRecipes = $this->getNewRecipesThisWeek();
        
        // Récupérer les statistiques
        $stats = $this->getStats();
        
        // Récupérer les objectifs atteints cette semaine
        $goalsAchieved = $this->getGoalsAchievedThisWeek();
        
        // Récupérer les catégories proches de l'objectif
        $nearGoals = $this->getCategoriesNearGoal();
        
        return [
            'newRecipes' => $newRecipes,
            'stats' => $stats,
            'goalsAchieved' => $goalsAchieved,
            'nearGoals' => $nearGoals,
            'date' => date('d/m/Y')
        ];
    }
    
    // Récupérer les nouvelles recettes de la semaine
    private function getNewRecipesThisWeek() {
        $query = "SELECT * FROM recipes 
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                  ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer les statistiques générales
    private function getStats() {
        // Total des recettes
        $query = "SELECT COUNT(*) as total FROM recipes";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Évolution (recettes ce mois vs mois dernier)
        $query = "SELECT 
                    COUNT(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE()) THEN 1 END) as this_month,
                    COUNT(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) THEN 1 END) as last_month
                  FROM recipes";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $evolution = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Recettes par type
        $query = "SELECT 
                    SUM(is_vegan) as vegan,
                    SUM(is_vegetarian) as vegetarian,
                    SUM(is_gluten_free) as gluten_free,
                    COUNT(*) - SUM(is_vegan) - SUM(is_vegetarian) as standard
                  FROM recipes";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $types = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'total' => $total['total'],
            'this_month' => $evolution['this_month'],
            'last_month' => $evolution['last_month'],
            'evolution' => $evolution['this_month'] - $evolution['last_month'],
            'types' => $types
        ];
    }
    
    // Récupérer les objectifs atteints cette semaine
    private function getGoalsAchievedThisWeek() {
        // Cette fonction vérifie dans les notifications récentes
        $goals = [];
        if (isset($_SESSION['goals_notified'])) {
            foreach ($_SESSION['goals_notified'] as $key => $value) {
                if (strpos($key, 'goal_reached') !== false) {
                    $goals[] = $key;
                }
            }
        }
        return $goals;
    }
    
    // Récupérer les catégories proches de l'objectif
    private function getCategoriesNearGoal() {
        $categories = [];
        $query = "SELECT c.*, COUNT(r.id) as nb_recettes 
                  FROM categories c
                  LEFT JOIN recipes r ON c.idCategorie = r.idCategorie
                  GROUP BY c.idCategorie";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $allCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($allCategories as $cat) {
            $goal = $_SESSION['category_goals'][$cat['idCategorie']] ?? 10;
            $nb = $cat['nb_recettes'] ?? 0;
            $remaining = $goal - $nb;
            if ($remaining <= 3 && $remaining > 0) {
                $categories[] = [
                    'nom' => $cat['nom'],
                    'remaining' => $remaining,
                    'total' => $nb,
                    'goal' => $goal
                ];
            }
        }
        return $categories;
    }
    
    // Générer le HTML du rapport
    public function generateReportHTML($reportData) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Rapport hebdomadaire NutriFlow AI</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; padding: 30px; text-align: center; }
                .header h1 { margin: 0; font-size: 24px; }
                .header p { margin: 10px 0 0; opacity: 0.9; }
                .content { padding: 30px; }
                .section { margin-bottom: 30px; }
                .section h2 { color: #2ecc71; border-bottom: 2px solid #2ecc71; padding-bottom: 10px; margin-bottom: 20px; }
                .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
                .stat-card { background: #f8f9fa; padding: 15px; border-radius: 10px; text-align: center; }
                .stat-number { font-size: 28px; font-weight: bold; color: #2ecc71; }
                .recipe-item { padding: 10px; border-bottom: 1px solid #eee; }
                .recipe-title { font-weight: bold; }
                .recipe-date { font-size: 12px; color: #999; float: right; }
                .badge { display: inline-block; padding: 3px 8px; border-radius: 20px; font-size: 12px; }
                .badge-success { background: #d4edda; color: #155724; }
                .badge-warning { background: #fff3cd; color: #856404; }
                .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #999; }
                .evolution-up { color: #2ecc71; }
                .evolution-down { color: #e74c3c; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🍃 NutriFlow AI</h1>
                    <p>Rapport hebdomadaire - Semaine du ' . $reportData['date'] . '</p>
                </div>
                
                <div class="content">
                    <!-- Section Statistiques -->
                    <div class="section">
                        <h2>📊 Statistiques générales</h2>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number">' . $reportData['stats']['total'] . '</div>
                                <div>Total recettes</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number ' . ($reportData['stats']['evolution'] >= 0 ? 'evolution-up' : 'evolution-down') . '">
                                    ' . ($reportData['stats']['evolution'] >= 0 ? '+' : '') . $reportData['stats']['evolution'] . '
                                </div>
                                <div>vs mois dernier</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section Types de recettes -->
                    <div class="section">
                        <h2>🥗 Répartition des recettes</h2>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number">' . ($reportData['stats']['types']['vegan'] ?? 0) . '</div>
                                <div>🌱 Vegan</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">' . ($reportData['stats']['types']['vegetarian'] ?? 0) . '</div>
                                <div>🥕 Végétarien</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">' . ($reportData['stats']['types']['gluten_free'] ?? 0) . '</div>
                                <div>🌾 Sans gluten</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">' . ($reportData['stats']['types']['standard'] ?? 0) . '</div>
                                <div>🍽️ Standard</div>
                            </div>
                        </div>
                    </div>';
        
        // Nouvelles recettes
        if (count($reportData['newRecipes']) > 0) {
            $html .= '
                    <div class="section">
                        <h2>🆕 Nouvelles recettes (' . count($reportData['newRecipes']) . ')</h2>';
            foreach ($reportData['newRecipes'] as $recipe) {
                $html .= '
                        <div class="recipe-item">
                            <span class="recipe-title">' . htmlspecialchars($recipe['title']) . '</span>
                            <span class="recipe-date">' . date('d/m/Y', strtotime($recipe['created_at'])) . '</span>
                        </div>';
            }
            $html .= '
                    </div>';
        }
        
        // Objectifs atteints
        if (count($reportData['goalsAchieved']) > 0) {
            $html .= '
                    <div class="section">
                        <h2>🏆 Objectifs atteints</h2>';
            foreach ($reportData['goalsAchieved'] as $goal) {
                $html .= '
                        <div class="recipe-item">
                            <span class="badge badge-success">✓ Objectif atteint</span>
                        </div>';
            }
            $html .= '
                    </div>';
        }
        
        // Catégories proches de l'objectif
        if (count($reportData['nearGoals']) > 0) {
            $html .= '
                    <div class="section">
                        <h2>🔥 Objectifs presque atteints</h2>';
            foreach ($reportData['nearGoals'] as $cat) {
                $html .= '
                        <div class="recipe-item">
                            <strong>' . htmlspecialchars($cat['nom']) . '</strong><br>
                            <span class="badge badge-warning">Plus que ' . $cat['remaining'] . ' recette(s) pour atteindre ' . $cat['goal'] . '</span>
                        </div>';
            }
            $html .= '
                    </div>';
        }
        
        $html .= '
                </div>
                
                <div class="footer">
                    <p>Cet email a été envoyé automatiquement par NutriFlow AI.</p>
                    <p>© 2024 NutriFlow AI - Tous droits réservés</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    // Envoyer le rapport hebdomadaire
    public function sendWeeklyReport($recipientEmail = null) {
        $recipient = $recipientEmail ?? MailConfig::$default_recipient;
        
        // Générer les données du rapport
        $reportData = $this->generateWeeklyReport();
        
        // Générer le HTML
        $html = $this->generateReportHTML($reportData);
        
        // Sujet de l'email
        $subject = '📊 Rapport hebdomadaire NutriFlow AI - Semaine du ' . date('d/m/Y');
        
        // Envoyer l'email
        return $this->sendMail($recipient, $subject, $html);
    }
}
?>