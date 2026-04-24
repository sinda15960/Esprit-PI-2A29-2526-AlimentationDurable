<?php
session_start();
require_once __DIR__ . '/../../Config/Database.php';

$db = Database::getInstance()->getConnection();

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

// ==================== CRUD ALLERGIES ====================

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

// Supprimer une allergie (sans confirmation popup)
if (isset($_GET['delete_allergie'])) {
    $id = $_GET['delete_allergie'];
    // Supprimer l'image associée
    $stmt = $db->prepare("SELECT image_url FROM allergies WHERE id = ?");
    $stmt->execute([$id]);
    $image = $stmt->fetchColumn();
    if ($image && file_exists(__DIR__ . '/../../' . $image)) {
        unlink(__DIR__ . '/../../' . $image);
    }
    $db->prepare("DELETE FROM traitements WHERE allergie_id=?")->execute([$id]);
    $db->prepare("DELETE FROM allergies WHERE id=?")->execute([$id]);
    $_SESSION['success'] = "✅ Allergie supprimée avec succès !";
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// ==================== RÉCUPÉRATION DES DONNÉES ====================
$allergies = $db->query("SELECT * FROM allergies ORDER BY nom")->fetchAll();

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success']);
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { 
            background: white; 
            padding: 1.5rem; 
            border-radius: 20px; 
            text-align: center; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.12); }
        .stat-number { font-size: 2.5rem; font-weight: 700; color: #2d5016; }
        .stat-label { font-size: 0.9rem; color: #666; margin-top: 0.5rem; font-weight: 500; }
        .card { 
            background: white; 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
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
        .export-buttons { display: flex; gap: 0.5rem; }
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
        .card-body { padding: 1.5rem; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: 600; color: #2d5016; font-size: 0.9rem; }
        tr:hover { background: #f9f9f9; }
        .badge { display: inline-block; padding: 0.3rem 0.8rem; border-radius: 30px; font-size: 0.75rem; font-weight: 600; }
        .badge-legere { background: #4caf50; color: white; }
        .badge-moderate { background: #ff9800; color: white; }
        .badge-severe { background: #f44336; color: white; }
        .btn { 
            padding: 0.5rem 1rem; 
            border: none; 
            border-radius: 12px; 
            cursor: pointer; 
            font-size: 0.8rem; 
            margin: 0 0.2rem; 
            display: inline-flex; 
            align-items: center; 
            gap: 6px; 
            font-weight: 500; 
            transition: all 0.2s ease; 
        }
        .btn-add { background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%); color: white; border: none; padding: 0.6rem 1.2rem; }
        .btn-add:hover { transform: scale(1.02); box-shadow: 0 4px 12px rgba(45,80,22,0.3); }
        .btn-edit { background: #2196F3; color: white; }
        .btn-edit:hover { background: #0b7dda; transform: scale(1.02); }
        .btn-delete { background: #f44336; color: white; }
        .btn-delete:hover { background: #d32f2f; transform: scale(1.02); }
        .btn-save { background: #4caf50; color: white; padding: 0.6rem 1.2rem; }
        .btn-save:hover { background: #45a049; transform: scale(1.02); }
        .btn-cancel { background: #999; color: white; padding: 0.6rem 1.2rem; }
        .btn-cancel:hover { background: #777; }
        .btn-treatment {
            background: linear-gradient(135deg, #9c27b0 0%, #6a1b9a 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(156,39,176,0.3);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-treatment:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(156,39,176,0.4); }
        .btn-treatment::before { content: "💊"; font-size: 0.9rem; }
        .action-buttons { display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center; }
        .image-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 10px; border: 1px solid #ddd; }
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
            transition: all 0.3s ease; 
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #4a7c2b; box-shadow: 0 0 0 3px rgba(74,124,43,0.1); }
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
            th, td { font-size: 0.75rem; padding: 0.5rem; } 
            .action-buttons { flex-direction: column; gap: 0.3rem; } 
            .card-header { flex-direction: column; align-items: stretch; }
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

        <div class="stats">
            <div class="stat-card"><div class="stat-number"><?= count($allergies) ?></div><div class="stat-label">🔬 Allergies</div></div>
            <div class="stat-card"><div class="stat-number"><?= $db->query("SELECT COUNT(*) FROM traitements")->fetchColumn() ?></div><div class="stat-label">💊 Traitements</div></div>
            <div class="stat-card"><div class="stat-number"><?= $db->query("SELECT COUNT(*) FROM feedbacks")->fetchColumn() ?></div><div class="stat-label">💬 Feedbacks</div></div>
        </div>

        <!-- TABLE DES ALLERGIES -->
        <div class="card">
            <div class="card-header">
                <span>📋 Liste des allergies</span>
                <div class="export-buttons">
                    <a href="../../Controller/export_pdf.php" target="_blank" class="export-btn export-pdf">📄 Exporter PDF</a>
                    <a href="../../Controller/export_excel.php" class="export-btn export-excel">📊 Exporter Excel</a>
                    <button class="btn btn-add" onclick="openAddAllergieModal()">➕ Ajouter une allergie</button>
                </div>
            </div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr><th>🏷️ Nom</th><th>📂 Catégorie</th><th>🖼️ Image</th><th>📝 Description</th><th>⚠️ Symptômes</th><th>🚫 Déclencheurs</th><th>⚡ Gravité</th><th>🎯 Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allergies as $a): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($a['nom']) ?></strong></td>
                            <td><?= htmlspecialchars($a['categorie']) ?></td>
                            <td>
                                <?php 
                                $imagePath = $a['image_url'] ?? null;
                                $fullImagePath = __DIR__ . '/../../' . $imagePath;
                                if ($imagePath && file_exists($fullImagePath)): 
                                ?>
                                    <div style="text-align: center;">
                                        <img src="../../<?= $imagePath ?>" class="image-preview">
                                        <div style="margin-top: 5px; display: flex; gap: 5px; flex-wrap: wrap; justify-content: center;">
                                            <button class="btn btn-edit" onclick="openImageModal(<?= $a['id'] ?>, '<?= htmlspecialchars($a['nom']) ?>')" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">✏️ Modifier</button>
                                            <a href="../../Controller/delete_image.php?id=<?= $a['id'] ?>" class="btn btn-delete" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;" onclick="return confirm('Supprimer cette image ?')">🗑️ Supprimer</a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div style="text-align: center;">
                                        <span style="color: #999;">Aucune image</span>
                                        <div style="margin-top: 5px;">
                                            <button class="btn btn-add" onclick="openImageModal(<?= $a['id'] ?>, '<?= htmlspecialchars($a['nom']) ?>')" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">➕ Ajouter une image</button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                             </div>
                            </td>
                            <td><?= htmlspecialchars(substr($a['description'], 0, 50)) ?>...</td>
                            <td><?= htmlspecialchars(substr($a['symptomes'], 0, 40)) ?>...</td>
                            <td><?= htmlspecialchars(substr($a['declencheurs'], 0, 40)) ?>...</td>
                            <td><span class="badge badge-<?= $a['gravite'] ?>"><?= ucfirst($a['gravite']) ?></span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-edit" onclick="editAllergie(<?= $a['id'] ?>)">✏️ Modifier</button>
                                    <button class="btn-treatment" onclick="openTraitementModal(<?= $a['id'] ?>, '<?= htmlspecialchars($a['nom']) ?>')">💊 Gérer traitement</button>
                                    <a href="?delete_allergie=<?= $a['id'] ?>" class="btn btn-delete">🗑️ Supprimer</a>
                                </div>
                             </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
                    <div class="form-group"><label>🏷️ Nom *</label><input type="text" name="nom" id="allergie_nom" required><div class="error-message" id="allergie_nom_error">⚠️ Le nom ne doit pas contenir de chiffres</div></div>
                    <div class="form-group"><label>📂 Catégorie *</label><select name="categorie" id="allergie_categorie" required><option value="">Sélectionner</option><option value="Alimentaire">Alimentaire</option><option value="Respiratoire">Respiratoire</option><option value="Cutane">Cutane</option><option value="Médicamenteuse">Médicamenteuse</option></select><div class="error-message" id="allergie_categorie_error">⚠️ Veuillez sélectionner une catégorie</div></div>
                    <div class="form-group"><label>📝 Description *</label><textarea name="description" id="allergie_description" rows="3" required></textarea><div class="error-message" id="allergie_description_error">⚠️ Minimum 10 caractères</div></div>
                    <div class="form-group"><label>⚠️ Symptômes *</label><textarea name="symptomes" id="allergie_symptomes" rows="3" required></textarea><div class="error-message" id="allergie_symptomes_error">⚠️ Minimum 10 caractères</div></div>
                    <div class="form-group"><label>🚫 Déclencheurs *</label><input type="text" name="declencheurs" id="allergie_declencheurs" required><div class="error-message" id="allergie_declencheurs_error">⚠️ Champ obligatoire</div></div>
                    <div class="form-group"><label>⚡ Gravité *</label><select name="gravite" id="allergie_gravite" required><option value="">Sélectionner</option><option value="legere">Légère</option><option value="moderate">Modérée</option><option value="severe">Sévère</option></select><div class="error-message" id="allergie_gravite_error">⚠️ Veuillez sélectionner une gravité</div></div>
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
                    <div class="form-group"><label>🔬 Allergie concernée</label><input type="text" id="traitement_allergie_nom" readonly style="background:#f0f0f0; font-weight:600;"></div>
                    <div class="form-group"><label>💡 Conseils *</label><textarea name="conseil" id="traitement_conseil" rows="3" required></textarea><div class="error-message" id="traitement_conseil_error">⚠️ Minimum 10 caractères</div></div>
                    <div class="form-group"><label>🚫 Interdits *</label><textarea name="interdits" id="traitement_interdits" rows="3" required></textarea><div class="error-message" id="traitement_interdits_error">⚠️ Minimum 5 caractères</div></div>
                    <div class="form-group"><label>💊 Médicaments</label><input type="text" name="medicaments" id="traitement_medicaments" placeholder="Ex: Antihistaminiques, EpiPen..."></div>
                    <div class="form-group"><label>⏰ Durée</label><input type="text" name="duree" id="traitement_duree" placeholder="Ex: Permanente, Temporaire..."></div>
                    <div class="form-group"><label>🚨 Niveau d'urgence *</label><select name="niveau_urgence" id="traitement_niveau_urgence" required><option value="faible">🟢 Faible</option><option value="moyen">🟠 Moyen</option><option value="eleve">🔴 Élevé</option></select><div class="error-message" id="traitement_urgence_error">⚠️ Veuillez sélectionner un niveau</div></div>
                    <div class="modal-footer"><button type="button" class="btn btn-cancel" onclick="closeTraitementModal()">Annuler</button><button type="submit" class="btn btn-save">💾 Enregistrer le traitement</button></div>
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
                        <input type="text" id="image_allergie_nom" readonly style="background:#f0f0f0; font-weight:600;">
                    </div>
                    <div class="form-group">
                        <label>Choisir une image</label>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" required>
                        <small style="color: #666; display: block; margin-top: 5px;">Formats : JPG, PNG, GIF, WEBP</small>
                    </div>
                    <div id="image_preview_container" style="margin-top: 10px;"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" onclick="closeImageModal()">Annuler</button>
                        <button type="submit" class="btn btn-save">💾 Enregistrer l'image</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Validation Allergie
        function validateAllergieNom() { 
            let input = document.getElementById('allergie_nom'); 
            let error = document.getElementById('allergie_nom_error'); 
            if (input.value.trim() === '') { 
                error.classList.add('show'); 
                input.classList.add('error'); 
                return false; 
            } else if (/[0-9]/.test(input.value)) { 
                error.classList.add('show'); 
                input.classList.add('error'); 
                return false; 
            } else { 
                error.classList.remove('show'); 
                input.classList.remove('error'); 
                input.classList.add('valid'); 
                return true; 
            } 
        }
        function validateAllergieDescription() { 
            let input = document.getElementById('allergie_description'); 
            let error = document.getElementById('allergie_description_error'); 
            if (input.value.trim().length < 10 && input.value.trim().length > 0) { 
                error.classList.add('show'); 
                input.classList.add('error'); 
                return false; 
            } else if (input.value.trim() === '') { 
                error.classList.add('show'); 
                input.classList.add('error'); 
                return false; 
            } else { 
                error.classList.remove('show'); 
                input.classList.remove('error'); 
                input.classList.add('valid'); 
                return true; 
            } 
        }
        function validateAllergieSymptomes() { 
            let input = document.getElementById('allergie_symptomes'); 
            let error = document.getElementById('allergie_symptomes_error'); 
            if (input.value.trim().length < 10 && input.value.trim().length > 0) { 
                error.classList.add('show'); 
                input.classList.add('error'); 
                return false; 
            } else if (input.value.trim() === '') { 
                error.classList.add('show'); 
                input.classList.add('error'); 
                return false; 
            } else { 
                error.classList.remove('show'); 
                input.classList.remove('error'); 
                input.classList.add('valid'); 
                return true; 
            } 
        }
        function validateAllergieCategorie() { 
            let select = document.getElementById('allergie_categorie'); 
            let error = document.getElementById('allergie_categorie_error'); 
            if (select.value === '') { 
                error.classList.add('show'); 
                select.classList.add('error'); 
                return false; 
            } else { 
                error.classList.remove('show'); 
                select.classList.remove('error'); 
                select.classList.add('valid'); 
                return true; 
            } 
        }
        function validateAllergieDeclencheurs() { 
            let input = document.getElementById('allergie_declencheurs'); 
            let error = document.getElementById('allergie_declencheurs_error'); 
            if (input.value.trim() === '') { 
                error.classList.add('show'); 
                input.classList.add('error'); 
                return false; 
            } else { 
                error.classList.remove('show'); 
                input.classList.remove('error'); 
                input.classList.add('valid'); 
                return true; 
            } 
        }
        function validateAllergieGravite() { 
            let select = document.getElementById('allergie_gravite'); 
            let error = document.getElementById('allergie_gravite_error'); 
            if (select.value === '') { 
                error.classList.add('show'); 
                select.classList.add('error'); 
                return false; 
            } else { 
                error.classList.remove('show'); 
                select.classList.remove('error'); 
                select.classList.add('valid'); 
                return true; 
            } 
        }
        function validateAllergieForm() { 
            let isValid = validateAllergieNom() && validateAllergieCategorie() && validateAllergieDescription() && validateAllergieSymptomes() && validateAllergieDeclencheurs() && validateAllergieGravite(); 
            document.getElementById('allergie_submit_btn').disabled = !isValid; 
            return isValid; 
        }

        // Validation Traitement
        function validateTraitementConseil() { 
            let input = document.getElementById('traitement_conseil'); 
            let error = document.getElementById('traitement_conseil_error'); 
            if (input.value.trim().length < 10 && input.value.trim().length > 0) { 
                error.classList.add('show'); 
                input.classList.add('error'); 
                return false; 
            } else if (input.value.trim() === '') { 
                error.classList.add('show'); 
                input.classList.add('error'); 
                return false; 
            } else { 
                error.classList.remove('show'); 
                input.classList.remove('error'); 
                input.classList.add('valid'); 
                return true; 
            } 
        }
        function validateTraitementInterdits() { 
            let input = document.getElementById('traitement_interdits'); 
            let error = document.getElementById('traitement_interdits_error'); 
            if (input.value.trim().length < 5 && input.value.trim().length > 0) { 
                error.classList.add('show'); 
                input.classList.add('error'); 
                return false; 
            } else if (input.value.trim() === '') { 
                error.classList.add('show'); 
                input.classList.add('error'); 
                return false; 
            } else { 
                error.classList.remove('show'); 
                input.classList.remove('error'); 
                input.classList.add('valid'); 
                return true; 
            } 
        }
        function validateTraitementUrgence() { 
            let select = document.getElementById('traitement_niveau_urgence'); 
            let error = document.getElementById('traitement_urgence_error'); 
            if (select.value === '') { 
                error.classList.add('show'); 
                select.classList.add('error'); 
                return false; 
            } else { 
                error.classList.remove('show'); 
                select.classList.remove('error'); 
                select.classList.add('valid'); 
                return true; 
            } 
        }

        // Écouteurs d'événements
        document.getElementById('allergie_nom').addEventListener('input', validateAllergieForm);
        document.getElementById('allergie_categorie').addEventListener('change', validateAllergieForm);
        document.getElementById('allergie_description').addEventListener('input', validateAllergieForm);
        document.getElementById('allergie_symptomes').addEventListener('input', validateAllergieForm);
        document.getElementById('allergie_declencheurs').addEventListener('input', validateAllergieForm);
        document.getElementById('allergie_gravite').addEventListener('change', validateAllergieForm);
        document.getElementById('traitement_conseil').addEventListener('input', validateTraitementConseil);
        document.getElementById('traitement_interdits').addEventListener('input', validateTraitementInterdits);
        document.getElementById('traitement_niveau_urgence').addEventListener('change', validateTraitementUrgence);

        // Aperçu de l'image avant upload
        document.querySelector('#image-form input[type="file"]')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const container = document.getElementById('image_preview_container');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    container.innerHTML = `<img src="${event.target.result}" style="max-width: 100%; max-height: 150px; border-radius: 10px; border: 1px solid #ddd;">`;
                };
                reader.readAsDataURL(file);
            } else {
                container.innerHTML = '';
            }
        });

        // Fonctions modales
        function openAddAllergieModal() { 
            document.getElementById('allergie-form').reset(); 
            document.getElementById('allergie_id').value = ''; 
            document.getElementById('allergie-modal-title').textContent = '➕ Ajouter une allergie'; 
            document.getElementById('allergie_submit_btn').name = 'add_allergie'; 
            document.getElementById('allergie-modal').classList.add('active'); 
            validateAllergieForm(); 
        }
        function editAllergie(id) { 
            fetch(`../../Controller/get_allergie.php?id=${id}`).then(response => response.json()).then(data => { 
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
            document.getElementById('traitement-modal').classList.add('active'); 
            fetch(`../../Controller/get_traitement_by_allergie.php?allergie_id=${allergieId}`).then(response => response.json()).then(data => { 
                if (data && data.id) { 
                    document.getElementById('traitement_conseil').value = data.conseil || ''; 
                    document.getElementById('traitement_interdits').value = data.interdits || ''; 
                    document.getElementById('traitement_medicaments').value = data.medicaments || ''; 
                    document.getElementById('traitement_duree').value = data.duree || ''; 
                    document.getElementById('traitement_niveau_urgence').value = data.niveau_urgence || 'faible'; 
                    document.getElementById('traitement_conseil').classList.add('valid'); 
                    document.getElementById('traitement_interdits').classList.add('valid'); 
                } 
                validateTraitementConseil(); 
                validateTraitementInterdits(); 
                validateTraitementUrgence(); 
            }); 
        }
        function closeTraitementModal() { document.getElementById('traitement-modal').classList.remove('active'); }
        
        function openImageModal(id, nom) {
            document.getElementById('image_allergie_id').value = id;
            document.getElementById('image_allergie_nom').value = nom;
            document.getElementById('image-modal').classList.add('active');
            document.getElementById('image_preview_container').innerHTML = '';
            const fileInput = document.querySelector('#image-form input[type="file"]');
            if (fileInput) fileInput.value = '';
        }
        function closeImageModal() {
            document.getElementById('image-modal').classList.remove('active');
        }
        
        window.onclick = function(event) { 
            if (event.target.classList.contains('modal')) { 
                event.target.classList.remove('active'); 
            } 
        }
    </script>
</body>
</html>