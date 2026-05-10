<?php
session_start();
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/Controller/ProfileController.php';

$db = Database::getInstance()->getConnection();

// Générer ou récupérer l'ID de session
if (!isset($_SESSION['profile_session_id'])) {
    $_SESSION['profile_session_id'] = session_id() . '_' . time();
}
$session_id = $_SESSION['profile_session_id'];

$profileController = new ProfileController();
$profile = $profileController->getProfile($session_id);

// Récupérer toutes les allergies
$allergies = $db->query("SELECT id, nom, gravite FROM allergies ORDER BY nom")->fetchAll();

// Traitement du formulaire
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom' => $_POST['nom'] ?? '',
        'prenom' => $_POST['prenom'] ?? '',
        'date_naissance' => $_POST['date_naissance'] ?? null,
        'telephone' => $_POST['telephone'] ?? '',
        'medicament_urgence' => $_POST['medicament_urgence'] ?? '',
        'selected_allergies' => $_POST['selected_allergies'] ?? [],
        'critical_allergies' => $_POST['critical_allergies'] ?? []
    ];
    
    if ($profileController->saveProfile($session_id, $data)) {
        $success = true;
        $profile = $profileController->getProfile($session_id);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil Allergique - NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            position: relative;
            overflow: hidden;
        }
        .banner h1 { font-size: 2.5rem; letter-spacing: 3px; }
        .banner p { opacity: 0.9; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
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
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .back-btn:hover { transform: translateX(-3px); }
        .profile-card {
            background: white;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .profile-header {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white;
            padding: 1.5rem 2rem;
        }
        .profile-header h2 { font-size: 1.5rem; font-weight: 600; }
        .profile-body { padding: 2rem; }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .form-group { margin-bottom: 1rem; }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
            font-size: 0.85rem;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
        }
        .form-group input:focus {
            outline: none;
            border-color: #4a7c2b;
        }
        .allergies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 0.8rem;
            margin-top: 0.5rem;
        }
        .allergy-check {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .allergy-check:hover { background: #e8f5e9; }
        .allergy-check input { width: 18px; height: 18px; margin: 0; }
        .allergy-check label { margin: 0; cursor: pointer; font-weight: normal; }
        .badge-critical {
            background: #f44336;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-size: 0.7rem;
            margin-left: 8px;
        }
        .btn-save {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1.5rem;
            width: 100%;
        }
        .btn-save:hover { transform: scale(1.02); }
        .btn-card {
            background: #ff9800;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }
        .footer {
            background: linear-gradient(135deg, #1a3c0e 0%, #2d5016 100%);
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        @media (max-width: 768px) { .container { padding: 1rem; } }
    </style>
</head>
<body>
    <div class="banner">
        <h1>🆔 Mon Profil Allergique</h1>
        <p>Créez votre carte d'urgence personnalisée</p>
    </div>

    <div class="container">
        <a href="front_allergie_traitement.php" class="back-btn">← Retour aux allergies</a>
        
        <div class="profile-card">
            <div class="profile-header">
                <h2><i class="fas fa-id-card"></i> Informations personnelles</h2>
            </div>
            <div class="profile-body">
                <?php if ($success): ?>
                    <div class="alert-success">
                        ✅ Profil enregistré avec succès ! 
                        <a href="emergency_card.php" style="color: #2d5016; font-weight: 600;">➡️ Générer ma carte d'urgence</a>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>👤 Prénom</label>
                            <input type="text" name="prenom" value="<?= htmlspecialchars($profile['prenom'] ?? '') ?>" placeholder="Votre prénom">
                        </div>
                        <div class="form-group">
                            <label>📛 Nom</label>
                            <input type="text" name="nom" value="<?= htmlspecialchars($profile['nom'] ?? '') ?>" placeholder="Votre nom">
                        </div>
                        <div class="form-group">
                            <label>📅 Date de naissance</label>
                            <input type="date" name="date_naissance" value="<?= htmlspecialchars($profile['date_naissance'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>📞 Téléphone (urgence)</label>
                            <input type="tel" name="telephone" value="<?= htmlspecialchars($profile['telephone'] ?? '') ?>" placeholder="06 XX XX XX XX">
                        </div>
                        <div class="form-group">
                            <label>💊 Médicament d'urgence</label>
                            <input type="text" name="medicament_urgence" value="<?= htmlspecialchars($profile['medicament_urgence'] ?? '') ?>" placeholder="Ex: EpiPen, Antihistaminiques...">
                        </div>
                    </div>
                    
                    <h3 style="margin: 1.5rem 0 1rem 0; color: #2d5016;">🔬 Mes allergies</h3>
                    <div class="allergies-grid">
                        <?php foreach ($allergies as $a): ?>
                            <?php 
                            $selected = in_array($a['id'], $profile['selected_allergies_array'] ?? []);
                            $critical = in_array($a['id'], $profile['critical_allergies_array'] ?? []);
                            ?>
                            <div class="allergy-check">
                                <input type="checkbox" name="selected_allergies[]" value="<?= $a['id'] ?>" id="allergy_<?= $a['id'] ?>" <?= $selected ? 'checked' : '' ?>>
                                <label for="allergy_<?= $a['id'] ?>">
                                    <?= htmlspecialchars($a['nom']) ?>
                                    <?php if ($a['gravite'] == 'severe'): ?>
                                        <span class="badge-critical">Critique</span>
                                    <?php endif; ?>
                                </label>
                                <?php if ($selected && $a['gravite'] == 'severe'): ?>
                                    <input type="checkbox" name="critical_allergies[]" value="<?= $a['id'] ?>" id="critical_<?= $a['id'] ?>" <?= $critical ? 'checked' : '' ?> style="width: auto; margin-left: auto;">
                                    <label for="critical_<?= $a['id'] ?>" style="font-size: 0.7rem; color: #f44336;">⚠️ Critique</label>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Enregistrer mon profil
                    </button>
                </form>
                
                <?php if ($profile && !empty($profile['selected_allergies'])): ?>
                    <div style="margin-top: 1.5rem; text-align: center;">
                        <a href="emergency_card.php" class="btn-card">
                            <i class="fas fa-print"></i> 🆘 Générer ma carte d'urgence
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <p>© 2024 NutriFlow AI - Mangez sainement, vivez pleinement</p>
    </footer>
</body>
</html>