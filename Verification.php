<?php
require_once __DIR__ . '/AllergieController.php';

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../View/BackOffice/login.php');
    exit();
}

$controller = new AllergieController();

// Validation du formulaire
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validation du nom (pas de chiffres)
    if (empty($_POST['nom'])) {
        $errors[] = "Le nom de l'allergie est obligatoire";
    } elseif (preg_match('/[0-9]/', $_POST['nom'])) {
        $errors[] = "❌ Le nom ne doit pas contenir de chiffres";
    }
    
    // Validation de la catégorie
    if (empty($_POST['categorie'])) {
        $errors[] = "La catégorie est obligatoire";
    }
    
    // Validation de la description (min 10 caractères)
    if (empty($_POST['description'])) {
        $errors[] = "La description est obligatoire";
    } elseif (strlen(trim($_POST['description'])) < 10) {
        $errors[] = "❌ La description doit contenir au moins 10 caractères";
    }
    
    // Validation des symptômes (min 10 caractères)
    if (empty($_POST['symptomes'])) {
        $errors[] = "Les symptômes sont obligatoires";
    } elseif (strlen(trim($_POST['symptomes'])) < 10) {
        $errors[] = "❌ Les symptômes doivent contenir au moins 10 caractères";
    }
    
    // Validation des déclencheurs
    if (empty($_POST['declencheurs'])) {
        $errors[] = "Les déclencheurs sont obligatoires";
    }
    
    // Validation de la gravité
    if (empty($_POST['gravite'])) {
        $errors[] = "La gravité est obligatoire";
    }
    
    // S'il n'y a pas d'erreurs, on ajoute
    if (empty($errors)) {
        $result = $controller->addAllergie(
            $_POST['nom'],
            $_POST['categorie'],
            $_POST['description'],
            $_POST['symptomes'],
            $_POST['declencheurs'],
            $_POST['gravite']
        );
        
        if ($result) {
            $success = true;
            $message = "✅ Allergie ajoutée avec succès !";
        } else {
            $errors[] = "Erreur lors de l'ajout dans la base de données";
        }
    }
}

// Création de l'objet Allergie pour l'affichage
$allergie = new Allergie(
    $_POST['nom'] ?? '',
    $_POST['categorie'] ?? '',
    $_POST['description'] ?? '',
    $_POST['symptomes'] ?? '',
    $_POST['declencheurs'] ?? '',
    $_POST['gravite'] ?? ''
);
$allergie->setId(999);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification - NutriFlow AI</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 2rem;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1, h2 { color: #2d5016; }
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            border: 1px solid #f5c6cb;
        }
        .btn {
            background: #2d5016;
            color: white;
            padding: 0.8rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #2d5016;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Vérification de l'ajout</h1>
        
        <?php if ($success): ?>
            <div class="alert-success">
                ✅ <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <strong>❌ Erreurs détectées :</strong><br>
                <?php foreach ($errors as $error): ?>
                    - <?= htmlspecialchars($error) ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <h2>📊 Affichage avec var_dump() :</h2>
        <pre style="background: #f0f0f0; padding: 1rem; overflow-x: auto;">
<?php var_dump($allergie); ?>
        </pre>
        
        <h2>📊 Affichage avec la méthode show() :</h2>
        <?php $allergie->show(); ?>
        
        <h2>📊 Affichage avec showBook() du contrôleur :</h2>
        <?php $controller->showBook($allergie); ?>
        
        <a href="../View/BackOffice/gestion_allergies.php" class="btn">← Retour à la gestion</a>
    </div>
</body>
</html>