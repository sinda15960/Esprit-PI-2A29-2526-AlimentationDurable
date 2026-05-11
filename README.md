# 🌱 NutriFlow AI — Alimentation Durable & Nutrition Intelligente

<div align="center">

**Esprit School of Engineering — Projet Intégré 2A | 2025–2026**

[🔗 GitHub Repository](https://github.com/sinda15960/Esprit-PI-2A29-2526-AlimentationDurable)

</div>

---

## 📋 Description du projet

**NutriFlow AI** est une application web développée dans le cadre du module **Projet Technologies Web (2A)** à Esprit School of Engineering (année universitaire 2025–2026).

L'application aide les utilisateurs à adopter une alimentation durable en :
- Réduisant le gaspillage alimentaire grâce à la gestion intelligente du frigo
- Gérant les allergies alimentaires avec des informations médicales fiables
- Proposant des recettes adaptées aux restes disponibles
- Offrant des fonctionnalités d'urgence pour les personnes allergiques

---

## 🛠️ Stack Technique

| Couche | Technologies |
|--------|-------------|
| **Backend** | PHP 8.0+ |
| **Frontend** | HTML5, CSS3, JavaScript (ES6) |
| **Base de données** | MySQL / SQL |
| **APIs externes** | Google Gemini AI, OpenWeatherMap, Leaflet.js |
| **Librairies** | Chart.js, FPDF (export PDF), PhpSpreadsheet (export Excel) |
| **Environnement** | VS Code, XAMPP / WAMP |

---

## 🏗️ Architecture du projet

```
Esprit-PI-2A29-2526-AlimentationDurable/
├── Controller/                    # Contrôleurs MVC
├── EspritNutriFlowMVC/           # Architecture MVC principale
├── api/                          # Endpoints API
├── config/                       # Configuration DB et app
├── frigo/                        # Module gestion frigo
├── gestion_plan/                 # Module plan alimentaire
├── model/                        # Modèles de données
├── sql/                          # Scripts SQL (tables, triggers)
├── uploads/allergies/            # Images des allergies
├── view/                         # Vues HTML/PHP
├── back_allergie_traitement.php  # Back office allergies (Nada)
├── front_allergie_traitement.php # Front office allergies (Nada)
├── chatbot.php                   # Chatbot IA Gemini (Nada)
├── compare_allergies.php         # Comparateur allergies (Nada)
├── emergency_card.php            # Carte d'urgence PDF (Nada)
├── pollen_alerts.php             # Alertes pollen (Nada)
├── database.sql                  # Base de données principale
└── index.php                     # Point d'entrée
```

---

## ⚙️ Installation & Lancement

### Prérequis
- PHP 8.0+
- MySQL 5.7+
- Serveur local : XAMPP ou WAMP
- Navigateur moderne (Chrome, Firefox, Edge)

### Étapes

```bash
# 1. Cloner le repository
git clone https://github.com/sinda15960/Esprit-PI-2A29-2526-AlimentationDurable.git

# 2. Placer le dossier dans htdocs (XAMPP) ou www (WAMP)
cp -r Esprit-PI-2A29-2526-AlimentationDurable/ C:/xampp/htdocs/

# 3. Importer la base de données
# Ouvrir phpMyAdmin → Créer une BDD "nutriflow"
# Importer le fichier database.sql
# Puis importer database_update.sql et add_new_features.sql

# 4. Configurer la connexion DB
# Modifier config/database.php avec vos identifiants

# 5. Lancer le projet
# Ouvrir : http://localhost/Esprit-PI-2A29-2526-AlimentationDurable/index.php
```

---

## 👥 Fonctionnalités par module

| Module | Responsable | Fonctionnalités |
|--------|------------|-----------------|
| 🗄️ Gestion Allergies & Traitements | **Nada Azlouk** | CRUD complet, chatbot IA, bouton SOS, alertes pollen, comparateur |
| 🧊 Gestion Frigo | **Hajer BenTemessek** | Inventaire, alertes péremption & stock faible, supermarché virtuel, panier & commandes, paiement CB, carte livraison,code promo, scan QR, suggestions IA, statistiques admin, CRUD complet |
| 📋 Gestion Plan Alimentaire | **Maissa Jouini** | Planification repas, recettes |
| 👤 Gestion plan | **Cyrine Sboui** | Gestion des objectifs personnalisés, exercices et programmes, système de favoris, assistant vocal, scan OCR, abonnements Stripe, mode sombre/clair, notifications intelligentes, filtrage et tri des programmes, statistiques, export PDF, CRUD complet Back Office |
| 🛒 Gestion Recettes & Catégories | **Sarra Dimassi** | CRUD recettes & catégories, planificateur de repas IA, recherche dynamique & tri, comparateur de recettes, statistiques & progression, export CSV, notifications, recette surprise, historique des modifications, commandes vocales, système de commandes |
| 📊 Statistiques & Rapports | **synda lazaar** | Tableaux de bord, rapports |

---

## 🌟 — Nada Azlouk

### Module : Gestion Allergies & Traitements

#### 🗄️ 1. Base de données
- Création de **7 tables** : `allergies`, `traitements`, `feedbacks`, `user_profiles`, `logs`, `user_pollen_prefs`, `urgence_contacts`
- Gestion des relations (clés étrangères, contraintes d'intégrité)
- Mise en place de **triggers SQL** pour l'audit log automatique

#### 🎨 2. Back Office (Administration)
- CRUD complet allergies & traitements avec upload d'images
- Validation des données (nom sans chiffres, description min. 10 caractères)
- **Export PDF & Excel** des données
- Tableau de bord avec graphiques **Chart.js** (répartition par catégorie & gravité)
- Système d'**audit log** — traçabilité complète des actions admin

#### 🌍 3. Front Office (Utilisateur)
- Affichage des allergies en **carrousel interactif**
- Recherche avancée (nom, catégorie, gravité)
- Fiches détaillées avec traitements associés
- Système d'**évaluation par étoiles**
- Formulaire de **feedback utilisateur**

#### 🆘 4. Bouton d'Urgence SOS ⭐
- **Géolocalisation GPS** haute précision (navigator.geolocation)
- Sauvegarde de position dans `localStorage`
- Envoi d'**emails d'alerte** aux contacts d'urgence configurés
- Génération de **lien Google Maps** de la position
- Carte interactive **Leaflet.js**

#### 🤖 5. Chatbot IA (Google Gemini)
- Intégration de l'**API Google Gemini**
- Analyse intelligente des symptômes allergiques
- Mode fallback (si API indisponible)
- Historique des conversations

#### 🌤️ 6. Alertes Pollen Saisonnières
- Intégration **API OpenWeatherMap**
- Cache des données (réduction des appels API)
- Recommandations personnalisées selon niveau de risque
- Calendrier pollinique interactif

#### ⚖️ 7. Comparateur d'Allergies
- Comparaison côte à côte (symptômes, gravité, traitements)
- Calcul de **scores de dangerosité**
- **Graphiques radar** (Chart.js) pour visualisation
- Identification des points communs

#### 🆔 8. Profil Allergique & Carte d'Urgence
- Création de profil **sans authentification**
- Sélection d'allergies critiques personnelles
- **Génération PDF** de la carte d'urgence (style carte bancaire, imprimable)

#### 🎨 9. Design UI/UX
- Mode **sombre / clair** (toggle)
- Interface **responsive** (mobile / tablette / desktop)
- Animations et transitions CSS fluides
- Palette cohérente vert/santé

---


## 📸 Aperçu de l'interface

| Front Office | Back Office Admin |
|---|---|
| Carrousel allergies, recherche avancée | Dashboard stats, audit log, exports |
| Chatbot IA, bouton SOS | CRUD complet avec validation |

---

## 🔗 Liens utiles

- 📁 **Repository GitHub** : [Esprit-PI-2A29-2526-AlimentationDurable](https://github.com/sinda15960/Esprit-PI-2A29-2526-AlimentationDurable)
- 👤 **LinkedIn** : [Nada Azlouk](https://www.linkedin.com/in/nada-az-095776319)
- 🏫 **Esprit School of Engineering** : [esprit.tn](https://esprit.tn)
---
## 🌟 — Sarra Dimassi

### Module : Gestion Recettes, Catégories & Planification IA

#### 🗄️ 1. Gestion des Recettes

* CRUD complet des recettes (ajout, modification, suppression, affichage)
* Gestion des informations : nom, ingrédients, calories, temps, difficulté, catégorie, image, description
* Upload et gestion d’images des recettes
* Validation des données côté client et serveur
* Historique des modifications des recettes

#### 🥗 2. Planificateur de Repas Hebdomadaire IA

* Génération automatique d’un menu équilibré pour toute la semaine
* Adaptation selon les objectifs utilisateurs :

  * Perte de poids
  * Gain de poids
  * Équilibre alimentaire
* Alternance intelligente des types de plats
* Équilibrage des calories et recommandations nutritionnelles

#### 🔍 3. Recherche Dynamique & Tri Avancé

* Recherche instantanée des recettes et catégories
* Tri dynamique (nom, calories, difficulté, temps)
* Filtres avancés pour une navigation rapide
* Affichage responsive et interactif

#### 📊 4. Statistiques & Analyse

* Tableau de bord statistique interactif
* Suivi des recettes par catégorie
* Système d’objectifs et progression
* Visualisation des performances et indicateurs
* Export CSV des statistiques et données

#### ⚖️ 5. Comparateur de Recettes

* Comparaison côte à côte de deux recettes
* Analyse des calories, temps, difficulté et catégories
* Identification des meilleures options nutritionnelles
* Interface intuitive pour faciliter le choix des repas

#### 🎲 6. Fonctionnalités Intelligentes

* Génération de recette surprise aléatoire
* Système de notifications
* Bouton “Envoyer rapport”
* Suggestions intelligentes de repas

#### 🎤 7. Commandes Vocales

* Intégration de reconnaissance vocale
* Exécution d’actions vocales :

  * Ajouter une recette
  * Rechercher une catégorie
  * Naviguer dans l’interface
* Expérience utilisateur moderne et interactive

#### 📂 8. Gestion des Catégories

* CRUD complet des catégories
* Gestion des objectifs et progression par catégorie
* Tri et statistiques avancées
* Interface d’administration intuitive
* Export CSV des données catégories

#### 🎨 9. Design UI/UX

* Interface moderne et responsive (mobile / tablette / desktop)
* Animations et transitions CSS fluides
* Dashboard administrateur interactif
* Palette cohérente avec le thème alimentation durable

---

## 📸 Aperçu de l’interface

| Front Office                               | Back Office Admin                    |
| ------------------------------------------ | ------------------------------------ |
| Recherche dynamique & recettes IA          | Dashboard statistiques & exports CSV |
| Comparateur de recettes & recette surprise | CRUD complet recettes & catégories   |
| Commandes vocales & planificateur IA       | Gestion objectifs, progression & tri |

---

## 🔗 Liens utiles

* 📁 **Repository GitHub** : [Esprit-PI-2A29-2526-AlimentationDurable](https://github.com/sinda15960/Esprit-PI-2A29-2526-AlimentationDurable)
* 👤 **LinkedIn** : Sarra Dimassi ( www.linkedin.com/in/sarra-dimassi-57718840a )
* 🏫 **Esprit School of Engineering** : [esprit.tn](https://esprit.tn)
  
---
## 🌟 — Hajer Ben Temesssek
### Module : Gestion Intelligente du Frigo

### 🗄️ 1. Base de données
Création des tables : produit, categorie, commande, commande_produit, frigo_utilisateur, favori, code_promo
Gestion des relations (clés étrangères, contraintes d’intégrité)
Gestion des stocks, seuils d’alerte et dates d’expiration
Mise en place des jointures complexes pour statistiques et recommandations

### 🎨 2. Back Office (Administration)
CRUD complet produits & catégories avec validation avancée
Gestion des commandes (validation, modification, suppression)
Gestion des codes promo avec activation/désactivation
Dashboard statistique avec graphiques Chart.js :

Chiffre d’affaires par jour
Top produits vendus
Répartition par catégorie et paiement
Export CSV des statistiques
Système de suivi des stocks faibles et produits périmés

### 🌍 3. Front Office (Utilisateur)
Gestion du frigo connecté avec ajout d’aliments
Ajout manuel avec emojis automatiques
Ajout via scan QR / code-barres
Ajout par reconnaissance vocale 🎤
Gestion des quantités en temps réel
Alertes couleur : frais / bientôt périmé / périmé
Recherche et filtrage par catégorie
Système de favoris ⭐

### 🛒 4. Supermarché Virtuel
Affichage des produits par catégorie avec emojis dynamiques
Panier interactif avec modification des quantités
Application des codes promo
Suggestions de produits complémentaires
Historique des commandes utilisateur
Recommandations personnalisées basées sur l’historique

### 🆘 5. Livraison Géolocalisée ⭐
Carte interactive Leaflet.js + OpenStreetMap
Calcul automatique de la distance utilisateur/magasin
Frais de livraison dynamiques :

Gratuit ≤ 2 km
3 TND ≤ 5 km
5 TND ≤ 10 km
Zone non livrable au-delà de 10 km
Géocodage inverse et récupération automatique d’adresse

### 💳 6. Paiement & Commandes
Méthodes de paiement : Espèces / Carte bancaire / Virement
Validation carte bancaire côté client (Luhn, CVV, expiration)
Formulaire de checkout avec validation en temps réel
Historique des commandes
Génération PDF récapitulatif après confirmation

### 🎤 7. Commandes Vocales & Suggestions Intelligentes
Ajout d’aliments par reconnaissance vocale (Web Speech API)
Lecture vocale du contenu du frigo
Suggestions intelligentes basées sur :

Stocks faibles
Produits favoris
Historique des commandes
Produits complémentaires
Gestion des erreurs avec fallback textuel

### 📸 8. Scan Intelligent des Produits
Intégration Html5Qrcode
Reconnaissance QR codes & codes-barres EAN-13
Ajout automatique des produits au frigo
Fallback manuel si produit non reconnu

### 🎨 9. Design UI/UX
Interface responsive (mobile / tablette / desktop)
Palette de couleurs vert/santé (#2d6a2d)
Animations et transitions CSS fluides
Séparation Front Office / Back Office
Emojis automatiques pour les aliments
Interface moderne et intuitive

## 📸 Aperçu de l’interface

| Front Office | Back Office Admin |
|-------------------------------------------|--------------------------------------|
| 🧊 Mon Frigo avec alertes & emojis | 📦 Gestion des produits |
| 🛒 Panier interactif & promos | 📊 Dashboard statistiques |
| 🗺️ Carte livraison interactive | 🏷️ CRUD catégories |
| 🎤 Commande vocale | 💰 Gestion commandes |
| ⭐ Favoris & recommandations | 📈 Export CSV |

### 🔗 Liens utiles
📁 Repository GitHub : [Esprit-PI-2A29-2526-AlimentationDurable](https://github.com/sinda15960/Esprit-PI-2A29-2526-AlimentationDurable)
👤 LinkedIn : Hajer ben temessek (www.linkedin.com/in/hajer-ben-temessek-7a8a8b3a9)
🏫 Esprit School of Engineering :  [esprit.tn](https://esprit.tn)

---
## 🌟 — Cyrine Sboui
Module : Gestion Objectifs Personnalisés & Programmes Fitness
### 🗄️ 1. Base de données

Création des tables : objectif, objectif_personnalise, exercice, programme, favori, abonnement
Gestion des relations entre objectifs, exercices et programmes
Mise en place des clés étrangères et contraintes d’intégrité
Gestion des jointures complexes pour recommandations, statistiques et progression utilisateur

### 🎨 2. Back Office (Administration)

CRUD complet des objectifs, objectifs personnalisés, exercices et programmes
Gestion des catégories et organisation des programmes fitness
Dashboard statistique avec graphiques et indicateurs de progression
Suivi des utilisateurs, abonnements et activités
Export PDF des statistiques et rapports
Validation des données et gestion sécurisée des opérations administratives

### 🌍 3. Front Office (Utilisateur)

Création d’objectifs personnalisés selon :

poids
taille
âge
conditions de santé

Calcul automatique du BMI avec conseils personnalisés
Affichage des programmes fitness adaptés au profil utilisateur
Système de favoris pour sauvegarder les programmes préférés ❤️
Recherche, filtrage et tri dynamique des programmes
Interface responsive et intuitive pour une meilleure expérience utilisateur

### 🏋️ 4. Gestion des Exercices & Programmes

Système de progression des exercices étape par étape
Déblocage automatique des exercices après validation du précédent
Chronomètre interactif avec barre de progression en temps réel ⏱️
Notifications sonores à la fin des exercices
Suivi automatique de la progression utilisateur
Historique des programmes suivis

### 🤖 5. Intelligence Artificielle & Assistance

Assistant vocal intégré pour faciliter la navigation 🎤
Système intelligent de recommandation de programmes inspiré des recommandations Netflix
Suggestions personnalisées selon :

profil utilisateur
objectifs fitness
progression réalisée

Notifications intelligentes en cas de retard ou d’inactivité 🔔

### 📸 6. OCR & Scan Intelligent

Intégration d’un système OCR pour extraction intelligente des données 📷
Analyse automatique des informations scannées
Ajout simplifié des données utilisateur grâce au scan intelligent

### 💳 7. Abonnements & Paiement

Intégration Stripe pour la gestion des abonnements
Plans disponibles :

Basic
Pro
Pro Max

Gestion des paiements et historique des abonnements
Validation sécurisée des transactions

### 🎨 8. Design UI/UX

Mode sombre / clair 🌙
Interface responsive (mobile / tablette / desktop)
Design moderne avec Bootstrap 5
Animations et transitions fluides
Séparation Front Office / Back Office
Expérience utilisateur optimisée et navigation intuitive

### 📸 Aperçu de l’interface
| Front Office | Back Office Admin |
|--------------|-------------------|
| 🎯 Objectifs personnalisés | ⚙️ Gestion des objectifs |
| 🏋️ Programmes & exercices | 📊 Dashboard statistiques |
| ❤️ Système de favoris | 📄 Export PDF |
| 🎤 Assistant vocal | 🛠️ CRUD complet |


### 🔗 Liens utiles
📁 Repository GitHub : [Ajouter votre lien GitHub]
👤 LinkedIn : Cyrine Sboui (Ajouter votre profil LinkedIn)
🏫 Esprit School of Engineering : esprit.tn

---

## 👩‍💻 Contributeurs

| Nom | Module |
|-----|--------|
| **Sinda Lazaar** | **Gestion Frigo** |
| **Maissa Jouini** | **Gestion Plan Alimentaire** |
| **Cyrine Sboui** | **Gestion Utilisateurs** |
| **Sarra Dimassi** | **Gestion Recettes & Catégories** |
| **Hajer BenTemessek** | **Gestion frigo** |
| **Nada Azlouk** | **Gestion Allergies & Traitements** |

---

## 🎓 Contexte Académique

> Projet réalisé à **Esprit School of Engineering** — Tunis, Tunisie  
> Année universitaire : **2025–2026**  
> Module : **Projet Intégré Technologies Web (2A)**  
> Classe : **2A29**

---

<div align="center">
  <sub>© 2026 NutriFlow AI — Mangez sainement, vivez pleinement 🌿</sub>
</div>
