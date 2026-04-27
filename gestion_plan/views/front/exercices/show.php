<?php 
require_once 'C:/xampp/htdocs/gestion_plan/header.php'; 
?>

<div class="container mt-4" style="max-width:800px;">

    <!-- ── Retour ──────────────────────────────── -->
    <a href="index.php?module=exercice&action=indexByProgramme&programme_id=<?= (int)$exercice['programme_id'] ?>&office=front"
       class="btn btn-secondary btn-sm mb-3">
        ← Retour aux exercices
    </a>

    <!-- ── Alertes flash ───────────────────────── -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            ✅ <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            ⚠️ <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════
         READ — Infos de l'exercice (admin)
    ══════════════════════════════════════════ -->
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <?php
                    if ($exercice['statut'] === 'termine')      echo '✅ ';
                    elseif ($exercice['statut'] === 'en_cours') echo '▶️ ';
                    else                                         echo '🔒 ';
                ?>
                Étape <?= (int)$exercice['ordre'] ?> — <?= htmlspecialchars($exercice['nom']) ?>
            </h4>
        </div>
        <div class="card-body">
            <?php if (!empty($exercice['description'])): ?>
                <p class="card-text"><?= nl2br(htmlspecialchars($exercice['description'])) ?></p>
            <?php endif; ?>

            <div class="d-flex gap-2 flex-wrap mb-3">
                <?php if (!empty($exercice['duree_minutes'])): ?>
                    <span class="badge bg-light text-dark border">⏱ <?= (int)$exercice['duree_minutes'] ?> min</span>
                <?php endif; ?>
                <?php
                    $statutBadge = match($exercice['statut']) {
                        'termine'   => 'bg-success',
                        'en_cours'  => 'bg-warning text-dark',
                        default     => 'bg-secondary',
                    };
                ?>
                <span class="badge <?= $statutBadge ?>"><?= htmlspecialchars($exercice['statut']) ?></span>
                <span class="badge bg-light text-dark border">📋 <?= htmlspecialchars($exercice['programme_nom']) ?></span>
            </div>

            <?php if (!empty($exercice['video_url'])): ?>
                <div class="ratio ratio-16x9" style="max-width:480px;">
                    <iframe src="<?= htmlspecialchars($exercice['video_url']) ?>"
                            allowfullscreen style="border-radius:8px;"></iframe>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         READ — Données de performance actuelles
    ══════════════════════════════════════════ -->
    <?php
        $aDesDonnees = !empty($exercice['repetitions_realisees'])
                    || !empty($exercice['poids_utilise'])
                    || !empty($exercice['ressenti'])
                    || !empty($exercice['note_user']);
    ?>

    <?php if ($aDesDonnees): ?>
    <div class="card mb-4 border-success">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">📊 Mes données enregistrées</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <?php if (!empty($exercice['repetitions_realisees'])): ?>
                <div class="col-6 col-md-3 text-center">
                    <div class="border rounded p-3">
                        <div style="font-size:1.8rem;font-weight:700;color:#0d6efd;">
                            <?= (int)$exercice['repetitions_realisees'] ?>
                        </div>
                        <div class="text-muted small">Répétitions</div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($exercice['poids_utilise'])): ?>
                <div class="col-6 col-md-3 text-center">
                    <div class="border rounded p-3">
                        <div style="font-size:1.8rem;font-weight:700;color:#0d6efd;">
                            <?= number_format((float)$exercice['poids_utilise'], 1) ?> kg
                        </div>
                        <div class="text-muted small">Poids utilisé</div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($exercice['ressenti'])): ?>
                <div class="col-6 col-md-3 text-center">
                    <div class="border rounded p-3">
                        <?php
                            $ressentiBadge = match($exercice['ressenti']) {
                                'facile'    => ['bg-success', '😊'],
                                'moyen'     => ['bg-warning text-dark', '😐'],
                                'difficile' => ['bg-danger',  '😓'],
                                default     => ['bg-secondary', '—'],
                            };
                        ?>
                        <div style="font-size:1.4rem;"><?= $ressentiBadge[1] ?></div>
                        <span class="badge <?= $ressentiBadge[0] ?> mt-1">
                            <?= ucfirst(htmlspecialchars($exercice['ressenti'])) ?>
                        </span>
                        <div class="text-muted small mt-1">Ressenti</div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($exercice['note_user'])): ?>
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        <strong>📝 Ma note :</strong><br>
                        <?= nl2br(htmlspecialchars($exercice['note_user'])) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════
         CREATE / UPDATE — Formulaire de performance
    ══════════════════════════════════════════ -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <?= $aDesDonnees ? '✏️ Modifier mes données' : '➕ Enregistrer ma performance' ?>
            </h5>
        </div>
        <div class="card-body">
            <form method="POST"
                  action="index.php?module=exercice&action=savePerformance&office=front"
                  id="formPerformance"
                  novalidate>

                <input type="hidden" name="exercice_id" value="<?= (int)$exercice['id'] ?>">

                <div class="row g-3">

                    <!-- Répétitions -->
                    <div class="col-md-6">
                        <label for="repetitions_realisees" class="form-label fw-semibold">
                            🔁 Répétitions réalisées
                        </label>
                        <input type="text"
                               class="form-control"
                               id="repetitions_realisees"
                               name="repetitions_realisees"
                               value="<?= htmlspecialchars($exercice['repetitions_realisees'] ?? '') ?>"
                               placeholder="ex: 12"
                               autocomplete="off">
                        <div class="invalid-feedback" id="err_reps"></div>
                    </div>

                    <!-- Poids -->
                    <div class="col-md-6">
                        <label for="poids_utilise" class="form-label fw-semibold">
                            🏋️ Poids utilisé (kg)
                        </label>
                        <input type="text"
                               class="form-control"
                               id="poids_utilise"
                               name="poids_utilise"
                               value="<?= htmlspecialchars($exercice['poids_utilise'] ?? '') ?>"
                               placeholder="ex: 20.5"
                               autocomplete="off">
                        <div class="invalid-feedback" id="err_poids"></div>
                    </div>

                    <!-- Ressenti -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">😌 Ressenti</label>
                        <div class="d-flex gap-2 flex-wrap" id="ressentiGroup">
                            <?php
                                $ressentis = [
                                    'facile'    => ['😊 Facile',    'btn-outline-success'],
                                    'moyen'     => ['😐 Moyen',     'btn-outline-warning'],
                                    'difficile' => ['😓 Difficile', 'btn-outline-danger'],
                                ];
                                foreach ($ressentis as $val => [$label, $btnClass]):
                                    $selected = ($exercice['ressenti'] ?? '') === $val ? 'active' : '';
                            ?>
                            <button type="button"
                                    class="btn <?= $btnClass ?> btn-ressenti <?= $selected ?>"
                                    data-value="<?= $val ?>">
                                <?= $label ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="ressenti" id="ressentiInput"
                               value="<?= htmlspecialchars($exercice['ressenti'] ?? '') ?>">
                        <div class="text-danger small mt-1" id="err_ressenti" style="display:none;"></div>
                    </div>

                    <!-- Note -->
                    <div class="col-12">
                        <label for="note_user" class="form-label fw-semibold">
                            📝 Note personnelle
                        </label>
                        <textarea class="form-control"
                                  id="note_user"
                                  name="note_user"
                                  rows="3"
                                  placeholder="Tes impressions, difficultés, conseils…"
                        ><?= htmlspecialchars($exercice['note_user'] ?? '') ?></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <div class="text-danger small" id="err_note" style="display:none;"></div>
                            <span id="noteCompteur" class="text-muted small ms-auto">0 / 1000</span>
                        </div>
                    </div>

                </div>

                <!-- Erreur globale JS -->
                <div class="alert alert-danger mt-3" id="errGlobale" style="display:none;"></div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        💾 <?= $aDesDonnees ? 'Modifier' : 'Enregistrer' ?>
                    </button>
                    <?php if ($aDesDonnees): ?>
                        <button type="button" class="btn btn-outline-danger" id="btnReset">
                            🗑 Réinitialiser tout
                        </button>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Formulaire reset (DELETE) caché -->
            <?php if ($aDesDonnees): ?>
            <form method="POST"
                  action="index.php?module=exercice&action=resetPerformance&office=front"
                  id="formReset">
                <input type="hidden" name="exercice_id" value="<?= (int)$exercice['id'] ?>">
            </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Valider l'étape si en cours -->
    <?php if ($exercice['statut'] === 'en_cours'): ?>
    <div class="card border-success mb-4">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>Prêt(e) à passer à l'étape suivante ?</span>
            <a href="index.php?module=exercice&action=validerEtape&id=<?= (int)$exercice['id'] ?>&programme_id=<?= (int)$exercice['programme_id'] ?>&office=front"
               class="btn btn-success"
               onclick="return confirm('Marquer cet exercice comme terminé ?')">
                ✔ Valider l'étape
            </a>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- ══════════════════════════════════════════════════
     VALIDATION JS — aucun attribut HTML5 required/pattern
