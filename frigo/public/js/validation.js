<?php header('Content-Type: application/javascript'); ?>
// =============================================
// FONCTIONS DE VALIDATION SANS HTML5
// =============================================

// Valide un nom (min 2 caractères, lettres et espaces)
function validateNom(valeur, idErreur) {
    var err = document.getElementById(idErreur);
    if (!err) return true;
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
    if (!err) return true;
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
    if (!err) return true;
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
    if (!err) return true;
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
    if (!err) return true;
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
    if (!err) return true;
    err.textContent = '';
    valeur = valeur.trim();
    if (valeur === '') return true;
    if (!/^\d{4}-\d{2}-\d{2}$/.test(valeur)) {
        err.textContent = 'Format invalide (YYYY-MM-DD).';
        return false;
    }
    return true;
}

// Valide un select (valeur non vide)
function validateSelect(valeur, idErreur, message) {
    var err = document.getElementById(idErreur);
    if (!err) return true;
    err.textContent = '';
    if (!valeur || valeur === '') {
        err.textContent = message || 'Veuillez faire un choix.';
        return false;
    }
    return true;
}

// ========== NOUVELLES FONCTIONS POUR CARTE BANCAIRE ==========

// Algorithme de Luhn (validation numéro de carte)
function validateLuhn(numero) {
    var sum = 0;
    var alternate = false;
    for (var i = numero.length - 1; i >= 0; i--) {
        var n = parseInt(numero.charAt(i));
        if (alternate) {
            n *= 2;
            if (n > 9) n = (n % 10) + 1;
        }
        sum += n;
        alternate = !alternate;
    }
    return (sum % 10 === 0);
}

// Valide un numéro de carte bancaire (16 chiffres + Luhn)
function validateCarteNumero(valeur, idErreur) {
    var err = document.getElementById(idErreur);
    if (!err) return true;
    err.textContent = '';
    valeur = valeur.replace(/\s/g, '');
    
    if (valeur === '') {
        err.textContent = 'Numéro de carte obligatoire.';
        return false;
    }
    if (!/^\d{16}$/.test(valeur)) {
        err.textContent = 'Le numéro doit contenir exactement 16 chiffres.';
        return false;
    }
    if (!validateLuhn(valeur)) {
        err.textContent = 'Numéro de carte invalide.';
        return false;
    }
    return true;
}

// Valide la date d'expiration (MM/AA)
function validateCarteExpiration(valeur, idErreur) {
    var err = document.getElementById(idErreur);
    if (!err) return true;
    err.textContent = '';
    valeur = valeur.trim();
    
    if (valeur === '') {
        err.textContent = 'Date d\'expiration obligatoire.';
        return false;
    }
    if (!/^(0[1-9]|1[0-2])\/([0-9]{2})$/.test(valeur)) {
        err.textContent = 'Format MM/AA (ex: 12/25).';
        return false;
    }
    
    var mois = parseInt(valeur.split('/')[0]);
    var annee = parseInt(valeur.split('/')[1]);
    var dateActuelle = new Date();
    var anneeActuelle = dateActuelle.getFullYear() % 100;
    var moisActuel = dateActuelle.getMonth() + 1;
    
    if (annee < anneeActuelle || (annee === anneeActuelle && mois < moisActuel)) {
        err.textContent = 'Carte expirée.';
        return false;
    }
    return true;
}

// Valide le CVV (3 chiffres)
function validateCarteCVV(valeur, idErreur) {
    var err = document.getElementById(idErreur);
    if (!err) return true;
    err.textContent = '';
    valeur = valeur.trim();
    
    if (valeur === '') {
        err.textContent = 'CVV obligatoire.';
        return false;
    }
    if (!/^\d{3}$/.test(valeur)) {
        err.textContent = 'Le CVV doit contenir 3 chiffres.';
        return false;
    }
    return true;
}

// Valide le nom du titulaire
function validateCarteTitulaire(valeur, idErreur) {
    var err = document.getElementById(idErreur);
    if (!err) return true;
    err.textContent = '';
    valeur = valeur.trim().toUpperCase();
    
    if (valeur === '') {
        err.textContent = 'Nom du titulaire obligatoire.';
        return false;
    }
    if (!/^[A-Z\s]{2,}$/.test(valeur)) {
        err.textContent = 'Nom invalide (lettres et espaces uniquement).';
        return false;
    }
    return true;
}

// Détection automatique du type de carte (visa, mastercard, amex)
function detectCarteType(numero) {
    var clean = numero.replace(/\s/g, '');
    if (/^4/.test(clean)) return 'Visa';
    if (/^5[1-5]/.test(clean)) return 'Mastercard';
    if (/^3[47]/.test(clean)) return 'American Express';
    return 'Carte bancaire';
}