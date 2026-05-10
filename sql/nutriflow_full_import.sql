-- =============================================================================
-- NutriFlow — SCHÉMA UNIQUE (nutriflow_db) — SEUL FICHIER À IMPORTER
-- Import phpMyAdmin : Importer ce fichier (il DROP/CREATE nutriflow_db).
-- Inclut déjà : gestion_plan.favori.date_ajout, frigo_utilisateur.emoji, etc.
-- (Les anciens patch_*.sql ont été fusionnés ici — ne pas les importer séparément.)
-- =============================================================================
--
-- Contenu fusionné :
--   • Module allergies / traitements / logs / chatbot (sql/*.sql)
--   • Module gestion_plan : categorie (id_categorie), objectif, programme, exercice, favori
--   • Module frigo : tables préfixées frigo_* (évite conflit avec categorie / favori du plan)
--   • Module projet2A : categories, recipes, instructions, recipe_versions, voice_settings,
--     associations, dons, users étendu + tables admin NutriFlow
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS nutriflow_db;
CREATE DATABASE nutriflow_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nutriflow_db;

-- ---------------------------------------------------------------------------
-- ALLERGIES & TRAITEMENTS (colonnes fusionnées avec database_update + add_new_features)
-- ---------------------------------------------------------------------------
CREATE TABLE allergies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    categorie VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    symptomes TEXT NOT NULL,
    declencheurs TEXT NOT NULL,
    gravite ENUM('legere', 'moderate', 'severe') NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    vue_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE traitements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    allergie_id INT NOT NULL,
    conseil TEXT NOT NULL,
    interdits TEXT NOT NULL,
    medicaments TEXT,
    medicaments_urgence VARCHAR(255) DEFAULT NULL,
    duree VARCHAR(100),
    niveau_urgence ENUM('faible', 'moyen', 'eleve') NOT NULL,
    note_moyenne DECIMAL(3,1) DEFAULT 0,
    nb_notes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (allergie_id) REFERENCES allergies(id) ON DELETE CASCADE,
    UNIQUE KEY unique_allergie (allergie_id)
);

CREATE TABLE feedbacks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('erreur', 'suggestion', 'experience', 'alternative') NOT NULL,
    message TEXT NOT NULL,
    email VARCHAR(255),
    status ENUM('approuve', 'en_attente', 'rejete') DEFAULT 'en_attente',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE evaluations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    traitement_id INT NOT NULL,
    note INT NOT NULL,
    ip_address VARCHAR(45),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (traitement_id) REFERENCES traitements(id) ON DELETE CASCADE,
    UNIQUE KEY unique_ip_traitement (traitement_id, ip_address)
);

CREATE TABLE logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    action_type ENUM('ADD', 'EDIT', 'DELETE') NOT NULL,
    table_name VARCHAR(50) NOT NULL,
    record_id INT NOT NULL,
    record_name VARCHAR(255),
    details TEXT,
    admin_user VARCHAR(100) DEFAULT 'admin',
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_table_record (table_name, record_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE user_profiles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id VARCHAR(255) NOT NULL,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    date_naissance DATE,
    telephone VARCHAR(20),
    medicament_urgence VARCHAR(255),
    selected_allergies TEXT,
    critical_allergies TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_session (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE chatbot_conversations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id VARCHAR(255) NOT NULL,
    user_message TEXT NOT NULL,
    bot_response TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session (session_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE allergy_comparisons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id VARCHAR(255) NOT NULL,
    allergy_id_1 INT NOT NULL,
    allergy_id_2 INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (allergy_id_1) REFERENCES allergies(id) ON DELETE CASCADE,
    FOREIGN KEY (allergy_id_2) REFERENCES allergies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------------
-- UTILISATEURS (NutriFlow projet2A + champs utilisés par gestion_plan / stats)
-- ---------------------------------------------------------------------------
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    nom VARCHAR(255) DEFAULT NULL,
    full_name VARCHAR(255) DEFAULT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    age INT DEFAULT NULL,
    weight DECIMAL(6,2) DEFAULT NULL,
    height DECIMAL(6,2) DEFAULT NULL,
    dietary_preference VARCHAR(255) DEFAULT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    needs_welcome_message TINYINT(1) NOT NULL DEFAULT 0,
    has_face_id TINYINT(1) NOT NULL DEFAULT 0,
    remember_token VARCHAR(255) DEFAULT NULL,
    token_expires DATETIME DEFAULT NULL,
    date_creation TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------------------------
-- GESTION PLAN (catégorie = id_categorie ; favori = programmes favoris utilisateur)
-- ---------------------------------------------------------------------------
CREATE TABLE categorie (
    id_categorie INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE objectif (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    type_objectif ENUM('grossir','maigrir','maintenir','muscler') DEFAULT NULL,
    description TEXT,
    maladies TEXT,
    preferences TEXT,
    calories_min INT DEFAULT NULL,
    calories_max INT DEFAULT NULL,
    poids_actuel DECIMAL(6,2) DEFAULT NULL,
    poids_cible DECIMAL(6,2) DEFAULT NULL,
    taille DECIMAL(5,2) DEFAULT NULL,
    age INT DEFAULT NULL,
    etat_sante TEXT,
    date_debut DATE DEFAULT NULL,
    date_fin_prevue DATE DEFAULT NULL,
    user_id INT DEFAULT NULL,
    is_personal TINYINT(1) NOT NULL DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE programme (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    duree_semaines INT NOT NULL DEFAULT 4,
    niveau ENUM('debutant','intermediaire','avance') NOT NULL,
    objectif_id INT NOT NULL,
    categorie_id INT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (objectif_id) REFERENCES objectif(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categorie(id_categorie) ON DELETE RESTRICT
);

CREATE TABLE exercice (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    ordre INT NOT NULL DEFAULT 1,
    duree_minutes INT DEFAULT 0,
    video_url VARCHAR(500) DEFAULT NULL,
    programme_id INT NOT NULL,
    statut ENUM('en_attente','en_cours','termine') NOT NULL DEFAULT 'en_attente',
    date_validation DATETIME DEFAULT NULL,
    repetitions_realisees INT DEFAULT NULL,
    poids_utilise DECIMAL(8,2) DEFAULT NULL,
    ressenti ENUM('facile','moyen','difficile') DEFAULT NULL,
    note_user TEXT,
    user_id INT DEFAULT NULL,
    FOREIGN KEY (programme_id) REFERENCES programme(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE favori (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    programme_id INT NOT NULL,
    date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_gp_favori (user_id, programme_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (programme_id) REFERENCES programme(id) ON DELETE CASCADE
);

-- ---------------------------------------------------------------------------
-- FRIGO (tables préfixées — alignées avec le code PHP mis à jour frigo/*)
-- ---------------------------------------------------------------------------
CREATE TABLE frigo_categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255)
);

CREATE TABLE frigo_produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL DEFAULT 0,
    quantite INT NOT NULL DEFAULT 0,
    date_expiration DATE DEFAULT NULL,
    categorie_id INT DEFAULT NULL,
    image VARCHAR(255),
    FOREIGN KEY (categorie_id) REFERENCES frigo_categorie(id) ON DELETE SET NULL
);

CREATE TABLE frigo_commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_client VARCHAR(150) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    adresse TEXT NOT NULL,
    methode_paiement ENUM('especes','carte','virement') NOT NULL,
    total DECIMAL(10,2) DEFAULT 0,
    statut ENUM('en_attente','confirmee','annulee') DEFAULT 'en_attente',
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE frigo_commande_produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES frigo_commande(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES frigo_produit(id) ON DELETE CASCADE
);

CREATE TABLE frigo_favori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produit_id INT NOT NULL UNIQUE,
    FOREIGN KEY (produit_id) REFERENCES frigo_produit(id) ON DELETE CASCADE
);

CREATE TABLE frigo_code_promo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    reduction DECIMAL(10,2) NOT NULL DEFAULT 0,
    type_reduction ENUM('pourcentage','montant') NOT NULL DEFAULT 'pourcentage',
    actif TINYINT(1) NOT NULL DEFAULT 1,
    date_expiration DATE DEFAULT NULL,
    utilisation_max INT NOT NULL DEFAULT 0,
    utilisation_compteur INT NOT NULL DEFAULT 0,
    client_unique TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE frigo_code_promo_utilisation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code_promo_id INT NOT NULL,
    commande_id INT NOT NULL,
    telephone_client VARCHAR(20) DEFAULT NULL,
    reduction_appliquee DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (code_promo_id) REFERENCES frigo_code_promo(id) ON DELETE CASCADE,
    FOREIGN KEY (commande_id) REFERENCES frigo_commande(id) ON DELETE CASCADE
);

CREATE TABLE frigo_utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produit_id INT DEFAULT NULL,
    nom_custom VARCHAR(255) DEFAULT NULL,
    quantite INT NOT NULL DEFAULT 1,
    date_expiration DATE DEFAULT NULL,
    seuil_alerte INT NOT NULL DEFAULT 1,
    emoji VARCHAR(32) DEFAULT '🥗',
    FOREIGN KEY (produit_id) REFERENCES frigo_produit(id) ON DELETE SET NULL
);

-- ---------------------------------------------------------------------------
-- RECETTES (projet2A) — table categories (nom anglais) ≠ categorie gestion_plan
-- ---------------------------------------------------------------------------
CREATE TABLE categories (
    idCategorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(100) DEFAULT NULL,
    couleur VARCHAR(32) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    ingredients TEXT NOT NULL,
    prep_time INT NOT NULL,
    cook_time INT NOT NULL,
    difficulty ENUM('facile', 'moyen', 'difficile') DEFAULT 'moyen',
    calories INT DEFAULT NULL,
    protein DECIMAL(5,2) DEFAULT NULL,
    carbs DECIMAL(5,2) DEFAULT NULL,
    fats DECIMAL(5,2) DEFAULT NULL,
    image_url VARCHAR(500) DEFAULT NULL,
    is_vegan TINYINT(1) DEFAULT 0,
    is_vegetarian TINYINT(1) DEFAULT 0,
    is_gluten_free TINYINT(1) DEFAULT 0,
    idCategorie INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idCategorie) REFERENCES categories(idCategorie) ON DELETE SET NULL
);

CREATE TABLE instructions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT NOT NULL,
    step_number INT NOT NULL,
    description TEXT NOT NULL,
    tip TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);

CREATE TABLE recipe_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    ingredients TEXT NOT NULL,
    prep_time INT NOT NULL,
    cook_time INT NOT NULL,
    difficulty ENUM('facile', 'moyen', 'difficile') NOT NULL,
    calories INT DEFAULT NULL,
    protein DECIMAL(5,2) DEFAULT NULL,
    carbs DECIMAL(5,2) DEFAULT NULL,
    fats DECIMAL(5,2) DEFAULT NULL,
    image_url VARCHAR(500) DEFAULT NULL,
    is_vegan TINYINT(1) DEFAULT 0,
    is_vegetarian TINYINT(1) DEFAULT 0,
    is_gluten_free TINYINT(1) DEFAULT 0,
    idCategorie INT DEFAULT NULL,
    change_comment TEXT,
    version_number INT NOT NULL DEFAULT 1,
    modified_by VARCHAR(255) DEFAULT NULL,
    modified_at DATETIME DEFAULT NULL,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);

CREATE TABLE voice_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    voice_gender VARCHAR(20) DEFAULT 'female',
    voice_rate DECIMAL(4,2) DEFAULT 1.00,
    voice_pitch DECIMAL(4,2) DEFAULT 1.00,
    voice_volume DECIMAL(4,2) DEFAULT 1.00,
    enabled TINYINT(1) DEFAULT 1,
    UNIQUE KEY uq_voice_user (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE associations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    postal_code VARCHAR(10) NOT NULL,
    siret VARCHAR(14) NOT NULL UNIQUE,
    mission TEXT NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE dons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    association_id INT NOT NULL,
    donor_name VARCHAR(100) NOT NULL,
    donor_email VARCHAR(100) NOT NULL,
    donor_phone VARCHAR(20),
    amount DECIMAL(10,2) DEFAULT 0,
    donation_type ENUM('monetary', 'food', 'equipment') NOT NULL,
    food_type VARCHAR(50),
    quantity INT DEFAULT 0,
    message TEXT,
    status ENUM('pending', 'confirmed', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method ENUM('card', 'paypal', 'bank_transfer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (association_id) REFERENCES associations(id) ON DELETE CASCADE
);

CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread','read') NOT NULL DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_login_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT DEFAULT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    country VARCHAR(100) DEFAULT NULL,
    latitude DECIMAL(10,7) DEFAULT NULL,
    longitude DECIMAL(10,7) DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE admin_notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(500) DEFAULT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_face_data (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    face_signature LONGTEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------------
-- DONNÉES INITIALES
-- ---------------------------------------------------------------------------

INSERT INTO allergies (nom, categorie, description, symptomes, declencheurs, gravite, image_url, vue_count) VALUES
('Gluten', 'Alimentaire', 'Intolérance au gluten, maladie cœliaque. Le gluten est une protéine présente dans certaines céréales.', 'Ballonnements, douleurs abdominales, fatigue, diarrhée, perte de poids', 'Blé, orge, seigle, avoine, pain, pâtes, biscuits', 'severe', 'https://cdn-icons-png.flaticon.com/512/1046/1046784.png', 0),
('Lactose', 'Alimentaire', 'Intolérance au lactose causée par un déficit en lactase, l\'enzyme qui digère le lactose.', 'Diarrhées, gaz, ballonnements, douleurs abdominales, nausées', 'Lait, fromage frais, crème, yaourt, beurre, glaces', 'moderate', 'https://cdn-icons-png.flaticon.com/512/3059/3059995.png', 0),
('Arachides', 'Alimentaire', 'Allergie aux arachides, une des allergies alimentaires les plus dangereuses.', 'Urticaire, gonflement du visage, difficultés respiratoires, choc anaphylactique', 'Arachides, beurre d\'arachide, huile d\'arachide, cacahuètes', 'severe', 'https://cdn-icons-png.flaticon.com/512/4240/4240723.png', 0),
('Fruits de mer', 'Alimentaire', 'Allergie aux crustacés et mollusques, souvent à vie.', 'Démangeaisons, urticaire, nausées, vomissements, difficultés respiratoires', 'Crevettes, crabes, homards, moules, huîtres, calmars', 'severe', 'https://cdn-icons-png.flaticon.com/512/3215/3215905.png', 0),
('Œufs', 'Alimentaire', 'Allergie aux protéines de l\'œuf, fréquente chez les enfants.', 'Éruptions cutanées, urticaire, congestion nasale, troubles digestifs', 'Œufs entiers, blanc d\'œuf, mayonnaise, pâtisseries', 'moderate', 'https://cdn-icons-png.flaticon.com/512/3031/3031041.png', 0),
('Soja', 'Alimentaire', 'Allergie au soja, présent dans de nombreux aliments transformés.', 'Fourmillements dans la bouche, urticaire, démangeaisons, difficultés respiratoires', 'Soja, tofu, tempeh, sauce soja, lait de soja, edamame', 'moderate', 'https://cdn-icons-png.flaticon.com/512/4194/4194830.png', 0),
('Poisson', 'Alimentaire', 'Allergie au poisson, distincte des fruits de mer.', 'Urticaire, gonflement, vomissements, difficultés respiratoires', 'Saumon, thon, morue, sardine, maquereau, truite', 'severe', 'https://cdn-icons-png.flaticon.com/512/4060/4060854.png', 0),
('Pollen', 'Respiratoire', 'Allergie au pollen des arbres, graminées et herbes.', 'Éternuements, nez qui coule, yeux qui piquent, congestion nasale', 'Pollen de bouleau, graminées, ambroisie, olivier', 'legere', 'https://cdn-icons-png.flaticon.com/512/3014/3014885.png', 0),
('Acariens', 'Respiratoire', 'Allergie aux acariens de la poussière domestique.', 'Éternuements, nez bouché, yeux rouges, asthme, toux', 'Poussière, literie, moquettes, rideaux, peluches', 'moderate', 'https://cdn-icons-png.flaticon.com/512/3163/3163495.png', 0),
('Penicilline', 'Médicamenteuse', 'Allergie à la pénicilline et antibiotiques dérivés.', 'Urticaire, démangeaisons, gonflement, difficultés respiratoires', 'Pénicilline, amoxicilline, ampicilline', 'severe', 'https://cdn-icons-png.flaticon.com/512/2938/2938527.png', 0);

INSERT INTO traitements (allergie_id, conseil, interdits, medicaments, medicaments_urgence, duree, niveau_urgence, note_moyenne, nb_notes) VALUES
(1, 'Évitez tous les aliments contenant du gluten. Lisez attentivement les étiquettes.', 'Pain, pâtes, biscuits, céréales, bière, pâtisseries', 'Aucun médicament spécifique, régime strict à vie', NULL, 'Permanente', 'moyen', 0, 0),
(2, 'Privilégiez les produits sans lactose ou prenez des enzymes lactase.', 'Lait, fromage frais, crème, yaourt, glaces', 'Compléments de lactase (Lactaid, Lactrase)', NULL, 'Selon tolérance', 'faible', 0, 0),
(3, 'Évitez tout contact avec les arachides. Ayez toujours un auto-injecteur d\'adrénaline.', 'Arachides, beurre d\'arachide, huile d\'arachide, cacahuètes', 'Antihistaminiques, EpiPen (adrénaline)', 'EpiPen, Antihistaminiques', 'À vie', 'eleve', 0, 0),
(4, 'Évitez tous les crustacés et mollusques. Attention aux contaminations croisées.', 'Crevettes, crabes, homards, moules, huîtres, calmars', 'Antihistaminiques, EpiPen pour réactions sévères', 'EpiPen, Antihistaminiques', 'À vie', 'eleve', 0, 0),
(5, 'Évitez les œufs et produits dérivés. La cuisson ne détruit pas l\'allergène.', 'Œufs entiers, blanc d\'œuf, mayonnaise, pâtisseries, viennoiseries', 'Antihistaminiques', 'Antihistaminiques', 'Peut disparaître avec l\'âge', 'moyen', 0, 0),
(6, 'Lisez les étiquettes, le soja est présent dans de nombreux produits transformés.', 'Tofu, tempeh, sauce soja, lait de soja, edamame', 'Antihistaminiques', 'Antihistaminiques', 'À vie généralement', 'moyen', 0, 0),
(7, 'Évitez tous les poissons. Attention aux restaurants et aux aliments transformés.', 'Saumon, thon, morue, sardine, maquereau, truite', 'Antihistaminiques, EpiPen', 'EpiPen, Antihistaminiques', 'À vie', 'eleve', 0, 0),
(8, 'Restez à l\'intérieur pendant les pics polliniques. Prenez une douche en rentrant.', 'Sorties prolongées au printemps, fenêtres ouvertes', 'Antihistaminiques (Cetirizine, Loratadine)', 'Antihistaminiques', 'Saisonnière', 'faible', 0, 0),
(9, 'Utilisez des housses anti-acariens. Lavez la literie à 60°C.', 'Tapis, moquettes, rideaux épais, peluches', 'Antihistaminiques, corticoïdes inhalés', 'Antihistaminiques', 'Toute l\'année', 'moyen', 0, 0),
(10, 'Signalez toujours votre allergie avant tout traitement antibiotique.', 'Pénicilline, amoxicilline, ampicilline et dérivés', 'Antibiotiques alternatifs (macrolides, quinolones)', 'Antibiotiques alternatifs', 'À vie', 'eleve', 0, 0);

UPDATE traitements SET medicaments_urgence = 'EpiPen, Antihistaminiques' WHERE niveau_urgence = 'eleve';
UPDATE traitements SET medicaments_urgence = 'Antihistaminiques' WHERE niveau_urgence = 'moyen';
UPDATE traitements SET medicaments_urgence = 'Aucun spécifique' WHERE niveau_urgence = 'faible';

INSERT INTO feedbacks (type, message, email, status) VALUES
('experience', 'Je suis cœliaque depuis 5 ans, le régime sans gluten a changé ma vie !', '', 'approuve'),
('suggestion', 'Ajoutez l\'EPIPEN dans les traitements d\'urgence pour les allergies sévères !', '', 'approuve');

-- Mot de passe hash Laravel test = "password"
INSERT INTO users (id, username, email, password, nom, full_name, role, is_active, date_creation, created_at) VALUES
(1, 'alice', 'alice@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Alice Martin', 'Alice Martin', 'user', 1, NOW(), NOW()),
(2, 'bob', 'bob@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bob Dupont', 'Bob Dupont', 'user', 1, NOW(), NOW()),
(3, 'charlie', 'charlie@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Charlie Durand', 'Charlie Durand', 'user', 1, NOW(), NOW()),
(99, 'admin', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrateur', 'Administrateur', 'admin', 1, NOW(), NOW());

INSERT INTO categorie (nom, description) VALUES
('Cardio', 'Programmes endurance et cardio'),
('Musculation', 'Force et hypertrophie'),
('Flexibilité', 'Mobilité et stretching');

INSERT INTO objectif (titre, type_objectif, description, maladies, preferences, calories_min, calories_max, is_personal) VALUES
('Perte de poids durable', 'maigrir', 'Objectif calorie contrôlée avec activité régulière.', '', '', 1200, 1800, 0),
('Prise de masse', 'grossir', 'Surplus modéré et renforcement.', '', '', 2200, 3200, 0);

INSERT INTO programme (nom, description, duree_semaines, niveau, objectif_id, categorie_id) VALUES
('Starter cardio 4 semaines', 'Marche rapide et vélo léger.', 4, 'debutant', 1, 1),
('Force full body', 'Séances polyarticulaires 3x/semaine.', 8, 'intermediaire', 2, 2);

INSERT INTO exercice (nom, description, ordre, duree_minutes, video_url, programme_id, statut) VALUES
('Échauffement 5 min', 'Articulations et cardio léger.', 1, 5, NULL, 1, 'en_attente'),
('Marche active 20 min', 'FC modérée.', 2, 20, NULL, 1, 'en_attente'),
('Squat au poids du corps', 'Technique et amplitude.', 1, 15, NULL, 2, 'en_attente'),
('Tractions assistées', 'Ou rowing si matériel.', 2, 20, NULL, 2, 'en_attente');

INSERT INTO categories (nom, description, icon, couleur) VALUES
('Plats végétaux', 'Recettes végétariennes et véganes', '🥗', '#2d5a27'),
('Plats équilibrés', 'Macro équilibrés', '🍽️', '#4a8f3f'),
('Desserts légers', 'Sans excès de sucre', '🍰', '#e67e22');

INSERT INTO recipes (title, description, ingredients, prep_time, cook_time, difficulty, calories, protein, carbs, fats, is_vegan, is_vegetarian, is_gluten_free, idCategorie) VALUES
('Bowl Végétal aux Quinoa et Légumes Rôtis', 'Un bowl équilibré riche en protéines végétales et légumes de saison', 'Quinoa, Courgettes, Poivrons, Oignons rouges, Huile d''olive, Épices, Tofu fumé', 15, 25, 'moyen', 450, 15.5, 65.2, 12.3, 1, 1, 1, 1),
('Curry de Lentilles Corail', 'Un curry crémeux et réconfortant, riche en fer et protéines', 'Lentilles corail, Lait de coco, Tomates, Oignons, Ail, Gingembre, Épices, Coriandre', 10, 30, 'facile', 380, 18.2, 52.4, 8.5, 1, 1, 1, 1),
('Salade de Pâtes Complètes au Pesto', 'Une salade fraîche et nourrissante, parfaite pour l''été', 'Pâtes complètes, Pesto maison, Tomates cerises, Roquette, Pignons de pin, Parmesan végétal', 15, 10, 'facile', 520, 14.8, 68.5, 18.2, 0, 1, 0, 3);

INSERT INTO instructions (recipe_id, step_number, description, tip) VALUES
(1, 1, 'Rincez le quinoa et faites-le cuire dans 2 volumes d''eau pendant 15 minutes', 'Ajoutez un bouillon cube pour plus de saveur'),
(1, 2, 'Coupez les légumes en dés et enfournez à 200°C pendant 20 minutes', 'Mélangez-les à mi-cuisson'),
(1, 3, 'Faites revenir le tofu fumé à la poêle jusqu''à ce qu''il soit doré', ''),
(1, 4, 'Assemblez le bowl : quinoa, légumes rôtis, tofu et une sauce de votre choix', ''),
(2, 1, 'Faites revenir les oignons, l''ail et le gingembre dans une casserole', ''),
(2, 2, 'Ajoutez les épices et faites-les torréfier 1 minute', ''),
(2, 3, 'Ajoutez les lentilles, les tomates et le lait de coco, laissez mijoter 20 minutes', ''),
(2, 4, 'Servez avec du riz basmati et de la coriandre fraîche', ''),
(3, 1, 'Faites cuire les pâtes dans une grande casserole d''eau salée', 'Al dente pour la salade'),
(3, 2, 'Préparez le pesto : basilic, pignons, ail, huile d''olive et parmesan végétal', ''),
(3, 3, 'Mélangez les pâtes refroidies avec le pesto et les tomates cerises', ''),
(3, 4, 'Ajoutez la roquette et les pignons juste avant de servir', '');

INSERT INTO voice_settings (user_id, voice_gender, voice_rate, voice_pitch, voice_volume, enabled) VALUES
(NULL, 'female', 1.00, 1.00, 1.00, 1);

INSERT INTO associations (name, email, phone, address, city, postal_code, siret, mission, status) VALUES
('Les Restos du Cœur', 'contact@restosducoeur.fr', '0140055555', '12 Rue Palestine', 'Tunis', '75010', '14211564', 'Lutter contre la précarité alimentaire en distribuant des repas gratuits.', 'active'),
('Secours Populaire', 'contact@secourspopulaire.fr', '0144782120', '8 Rue de la liberté', 'Tunis', '75011', '23456789', 'Agir contre la pauvreté et l\'exclusion sous toutes ses formes.', 'active'),
('Banque Alimentaire', 'contact@banquealimentaire.org', '0144756200', '21 Rue de paris', 'Tunis', '75018', '34567890', 'Collecter et redistribuer les invendus alimentaires.', 'active');

INSERT INTO dons (association_id, donor_name, donor_email, donor_phone, amount, donation_type, food_type, quantity, message, status, payment_method) VALUES
(1, 'Maissa Jouini', 'maissa@email.com', '53211456', 150.00, 'monetary', NULL, 0, 'Pour aider', 'confirmed', 'card'),
(2, 'Eya Jouini', 'eya@email.com', '06234567', 75.50, 'monetary', NULL, 0, 'Bon courage', 'confirmed', 'paypal');

INSERT INTO frigo_categorie (nom, description) VALUES
('Fruits', 'Fruits frais et de saison'),
('Légumes', 'Légumes frais du marché'),
('Produits laitiers', 'Lait, fromage, yaourt'),
('Viandes', 'Viandes et volailles'),
('Boissons', 'Eaux, jus, sodas');

INSERT INTO frigo_produit (nom, description, prix, quantite, date_expiration, categorie_id) VALUES
('Pommes Golden', 'Sac 1kg', 4.50, 40, DATE_ADD(CURDATE(), INTERVAL 7 DAY), 1),
('Salade verte', 'Botte', 1.20, 25, DATE_ADD(CURDATE(), INTERVAL 3 DAY), 2),
('Yaourt nature', 'Pack x4', 3.90, 30, DATE_ADD(CURDATE(), INTERVAL 14 DAY), 3);

INSERT INTO frigo_code_promo (code, reduction, type_reduction, actif, date_expiration, utilisation_max, utilisation_compteur, client_unique) VALUES
('NUTRI10', 10.00, 'pourcentage', 1, DATE_ADD(CURDATE(), INTERVAL 365 DAY), 1000, 0, 0);

ALTER TABLE users AUTO_INCREMENT = 100;
