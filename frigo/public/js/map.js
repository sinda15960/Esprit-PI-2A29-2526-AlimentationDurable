<?php header('Content-Type: application/javascript'); ?>
let map;
let marker;
let latitudeMagasin = 36.8065;
let longitudeMagasin = 10.1815;
let rayonMaxKm = 10;

function initMap() {
    // Coordonnées par défaut (Tunis Centre)
    var defaultLat = parseFloat(document.getElementById('latitude').value) || latitudeMagasin;
    var defaultLng = parseFloat(document.getElementById('longitude').value) || longitudeMagasin;
    
    map = L.map('map').setView([defaultLat, defaultLng], 13);
    
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);
    
    // Marqueur du magasin
    var magasinIcon = L.divIcon({
        html: '<div style="background-color:#2d6a2d; border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; font-size:18px;">🏪</div>',
        iconSize: [30, 30],
        className: 'magasin-marker'
    });
    L.marker([latitudeMagasin, longitudeMagasin], {icon: magasinIcon})
        .addTo(map)
        .bindPopup('<strong>Notre magasin</strong><br>Livraison depuis ce point');
    
    // Cercle de livraison
    var rayonCercle = L.circle([latitudeMagasin, longitudeMagasin], {
        color: '#2d6a2d',
        fillColor: '#2d6a2d',
        fillOpacity: 0.1,
        radius: rayonMaxKm * 1000
    }).addTo(map);
    
    // Marqueur cliquable pour l'utilisateur
    if (defaultLat !== latitudeMagasin || defaultLng !== longitudeMagasin) {
        ajouterMarqueurUtilisateur(defaultLat, defaultLng);
    }
    
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        ajouterMarqueurUtilisateur(lat, lng);
        calculerFraisLivraison(lat, lng);
    });
}

function ajouterMarqueurUtilisateur(lat, lng) {
    if (marker) {
        map.removeLayer(marker);
    }
    
    var userIcon = L.divIcon({
        html: '<div style="background-color:#f0a500; border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; font-size:18px;">📍</div>',
        iconSize: [30, 30],
        className: 'user-marker'
    });
    
    marker = L.marker([lat, lng], {icon: userIcon, draggable: true}).addTo(map);
    marker.on('dragend', function(e) {
        var pos = marker.getLatLng();
        calculerFraisLivraison(pos.lat, pos.lng);
    });
    
    document.getElementById('latitude').value = lat.toFixed(8);
    document.getElementById('longitude').value = lng.toFixed(8);
    document.getElementById('adresse_lat').value = lat.toFixed(8);
    document.getElementById('adresse_lng').value = lng.toFixed(8);
}

function calculerFraisLivraison(lat, lng) {
    var R = 6371;
    var dLat = deg2rad(lat - latitudeMagasin);
    var dLng = deg2rad(lng - longitudeMagasin);
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(deg2rad(latitudeMagasin)) * Math.cos(deg2rad(lat)) *
            Math.sin(dLng/2) * Math.sin(dLng/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var distance = R * c;
    
    var frais = 0;
    var livrable = true;
    var message = '';
    
    if (distance <= 2) {
        frais = 0;
        message = 'Livraison offerte (moins de 2km)';
    } else if (distance <= 5) {
        frais = 3.00;
        message = 'Frais de livraison : 3.00 TND (2-5 km)';
    } else if (distance <= 10) {
        frais = 5.00;
        message = 'Frais de livraison : 5.00 TND (5-10 km)';
    } else {
        livrable = false;
        message = '⚠️ Désolé, nous ne livrons pas au-delà de 10km.';
    }
    
    document.getElementById('frais_livraison').value = frais.toFixed(2);
    document.getElementById('frais_livraison_span').textContent = frais.toFixed(2) + ' TND';
    document.getElementById('distance_info').innerHTML = message;
    
    if (!livrable) {
        document.getElementById('distance_info').classList.add('text-danger');
        document.getElementById('zone-info').innerHTML = 
            '<div class="alert alert-danger">Zone non livrable. Veuillez choisir une autre adresse.</div>';
    } else {
        document.getElementById('distance_info').classList.remove('text-danger');
        document.getElementById('zone-info').innerHTML = 
            '<div class="alert alert-success">✓ Zone livrable ! ' + message + '</div>';
        mettreAJourTotalPanier();
    }
}

function deg2rad(deg) {
    return deg * (Math.PI/180);
}

function mettreAJourTotalPanier() {
    var totalInitial = parseFloat(document.getElementById('total_initial').value);
    var frais = parseFloat(document.getElementById('frais_livraison').value);
    var nouveauTotal = totalInitial + frais;
    document.getElementById('total_avec_frais').textContent = nouveauTotal.toFixed(2) + ' TND';
    document.getElementById('total_final_input').value = nouveauTotal.toFixed(2);
}