<?php
session_start();
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Controller/ProfileController.php';

$db = Database::getInstance()->getConnection();

if (!isset($_SESSION['profile_session_id'])) {
    header('Location: profile_builder.php');
    exit();
}
$session_id = $_SESSION['profile_session_id'];

$profileController = new ProfileController();
$profile = $profileController->getProfile($session_id);

if (!$profile || empty($profile['selected_allergies'])) {
    header('Location: profile_builder.php');
    exit();
}

// Récupérer les noms des allergies sélectionnées
$selectedIds = $profile['selected_allergies_array'];
$criticalIds = $profile['critical_allergies_array'] ?? [];

$allergiesNom = [];
$criticalNom = [];

if (!empty($selectedIds)) {
    $placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
    $stmt = $db->prepare("SELECT id, nom, gravite FROM allergies WHERE id IN ($placeholders) ORDER BY nom");
    $stmt->execute($selectedIds);
    $allergiesList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($allergiesList as $a) {
        if (in_array($a['id'], $criticalIds) || $a['gravite'] == 'severe') {
            $criticalNom[] = $a['nom'];
        } else {
            $allergiesNom[] = $a['nom'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte d'Urgence - NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        .card {
            width: 450px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            overflow: hidden;
            position: relative;
        }
        .card::before {
            content: "🆘";
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 4rem;
            opacity: 0.1;
        }
        .card-header {
            background: linear-gradient(135deg, #d32f2f 0%, #f44336 100%);
            color: white;
            padding: 1.2rem;
            text-align: center;
        }
        .card-header h1 { font-size: 1.4rem; letter-spacing: 2px; }
        .card-header p { font-size: 0.7rem; opacity: 0.9; }
        .card-body { padding: 1.5rem; }
        .info-row {
            display: flex;
            margin-bottom: 0.8rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            width: 100px;
            font-weight: 700;
            color: #555;
            font-size: 0.8rem;
        }
        .info-value {
            flex: 1;
            color: #333;
            font-weight: 500;
            font-size: 0.85rem;
        }
        .allergies-section {
            margin: 1rem 0;
            padding: 0.5rem 0;
        }
        .allergy-line {
            display: flex;
            align-items: center;
            padding: 0.4rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .allergy-badge {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .allergy-badge.critical { background: #f44336; box-shadow: 0 0 4px #f44336; }
        .allergy-badge.normal { background: #ff9800; }
        .allergy-name {
            font-size: 0.85rem;
            font-weight: 500;
            color: #333;
        }
        .allergy-name.critical { color: #c62828; font-weight: 600; }
        .section-title {
            font-weight: 600;
            font-size: 0.75rem;
            margin: 0.5rem 0;
            color: #666;
            letter-spacing: 0.5px;
        }
        .emergency-note {
            margin-top: 1rem;
            padding-top: 0.8rem;
            border-top: 2px dashed #ddd;
            background: #fce4ec;
            border-radius: 10px;
            padding: 0.8rem;
        }
        .emergency-note p {
            font-size: 0.65rem;
            color: #c62828;
            text-align: center;
            font-weight: 600;
        }
        .footer-card {
            background: #2d5016;
            color: white;
            text-align: center;
            padding: 0.8rem;
            font-size: 0.65rem;
        }
        .print-btn, .back-btn {
            background: #2196F3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 1rem;
            width: 100%;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .back-btn { background: #666; margin-top: 0.5rem; }
        .print-btn:hover { background: #0b7dda; }
        .back-btn:hover { background: #555; }
        @media print {
            body { background: white; padding: 0; }
            .print-btn, .back-btn { display: none; }
            .card { box-shadow: none; margin: 0; width: 100%; }
        }
        .buttons-container { margin-top: 1rem; }
        .empty-message {
            text-align: center;
            color: #999;
            font-size: 0.75rem;
            padding: 0.5rem;
        }
    </style>
</head>
<body>
    <div style="text-align: center; width: 100%; max-width: 480px;">
        <div class="buttons-container">
            <button onclick="window.print()" class="print-btn">🖨️ Imprimer / Sauvegarder en PDF</button>
            <a href="profile_builder.php" class="back-btn">← Retour modifier mon profil</a>
        </div>
        
        <div class="card" style="margin-top: 1rem;">
            <div class="card-header">
                <h1>🆘 CARTE D'URGENCE ALLERGIQUE</h1>
                <p>À présenter en cas d'urgence médicale</p>
            </div>
            <div class="card-body">
                <!-- Informations personnelles -->
                <div class="info-row">
                    <div class="info-label">👤 Patient</div>
                    <div class="info-value"><?= strtoupper(htmlspecialchars($profile['prenom'] ?? '')) ?> <?= strtoupper(htmlspecialchars($profile['nom'] ?? '')) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">📞 Urgence</div>
                    <div class="info-value"><?= htmlspecialchars($profile['telephone'] ?? 'Non renseigné') ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">💊 Médicament</div>
                    <div class="info-value"><?= htmlspecialchars($profile['medicament_urgence'] ?? 'Aucun') ?></div>
                </div>
                
                <!-- Liste des allergies critiques -->
                <div class="allergies-section">
                    <div class="section-title">🚨 ALLERGIES CRITIQUES</div>
                    <?php if (!empty($criticalNom)): ?>
                        <?php foreach ($criticalNom as $nom): ?>
                            <div class="allergy-line">
                                <div class="allergy-badge critical"></div>
                                <div class="allergy-name critical"><?= htmlspecialchars($nom) ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-message">Aucune allergie critique</div>
                    <?php endif; ?>
                </div>
                
                <!-- Liste des autres allergies -->
                <?php if (!empty($allergiesNom)): ?>
                <div class="allergies-section">
                    <div class="section-title">📋 AUTRES ALLERGIES</div>
                    <?php foreach ($allergiesNom as $nom): ?>
                        <div class="allergy-line">
                            <div class="allergy-badge normal"></div>
                            <div class="allergy-name"><?= htmlspecialchars($nom) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Message d'urgence -->
                <div class="emergency-note">
                    <p>🚨 RÉACTION ALLERGIQUE : APPELER LE 15 🚨</p>
                </div>
            </div>
            <div class="footer-card">
                NutriFlow AI - Carte d'urgence allergique | Générée le <?= date('d/m/Y') ?>
            </div>
        </div>
    </div>
</body>
</html>