══════════════════════════════════════════════════ -->
<script>
(function () {
    'use strict';

    // ── Ressenti : boutons toggle ─────────────────────
    var btnRessenti   = document.querySelectorAll('.btn-ressenti');
    var ressentiInput = document.getElementById('ressentiInput');

    btnRessenti.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var val = this.getAttribute('data-value');
            // Toggle : re-cliquer déselectionne
            if (ressentiInput.value === val) {
                ressentiInput.value = '';
                this.classList.remove('active');
            } else {
                ressentiInput.value = val;
                btnRessenti.forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');
            }
        });
    });

    // ── Compteur note ─────────────────────────────────
    var noteTextarea = document.getElementById('note_user');
    var noteCompteur = document.getElementById('noteCompteur');

    function majCompteur() {
        var len = noteTextarea.value.length;
        noteCompteur.textContent = len + ' / 1000';
        noteCompteur.style.color = len > 1000 ? '#dc3545' : '#6c757d';
    }
    noteTextarea.addEventListener('input', majCompteur);
    majCompteur();

    // ── Validation formulaire ─────────────────────────
    document.getElementById('formPerformance').addEventListener('submit', function (e) {
        var reps     = document.getElementById('repetitions_realisees').value.trim();
        var poids    = document.getElementById('poids_utilise').value.trim();
        var ressenti = ressentiInput.value;
        var note     = noteTextarea.value.trim();
        var valide   = true;

        // Reset erreurs
        ['repetitions_realisees', 'poids_utilise'].forEach(function (id) {
            var el = document.getElementById(id);
            el.classList.remove('is-invalid');
        });
        document.getElementById('err_reps').textContent    = '';
        document.getElementById('err_poids').textContent   = '';
        document.getElementById('err_ressenti').style.display = 'none';
        document.getElementById('err_note').style.display  = 'none';
        document.getElementById('errGlobale').style.display = 'none';

        // Au moins un champ rempli
        if (reps === '' && poids === '' && ressenti === '' && note === '') {
            document.getElementById('errGlobale').textContent = 'Veuillez remplir au moins un champ.';
            document.getElementById('errGlobale').style.display = 'block';
            e.preventDefault();
            valide = false;
        }

        // Répétitions
        if (reps !== '') {
            var repsInt = parseInt(reps, 10);
            if (!/^\d+$/.test(reps) || repsInt < 1) {
                document.getElementById('repetitions_realisees').classList.add('is-invalid');
                document.getElementById('err_reps').textContent = 'Nombre entier positif requis.';
                e.preventDefault(); valide = false;
            } else if (repsInt > 9999) {
                document.getElementById('repetitions_realisees').classList.add('is-invalid');
                document.getElementById('err_reps').textContent = 'Maximum 9999 répétitions.';
                e.preventDefault(); valide = false;
            }
        }

        // Poids
        if (poids !== '') {
            var poidsFloat = parseFloat(poids);
            if (isNaN(poidsFloat) || poidsFloat <= 0) {
                document.getElementById('poids_utilise').classList.add('is-invalid');
                document.getElementById('err_poids').textContent = 'Nombre positif requis (ex: 12.5).';
                e.preventDefault(); valide = false;
            } else if (poidsFloat > 999) {
                document.getElementById('poids_utilise').classList.add('is-invalid');
                document.getElementById('err_poids').textContent = 'Maximum 999 kg.';
                e.preventDefault(); valide = false;
            }
        }

        // Note
        if (note !== '') {
            if (note.length > 1000) {
                document.getElementById('err_note').textContent = 'La note dépasse 1000 caractères.';
                document.getElementById('err_note').style.display = 'block';
                noteTextarea.classList.add('is-invalid');
                e.preventDefault(); valide = false;
            }
            // Détection HTML
            var tmp = document.createElement('div');
            tmp.innerHTML = note;
            if (tmp.innerHTML !== note) {
                document.getElementById('err_note').textContent = 'La note ne doit pas contenir de balises HTML.';
                document.getElementById('err_note').style.display = 'block';
                noteTextarea.classList.add('is-invalid');
                e.preventDefault(); valide = false;
            }
        }

        return valide;
    });

    // ── Confirmation reset (DELETE) ───────────────────
    var btnReset  = document.getElementById('btnReset');
    var formReset = document.getElementById('formReset');

    if (btnReset && formReset) {
        btnReset.addEventListener('click', function () {
            if (confirm('Supprimer toutes tes données (répétitions, poids, ressenti, note) ? Cette action est irréversible.')) {
                formReset.submit();
            }
        });
    }

})();
</script>

<?php 
require_once 'C:/xampp/htdocs/gestion_plan/footer.php'; 
?>