// Carte interactive pour la livraison

var map;
var marker;
var latitudeMagasin = 36.8065;  // Tunis Centre
var longitudeMagasin = 10.1815;
var rayonMaxKm = 10;

function initMap() {
    var defaultLat = parseFloat(document.getElementById('latitude').value) || latitudeMagasin;
    var defaultLng = parseFloat(document.getElementById('longitude').value) || longitudeMagasin;
    
    map = L.map('map').setView([defaultLat, defaultLng], 14);
    
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);
    
    var magasinIcon = L.divIcon({
        html: '<div style="background-color:#2d6a2d; border-radius:50%; width:42px; height:42px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 2px 8px rgba(0,0,0,0.2);">🏪</div>',
        iconSize: [42, 42],
        className: 'magasin-marker'
    });
    L.marker([latitudeMagasin, longitudeMagasin], {icon: magasinIcon})
        .addTo(map)
        .bindPopup('<strong>🏪 Notre magasin</strong><br>Livraison depuis ce point');
    
    var rayonCercle = L.circle([latitudeMagasin, longitudeMagasin], {
        color: '#2d6a2d',
        fillColor: '#2d6a2d',
        fillOpacity: 0.08,
        weight: 2,
        radius: rayonMaxKm * 1000
    }).addTo(map);
    
    if (defaultLat !== latitudeMagasin || defaultLng !== longitudeMagasin) {
        ajouterMarqueurUtilisateur(defaultLat, defaultLng);
        calculerFraisLivraison(defaultLat, defaultLng);
    }
    
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        ajouterMarqueurUtilisateur(lat, lng);
        calculerFraisLivraison(lat, lng);
        geocoderInverse(lat, lng);
    });
}

function ajouterMarqueurUtilisateur(lat, lng) {
    if (marker) {
        map.removeLayer(marker);
    }
    
    var userIcon = L.divIcon({
        html: '<div style="background-color:#f0a500; border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; font-size:20px; box-shadow:0 2px 5px rgba(0,0,0,0.2);">📍</div>',
        iconSize: [36, 36],
        className: 'user-marker'
    });
    
    marker = L.marker([lat, lng], {icon: userIcon, draggable: true}).addTo(map);
    marker.on('dragend', function(e) {
        var pos = marker.getLatLng();
        calculerFraisLivraison(pos.lat, pos.lng);
        geocoderInverse(pos.lat, pos.lng);
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
        message = '✓ Livraison OFFERTE (moins de 2km)';
    } else if (distance <= 5) {
        frais = 3.00;
        message = '✓ Frais de livraison : 3.00 TND (2-5 km)';
    } else if (distance <= 10) {
        frais = 5.00;
        message = '✓ Frais de livraison : 5.00 TND (5-10 km)';
    } else {
        livrable = false;
        message = '⚠️ Désolé, nous ne livrons pas au-delà de 10km.';
    }
    
    var distanceArrondie = Math.round(distance * 100) / 100;
    document.getElementById('distance_info').innerHTML = message + '<br><small>Distance approximative: ' + distanceArrondie + ' km</small>';
    
    if (document.getElementById('frais_livraison')) {
        document.getElementById('frais_livraison').value = frais.toFixed(2);
    }
    if (document.getElementById('frais_livraison_span')) {
        document.getElementById('frais_livraison_span').textContent = frais.toFixed(2) + ' TND';
    }
    
    if (!livrable) {
        document.getElementById('distance_info').style.color = '#c0392b';
        document.getElementById('zone-info').innerHTML = 
            '<div class="alert alert-danger">❌ Zone non livrable. Veuillez choisir une autre adresse.</div>';
    } else {
        document.getElementById('distance_info').style.color = '#2d6a2d';
        document.getElementById('zone-info').innerHTML = 
            '<div class="alert alert-success">✅ Zone livrable ! ' + message + '</div>';
    }
    
    if (typeof mettreAJourTotalPanier !== 'undefined') {
        mettreAJourTotalPanier();
    }
}

function deg2rad(deg) {
    return deg * (Math.PI/180);
}

function geocoderInverse(lat, lng) {
    var url = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18&addressdetails=1';
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.display_name) {
                var adresse = data.display_name;
                document.getElementById('adresse').value = adresse.substring(0, 200);
            }
        })
        .catch(error => {
            console.log('Erreur géocodage:', error);
        });
}

// Redimensionner la carte quand la modale s'ouvre
document.addEventListener('DOMContentLoaded', function() {
    var mapElement = document.getElementById('map');
    if (mapElement && typeof L !== 'undefined') {
        setTimeout(function() {
            if (map) {
                map.invalidateSize();
            }
        }, 300);
    }
});