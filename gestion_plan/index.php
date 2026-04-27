<?php
session_start();

$module = $_GET['module'] ?? 'objectif';
$action = $_GET['action'] ?? 'index';
$office = $_GET['office'] ?? 'front';

if ($office === 'front' && !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $office = $_GET['office'] ?? 'back';
} else {
    $office = 'front';
}

if ($action === 'logout') {
    session_destroy();
    header("Location: login.php");
    exit;
}

$controllers = [
    'objectif'    => __DIR__ . '/controllers/ObjectifController.php',
    'programme'   => __DIR__ . '/controllers/ProgrammeController.php',
    'exercice'    => __DIR__ . '/controllers/ExerciceController.php',
    'categorie'   => __DIR__ . '/controllers/CategorieController.php',
    'statistique' => __DIR__ . '/controllers/StatistiqueController.php',
    'favori'      => __DIR__ . '/controllers/FavoriController.php',
    'pdf'         => __DIR__ . '/controllers/PdfController.php',
];

if (isset($controllers[$module])) {
    require_once $controllers[$module];
    $class = ucfirst($module) . 'Controller';
    $ctrl = new $class();

    if (method_exists($ctrl, $action)) {
        $ctrl->$action($office);
    } else {
        echo "Action introuvable : " . htmlspecialchars($action);
    }
} else {
    echo "Module introuvable : " . htmlspecialchars($module);
}
?>