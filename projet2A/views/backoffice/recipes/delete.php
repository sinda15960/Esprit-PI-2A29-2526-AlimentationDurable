<?php
// Ce fichier est utilisé comme page de confirmation de suppression
// Mais nous utilisons un modal dans index.php, ce fichier sert de fallback
require_once dirname(__DIR__) . '/../../models/Recipe.php';
require_once dirname(__DIR__) . '/../../models/Instruction.php';

session_start();

// Vérifier si l'ID est fourni
if(!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID de recette non spécifié";
    header("Location: index.php?action=backRecipes");
    exit();
}

$id = (int)$_GET['id'];
$recipe = new Recipe();
$recipe->id = $id;
$recipeData = $recipe->readOne();

if(!$recipeData) {
    $_SESSION['error'] = "Recette non trouvée";
    header("Location: index.php?action=backRecipes");
    exit();
}

// Traitement de la suppression
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Supprimer d'abord les instructions liées
    $instruction = new Instruction();
    $instruction->deleteByRecipe($id);
    
    // Supprimer la recette
    if($recipe->delete()) {
        $_SESSION['success'] = "La recette \"" . htmlspecialchars($recipeData['title']) . "\" a été supprimée avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression de la recette";
    }
    
    header("Location: index.php?action=backRecipes");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer la recette - BackOffice NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/backoffice.css">
    <style>
        .delete-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 2rem;
        }
        
        .delete-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .delete-icon {
            width: 80px;
            height: 80px;
            background: #fee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .delete-icon i {
            font-size: 3rem;
            color: #e74c3c;
        }
        
        .delete-card h2 {
            color: #e74c3c;
            margin-bottom: 1rem;
        }
        
        .recipe-title {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
            font-weight: 600;
            color: #1a2a3a;
        }
        
        .warning-message {
            background: #fff3cd;
            color: #856404;
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
            font-size: 0.9rem;
        }
        
        .warning-message i {
            margin-right: 0.5rem;
        }
        
        .info-message {
            background: #d1ecf1;
            color: #0c5460;
            padding: 0.8rem;
            border-radius: 10px;
            margin: 1rem 0;
            font-size: 0.85rem;
        }
        
        .delete-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn-confirm-delete {
            flex: 1;
            padding: 12px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, background 0.3s;
        }
        
        .btn-confirm-delete:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        
        .btn-cancel-delete {
            flex: 1;
            padding: 12px;
            background: #95a5a6;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.3s, background 0.3s;
        }
        
        .btn-cancel-delete:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
        }
        
        .related-info {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="delete-container">
        <div class="delete-card">
            <div class="delete-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            
            <h2>Confirmer la suppression</h2>
            
            <p>Êtes-vous sûr de vouloir supprimer définitivement cette recette ?</p>
            
            <div class="recipe-title">
                <i class="fas fa-utensils"></i> <?php echo htmlspecialchars($recipeData['title']); ?>
            </div>
            
            <div class="warning-message">
                <i class="fas fa-trash-alt"></i>
                <strong>Action irréversible !</strong> Cette recette et toutes ses instructions seront définitivement supprimées.
            </div>
            
            <div class="info-message">
                <i class="fas fa-info-circle"></i>
                Les instructions associées à cette recette seront également supprimées.
            </div>
            
            <div class="related-info">
                <small>
                    <i class="fas fa-list-ol"></i> 
                    <?php 
                        $instruction = new Instruction();
                        $stmt = $instruction->readByRecipe($id);
                        $count = $stmt->rowCount();
                        echo $count . " instruction(s) associée(s)";
                    ?>
                </small>
            </div>
            
            <div class="delete-actions">
                <form method="POST" style="flex: 1;">
                    <button type="submit" class="btn-confirm-delete">
                        <i class="fas fa-trash-alt"></i> Oui, supprimer
                    </button>
                </form>
                <a href="index.php?action=backRecipes" class="btn-cancel-delete">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </div>
</body>
</html>