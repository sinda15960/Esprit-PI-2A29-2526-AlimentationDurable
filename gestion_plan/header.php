<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EAT HEALTHY - Plan your meals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: 240px; min-height: 100vh;
            background: #2d5a27; position: fixed;
            top: 0; left: 0; z-index: 999;
        }
        .sidebar .logo {
            color: #fff; font-size: 1.2rem; font-weight: bold;
            padding: 25px 20px; border-bottom: 1px solid #3d7a35;
        }
        .sidebar a {
            display: block; color: #c8e6c4;
            padding: 14px 20px; text-decoration: none; transition: all 0.2s;
        }
        .sidebar a:hover { background: #3d7a35; color: #fff; padding-left: 28px; }
        .topbar {
            background: #2d5a27; color: #fff;
            padding: 12px 25px; position: fixed;
            top: 0; left: 240px; right: 0; z-index: 998;
            display: flex; justify-content: space-between; align-items: center;
        }
        .badge-admin {
            background: #ff9800; color: #fff;
            border-radius: 10px; padding: 3px 10px;
            font-size: 0.75rem; margin-left: 8px;
        }
        .main-content { margin-left: 240px; margin-top: 55px; padding: 30px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
        .btn-green { background: #2d5a27; color: #fff; border: none; }
        .btn-green:hover { background: #3d7a35; color: #fff; }
        .table thead { background: #2d5a27; color: #fff; }
        .error-msg { color: #dc3545; font-size: 0.85rem; margin-top: 4px; }
        .section-title {
            border-left: 4px solid #2d5a27; padding-left: 12px;
            color: #2d5a27; font-weight: bold; font-size: 1.4rem; margin-bottom: 20px;
        }
        .navbar { background: #2d5a27 !important; }
        .navbar-brand { color: #fff !important; font-weight: bold; font-size: 1.3rem; }
        .nav-link { color: #c8e6c4 !important; }
        .nav-link:hover { color: #fff !important; }
        .hero {
            background: linear-gradient(135deg, #2d5a27, #4a8f3f);
            color: white; padding: 70px 0; text-align: center;
        }
        .hero h1 { font-size: 3rem; font-weight: 900; }
        .card-feature {
            border: none; border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 25px; background: #fff; height: 100%;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }
        .user-badge {
            background: rgba(255,255,255,0.15); border-radius: 20px;
            padding: 5px 14px; font-size: 0.85rem; color: #fff;
        }
        .btn-coach {
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            color: #fff !important;
            border: none;
            font-weight: 600;
            animation: pulse-coach 2s infinite;
        }
        @keyframes pulse-coach {
            0%, 100% { box-shadow: 0 0 0 0 rgba(255,107,53,0.5); }
            50%       { box-shadow: 0 0 0 6px rgba(255,107,53,0); }
        }
    </style>
</head>
<body>

<?php
$isBack = (isset($_GET['office']) && $_GET['office'] === 'back');
?>

<?php if ($isBack): ?>
<!-- ========== BACK OFFICE (ADMIN) ========== -->
<div class="topbar">
    <span>⚙️ Mode Administration <span class="badge-admin">Admin</span></span>
    <a href="index.php?module=objectif&action=index&office=front" class="btn btn-sm btn-light">🌐 Voir FrontOffice</a>
</div>
<div class="sidebar">
    <div class="logo">🥗 EAT HEALTHY</div>
    <a href="index.php?module=objectif&action=index&office=back">🎯 Objectifs</a>
    <a href="index.php?module=categorie&action=index&office=back">📂 Categories</a>
    <a href="index.php?module=statistique&action=indexBack&office=back">📊 Statistiques</a>
</div>
<div class="main-content">

<?php else: ?>
<!-- ========== FRONT OFFICE (USER) ========== -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php?module=objectif&action=index&office=front">🥗 EAT HEALTHY</a>
        <div class="ms-auto d-flex gap-2 align-items-center flex-wrap">
            <a href="index.php?module=objectif&action=index&office=front"    class="btn btn-sm btn-light">🎯 Objectifs</a>
            <a href="index.php?module=programme&action=index&office=front"   class="btn btn-sm btn-light">📋 Programmes</a>
            <a href="index.php?module=exercice&action=index&office=front"    class="btn btn-sm btn-light">🏋️ Exercices</a>
            <a href="index.php?module=statistique&action=index&office=front" class="btn btn-sm btn-light">📊 Statistiques</a>
            <a href="index.php?module=favori&action=index&office=front"      class="btn btn-sm btn-light">❤️ Favoris</a>
            <a href="index.php?module=coach&action=index&office=front"       class="btn btn-sm btn-coach">🤖 Coach IA</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="index.php?module=objectif&action=index&office=back" class="btn btn-sm btn-warning">⚙️ Admin</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_nom'])): ?>
                <span class="user-badge">👤 <?php echo htmlspecialchars($_SESSION['user_nom']); ?></span>
            <?php endif; ?>
            <a href="index.php?action=logout&office=front" class="btn btn-sm btn-outline-light">🚪 Deconnexion</a>
        </div>
    </div>
</nav>
<div class="container mt-4">
<?php endif; ?>