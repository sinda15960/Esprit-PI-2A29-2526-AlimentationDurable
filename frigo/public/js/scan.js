<?php header('Content-Type: application/javascript'); ?>
// Scan de code-barres avec html5-qrcode
let html5QrCode = null;
let scanActive = false;

function demarrerScanCodeBarres() {
    if (html5QrCode === null) {
        html5QrCode = new Html5Qrcode("qr-reader");
    }
    
    const config = { 
        fps: 10, 
        qrbox: { width: 300, height: 200 },
        aspectRatio: 1.0
    };
    
    if (scanActive) return;
    
    html5QrCode.start({ facingMode: "environment" }, config, 
        (decodedText, decodedResult) => {
            // Traiter le code-barres/QR détecté
            traiterCodeBarres(decodedText);
        }, 
        (errorMessage) => {
            // Erreur silencieuse pour ne pas polluer
            // console.log(errorMessage);
        }
    );
    scanActive = true;
}

function arreterScanCodeBarres() {
    if (html5QrCode && scanActive) {
        html5QrCode.stop().then(() => {
            scanActive = false;
        });
    }
}

function traiterCodeBarres(code) {
    // Extraire l'ID du produit ou le code EAN
    // Format attendu: "PROD:123" ou directement le code EAN
    var produitId = null;
    var codeBarres = null;
    
    // Format PROD:123
    var matchProd = code.match(/PROD[:\s]*(\d+)/i);
    if (matchProd && matchProd[1]) {
        produitId = matchProd[1];
    } else {
        // Sinon c'est un code EAN-13
        codeBarres = code.replace(/[^0-9]/g, '');
        if (codeBarres.length >= 8 && codeBarres.length <= 13) {
            produitId = null; // Sera recherché par code-barres
        }
    }
    
    // Rediriger vers l'action appropriée
    var url = '/frigo/index.php?mode=front&controller=produit&action=ajouterParScan';
    if (produitId) {
        url += '&produit_id=' + produitId;
    } else if (codeBarres) {
        url += '&code_barres=' + codeBarres;
    }
    
    window.location.href = url;
}