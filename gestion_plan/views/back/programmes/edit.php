<?php
require_once 'C:/xampp/htdocs/gestion_plan/header.php';
require_once __DIR__ . '/../../../config.php';

// Récupérer les catégories directement avec PDO
$pdo = getConnection();
$sql = "SELECT * FROM categorie ORDER BY nom";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Modifier le Programme</h2>
    <?php if (!empty($programme['objectif_id'])): ?>
        <a href="index.php?module=objectif&action=show&id=<?php echo $programme['objectif_id']; ?>&office=back" class="btn btn-secondary">← Retour</a>
    <?php else: ?>
        <a href="index.php?module=objectif&action=index&office=back" class="btn btn-secondary">← Retour</a>
    <?php endif; ?>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="index.php?module=programme&action=update&office=back"
              onsubmit="return validerFormulaire()">

            <input type="hidden" name="id" value="<?php echo $programme['id']; ?>">
            <input type="hidden" name="objectif_id" value="<?php echo $programme['objectif_id'] ?? 0; ?>">

            <!-- Nom -->
            <div class="mb-3">
                <label class="form-label fw-bold">Nom du programme</label>
                <input type="text" name="nom" id="nom" class="form-control"
                       value="<?php echo htmlspecialchars($errors ? ($_POST['nom'] ?? '') : $programme['nom']); ?>">
                <div class="error-msg text-danger small" id="err-nom"><?php echo $errors['nom'] ?? ''; ?></div>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($errors ? ($_POST['description'] ?? '') : ($programme['description'] ?? '')); ?></textarea>
            </div>

            <!-- Duree -->
            <div class="mb-3">
                <label class="form-label fw-bold">Duree (semaines)</label>
                <input type="text" name="duree_semaines" id="duree_semaines" class="form-control"
                       value="<?php echo htmlspecialchars($errors ? ($_POST['duree_semaines'] ?? '') : $programme['duree_semaines']); ?>">
                <div class="error-msg text-danger small" id="err-duree"><?php echo $errors['duree_semaines'] ?? ''; ?></div>
            </div>

            <!-- Niveau -->
            <div class="mb-3">
                <label class="form-label fw-bold">Niveau</label>
                <select name="niveau" id="niveau" class="form-select">
                    <option value="">-- Choisir un niveau --</option>
                    <?php
                    $niveauActuel = $errors ? ($_POST['niveau'] ?? '') : $programme['niveau'];
                    foreach (['debutant' => 'Debutant', 'intermediaire' => 'Intermediaire', 'avance' => 'Avance'] as $val => $label):
                    ?>
                        <option value="<?php echo $val; ?>" <?php echo $niveauActuel === $val ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="error-msg text-danger small" id="err-niveau"><?php echo $errors['niveau'] ?? ''; ?></div>
            </div>

            <!-- CATEGORIE -->
            <div class="mb-3">
                <label class="form-label fw-bold">Categorie</label>
                <select name="categorie_id" id="categorie_id" class="form-select">
                    <option value="">-- Choisir une categorie --</option>
                    <?php
                    $categorieActuelle = $errors ? ($_POST['categorie_id'] ?? '') : ($programme['categorie_id'] ?? '');
                    foreach ($categories as $cat):
                    ?>
                        <option value="<?php echo $cat['id_categorie']; ?>"
                            <?php echo $categorieActuelle == $cat['id_categorie'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="error-msg text-danger small" id="err-categorie"><?php echo $errors['categorie_id'] ?? ''; ?></div>
            </div>

            <button type="submit" class="btn btn-green w-100">Enregistrer les modifications</button>
        </form>
    </div>
</div>

<script>
function validerFormulaire() {
    let valide = true;
    ['err-nom', 'err-duree', 'err-niveau', 'err-categorie'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = '';
    });

    const nom = document.getElementById('nom').value.trim();
    if (nom === '') {
        document.getElementById('err-nom').textContent = 'Le nom est obligatoire.';
        valide = false;
    } else if (nom.length < 2) {
        document.getElementById('err-nom').textContent = 'Le nom doit avoir au moins 2 caracteres.';
        valide = false;
    }

    const duree = document.getElementById('duree_semaines').value.trim();
    if (duree === '' || isNaN(duree) || parseInt(duree) < 1) {
        document.getElementById('err-duree').textContent = 'La duree doit etre au moins 1 semaine.';
        valide = false;
    }

    const niveau = document.getElementById('niveau').value;
    if (niveau === '') {
        document.getElementById('err-niveau').textContent = 'Veuillez choisir un niveau.';
        valide = false;
    }

    return valide;
}
</script>

<?php
require_once 'C:/xampp/htdocs/gestion_plan/footer.php';
?>