// =============================================
// FONCTIONS DE VALIDATION SANS HTML5
// =============================================

// Valide un nom (min 2 caractères, lettres et espaces)
function validateNom(valeur, idErreur) {
    var err = document.getElementById(idErreur);
    err.textContent = '';
    valeur = valeur.trim();
    if (valeur.length === 0) {
        err.textContent = 'Ce champ est obligatoire.';
        return false;
    }
    if (valeur.length < 2) {
        err.textContent = 'Minimum 2 caractères requis.';
        return false;
    }
    return true;
}

// Valide un prix (nombre positif)
function validatePrix(valeur, idErreur) {
    var err = document.getElementById(idErreur);
    err.textContent = '';
    valeur = valeur.trim();
    if (valeur === '') {
        err.textContent = 'Le prix est obligatoire.';
        return false;
    }
    if (isNaN(valeur) || parseFloat(valeur) < 0) {
        err.textContent = 'Le prix doit être un nombre positif.';
        return false;
    }
    return true;
}

// Valide une quantité (entier positif)
function validateQuantite(valeur, idErreur) {
    var err = document.getElementById(idErreur);
    err.textContent = '';
    valeur = valeur.trim();
    if (!/^\d+$/.test(valeur)) {
        err.textContent = 'La quantité doit être un entier positif.';
        return false;
    }
    if (parseInt(valeur) < 1) {
        err.textContent = 'La quantité doit être au moins 1.';
        return false;
    }
    return true;
}

// Valide un numéro de téléphone (exactement 8 chiffres)
function validateTelephone(valeur, idErreur) {
    var err = document.getElementById(idErreur);
    err.textContent = '';
    valeur = valeur.replace(/\s/g, '');
    if (valeur === '') {
        err.textContent = 'Le téléphone est obligatoire.';
        return false;
    }
    if (!/^\d{8}$/.test(valeur)) {
        err.textContent = 'Le numéro doit contenir exactement 8 chiffres.';
        return false;
    }
    return true;
}

// Valide une adresse (min 5 caractères)
function validateAdresse(valeur, idErreur) {
    var err = document.getElementById(idErreur);
    err.textContent = '';
    valeur = valeur.trim();
    if (valeur.length === 0) {
        err.textContent = "L'adresse est obligatoire.";
        return false;
    }
    if (valeur.length < 5) {
        err.textContent = "L'adresse est trop courte (min 5 caractères).";
        return false;
    }
    return true;
}

// Valide une date au format YYYY-MM-DD
function validateDate(valeur, idErreur) {
    var err = document.getElementById(idErreur);
    err.textContent = '';
    valeur = valeur.trim();
    if (valeur === '') return true; // date optionnelle
    if (!/^\d{4}-\d{2}-\d{2}$/.test(valeur)) {
        err.textContent = 'Format de date invalide (YYYY-MM-DD).';
        return false;
    }
    return true;
}

// Valide un select (valeur non vide)
function validateSelect(valeur, idErreur, message) {
    var err = document.getElementById(idErreur);
    err.textContent = '';
    if (!valeur || valeur === '') {
        err.textContent = message || 'Veuillez faire un choix.';
        return false;
    }
    return true;
}