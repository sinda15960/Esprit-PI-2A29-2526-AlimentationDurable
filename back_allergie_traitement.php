<?php
session_start();
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../Controller/LogController.php';

$db = Database::getInstance()->getConnection();
$logController = new LogController();

// ==================== VALIDATION ====================
function validateNom($nom) {
    if (empty($nom)) return "Le nom est obligatoire";
    if (preg_match('/[0-9]/', $nom)) return "Le nom ne doit pas contenir de chiffres";
    if (strlen($nom) < 2) return "Le nom doit contenir au moins 2 caractères";
    return null;
}

function validateDescription($desc) {
    if (empty($desc)) return "La description est obligatoire";
    if (strlen(trim($desc)) < 10) return "La description doit contenir au moins 10 caractères";
    return null;
}

function validateSymptomes($symp) {
    if (empty($symp)) return "Les symptômes sont obligatoires";
    if (strlen(trim($symp)) < 10) return "Les symptômes doivent contenir au moins 10 caractères";
    return null;
}

function validateDeclencheurs($dec) {
    if (empty($dec)) return "Les déclencheurs sont obligatoires";
    return null;
}

// ==================== CRUD ALLERGIES AVEC LOGS ====================

// Ajouter une allergie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_allergie'])) {
    $error = null;
    $error = validateNom($_POST['nom']);
    if (!$error) $error = validateDescription($_POST['description']);
    if (!$error) $error = validateSymptomes($_POST['symptomes']);
    if (!$error) $error = validateDeclencheurs($_POST['declencheurs']);
    
    if (!$error) {
        $stmt = $db->prepare("INSERT INTO allergies (nom, categorie, description, symptomes, declencheurs, gravite) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$_POST['nom'], $_POST['categorie'], $_POST['description'], $_POST['symptomes'], $_POST['declencheurs'], $_POST['gravite']])) {
            $newId = $db->lastInsertId();
            $logController->addLog('ADD', 'allergies', $newId, $_POST['nom'], 'Ajout d\'une nouvelle allergie');
            $_SESSION['success'] = "✅ Allergie ajoutée avec succès !";
        } else {
            $_SESSION['error'] = "❌ Erreur lors de l'ajout";
        }
    } else {
        $_SESSION['error'] = $error;
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Modifier une allergie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_allergie'])) {
    $error = null;
    $error = validateNom($_POST['nom']);
    if (!$error) $error = validateDescription($_POST['description']);
    if (!$error) $error = validateSymptomes($_POST['symptomes']);
    if (!$error) $error = validateDeclencheurs($_POST['declencheurs']);
    
    if (!$error) {
        $stmt = $db->prepare("UPDATE allergies SET nom=?, categorie=?, description=?, symptomes=?, declencheurs=?, gravite=? WHERE id=?");
        if ($stmt->execute([$_POST['nom'], $_POST['categorie'], $_POST['description'], $_POST['symptomes'], $_POST['declencheurs'], $_POST['gravite'], $_POST['id']])) {
            $logController->addLog('EDIT', 'allergies', $_POST['id'], $_POST['nom'], 'Modification de l\'allergie');
            $_SESSION['success'] = "✅ Allergie modifiée avec succès !";
        } else {
            $_SESSION['error'] = "❌ Erreur lors de la modification";
        }
    } else {
        $_SESSION['error'] = $error;
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Supprimer une allergie
if (isset($_GET['delete_allergie'])) {
    $id = $_GET['delete_allergie'];
    
    // Récupérer les infos avant suppression
    $stmt = $db->prepare("SELECT nom, image_url FROM allergies WHERE id = ?");
    $stmt->execute([$id]);
    $allergieData = $stmt->fetch(PDO::FETCH_ASSOC);
    $nom = $allergieData['nom'] ?? 'ID:' . $id;
    $image = $allergieData['image_url'] ?? null;
    
    // Supprimer l'image associée
    if ($image && file_exists(__DIR__ . '/../../' . $image)) {
        unlink(__DIR__ . '/../../' . $image);
    }
    
    // Supprimer les traitements associés
    $db->prepare("DELETE FROM traitements WHERE allergie_id=?")->execute([$id]);
    
    // Supprimer l'allergie
    $db->prepare("DELETE FROM allergies WHERE id=?")->execute([$id]);
    
    $logController->addLog('DELETE', 'allergies', $id, $nom, 'Suppression de l\'allergie');
    $_SESSION['success'] = "✅ Allergie supprimée avec succès !";
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// ==================== RÉCUPÉRATION DES DONNÉES (AVANT HTML) ====================
$allergies = $db->query("SELECT * FROM allergies ORDER BY nom")->fetchAll();

// Statistiques pour les graphiques
$stmtCategories = $db->query("SELECT categorie, COUNT(*) as total FROM allergies GROUP BY categorie");
$categories = $stmtCategories->fetchAll();
$stmtGravites = $db->query("SELECT gravite, COUNT(*) as total FROM allergies GROUP BY gravite");
$gravites = $stmtGravites->fetchAll();

// Récupérer les logs
$logs = $logController->getRecentLogs(15);

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success']);
unset($_SESSION['error']);

// Calcul des statistiques
$nbAllergies = count($allergies);
$nbTraitements = $db->query("SELECT COUNT(*) FROM traitements")->fetchColumn();
$nbFeedbacks = $db->query("SELECT COUNT(*) FROM feedbacks")->fetchColumn();
$maxVal = max($nbAllergies, $nbTraitements, $nbFeedbacks, 1);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Poppins', 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }
        .admin-bar { 
            background: linear-gradient(135deg, #1a3c0e 0%, #2d5016 100%);
            color: white; 
            padding: 0.8rem 2rem; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .admin-bar h2 { font-size: 1.3rem; font-weight: 600; letter-spacing: 1px; }
        .admin-bar h2::before { content: "⚙️ "; }
        .admin-bar a { 
            color: white; 
            text-decoration: none; 
            background: rgba(255,255,255,0.15); 
            padding: 0.5rem 1.2rem; 
            border-radius: 30px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .admin-bar a:hover { background: rgba(255,255,255,0.3); transform: scale(1.02); }
        
        .banner { 
            background: linear-gradient(135deg, #1a3c0e 0%, #3a6b1e 100%);
            padding: 2rem; 
            text-align: center; 
            color: white;
            position: relative;
            overflow: hidden;
        }
        .banner::before { content: "🌱"; position: absolute; font-size: 150px; opacity: 0.1; bottom: -30px; right: -30px; }
        .banner h1 { font-size: 3rem; letter-spacing: 5px; font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
        .banner p { font-size: 1.1rem; opacity: 0.95; font-weight: 300; }
        
        .container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        
        /* STATS CARDS */
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.2rem; margin-bottom: 2rem; }
        .stat-card {
            background: white;
            border-radius: 18px;
            padding: 1.2rem 1.4rem;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.25s, box-shadow 0.25s;
            border-left: 5px solid transparent;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 28px rgba(0,0,0,0.12); }
        .stat-card.green  { border-left-color: #4caf50; }
        .stat-card.blue   { border-left-color: #2196F3; }
        .stat-card.purple { border-left-color: #9c27b0; }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; flex-shrink: 0;
        }
        .stat-icon.green  { background: #e8f5e9; }
        .stat-icon.blue   { background: #e3f2fd; }
        .stat-icon.purple { background: #f3e5f5; }
        .stat-info { flex: 1; }
        .stat-number { font-size: 2rem; font-weight: 700; line-height: 1; color: #1e3d0e; }
        .stat-label  { font-size: 0.78rem; color: #888; margin-top: 3px; font-weight: 500; }
        .stat-bar-wrap { height: 4px; background: #eee; border-radius: 4px; margin-top: 8px; }
        .stat-bar { height: 4px; border-radius: 4px; }
        .stat-bar.green  { background: #4caf50; }
        .stat-bar.blue   { background: #2196F3; }
        .stat-bar.purple { background: #9c27b0; }
        
        /* TIMELINE LOGS */
        .timeline-card {
            background: white; border-radius: 18px;
            padding: 1.2rem 1.4rem;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
            margin-bottom: 1.5rem;
        }
        .timeline-card h4 {
            color: #2d5016; font-size: 0.85rem; font-weight: 600;
            margin-bottom: 0.8rem; display: flex;
            align-items: center; gap: 6px; border-left: 3px solid #4a7c2b; padding-left: 8px;
        }
        .log-table { width: 100%; font-size: 0.7rem; border-collapse: collapse; }
        .log-table th { background: #f0f4e8; color: #2d5016; padding: 0.5rem; text-align: left; font-size: 0.65rem; }
        .log-table td { padding: 0.4rem 0.5rem; border-bottom: 1px solid #eee; }
        .badge-add { background: #4caf50; color: white; padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.6rem; display: inline-block; }
        .badge-edit { background: #ff9800; color: white; padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.6rem; display: inline-block; }
        .badge-delete { background: #f44336; color: white; padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.6rem; display: inline-block; }
        
        /* CARTES ALLERGIES */
        .card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .card-header { 
            background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%);
            color: white; 
            padding: 1.2rem 1.8rem; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .card-header span { 
            font-size: 1.3rem; 
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-header span::before { content: "📋"; font-size: 1.5rem; }
        .export-buttons { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .export-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .export-pdf { background: #f44336; color: white; }
        .export-excel { background: #4caf50; color: white; }
        .export-btn:hover { transform: scale(1.02); }
        
        .btn { 
            padding: 0.3rem 0.6rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.71rem;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-weight: 600;
            transition: all 0.18s ease;
            white-space: nowrap;
            text-decoration: none;
        }
        .btn-add    { background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%); color: white; padding: 0.5rem 1rem; font-size: 0.8rem; border-radius: 12px; }
        .btn-add:hover    { box-shadow: 0 4px 12px rgba(45,80,22,0.35); transform: translateY(-1px); }
        .btn-edit   { background: #e3f2fd; color: #1565c0; border: 1px solid #bbdefb; }
        .btn-edit:hover   { background: #1565c0; color: white; }
        .btn-delete { background: #fdecea; color: #c62828; border: 1px solid #ffcdd2; }
        .btn-delete:hover { background: #c62828; color: white; }
        .btn-save   { background: #4caf50; color: white; padding: 0.6rem 1.2rem; border-radius: 12px; }
        .btn-save:hover   { background: #45a049; }
        .btn-cancel { background: #999; color: white; padding: 0.6rem 1.2rem; border-radius: 12px; }
        .btn-cancel:hover { background: #777; }
        .btn-treatment {
            background: #f3e5f5; color: #6a1b9a;
            border: 1px solid #e1bee7;
            padding: 0.3rem 0.6rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.71rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            transition: all 0.18s ease;
            white-space: nowrap;
        }
        .btn-treatment:hover { background: #6a1b9a; color: white; }
        
        .badge { display: inline-block; padding: 0.3rem 0.8rem; border-radius: 30px; font-size: 0.75rem; font-weight: 600; }
        .badge-legere { background: #4caf50; color: white; }
        .badge-moderate { background: #ff9800; color: white; }
        .badge-severe { background: #f44336; color: white; }
        
        .stats-bottom { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; margin-top: 1.5rem; }
        .stat-chart-card {
            background: white; border-radius: 18px;
            padding: 1.2rem 1.4rem;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
        }
        .stat-chart-card h4 {
            color: #2d5016; font-size: 0.85rem; font-weight: 600;
            margin-bottom: 0.8rem; display: flex; align-items: center;
            gap: 6px; border-left: 3px solid #4a7c2b; padding-left: 8px;
        }
        .chart-wrap { position: relative; width: 100%; height: 200px; }
        
        /* MODALS */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: white; border-radius: 24px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 50px rgba(0,0,0,0.3); }
        .modal-header { background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%); color: white; padding: 1.2rem; font-size: 1.3rem; font-weight: 600; border-radius: 24px 24px 0 0; }
        .modal-body { padding: 1.8rem; }
        .modal-footer { padding: 1rem 1.8rem; display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid #eee; }
        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: 0.4rem; font-weight: 600; color: #333; }
        .form-group input, .form-group select, .form-group textarea { 
            width: 100%; 
            padding: 0.7rem; 
            border: 2px solid #e0e0e0; 
            border-radius: 12px; 
            font-family: 'Poppins', sans-serif; 
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #4a7c2b; }
        .form-group input.error, .form-group select.error, .form-group textarea.error { border-color: #f44336; background-color: #fff8f8; }
        .form-group input.valid, .form-group select.valid, .form-group textarea.valid { border-color: #4caf50; background-color: #f0fff0; }
        .error-message { color: #f44336; font-size: 0.7rem; margin-top: 0.25rem; display: none; }
        .error-message.show { display: block; }
        textarea { resize: vertical; min-height: 80px; }
        .alert { padding: 0.8rem 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #4caf50; }
        .alert-error { background: #f8d7da; color: #721c24; border-left: 4px solid #f44336; }
        .footer { background: #2d5016; color: white; text-align: center; padding: 1.5rem; margin-top: 2rem; font-weight: 300; }
        
        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .stats { grid-template-columns: 1fr; }
            .stats-bottom { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="admin-bar">
        <h2>Mode Administration</h2>
        <a href="logout.php">🔓 Déconnexion</a>
    </div>

    <div class="banner">
        <h1>EAT HEALTHY</h1>
        <p>plan your meals - Administration</p>
    </div>

    <div class="container">
        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- STATS CARDS -->
        <div class="stats">
            <div class="stat-card green">
                <div class="stat-icon green">🔬</div>
                <div class="stat-info">
                    <div class="stat-number"><?= $nbAllergies ?></div>
                    <div class="stat-label">Allergies enregistrées</div>
                    <div class="stat-bar-wrap"><div class="stat-bar green" style="width:<?= round($nbAllergies/$maxVal*100) ?>%"></div></div>
                </div>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon blue">💊</div>
                <div class="stat-info">
                    <div class="stat-number"><?= $nbTraitements ?></div>
                    <div class="stat-label">Traitements associés</div>
                    <div class="stat-bar-wrap"><div class="stat-bar blue" style="width:<?= round($nbTraitements/$maxVal*100) ?>%"></div></div>
                </div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon purple">💬</div>
                <div class="stat-info">
                    <div class="stat-number"><?= $nbFeedbacks ?></div>
                    <div class="stat-label">Feedbacks reçus</div>
                    <div class="stat-bar-wrap"><div class="stat-bar purple" style="width:<?= round($nbFeedbacks/$maxVal*100) ?>%"></div></div>
                </div>
            </div>
        </div>

        <!-- TIMELINE DES LOGS -->
        <div class="timeline-card">
            <h4>📜 Historique des modifications (Audit Log) <i class="fas fa-history"></i></h4>
            <div style="max-height: 250px; overflow-y: auto;">
                <table class="log-table">
                    <thead>
                        <tr><th>Date</th><th>Action</th><th>Table</th><th>Élément</th><th>Détails</th><th>Admin</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <?php
                            $actionClass = match($log['action_type']) {
                                'ADD' => 'badge-add',
                                'EDIT' => 'badge-edit',
                                'DELETE' => 'badge-delete',
                                default => ''
                            };
                            $actionIcon = match($log['action_type']) {
                                'ADD' => '➕',
                                'EDIT' => '✏️',
                                'DELETE' => '🗑️',
                                default => '📌'
                            };
                            ?>
                            <tr>
                                <td><?= date('d/m H:i', strtotime($log['created_at'])) ?></td>
                                <td><span class="<?= $actionClass ?>"><?= $actionIcon ?> <?= $log['action_type'] ?></span></td>
                                <td><?= htmlspecialchars($log['table_name']) ?></td>
                                <td><strong><?= htmlspecialchars($log['record_name'] ?? 'ID:'.$log['record_id']) ?></strong></td>
                                <td><?= htmlspecialchars(substr($log['details'] ?? '', 0, 40)) ?>...</div>
                                <td><?= htmlspecialchars($log['admin_user']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($logs)): ?>
                            <tr><td colspan="6" style="text-align: center; color: #999;">Aucun log pour le moment</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CARTES DES ALLERGIES -->
        <div class="card">
            <div class="card-header">
                <span>📋 Liste des allergies</span>
                <div class="export-buttons">
                    <a href="../../Controller/export_pdf.php" target="_blank" class="export-btn export-pdf">📄 Exporter PDF</a>
                    <a href="../../Controller/export_excel.php" class="export-btn export-excel">📊 Exporter Excel</a>
                    <button class="btn btn-add" onclick="openAddAllergieModal()">➕ Ajouter une allergie</button>
                </div>
            </div>
            <div class="card-body" style="padding: 1rem;">
                
                <?php if (empty($allergies)): ?>
                    <div style="text-align: center; padding: 3rem; color: #999;">
                        <i class="fas fa-database" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                        Aucune allergie trouvée. Cliquez sur "Ajouter une allergie" pour commencer.
                    </div>
                <?php else: ?>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 1.2rem;">
                    <?php foreach ($allergies as $a): ?>
                    <div style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #e5edd9; transition: transform 0.2s, box-shadow 0.2s;">
                        <div style="padding: 1rem;">
                            <!-- En-tête -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem; flex-wrap: wrap; gap: 0.5rem;">
                                <h3 style="color: #2d5016; font-size: 1.1rem; margin: 0;">🏷️ <?= htmlspecialchars($a['nom']) ?></h3>
                                <span class="badge badge-<?= $a['gravite'] ?>" style="font-size: 0.7rem;"><?= ucfirst($a['gravite']) ?></span>
                            </div>
                            
                            <!-- Catégorie -->
                            <div style="margin-bottom: 0.5rem;">
                                <span style="background: #f0f4e8; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.7rem; color: #2d5016;">
                                    📂 <?= htmlspecialchars($a['categorie']) ?>
                                </span>
                            </div>
                            
                            <!-- Image -->
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.8rem; flex-wrap: wrap;">
                                <?php 
                                $imagePath = $a['image_url'] ?? null;
                                $fullImagePath = __DIR__ . '/../../' . $imagePath;
                                if ($imagePath && file_exists($fullImagePath)): 
                                ?>
                                    <img src="../../<?= $imagePath ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 10px; border: 2px solid #c8ddb8;">
                                <?php else: ?>
                                    <div style="width: 50px; height: 50px; background: #f0f4e8; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; border: 2px dashed #c8ddb8;">🖼️</div>
                                <?php endif; ?>
                                <div style="font-size: 0.7rem; color: #666;">
                                    <?php if ($imagePath && file_exists($fullImagePath)): ?>
                                        <button class="btn btn-edit" onclick="openImageModal(<?= $a['id'] ?>, '<?= htmlspecialchars($a['nom']) ?>')" style="font-size: 0.6rem; padding: 0.2rem 0.5rem;">✏️ Changer</button>
                                        <a href="../../Controller/delete_image.php?id=<?= $a['id'] ?>" class="btn btn-delete" style="font-size: 0.6rem; padding: 0.2rem 0.5rem;" onclick="return confirm('Supprimer cette image ?')">🗑️ Supprimer</a>
                                    <?php else: ?>
                                        <button class="btn btn-add" onclick="openImageModal(<?= $a['id'] ?>, '<?= htmlspecialchars($a['nom']) ?>')" style="font-size: 0.6rem; padding: 0.2rem 0.5rem;">➕ Ajouter</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <div style="margin-bottom: 0.5rem;">
                                <strong style="font-size: 0.75rem; color: #555;">📝 Description</strong>
                                <p style="font-size: 0.75rem; color: #666; margin: 0.2rem 0 0 0; line-height: 1.4;"><?= htmlspecialchars(substr($a['description'], 0, 80)) ?>...</p>
                            </div>
                            
                            <!-- Symptômes -->
                            <div style="margin-bottom: 0.5rem;">
                                <strong style="font-size: 0.75rem; color: #555;">⚠️ Symptômes</strong>
                                <p style="font-size: 0.75rem; color: #666; margin: 0.2rem 0 0 0; line-height: 1.4;"><?= htmlspecialchars(substr($a['symptomes'], 0, 60)) ?>...</p>
                            </div>
                            
                            <!-- Déclencheurs -->
                            <div style="margin-bottom: 0.8rem;">
                                <strong style="font-size: 0.75rem; color: #555;">🚫 Déclencheurs</strong>
                                <p style="font-size: 0.75rem; color: #666; margin: 0.2rem 0 0 0; line-height: 1.4;"><?= htmlspecialchars(substr($a['declencheurs'], 0, 60)) ?>...</p>
                            </div>
                            
                            <!-- Boutons actions - SUPPRESSION DU CONFIRM HTML5 -->
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.8rem; padding-top: 0.8rem; border-top: 1px solid #eee;">
                                <button class="btn btn-edit" onclick="editAllergie(<?= $a['id'] ?>)" style="flex: 1; justify-content: center;">✏️ Modifier</button>
                                <button class="btn-treatment" onclick="openTraitementModal(<?= $a['id'] ?>, '<?= htmlspecialchars($a['nom']) ?>')" style="flex: 1; justify-content: center;">💊 Traitement</button>
                                <a href="?delete_allergie=<?= $a['id'] ?>" class="btn btn-delete" style="flex: 1; justify-content: center;">🗑️ Supprimer</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php endif; ?>
            </div>
        </div>

        <!-- GRAPHIQUES -->
        <div class="stats-bottom">
            <div class="stat-chart-card">
                <h4>📊 Répartition par catégorie</h4>
                <div class="chart-wrap"><canvas id="categorieChart"></canvas></div>
            </div>
            <div class="stat-chart-card">
                <h4>⚡ Répartition par gravité</h4>
                <div class="chart-wrap"><canvas id="graviteChart"></canvas></div>
            </div>
        </div>
    </div>

    <footer class="footer"><p>© 2024 NutriFlow AI - Mangez sainement, vivez pleinement</p></footer>

    <!-- MODAL ALLERGIE -->
    <div id="allergie-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header" id="allergie-modal-title">➕ Ajouter une allergie</div>
            <div class="modal-body">
                <form id="allergie-form" method="POST">
                    <input type="hidden" name="id" id="allergie_id">
                    <div class="form-group"><label>🏷️ Nom *</label><input type="text" name="nom" id="allergie_nom" required><div class="error-message" id="allergie_nom_error">⚠️ Pas de chiffres</div></div>
                    <div class="form-group"><label>📂 Catégorie *</label><select name="categorie" id="allergie_categorie" required><option value="">Sélectionner</option><option value="Alimentaire">Alimentaire</option><option value="Respiratoire">Respiratoire</option><option value="Cutane">Cutane</option><option value="Médicamenteuse">Médicamenteuse</option></select><div class="error-message" id="allergie_categorie_error">⚠️ Obligatoire</div></div>
                    <div class="form-group"><label>📝 Description *</label><textarea name="description" id="allergie_description" rows="3" required></textarea><div class="error-message" id="allergie_description_error">⚠️ Min 10 caractères</div></div>
                    <div class="form-group"><label>⚠️ Symptômes *</label><textarea name="symptomes" id="allergie_symptomes" rows="3" required></textarea><div class="error-message" id="allergie_symptomes_error">⚠️ Min 10 caractères</div></div>
                    <div class="form-group"><label>🚫 Déclencheurs *</label><input type="text" name="declencheurs" id="allergie_declencheurs" required><div class="error-message" id="allergie_declencheurs_error">⚠️ Obligatoire</div></div>
                    <div class="form-group"><label>⚡ Gravité *</label><select name="gravite" id="allergie_gravite" required><option value="">Sélectionner</option><option value="legere">Légère</option><option value="moderate">Modérée</option><option value="severe">Sévère</option></select><div class="error-message" id="allergie_gravite_error">⚠️ Obligatoire</div></div>
                    <div class="modal-footer"><button type="button" class="btn btn-cancel" onclick="closeAllergieModal()">Annuler</button><button type="submit" class="btn btn-save" name="add_allergie" id="allergie_submit_btn">💾 Enregistrer</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TRAITEMENT -->
    <div id="traitement-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">💊 Gestion du traitement</div>
            <div class="modal-body">
                <form id="traitement-form" method="POST" action="../../Controller/save_traitement.php">
                    <input type="hidden" name="allergie_id" id="traitement_allergie_id">
                    <div class="form-group"><label>🔬 Allergie</label><input type="text" id="traitement_allergie_nom" readonly style="background:#f0f0f0;"></div>
                    <div class="form-group"><label>💡 Conseils *</label><textarea name="conseil" id="traitement_conseil" rows="3" required></textarea><div class="error-message" id="traitement_conseil_error">⚠️ Min 10 caractères</div></div>
                    <div class="form-group"><label>🚫 Interdits *</label><textarea name="interdits" id="traitement_interdits" rows="3" required></textarea><div class="error-message" id="traitement_interdits_error">⚠️ Min 5 caractères</div></div>
                    <div class="form-group"><label>💊 Médicaments</label><input type="text" name="medicaments" id="traitement_medicaments" placeholder="Ex: Antihistaminiques"></div>
                    <div class="form-group"><label>💊 Médicaments d'urgence</label><input type="text" name="medicaments_urgence" id="traitement_medicaments_urgence" placeholder="Ex: EpiPen"></div>
                    <div class="form-group"><label>⏰ Durée</label><input type="text" name="duree" id="traitement_duree" placeholder="Ex: Permanente"></div>
                    <div class="form-group"><label>🚨 Niveau d'urgence *</label><select name="niveau_urgence" id="traitement_niveau_urgence" required><option value="faible">🟢 Faible</option><option value="moyen">🟠 Moyen</option><option value="eleve">🔴 Élevé</option></select><div class="error-message" id="traitement_urgence_error">⚠️ Obligatoire</div></div>
                    <div class="modal-footer"><button type="button" class="btn btn-cancel" onclick="closeTraitementModal()">Annuler</button><button type="submit" class="btn btn-save">💾 Enregistrer</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL IMAGE -->
    <div id="image-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">🖼️ Gérer l'image</div>
            <div class="modal-body">
                <form id="image-form" action="../../Controller/update_image.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="allergie_id" id="image_allergie_id">
                    <div class="form-group">
                        <label>Allergie</label>
                        <input type="text" id="image_allergie_nom" readonly style="background:#f0f0f0;">
                    </div>
                    <div class="form-group">
                        <label>Choisir une image</label>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" required>
                        <small>Formats : JPG, PNG, GIF, WEBP</small>
                    </div>
                    <div id="image_preview_container" style="margin-top: 10px;"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" onclick="closeImageModal()">Annuler</button>
                        <button type="submit" class="btn btn-save">💾 Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // GRAPHIQUES
        const categories = <?php echo json_encode($categories); ?>;
        const gravites   = <?php echo json_encode($gravites); ?>;

        new Chart(document.getElementById('categorieChart'), {
            type: 'doughnut',
            data: {
                labels: categories.map(c => c.categorie),
                datasets: [{
                    data: categories.map(c => c.total),
                    backgroundColor: ['#4caf50', '#2196F3', '#ff9800', '#9c27b0'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
        });

        new Chart(document.getElementById('graviteChart'), {
            type: 'bar',
            data: {
                labels: gravites.map(g => g.gravite.charAt(0).toUpperCase() + g.gravite.slice(1)),
                datasets: [{
                    label: 'Allergies',
                    data: gravites.map(g => g.total),
                    backgroundColor: ['#4caf50', '#ff9800', '#f44336'],
                    borderRadius: 8
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });

        // VALIDATIONS
        function validateAllergieNom() { 
            let input = document.getElementById('allergie_nom'); 
            let error = document.getElementById('allergie_nom_error'); 
            if (input.value.trim() === '') { error.classList.add('show'); input.classList.add('error'); return false; } 
            else if (/[0-9]/.test(input.value)) { error.classList.add('show'); input.classList.add('error'); return false; } 
            else { error.classList.remove('show'); input.classList.remove('error'); input.classList.add('valid'); return true; } 
        }
        function validateAllergieDescription() { 
            let input = document.getElementById('allergie_description'); 
            let error = document.getElementById('allergie_description_error'); 
            if (input.value.trim().length < 10 && input.value.trim().length > 0) { error.classList.add('show'); input.classList.add('error'); return false; } 
            else if (input.value.trim() === '') { error.classList.add('show'); input.classList.add('error'); return false; } 
            else { error.classList.remove('show'); input.classList.remove('error'); input.classList.add('valid'); return true; } 
        }
        function validateAllergieSymptomes() { 
            let input = document.getElementById('allergie_symptomes'); 
            let error = document.getElementById('allergie_symptomes_error'); 
            if (input.value.trim().length < 10 && input.value.trim().length > 0) { error.classList.add('show'); input.classList.add('error'); return false; } 
            else if (input.value.trim() === '') { error.classList.add('show'); input.classList.add('error'); return false; } 
            else { error.classList.remove('show'); input.classList.remove('error'); input.classList.add('valid'); return true; } 
        }
        function validateAllergieCategorie() { 
            let select = document.getElementById('allergie_categorie'); 
            let error = document.getElementById('allergie_categorie_error'); 
            if (select.value === '') { error.classList.add('show'); select.classList.add('error'); return false; } 
            else { error.classList.remove('show'); select.classList.remove('error'); select.classList.add('valid'); return true; } 
        }
        function validateAllergieDeclencheurs() { 
            let input = document.getElementById('allergie_declencheurs'); 
            let error = document.getElementById('allergie_declencheurs_error'); 
            if (input.value.trim() === '') { error.classList.add('show'); input.classList.add('error'); return false; } 
            else { error.classList.remove('show'); input.classList.remove('error'); input.classList.add('valid'); return true; } 
        }
        function validateAllergieGravite() { 
            let select = document.getElementById('allergie_gravite'); 
            let error = document.getElementById('allergie_gravite_error'); 
            if (select.value === '') { error.classList.add('show'); select.classList.add('error'); return false; } 
            else { error.classList.remove('show'); select.classList.remove('error'); select.classList.add('valid'); return true; } 
        }
        function validateAllergieForm() { 
            let isValid = validateAllergieNom() && validateAllergieCategorie() && validateAllergieDescription() && validateAllergieSymptomes() && validateAllergieDeclencheurs() && validateAllergieGravite(); 
            document.getElementById('allergie_submit_btn').disabled = !isValid; 
            return isValid; 
        }

        function validateTraitementConseil() { 
            let input = document.getElementById('traitement_conseil'); 
            let error = document.getElementById('traitement_conseil_error'); 
            if (input.value.trim().length < 10 && input.value.trim().length > 0) { error.classList.add('show'); input.classList.add('error'); return false; } 
            else if (input.value.trim() === '') { error.classList.add('show'); input.classList.add('error'); return false; } 
            else { error.classList.remove('show'); input.classList.remove('error'); input.classList.add('valid'); return true; } 
        }
        function validateTraitementInterdits() { 
            let input = document.getElementById('traitement_interdits'); 
            let error = document.getElementById('traitement_interdits_error'); 
            if (input.value.trim().length < 5 && input.value.trim().length > 0) { error.classList.add('show'); input.classList.add('error'); return false; } 
            else if (input.value.trim() === '') { error.classList.add('show'); input.classList.add('error'); return false; } 
            else { error.classList.remove('show'); input.classList.remove('error'); input.classList.add('valid'); return true; } 
        }
        function validateTraitementUrgence() { 
            let select = document.getElementById('traitement_niveau_urgence'); 
            let error = document.getElementById('traitement_urgence_error'); 
            if (select.value === '') { error.classList.add('show'); select.classList.add('error'); return false; } 
            else { error.classList.remove('show'); select.classList.remove('error'); select.classList.add('valid'); return true; } 
        }

        // Écouteurs
        document.getElementById('allergie_nom')?.addEventListener('input', validateAllergieForm);
        document.getElementById('allergie_categorie')?.addEventListener('change', validateAllergieForm);
        document.getElementById('allergie_description')?.addEventListener('input', validateAllergieForm);
        document.getElementById('allergie_symptomes')?.addEventListener('input', validateAllergieForm);
        document.getElementById('allergie_declencheurs')?.addEventListener('input', validateAllergieForm);
        document.getElementById('allergie_gravite')?.addEventListener('change', validateAllergieForm);
        document.getElementById('traitement_conseil')?.addEventListener('input', validateTraitementConseil);
        document.getElementById('traitement_interdits')?.addEventListener('input', validateTraitementInterdits);
        document.getElementById('traitement_niveau_urgence')?.addEventListener('change', validateTraitementUrgence);

        // Aperçu image
        document.querySelector('#image-form input[type="file"]')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const container = document.getElementById('image_preview_container');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) { container.innerHTML = `<img src="${event.target.result}" style="max-width: 100%; max-height: 150px; border-radius: 10px;">`; };
                reader.readAsDataURL(file);
            } else { container.innerHTML = ''; }
        });

        // MODALES
        function openAddAllergieModal() { 
            document.getElementById('allergie-form').reset(); 
            document.getElementById('allergie_id').value = ''; 
            document.getElementById('allergie-modal-title').textContent = '➕ Ajouter une allergie'; 
            document.getElementById('allergie_submit_btn').name = 'add_allergie'; 
            document.getElementById('allergie-modal').classList.add('active'); 
            validateAllergieForm(); 
        }
        function editAllergie(id) { 
            fetch(`../../Controller/get_allergie.php?id=${id}`).then(r => r.json()).then(data => { 
                document.getElementById('allergie_id').value = data.id; 
                document.getElementById('allergie_nom').value = data.nom; 
                document.getElementById('allergie_categorie').value = data.categorie; 
                document.getElementById('allergie_description').value = data.description; 
                document.getElementById('allergie_symptomes').value = data.symptomes; 
                document.getElementById('allergie_declencheurs').value = data.declencheurs; 
                document.getElementById('allergie_gravite').value = data.gravite; 
                document.getElementById('allergie-modal-title').textContent = '✏️ Modifier une allergie'; 
                document.getElementById('allergie_submit_btn').name = 'edit_allergie'; 
                document.getElementById('allergie-modal').classList.add('active'); 
                validateAllergieForm(); 
            }); 
        }
        function closeAllergieModal() { document.getElementById('allergie-modal').classList.remove('active'); }
        
        function openTraitementModal(allergieId, allergieNom) { 
            document.getElementById('traitement-form').reset(); 
            document.getElementById('traitement_allergie_id').value = allergieId; 
            document.getElementById('traitement_allergie_nom').value = allergieNom; 
            document.getElementById('traitement_medicaments_urgence').value = ''; 
            document.getElementById('traitement-modal').classList.add('active'); 
            fetch(`../../Controller/get_traitement_by_allergie.php?allergie_id=${allergieId}`).then(r => r.json()).then(data => { 
                if (data && data.id) { 
                    document.getElementById('traitement_conseil').value = data.conseil || ''; 
                    document.getElementById('traitement_interdits').value = data.interdits || ''; 
                    document.getElementById('traitement_medicaments').value = data.medicaments || ''; 
                    document.getElementById('traitement_medicaments_urgence').value = data.medicaments_urgence || ''; 
                    document.getElementById('traitement_duree').value = data.duree || ''; 
                    document.getElementById('traitement_niveau_urgence').value = data.niveau_urgence || 'faible'; 
                    document.getElementById('traitement_conseil').classList.add('valid'); 
                    document.getElementById('traitement_interdits').classList.add('valid'); 
                } 
                validateTraitementConseil(); validateTraitementInterdits(); validateTraitementUrgence(); 
            }); 
        }
        function closeTraitementModal() { document.getElementById('traitement-modal').classList.remove('active'); }
        
        function openImageModal(id, nom) {
            document.getElementById('image_allergie_id').value = id;
            document.getElementById('image_allergie_nom').value = nom;
            document.getElementById('image-modal').classList.add('active');
            document.getElementById('image_preview_container').innerHTML = '';
            document.querySelector('#image-form input[type="file"]').value = '';
        }
        function closeImageModal() { document.getElementById('image-modal').classList.remove('active'); }
        
        window.onclick = function(event) { if (event.target.classList.contains('modal')) event.target.classList.remove('active'); }
        
        // Rafraîchissement auto des logs
        setInterval(function() {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newLogsBody = doc.querySelector('.timeline-card .log-table tbody');
                    const oldLogsBody = document.querySelector('.timeline-card .log-table tbody');
                    if (newLogsBody && oldLogsBody) {
                        oldLogsBody.innerHTML = newLogsBody.innerHTML;
                    }
                })
                .catch(err => console.log('Refresh logs error:', err));
        }, 30000);
    </script>
</body>
</html>