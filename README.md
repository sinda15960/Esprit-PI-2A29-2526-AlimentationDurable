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
nutriflow-ai/
│
├── 📁 assets/
│   ├── 📁 css/
│   │   ├── front-style.css           # Styles de l'interface utilisateur
│   │   ├── back-style.css            # Styles de l'interface administrateur
│   │   └── dark-mode.css             # Styles du mode sombre / clair
│   │
│   └── 📁 js/
│       ├── validation.js             # Validation des formulaires côté front
│       ├── admin.js                  # Fonctions globales de l'administration
│       ├── dark-mode.js              # Gestion du mode sombre / clair
│       └── confetti.js               # Animation de confettis
│
├── 📁 config/
│   ├── database.php                 # Connexion à la base de données
│   └── session.php                  # Gestion des sessions PHP
│
├── 📁 controllers/
│   ├── UserController.php           # Contrôleur des actions utilisateur
│   └── AdminController.php          # Contrôleur des actions administrateur
│
├── 📁 models/
│   └── User.php                     # Modèle User (CRUD utilisateurs)
│
├── 📁 views/
│   │
│   ├── 📁 front/                    # Interface utilisateur
│   │   ├── layout.php               # Layout principal du front
│   │   ├── home.php                 # Page d’accueil
│   │   ├── login.php                # Page de connexion
│   │   ├── register.php             # Page d’inscription
│   │   ├── profile.php              # Profil utilisateur
│   │   ├── forgot-password.php      # Mot de passe oublié
│   │   └── reset-password.php       # Réinitialisation du mot de passe
│   │
│   ├── 📁 components/               # Composants réutilisables
│   │   ├── streak-widget.php        # Widget des séries de connexions
│   │   ├── daily-quote.php          # Citation quotidienne
│   │   ├── avatar-generator.php     # Générateur d’avatar
│   │   └── features-buttons.php     # Boutons des futures fonctionnalités
│   │
│   └── 📁 back/                     # Interface administrateur
│       ├── layout.php               # Layout principal admin
│       ├── dashboard.php            # Tableau de bord
│       ├── users.php                # Gestion des utilisateurs
│       ├── edit-user.php            # Modification d’un utilisateur
│       ├── add-user.php             # Ajout d’un utilisateur
│       ├── globe-3d.php             # Visualisation 3D du globe
│       ├── secret-zone.php          # Zone secrète (easter eggs)
│       ├── retro-terminal.php       # Interface terminal rétro
│       ├── incognito-mode.php       # Mode navigation privée
│       ├── keyboard-shortcuts.php   # Raccourcis clavier
│       ├── comparison-mode.php      # Comparaison de périodes
│       ├── admin-leaderboard.php    # Classement des administrateurs
│       └── database-cleaner.php     # Nettoyage de la base de données
│
├── 📁 database/
│   └── nutriflow_ai.sql             # Script SQL de création de la base
│
├── 📁 uploads/                      # Uploads (avatars, images, etc.)
│
└── index.php                        # Point d’entrée principal / routeur
```

---



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
| 🧊 Gestion Frigo |Hajer ben temessek | Inventaire, alertes péremption |
| 📋 Gestion Plan Alimentaire |Sarra Dimassi | Planification repas, recettes |
| 👤 Gestion Utilisateurs | Sinda Lazaar | Authentification, profils |
| 🛒 Gestion Commandes | Cyrine Sboui| Listes de courses, commandes |
| 📊 Gestion Donation | Maissa Jouini | Tableaux de bord, rapports |

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
SINDA LAZAAR
Module : Gestion Utilisateurs & Nutrition
🗄️ 1. Base de données
Table	Rôle
users	Informations utilisateurs
contact_messages	Messages de contact
admin_notifications	Notifications admin
user_login_logs	Historique des connexions
user_face_data	Signatures Face ID
🎨 2. Back Office (Admin)
Dashboard avec statistiques et graphiques

CRUD utilisateurs (ajout, modification, suppression)

Filtrage, recherche et tri des utilisateurs

Export CSV/Excel

Gestion des messages de contact

Widgets personnalisables

🌍 3. Front Office (Utilisateur)
Inscription / Connexion / Mot de passe oublié

Profil utilisateur avec avatar personnalisable

Suivi des objectifs (Goal Tracker)

Série de connexions (Streak) et niveaux XP

Citations inspirantes quotidiennes

Mode sombre / clair

🔐 4. Authentification
Connexion standard (email + mot de passe)

Remember Me (cookie 30 jours)

Face ID (via webcam)

Voice to Text (reconnaissance vocale)

Social Login (simulé)

🚀 5. Fonctionnalités Premium
Fonction	Description
Globe 3D	Visualisation des connexions mondiales
Zone secrète	Easter eggs + mini-jeu Snake
Terminal rétro	Interface style commandes
Mode incognito	Preview du site sans se déconnecter
Raccourcis clavier	Personnalisables
Leaderboard	Classement des admins
Nettoyeur BDD	Simulation de nettoyage
📊 6. Technologies utilisées
Type	Technologies
Backend	PHP 7.4+, MySQL
Frontend	HTML5, CSS3, JavaScript
Librairies	Chart.js, Three.js, Leaflet.js
Stockage	LocalStorage, SessionStorage
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

## 👩‍💻 Contributeurs

| Nom | Module |
|-----|--------|
| Sinda Lazaar | Gestion utilisateurs|
| Maissa Jouini | Gestion donation |
| Cyrine Sboui | Gestion plan alimentaires |
| Sarra Dimassi | Gestion Commandes |
| Hajer BenTemessek | Statistiques & Rapports |
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
