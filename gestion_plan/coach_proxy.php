<?php
// ─────────────────────────────────────────────────────────
// coach_proxy.php — Coach IA basé sur règles PHP
// Aucune API externe, aucun coût
// Place à la racine : C:/xampp/htdocs/gestion_plan/
// ─────────────────────────────────────────────────────────

session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['message']) || !isset($input['profil'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Données invalides']);
    exit;
}

// ── Validation PHP ────────────────────────────
$message = trim($input['message']);
$profil  = $input['profil'];

if (empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message vide']);
    exit;
}

if (mb_strlen($message) > 1000) {
    http_response_code(400);
    echo json_encode(['error' => 'Message trop long']);
    exit;
}

// Nettoyage
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
$msg     = mb_strtolower($message);

// ── Données du profil ────────────────────────
$nom             = $profil['nom']             ?? 'champion';
$objectif_titre  = $profil['objectif_titre']  ?? null;
$objectif_type   = $profil['objectif_type']   ?? null;
$imc             = $profil['imc']             ?? null;
$poids_actuel    = $profil['poids_actuel']    ?? null;
$poids_cible     = $profil['poids_cible']     ?? null;
$termines        = (int)($profil['termines']  ?? 0);
$total           = (int)($profil['total']     ?? 0);
$streak          = (int)($profil['streak']    ?? 0);
$jours_inactif   = $profil['jours_inactif']   ?? null;
$niveau          = $profil['niveau']          ?? 'Débutant';
$moy_reps        = $profil['moy_reps']        ?? null;
$moy_poids       = $profil['moy_poids']       ?? null;
$nb_difficile    = (int)($profil['nb_difficile'] ?? 0);
$nb_facile       = (int)($profil['nb_facile']   ?? 0);
$total_minutes   = (int)($profil['total_minutes'] ?? 0);
$maladies        = $profil['maladies']        ?? null;
$preferences     = $profil['preferences']     ?? null;
$programme_nom   = $profil['programme_nom']   ?? null;

// ── Moteur de réponses ────────────────────────

function contient($msg, array $mots): bool {
    foreach ($mots as $mot) {
        if (str_contains($msg, $mot)) return true;
    }
    return false;
}

$reponse = '';

// ── DOULEUR / BLESSURE ────────────────────────
if (contient($msg, ['mal', 'douleur', 'blessure', 'blessé', 'blesse', 'genou', 'dos', 'épaule', 'epaule', 'cheville', 'poignet', 'coude', 'hanche', 'muscle', 'crampe', 'tendon'])) {

    $zone = '';
    if (contient($msg, ['genou', 'genoux'])) $zone = 'aux genoux';
    elseif (contient($msg, ['dos'])) $zone = 'au dos';
    elseif (contient($msg, ['épaule', 'epaule'])) $zone = 'à l\'épaule';
    elseif (contient($msg, ['cheville'])) $zone = 'à la cheville';
    elseif (contient($msg, ['poignet'])) $zone = 'au poignet';

    $reponse = "⚠️ Je suis désolé d'apprendre que tu as mal {$zone}, {$nom}.\n\n";
    $reponse .= "**Conseils immédiats :**\n";
    $reponse .= "• Arrête immédiatement les exercices qui sollicitent cette zone\n";
    $reponse .= "• Applique de la glace 15 minutes toutes les 2 heures\n";
    $reponse .= "• Repose cette zone pendant 48 à 72 heures\n\n";

    if (contient($msg, ['genou', 'genoux'])) {
        $reponse .= "**Exercices alternatifs sans solliciter les genoux :**\n";
        $reponse .= "• Gainage abdominal (planche)\n";
        $reponse .= "• Exercices des bras et épaules\n";
        $reponse .= "• Natation si possible\n\n";
    } elseif (contient($msg, ['dos'])) {
        $reponse .= "**Exercices alternatifs sans solliciter le dos :**\n";
        $reponse .= "• Marche légère 20-30 min\n";
        $reponse .= "• Exercices des jambes (position allongée)\n";
        $reponse .= "• Étirements doux\n\n";
    }

    $reponse .= "🏥 **Important :** Si la douleur persiste plus de 48h ou est intense, consulte un médecin. Ta santé passe avant tout !";
}

// ── NUTRITION ─────────────────────────────────
elseif (contient($msg, ['manger', 'nutrition', 'alimentation', 'nourriture', 'régime', 'regime', 'calorie', 'protéine', 'proteine', 'repas', 'petit-déjeuner', 'déjeuner', 'diner', 'collation', 'hydrat', 'eau'])) {

    $reponse = "🥗 **Conseils nutrition personnalisés pour {$nom}**\n\n";

    if ($objectif_type === 'maigrir') {
        $cal = $poids_actuel ? round($poids_actuel * 28) : 1800;
        $reponse .= "**Ton objectif : perdre du poids**\n";
        $reponse .= "• Calories recommandées : environ **{$cal} kcal/jour**\n";
        $reponse .= "• Privilégie : légumes, protéines maigres (poulet, poisson, œufs)\n";
        $reponse .= "• Évite : sucres raffinés, aliments ultra-transformés, sodas\n";
        $reponse .= "• Bois **2 litres d'eau** minimum par jour\n\n";
        $reponse .= "**Exemple de journée :**\n";
        $reponse .= "🌅 Matin : Flocons d'avoine + fruits + thé sans sucre\n";
        $reponse .= "☀️ Midi : Salade composée + poulet grillé + eau\n";
        $reponse .= "🌙 Soir : Légumes vapeur + poisson + yaourt 0%\n";

    } elseif ($objectif_type === 'muscler' || $objectif_type === 'grossir') {
        $cal = $poids_actuel ? round($poids_actuel * 38) : 2800;
        $reponse .= "**Ton objectif : prendre de la masse**\n";
        $reponse .= "• Calories recommandées : environ **{$cal} kcal/jour**\n";
        $reponse .= "• Protéines : **" . ($poids_actuel ? round($poids_actuel * 2) : 150) . "g/jour**\n";
        $reponse .= "• Privilégie : viande, œufs, légumineuses, riz, pâtes, avocat\n";
        $reponse .= "• Mange toutes les 3-4 heures\n\n";
        $reponse .= "**Exemple de journée :**\n";
        $reponse .= "🌅 Matin : 3 œufs + pain complet + banane + lait\n";
        $reponse .= "☀️ Midi : Riz + viande rouge + légumes + huile d'olive\n";
        $reponse .= "🌙 Soir : Pâtes + thon + fromage blanc\n";

    } else {
        $reponse .= "**Nutrition équilibrée recommandée :**\n";
        $reponse .= "• Mange 3 repas complets par jour + 1-2 collations\n";
        $reponse .= "• 50% glucides complexes, 30% protéines, 20% bonnes graisses\n";
        $reponse .= "• Bois **1.5 à 2 litres d'eau** par jour\n";
        $reponse .= "• Évite les aliments ultra-transformés\n";
    }

    if ($maladies && str_contains(mb_strtolower($maladies), 'diabète') || str_contains(mb_strtolower($maladies ?? ''), 'diabete')) {
        $reponse .= "\n\n⚠️ **Attention diabète :** Consulte ton médecin pour adapter ce plan à ta situation.";
    }

    if ($preferences) {
        $reponse .= "\n\n✅ J'ai tenu compte de tes préférences : **{$preferences}**";
    }
}

// ── PROGRESSION ───────────────────────────────
elseif (contient($msg, ['progress', 'avance', 'résultat', 'resultat', 'bilan', 'semaine', 'évolu', 'evolu', 'comment je', 'où j\'en', 'ou j\'en'])) {

    $reponse = "📈 **Bilan de ta progression, {$nom}**\n\n";

    if ($total > 0) {
        $pct = round(($termines / $total) * 100);
        $reponse .= "**Exercices :** {$termines} / {$total} terminés (**{$pct}%**)\n";

        if ($pct >= 80) $reponse .= "🏆 Excellent ! Tu es presque au bout !\n";
        elseif ($pct >= 50) $reponse .= "💪 Très bien ! Tu es à mi-chemin !\n";
        elseif ($pct >= 20) $reponse .= "🌱 Tu avances bien, continue !\n";
        else $reponse .= "🚀 Tu viens de commencer, chaque exercice compte !\n";
    } else {
        $reponse .= "Tu n'as pas encore commencé d'exercices. Lance-toi dès aujourd'hui !\n";
    }

    $reponse .= "\n**Niveau actuel :** {$niveau}\n";
    $reponse .= "**Série active :** {$streak} jour(s) consécutifs\n";
    $reponse .= "**Temps total :** {$total_minutes} minutes d'entraînement\n";

    if ($moy_reps)  $reponse .= "**Reps moyennes :** {$moy_reps}\n";
    if ($moy_poids) $reponse .= "**Poids moyen utilisé :** {$moy_poids} kg\n";

    if ($jours_inactif !== null && $jours_inactif >= 3) {
        $reponse .= "\n⚠️ Tu n'as pas été actif depuis **{$jours_inactif} jour(s)**. Reprends dès aujourd'hui pour maintenir ta progression !";
    }

    if ($imc) {
        $reponse .= "\n\n**Ton IMC :** {$imc}";
        if ($poids_actuel && $poids_cible) {
            $diff = round($poids_actuel - $poids_cible, 1);
            if ($diff > 0) $reponse .= "\nIl te reste **{$diff} kg** à perdre pour atteindre ton objectif.";
            elseif ($diff < 0) $reponse .= "\nIl te reste **" . abs($diff) . " kg** à prendre pour atteindre ton objectif.";
            else $reponse .= "\n🎉 Tu as atteint ton poids cible !";
        }
    }
}

// ── EXERCICE DU JOUR ──────────────────────────
elseif (contient($msg, ['exercice', 'séance', 'seance', 'aujourd\'hui', 'faire', 'programme', 'entrainement', 'entraînement', 'sport'])) {

    $reponse = "🏋️ **Séance recommandée pour toi aujourd'hui, {$nom}**\n\n";

    if ($jours_inactif !== null && $jours_inactif >= 5) {
        $reponse .= "⚡ Tu es inactif depuis {$jours_inactif} jours. Je te recommande une **reprise en douceur** :\n\n";
        $reponse .= "• 10 min d'échauffement (marche rapide)\n";
        $reponse .= "• 2 séries de 10 répétitions par exercice (50% de ton habitude)\n";
        $reponse .= "• 10 min d'étirements\n";
        $reponse .= "• Durée totale : **30 minutes maximum**\n\n";
        $reponse .= "Ne force pas, ton corps a besoin de se réadapter ! 💪";

    } elseif ($niveau === 'Débutant') {
        $reponse .= "**Niveau Débutant — Séance complète :**\n\n";
        $reponse .= "1. 🔥 Échauffement — 5 min de marche rapide\n";
        $reponse .= "2. 💪 Pompes : 3 séries × 8 reps\n";
        $reponse .= "3. 🦵 Squats : 3 séries × 10 reps\n";
        $reponse .= "4. 🏋️ Gainage : 3 × 20 secondes\n";
        $reponse .= "5. 🧘 Étirements : 10 min\n\n";
        $reponse .= "⏱ Durée estimée : **35-40 minutes**";

    } elseif ($niveau === 'Intermédiaire') {
        $reponse .= "**Niveau Intermédiaire — Séance complète :**\n\n";
        $reponse .= "1. 🔥 Échauffement — 10 min cardio léger\n";
        $reponse .= "2. 💪 Pompes : 4 séries × 15 reps\n";
        $reponse .= "3. 🦵 Squats sautés : 4 séries × 12 reps\n";
        $reponse .= "4. 🏋️ Gainage : 4 × 45 secondes\n";
        $reponse .= "5. 🔄 Fentes : 3 × 10 par jambe\n";
        $reponse .= "6. 🧘 Étirements : 10 min\n\n";
        $reponse .= "⏱ Durée estimée : **50-60 minutes**";

    } else {
        $reponse .= "**Niveau Avancé — Séance intensive :**\n\n";
        $reponse .= "1. 🔥 Échauffement — 15 min cardio\n";
        $reponse .= "2. 💪 Pompes déclinées : 5 × 20 reps\n";
        $reponse .= "3. 🦵 Pistol squats : 4 × 8 par jambe\n";
        $reponse .= "4. 🏋️ Gainage latéral : 4 × 60 secondes\n";
        $reponse .= "5. 🔄 Burpees : 4 × 15 reps\n";
        $reponse .= "6. 🧘 Récupération active : 15 min\n\n";
        $reponse .= "⏱ Durée estimée : **70-80 minutes**";
    }

    if ($programme_nom) {
        $reponse .= "\n\n📋 Rappel : tu suis actuellement le programme **{$programme_nom}**. Pense à valider tes exercices !";
    }
}

// ── RÉGULARITÉ / STREAK ───────────────────────
elseif (contient($msg, ['régularité', 'regularite', 'assez', 'souvent', 'fréquence', 'frequence', 'streak', 'série', 'serie', 'actif', 'inactive', 'inactif'])) {

    $reponse = "📅 **Analyse de ta régularité, {$nom}**\n\n";
    $reponse .= "**Série actuelle :** {$streak} jour(s) consécutifs\n\n";

    if ($streak >= 7) {
        $reponse .= "🔥 **Incroyable !** Tu es en feu avec {$streak} jours consécutifs ! Continue comme ça !\n";
    } elseif ($streak >= 3) {
        $reponse .= "💪 **Très bien !** {$streak} jours consécutifs, tu es sur la bonne voie !\n";
    } elseif ($streak >= 1) {
        $reponse .= "🌱 **Bon début !** Tu as {$streak} jour(s) de série. Continue demain pour l'augmenter !\n";
    } else {
        $reponse .= "⚠️ Ta série est à 0. C'est le moment de recommencer !\n";
    }

    if ($jours_inactif !== null) {
        if ($jours_inactif >= 7) {
            $reponse .= "\n🚨 Tu es inactif depuis **{$jours_inactif} jours**. Ton corps est prêt à reprendre, commence par une séance légère aujourd'hui.";
        } elseif ($jours_inactif >= 3) {
            $reponse .= "\n⏰ Tu n'as pas été actif depuis **{$jours_inactif} jours**. Essaie de t'entraîner aujourd'hui !";
        } else {
            $reponse .= "\n✅ Ta dernière activité remonte à {$jours_inactif} jour(s). C'est bien, continue !";
        }
    }

    $reponse .= "\n\n**Conseil :** Pour de bons résultats, vise **3 à 4 séances par semaine**. La régularité est plus importante que l'intensité !";
}

// ── OBJECTIF ──────────────────────────────────
elseif (contient($msg, ['objectif', 'but', 'résultat', 'resultat', 'atteindre', 'vite', 'rapide', 'quand', 'combien de temps'])) {

    $reponse = "🎯 **Comment atteindre ton objectif plus vite, {$nom}**\n\n";

    if ($objectif_titre) $reponse .= "**Ton objectif :** {$objectif_titre}\n\n";

    if ($poids_actuel && $poids_cible) {
        $diff = abs($poids_actuel - $poids_cible);
        $semaines = round($diff / 0.5); // 0.5 kg par semaine = rythme sain
        $reponse .= "**Distance restante :** {$diff} kg\n";
        $reponse .= "**Durée estimée :** environ **{$semaines} semaines** à raison de 0.5 kg/semaine\n\n";
    }

    $reponse .= "**Les 3 piliers pour accélérer :**\n\n";
    $reponse .= "1️⃣ **Régularité** — S'entraîner 3-4x/semaine sans interruption\n";
    $reponse .= "2️⃣ **Nutrition** — Adapter ton alimentation à ton objectif\n";
    $reponse .= "3️⃣ **Récupération** — Dormir 7-8h, boire 2L d'eau/jour\n\n";

    if ($nb_difficile > $nb_facile) {
        $reponse .= "⚠️ Tes exercices te semblent souvent difficiles. Assure-toi de bien te reposer entre les séances.";
    } elseif ($nb_facile > $nb_difficile) {
        $reponse .= "💪 Tes exercices te semblent faciles. Tu pourrais augmenter progressivement l'intensité !";
    }
}

// ── IMC ───────────────────────────────────────
elseif (contient($msg, ['imc', 'poids', 'masse corporelle', 'corpor', 'taille'])) {

    $reponse = "⚖️ **Analyse de ton IMC, {$nom}**\n\n";

    if ($imc) {
        $reponse .= "**Ton IMC actuel :** {$imc}\n\n";
        if ($imc < 18.5) {
            $reponse .= "📊 **Catégorie : Insuffisance pondérale**\n";
            $reponse .= "Tu es en sous-poids. Je te recommande d'augmenter tes apports caloriques avec des aliments nutritifs.\n";
        } elseif ($imc < 25) {
            $reponse .= "📊 **Catégorie : Poids normal** ✅\n";
            $reponse .= "Ton poids est dans la norme. Continue tes bonnes habitudes !\n";
        } elseif ($imc < 30) {
            $reponse .= "📊 **Catégorie : Surpoids**\n";
            $reponse .= "Je te recommande de combiner cardio et alimentation équilibrée.\n";
        } else {
            $reponse .= "📊 **Catégorie : Obésité**\n";
            $reponse .= "Consulte un médecin pour un suivi personnalisé adapté à ta situation.\n";
        }
        if ($poids_actuel && $poids_cible) {
            $diff = round($poids_actuel - $poids_cible, 1);
            if ($diff > 0) $reponse .= "\n🎯 Il te reste **{$diff} kg** à perdre.";
            elseif ($diff < 0) $reponse .= "\n🎯 Il te reste **" . abs($diff) . " kg** à prendre.";
        }
    } else {
        $reponse .= "Je n'ai pas encore ton poids et ta taille. Renseigne-les dans ton objectif personnel pour que je puisse calculer ton IMC !";
    }
}

// ── MOTIVATION ────────────────────────────────
elseif (contient($msg, ['motivat', 'courage', 'fatigué', 'fatigue', 'découragé', 'decourage', 'abandonne', 'difficile', 'dur', 'envie', 'plus envie', 'aide'])) {

    $reponse = "💪 **{$nom}, je suis là pour toi !**\n\n";

    $citations = [
        "\"Le succès, c'est d'aller d'échec en échec sans perdre son enthousiasme.\"",
        "\"La douleur que tu ressens aujourd'hui sera la force que tu ressentiras demain.\"",
        "\"Chaque entraînement est un pas de plus vers ta meilleure version.\"",
        "\"Tu n'as pas à être fort tout le temps. Tu as juste à ne pas abandonner.\"",
    ];

    $reponse .= $citations[array_rand($citations)] . "\n\n";
    $reponse .= "**Rappelle-toi pourquoi tu as commencé :**\n";
    if ($objectif_titre) $reponse .= "→ Ton objectif : **{$objectif_titre}**\n";
    $reponse .= "→ Tu as déjà terminé **{$termines} exercice(s)** — c'est du travail réel !\n";
    if ($streak > 0) $reponse .= "→ Tu as une série de **{$streak} jour(s)** — ne la brise pas !\n\n";
    $reponse .= "Aujourd'hui, commence par seulement **10 minutes**. Souvent le plus dur c'est de commencer. Une fois lancé, tu verras que ça ira ! 🚀";
}

// ── BONJOUR / SALUTATION ──────────────────────
elseif (contient($msg, ['bonjour', 'bonsoir', 'salut', 'hello', 'coucou', 'hi ', 'hey'])) {

    $reponse = "Bonjour **{$nom}** ! 👋\n\n";
    $reponse .= "Je suis ton coach personnel. Voici un résumé rapide de ta situation :\n\n";
    $reponse .= "• **Niveau :** {$niveau}\n";
    $reponse .= "• **Exercices terminés :** {$termines} / {$total}\n";
    $reponse .= "• **Série active :** {$streak} jour(s)\n";
    if ($imc) $reponse .= "• **IMC :** {$imc}\n";

    if ($jours_inactif !== null && $jours_inactif >= 3) {
        $reponse .= "\n⚠️ Tu n'as pas été actif depuis {$jours_inactif} jours. C'est le bon moment pour reprendre !\n";
    } else {
        $reponse .= "\n✅ Continue comme ça, tu es sur la bonne voie !\n";
    }

    $reponse .= "\nComment puis-je t'aider aujourd'hui ? 💪";
}

// ── RÉPONSE PAR DÉFAUT ────────────────────────
else {
    $reponse = "🤖 Je suis ton coach sportif et nutritionniste personnel, **{$nom}**.\n\n";
    $reponse .= "Je peux t'aider sur :\n\n";
    $reponse .= "• 📈 **Ta progression** — analyse de tes résultats\n";
    $reponse .= "• 🏋️ **Exercices** — séance du jour adaptée à ton niveau\n";
    $reponse .= "• 🥗 **Nutrition** — conseils alimentaires selon ton objectif\n";
    $reponse .= "• 📅 **Régularité** — analyse de ta fréquence d'entraînement\n";
    $reponse .= "• 🩹 **Blessures** — conseils en cas de douleur\n";
    $reponse .= "• 🎯 **Objectif** — stratégie pour atteindre ton but\n";
    $reponse .= "• 💪 **Motivation** — coup de boost quand tu en as besoin\n\n";
    $reponse .= "Pose-moi ta question !";
}

// ── Renvoi de la réponse ─────────────────────
echo json_encode([
    'reponse' => $reponse,
    'status'  => 'ok'
]);
?>