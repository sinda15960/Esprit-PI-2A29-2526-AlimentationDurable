<?php
session_start();
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/Controller/CompareController.php';

$db = Database::getInstance()->getConnection();
$compareController = new CompareController();
$allergies = $compareController->getAllergiesForSelect();

$comparison = null;
if (isset($_GET['allergie1']) && isset($_GET['allergie2'])) {
    $comparison = $compareController->compareAllergies($_GET['allergie1'], $_GET['allergie2']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparateur d'Allergies - NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }
        .banner {
            background: linear-gradient(135deg, #1a3c0e 0%, #3a6b1e 100%);
            padding: 2rem 2rem;
            text-align: center;
            color: white;
        }
        .banner h1 { font-size: 2.5rem; letter-spacing: 3px; }
        .banner p { opacity: 0.9; }
        .container { max-width: 1300px; margin: 0 auto; padding: 2rem; }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: #2d5016;
            padding: 0.5rem 1.2rem;
            border-radius: 30px;
            text-decoration: none;
            margin-bottom: 1.5rem;
        }
        .selector-box {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-end;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
        }
        .selector-group {
            flex: 1;
            min-width: 200px;
        }
        .selector-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2d5016;
        }
        .selector-group select {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
        }
        .compare-btn {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white;
            border: none;
            padding: 0.8rem 1.8rem;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
        }
        
        /* TABLEAU COMPARATIF MODERNE */
        .comparison-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .vs-badge {
            text-align: center;
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ff9800, #f44336);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            padding: 0.5rem;
        }
        .comparison-table {
            width: 100%;
            border-collapse: collapse;
        }
        .comparison-table th {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white;
            padding: 1.2rem;
            text-align: center;
            font-size: 1.2rem;
        }
        .comparison-table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        .comparison-table .label-cell {
            background: #f8f9fa;
            font-weight: 600;
            width: 180px;
            color: #2d5016;
        }
        
        /* SCORES */
        .score-container {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .score-bar {
            flex: 1;
            height: 8px;
            background: #eee;
            border-radius: 4px;
            overflow: hidden;
        }
        .score-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.5s ease;
        }
        .score-fill.low { background: #4caf50; width: 30%; }
        .score-fill.medium { background: #ff9800; width: 60%; }
        .score-fill.high { background: #f44336; width: 90%; }
        .score-value {
            font-weight: 700;
            min-width: 45px;
        }
        
        /* GRILLE 3D */
        .stats-grid-compare {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin: 1rem 0;
        }
        .stat-compare {
            text-align: center;
            padding: 0.8rem;
            background: #f8f9fa;
            border-radius: 16px;
        }
        .stat-compare .number {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .stat-compare .label {
            font-size: 0.7rem;
            color: #666;
        }
        
        /* ALTERNATIVES */
        .alternatives-box {
            background: #e8f5e9;
            border-radius: 16px;
            padding: 1rem;
            margin-top: 1rem;
        }
        .alternatives-box h4 {
            color: #2d5016;
            margin-bottom: 0.5rem;
        }
        
        /* GRAPHIQUE RADAR */
        .radar-container {
            background: white;
            border-radius: 20px;
            padding: 1rem;
            margin-top: 1.5rem;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
        }
        .radar-container h3 {
            color: #2d5016;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .radar-wrapper {
            max-width: 400px;
            margin: 0 auto;
        }
        
        /* RECOMMANDATION */
        .recommendation {
            background: linear-gradient(135deg, #1a3c0e 0%, #2d5016 100%);
            color: white;
            padding: 1.2rem;
            border-radius: 16px;
            margin-top: 1rem;
            text-align: center;
        }
        
        .badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-legere { background: #4caf50; color: white; }
        .badge-moderate { background: #ff9800; color: white; }
        .badge-severe { background: #f44336; color: white; }
        
        .winner-badge {
            background: #ff9800;
            color: white;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.7rem;
            margin-left: 8px;
        }
        
        .footer {
            background: linear-gradient(135deg, #1a3c0e 0%, #2d5016 100%);
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .selector-box { flex-direction: column; }
            .comparison-table td, .comparison-table th { font-size: 0.75rem; padding: 0.6rem; }
            .label-cell { width: 100px; }
            .stats-grid-compare { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="banner">
        <h1>⚖️ Comparateur d'Allergies</h1>
        <p>Analysez et comparez deux allergies côte à côte</p>
    </div>

    <div class="container">
        <a href="front_allergie_traitement.php" class="back-btn">← Retour aux allergies</a>
        
        <div class="selector-box">
            <div class="selector-group">
                <label>🔬 Allergie 1</label>
                <select id="allergie1">
                    <option value="">Sélectionner...</option>
                    <?php foreach ($allergies as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= ($_GET['allergie1'] ?? '') == $a['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="selector-group">
                <label>🔬 Allergie 2</label>
                <select id="allergie2">
                    <option value="">Sélectionner...</option>
                    <?php foreach ($allergies as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= ($_GET['allergie2'] ?? '') == $a['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <button class="compare-btn" onclick="compare()">
                    <i class="fas fa-chart-simple"></i> Comparer
                </button>
            </div>
        </div>
        
        <?php if ($comparison): 
            $a1 = $comparison['allergie1'];
            $a2 = $comparison['allergie2'];
            $t1 = $a1['traitement'];
            $t2 = $a2['traitement'];
            
            // Calcul des scores
            $graviteScore = ['legere' => 20, 'moderate' => 60, 'severe' => 90];
            $urgenceScore = ['faible' => 10, 'moyen' => 50, 'eleve' => 85];
            $score1 = ($graviteScore[$a1['gravite']] + ($urgenceScore[$t1['niveau_urgence'] ?? 'faible'] ?? 10)) / 2;
            $score2 = ($graviteScore[$a2['gravite']] + ($urgenceScore[$t2['niveau_urgence'] ?? 'faible'] ?? 10)) / 2;
            $winner = $score1 > $score2 ? $a1['nom'] : ($score2 > $score1 ? $a2['nom'] : 'Égalité');
            $winnerColor = $score1 > $score2 ? '#f44336' : ($score2 > $score1 ? '#f44336' : '#ff9800');
        ?>
        
        <div class="comparison-card">
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th style="width: 180px;">Critère</th>
                        <th><?= htmlspecialchars($a1['nom']) ?> <span class="winner-badge">Score: <?= round($score1) ?>%</span></th>
                        <th><?= htmlspecialchars($a2['nom']) ?> <span class="winner-badge">Score: <?= round($score2) ?>%</span></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Score de dangerosité -->
                    <tr>
                        <td class="label-cell">⚠️ Score de dangerosité</td>
                        <td>
                            <div class="score-container">
                                <div class="score-bar"><div class="score-fill <?= $score1 > 60 ? 'high' : ($score1 > 30 ? 'medium' : 'low') ?>" style="width: <?= $score1 ?>%"></div></div>
                                <span class="score-value"><?= round($score1) ?>%</span>
                            </div>
                        </td>
                        <td>
                            <div class="score-container">
                                <div class="score-bar"><div class="score-fill <?= $score2 > 60 ? 'high' : ($score2 > 30 ? 'medium' : 'low') ?>" style="width: <?= $score2 ?>%"></div></div>
                                <span class="score-value"><?= round($score2) ?>%</span>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Catégorie -->
                    <tr>
                        <td class="label-cell">📂 Catégorie</td>
                        <td><?= htmlspecialchars($a1['categorie']) ?></td>
                        <td><?= htmlspecialchars($a2['categorie']) ?></td>
                    </tr>
                    
                    <!-- Gravité -->
                    <tr>
                        <td class="label-cell">⚡ Gravité</td>
                        <td><span class="badge badge-<?= $a1['gravite'] ?>"><?= ucfirst($a1['gravite']) ?></span></td>
                        <td><span class="badge badge-<?= $a2['gravite'] ?>"><?= ucfirst($a2['gravite']) ?></span></td>
                    </tr>
                    
                    <!-- Niveau urgence -->
                    <tr>
                        <td class="label-cell">🚨 Niveau urgence</td>
                        <td><span class="badge urgence-<?= $t1['niveau_urgence'] ?? 'faible' ?>"><?= ucfirst($t1['niveau_urgence'] ?? 'Non défini') ?></span></td>
                        <td><span class="badge urgence-<?= $t2['niveau_urgence'] ?? 'faible' ?>"><?= ucfirst($t2['niveau_urgence'] ?? 'Non défini') ?></span></td>
                    </tr>
                    
                    <!-- Description courte -->
                    <tr>
                        <td class="label-cell">📝 Description</td>
                        <td><?= htmlspecialchars(substr($a1['description'], 0, 100)) ?>...</td>
                        <td><?= htmlspecialchars(substr($a2['description'], 0, 100)) ?>...</td>
                    </tr>
                    
                    <!-- Symptômes -->
                    <tr>
                        <td class="label-cell">🤧 Symptômes</td>
                        <td><?= htmlspecialchars(substr($a1['symptomes'], 0, 120)) ?>...</td>
                        <td><?= htmlspecialchars(substr($a2['symptomes'], 0, 120)) ?>...</td>
                    </tr>
                    
                    <!-- Déclencheurs -->
                    <tr>
                        <td class="label-cell">🚫 Déclencheurs</td>
                        <td><?= htmlspecialchars(substr($a1['declencheurs'], 0, 100)) ?>...</td>
                        <td><?= htmlspecialchars(substr($a2['declencheurs'], 0, 100)) ?>...</td>
                    </tr>
                    
                    <!-- Médicaments -->
                    <tr>
                        <td class="label-cell">💊 Médicaments</td>
                        <td><?= htmlspecialchars($t1['medicaments'] ?? 'Aucun spécifique') ?></td>
                        <td><?= htmlspecialchars($t2['medicaments'] ?? 'Aucun spécifique') ?></td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Statistiques supplémentaires -->
            <div class="stats-grid-compare" style="padding: 1rem;">
                <div class="stat-compare">
                    <div class="number"><?= strlen($a1['symptomes']) ?></div>
                    <div class="label">caractères symptômes</div>
                </div>
                <div class="stat-compare">
                    <div class="number"><?= substr_count($a1['declencheurs'], ',') + 1 ?></div>
                    <div class="label">déclencheurs identifiés</div>
                </div>
                <div class="stat-compare">
                    <div class="number"><?= $t1['niveau_urgence'] == 'eleve' ? '🔄 Immédiat' : ($t1['niveau_urgence'] == 'moyen' ? '⏰ Rapide' : '📋 À surveiller') ?></div>
                    <div class="label">réaction recommandée</div>
                </div>
            </div>
            
            <!-- Points communs -->
            <?php if (!empty($comparison['commun_symptomes']) || !empty($comparison['commun_declencheurs'])): ?>
                <div style="background: #e3f2fd; border-radius: 16px; padding: 1rem; margin: 1rem;">
                    <strong><i class="fas fa-link"></i> Points communs :</strong><br>
                    <?php if (!empty($comparison['commun_symptomes'])): ?>
                        🤧 Symptômes communs : <?= implode(', ', $comparison['commun_symptomes']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($comparison['commun_declencheurs'])): ?>
                        🚫 Déclencheurs communs : <?= implode(', ', $comparison['commun_declencheurs']) ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Recommandation finale -->
            <div class="recommendation">
                <i class="fas fa-trophy" style="font-size: 1.5rem;"></i>
                <strong style="font-size: 1.1rem;"> <?= $winner ?> est la plus dangereuse</strong><br>
                <small>Score de dangerosité <?= round(max($score1, $score2)) ?>% - Une attention médicale urgente est recommandée</small>
            </div>
        </div>
        
        <!-- Graphique radar -->
        <div class="radar-container">
            <h3><i class="fas fa-chart-line"></i> Analyse comparative (sur 100)</h3>
            <div class="radar-wrapper">
                <canvas id="radarChart" style="width: 100%; height: 300px;"></canvas>
            </div>
        </div>
        
        <?php else: ?>
            <div style="background: white; border-radius: 20px; padding: 3rem; text-align: center; color: #999;">
                <i class="fas fa-chart-simple" style="font-size: 4rem; margin-bottom: 1rem; display: block;"></i>
                <p>Sélectionnez deux allergies dans les menus ci-dessus<br>pour comparer leurs caractéristiques et scores de dangerosité</p>
            </div>
        <?php endif; ?>
    </div>
    
    <footer class="footer">
        <p>© 2024 NutriFlow AI - Mangez sainement, vivez pleinement</p>
    </footer>

    <style>
        .urgence-faible { background: #4caf50; color: white; }
        .urgence-moyen { background: #ff9800; color: white; }
        .urgence-eleve { background: #f44336; color: white; }
    </style>
    
    <script>
        function compare() {
    const id1 = document.getElementById('allergie1').value;
    const id2 = document.getElementById('allergie2').value;
    
    // Supprimer l'ancien message d'erreur s'il existe
    const oldError = document.getElementById('compareError');
    if (oldError) oldError.remove();
    
    if (id1 && id2 && id1 !== id2) {
        window.location.href = `compare_allergies.php?allergie1=${id1}&allergie2=${id2}`;
    } else if (id1 === id2 && id1) {
        // Afficher le message directement dans la page (pas de popup)
        const errorDiv = document.createElement('div');
        errorDiv.id = 'compareError';
        errorDiv.innerHTML = '<div style="background: #ffebee; color: #c62828; padding: 0.8rem; border-radius: 12px; margin-bottom: 1rem; border-left: 4px solid #f44336; display: flex; align-items: center; gap: 10px;"><i class="fas fa-exclamation-triangle"></i> ⚠️ Veuillez sélectionner deux allergies différentes !</div>';
        const selectorBox = document.querySelector('.selector-box');
        selectorBox.parentNode.insertBefore(errorDiv, selectorBox.nextSibling);
        setTimeout(() => { if(errorDiv) errorDiv.remove(); }, 3000);
    } else {
        const errorDiv = document.createElement('div');
        errorDiv.id = 'compareError';
        errorDiv.innerHTML = '<div style="background: #ffebee; color: #c62828; padding: 0.8rem; border-radius: 12px; margin-bottom: 1rem; border-left: 4px solid #f44336; display: flex; align-items: center; gap: 10px;"><i class="fas fa-exclamation-triangle"></i> ⚠️ Veuillez sélectionner deux allergies !</div>';
        const selectorBox = document.querySelector('.selector-box');
        selectorBox.parentNode.insertBefore(errorDiv, selectorBox.nextSibling);
        setTimeout(() => { if(errorDiv) errorDiv.remove(); }, 3000);
    }
}
        
        <?php if ($comparison): ?>
        // Graphique radar
        const ctx = document.getElementById('radarChart').getContext('2d');
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Gravité', 'Urgence', 'Sévérité symptômes', 'Dangerosité', 'Impact quotidien'],
                datasets: [
                    {
                        label: '<?= addslashes($a1['nom']) ?>',
                        data: [
                            <?= $a1['gravite'] == 'severe' ? 90 : ($a1['gravite'] == 'moderate' ? 60 : 30) ?>,
                            <?= ($t1['niveau_urgence'] ?? 'faible') == 'eleve' ? 85 : (($t1['niveau_urgence'] ?? 'faible') == 'moyen' ? 55 : 20) ?>,
                            <?= min(90, strlen($a1['symptomes']) / 3) ?>,
                            <?= round($score1) ?>,
                            <?= $a1['gravite'] == 'severe' ? 80 : 40 ?>
                        ],
                        backgroundColor: 'rgba(76, 175, 80, 0.2)',
                        borderColor: '#4caf50',
                        borderWidth: 2,
                        pointBackgroundColor: '#4caf50'
                    },
                    {
                        label: '<?= addslashes($a2['nom']) ?>',
                        data: [
                            <?= $a2['gravite'] == 'severe' ? 90 : ($a2['gravite'] == 'moderate' ? 60 : 30) ?>,
                            <?= ($t2['niveau_urgence'] ?? 'faible') == 'eleve' ? 85 : (($t2['niveau_urgence'] ?? 'faible') == 'moyen' ? 55 : 20) ?>,
                            <?= min(90, strlen($a2['symptomes']) / 3) ?>,
                            <?= round($score2) ?>,
                            <?= $a2['gravite'] == 'severe' ? 80 : 40 ?>
                        ],
                        backgroundColor: 'rgba(244, 67, 54, 0.2)',
                        borderColor: '#f44336',
                        borderWidth: 2,
                        pointBackgroundColor: '#f44336'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { stepSize: 20 }
                    }
                },
                plugins: {
                    tooltip: { callbacks: { label: function(context) { return context.dataset.label + ': ' + context.raw + '/100'; } } }
                }
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>