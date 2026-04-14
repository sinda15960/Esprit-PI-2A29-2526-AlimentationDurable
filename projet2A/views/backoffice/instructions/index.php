<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Instructions - BackOffice NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/backoffice.css">
</head>
<body>
    <div class="backoffice-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-leaf"></i>
                    <span>NutriFlow AI</span>
                    <small>Administration</small>
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="index.php?action=backRecipes">
                    <i class="fas fa-utensils"></i>
                    <span>Recettes</span>
                </a>
                <a href="index.php?action=backInstructions&id=<?php echo $recipe['id']; ?>" class="active">
                    <i class="fas fa-list-ol"></i>
                    <span>Instructions</span>
                </a>
                <a href="#">
                    <i class="fas fa-chart-line"></i>
                    <span>Statistiques</span>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas fa-list-ol"></i> Instructions - <?php echo htmlspecialchars($recipe['title']); ?></h1>
                <div>
                    <a href="index.php?action=backCreateInstruction&id=<?php echo $recipe['id']; ?>" class="btn-primary">
                        <i class="fas fa-plus"></i> Ajouter une étape
                    </a>
                    <a href="index.php?action=backRecipes" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour aux recettes
                    </a>
                </div>
            </div>

            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Étape</th>
                            <th>Description</th>
                            <th>Astuce</th>
                            <th>Actions</th>
                        </th>
                    </thead>
                    <tbody>
                        <?php if(empty($instructions)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">Aucune instruction pour cette recette</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($instructions as $instruction): ?>
                                <tr>
                                    <td><span class="badge-difficulty" style="background: #2ecc71; color: white;">Étape <?php echo $instruction['step_number']; ?></span></td>
                                    <td><?php echo nl2br(htmlspecialchars(substr($instruction['description'], 0, 100))); ?>...</td>
                                    <td><?php echo htmlspecialchars($instruction['tip'] ?? '—'); ?></td>
                                    <td class="actions">
                                        <a href="index.php?action=backEditInstruction&id=<?php echo $instruction['id']; ?>" class="btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="showDeleteModal(<?php echo $instruction['id']; ?>, 'Étape <?php echo $instruction['step_number']; ?>')" class="btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal de suppression -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmer la suppression</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer :</p>
                <p><strong id="deleteInstructionTitle"></strong> ?</p>
                <p class="warning">Cette action est irréversible !</p>
            </div>
            <div class="modal-footer">
                <form method="POST" id="deleteForm">
                    <button type="button" class="btn-cancel">Annuler</button>
                    <button type="submit" class="btn-confirm">Supprimer</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal(id, title) {
            const modal = document.getElementById('deleteModal');
            const deleteTitle = document.getElementById('deleteInstructionTitle');
            const deleteForm = document.getElementById('deleteForm');
            
            if(modal && deleteTitle && deleteForm) {
                deleteTitle.textContent = title;
                deleteForm.action = `index.php?action=backDeleteInstruction&id=${id}`;
                modal.style.display = 'block';
                
                const closeBtn = modal.querySelector('.close');
                const cancelBtn = modal.querySelector('.btn-cancel');
                
                closeBtn.onclick = function() { modal.style.display = 'none'; };
                cancelBtn.onclick = function() { modal.style.display = 'none'; };
                
                window.onclick = function(event) {
                    if(event.target === modal) modal.style.display = 'none';
                };
            }
        }
    </script>
</body>
</html>