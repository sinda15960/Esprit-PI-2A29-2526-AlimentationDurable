<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../../Model/Allergie.php';
require_once __DIR__ . '/../../Model/Traitement.php';
require_once __DIR__ . '/../../Model/Feedback.php';

$allergies = Allergie::findAll();
$traitements = Traitement::findAll();
$feedbacks = Feedback::findAll();
$feedbacksPending = Feedback::findPending();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NutriFlow Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #2d5016 0%, #4a7c2b 100%); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1400px; margin: 2rem auto; padding: 0 2rem; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: white; padding: 1.5rem; border-radius: 10px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-number { font-size: 2rem; font-weight: bold; color: #2d5016; }
        .section { background: white; border-radius: 10px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .section h2 { color: #2d5016; margin-bottom: 1rem; border-left: 4px solid #4a7c2b; padding-left: 1rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; color: #2d5016; }
        .btn { padding: 0.3rem 0.8rem; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin: 0 0.2rem; font-size: 0.8rem; }
        .btn-add { background: #4a7c2b; color: white; padding: 0.5rem 1rem; }
        .btn-edit { background: #2196F3; color: white; }
        .btn-delete { background: #f44336; color: white; }
        .btn-approve { background: #4caf50; color: white; }
        .btn-reject { background: #ff9800; color: white; }
        .status { padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.75rem; }
        .status-approuve { background: #4caf50; color: white; }
        .status-en_attente { background: #ff9800; color: white; }
        .status-rejete { background: #f44336; color: white; }
        .logout { color: white; text-decoration: none; background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 5px; }
        .badge { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.7rem; font-weight: bold; }
        .badge-legere, .badge-faible { background: #4caf50; color: white; }
        .badge-moderate, .badge-moyen { background: #ff9800; color: white; }
        .badge-severe, .badge-eleve { background: #f44336; color: white; }
        @media (max-width: 768px) { .stats { grid-template-columns: repeat(2, 1fr); } th, td { font-size: 0.75rem; padding: 0.4rem; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>⚙️ NutriFlow AI - Administration</h1>
        <a href="logout.php" class="logout">🔓 Déconnexion</a>
    </div>
    
    <div class="container">
        <div class="stats">
            <div class="stat-card"><div class="stat-number"><?= count($allergies) ?></div><div>Allergies</div></div>
            <div class="stat-card"><div class="stat-number"><?= count($traitements) ?></div><div>Traitements</div></div>
            <div class="stat-card"><div class="stat-number"><?= count($feedbacks) ?></div><div>Feedbacks totaux</div></div>
            <div class="stat-card"><div class="stat-number"><?= count($feedbacksPending) ?></div><div>En attente</div></div>
        </div>
        
        <div class="section">
            <h2>📋 Gestion des Allergies</h2>
            <a href="ajouter_allergie.php" class="btn btn-add" style="margin-bottom: 1rem; display: inline-block;">+ Ajouter une allergie</a>
            <table>
                <thead><tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Gravité</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($allergies as $a): ?>
                    <tr>
                        <td><?= $a->getId() ?></td>
                        <td><?= htmlspecialchars($a->getNom()) ?></td>
                        <td><?= htmlspecialchars($a->getCategorie()) ?></td>
                        <td><span class="badge badge-<?= $a->getGravite() ?>"><?= $a->getGravite() ?></span></td>
                        <td>
                            <a href="modifier_allergie.php?id=<?= $a->getId() ?>" class="btn btn-edit">✏️</a>
                            <a href="supprimer_allergie.php?id=<?= $a->getId() ?>" class="btn btn-delete" onclick="return confirm('Supprimer ?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="section">
            <h2>💬 Gestion des Feedbacks</h2>
            <table>
                <thead><tr><th>ID</th><th>Type</th><th>Message</th><th>Email</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($feedbacks as $f): ?>
                    <tr>
                        <td><?= $f['id'] ?></td>
                        <td><?= htmlspecialchars($f['type']) ?></td>
                        <td><?= htmlspecialchars(substr($f['message'], 0, 50)) ?>...</td>
                        <td><?= htmlspecialchars($f['email'] ?: '-') ?></td>
                        <td><span class="status status-<?= $f['status'] ?>"><?= $f['status'] ?></span></td>
                        <td><?= $f['date_creation'] ?></td>
                        <td>
                            <?php if ($f['status'] == 'en_attente'): ?>
                                <a href="traiter_feedback.php?action=approve&id=<?= $f['id'] ?>" class="btn btn-approve">✓</a>
                                <a href="traiter_feedback.php?action=reject&id=<?= $f['id'] ?>" class="btn btn-reject">✗</a>
                            <?php endif; ?>
                            <a href="traiter_feedback.php?action=delete&id=<?= $f['id'] ?>" class="btn btn-delete" onclick="return confirm('Supprimer ?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>