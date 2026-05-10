                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                -- Création de la base de données
CREATE DATABASE IF NOT EXISTS nutriflow_db;
USE nutriflow_db;

-- Table des allergies
CREATE TABLE IF NOT EXISTS allergies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    categorie VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    symptomes TEXT NOT NULL,
    declencheurs TEXT NOT NULL,
    gravite ENUM('legere', 'moderate', 'severe') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des traitements
CREATE TABLE IF NOT EXISTS traitements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    allergie_id INT NOT NULL,
    conseil TEXT NOT NULL,
    interdits TEXT NOT NULL,
    medicaments TEXT,
    duree VARCHAR(100),
    niveau_urgence ENUM('faible', 'moyen', 'eleve') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (allergie_id) REFERENCES allergies(id) ON DELETE CASCADE,
    UNIQUE KEY unique_allergie (allergie_id)
);

-- Table des feedbacks
CREATE TABLE IF NOT EXISTS feedbacks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('erreur', 'suggestion', 'experience', 'alternative') NOT NULL,
    message TEXT NOT NULL,
    email VARCHAR(255),
    status ENUM('approuve', 'en_attente', 'rejete') DEFAULT 'en_attente',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion des données initiales
INSERT INTO allergies (nom, categorie, description, symptomes, declencheurs, gravite) VALUES
('Gluten', 'Alimentaire', 'Intolérance au gluten, maladie cœliaque. Le gluten est une protéine présente dans certaines céréales.', 'Ballonnements, douleurs abdominales, fatigue, diarrhée, perte de poids', 'Blé, orge, seigle, avoine, pain, pâtes, biscuits', 'severe'),
('Lactose', 'Alimentaire', 'Intolérance au lactose causée par un déficit en lactase, l\'enzyme qui digère le lactose.', 'Diarrhées, gaz, ballonnements, douleurs abdominales, nausées', 'Lait, fromage frais, crème, yaourt, beurre, glaces', 'moderate'),
('Arachides', 'Alimentaire', 'Allergie aux arachides, une des allergies alimentaires les plus dangereuses.', 'Urticaire, gonflement du visage, difficultés respiratoires, choc anaphylactique', 'Arachides, beurre d\'arachide, huile d\'arachide, cacahuètes', 'severe'),
('Fruits de mer', 'Alimentaire', 'Allergie aux crustacés et mollusques, souvent à vie.', 'Démangeaisons, urticaire, nausées, vomissements, difficultés respiratoires', 'Crevettes, crabes, homards, moules, huîtres, calmars', 'severe'),
('Œufs', 'Alimentaire', 'Allergie aux protéines de l\'œuf, fréquente chez les enfants.', 'Éruptions cutanées, urticaire, congestion nasale, troubles digestifs', 'Œufs entiers, blanc d\'œuf, mayonnaise, pâtisseries', 'moderate'),
('Soja', 'Alimentaire', 'Allergie au soja, présent dans de nombreux aliments transformés.', 'Fourmillements dans la bouche, urticaire, démangeaisons, difficultés respiratoires', 'Soja, tofu, tempeh, sauce soja, lait de soja, edamame', 'moderate'),
('Poisson', 'Alimentaire', 'Allergie au poisson, distincte des fruits de mer.', 'Urticaire, gonflement, vomissements, difficultés respiratoires', 'Saumon, thon, morue, sardine, maquereau, truite', 'severe'),
('Pollen', 'Respiratoire', 'Allergie au pollen des arbres, graminées et herbes.', 'Éternuements, nez qui coule, yeux qui piquent, congestion nasale', 'Pollen de bouleau, graminées, ambroisie, olivier', 'legere'),
('Acariens', 'Respiratoire', 'Allergie aux acariens de la poussière domestique.', 'Éternuements, nez bouché, yeux rouges, asthme, toux', 'Poussière, literie, moquettes, rideaux, peluches', 'moderate'),
('Penicilline', 'Médicamenteuse', 'Allergie à la pénicilline et antibiotiques dérivés.', 'Urticaire, démangeaisons, gonflement, difficultés respiratoires', 'Pénicilline, amoxicilline, ampicilline', 'severe');

INSERT INTO traitements (allergie_id, conseil, interdits, medicaments, duree, niveau_urgence) VALUES
(1, 'Évitez tous les aliments contenant du gluten. Lisez attentivement les étiquettes.', 'Pain, pâtes, biscuits, céréales, bière, pâtisseries', 'Aucun médicament spécifique, régime strict à vie', 'Permanente', 'moyen'),
(2, 'Privilégiez les produits sans lactose ou prenez des enzymes lactase.', 'Lait, fromage frais, crème, yaourt, glaces', 'Compléments de lactase (Lactaid, Lactrase)', 'Selon tolérance', 'faible'),
(3, 'Évitez tout contact avec les arachides. Ayez toujours un auto-injecteur d\'adrénaline.', 'Arachides, beurre d\'arachide, huile d\'arachide, cacahuètes', 'Antihistaminiques, EpiPen (adrénaline)', 'À vie', 'eleve'),
(4, 'Évitez tous les crustacés et mollusques. Attention aux contaminations croisées.', 'Crevettes, crabes, homards, moules, huîtres, calmars', 'Antihistaminiques, EpiPen pour réactions sévères', 'À vie', 'eleve'),
(5, 'Évitez les œufs et produits dérivés. La cuisson ne détruit pas l\'allergène.', 'Œufs entiers, blanc d\'œuf, mayonnaise, pâtisseries, viennoiseries', 'Antihistaminiques', 'Peut disparaître avec l\'âge', 'moyen'),
(6, 'Lisez les étiquettes, le soja est présent dans de nombreux produits transformés.', 'Tofu, tempeh, sauce soja, lait de soja, edamame', 'Antihistaminiques', 'À vie généralement', 'moyen'),
(7, 'Évitez tous les poissons. Attention aux restaurants et aux aliments transformés.', 'Saumon, thon, morue, sardine, maquereau, truite', 'Antihistaminiques, EpiPen', 'À vie', 'eleve'),
(8, 'Restez à l\'intérieur pendant les pics polliniques. Prenez une douche en rentrant.', 'Sorties prolongées au printemps, fenêtres ouvertes', 'Antihistaminiques (Cetirizine, Loratadine)', 'Saisonnière', 'faible'),
(9, 'Utilisez des housses anti-acariens. Lavez la literie à 60°C.', 'Tapis, moquettes, rideaux épais, peluches', 'Antihistaminiques, corticoïdes inhalés', 'Toute l\'année', 'moyen'),
(10, 'Signalez toujours votre allergie avant tout traitement antibiotique.', 'Pénicilline, amoxicilline, ampicilline et dérivés', 'Antibiotiques alternatifs (macrolides, quinolones)', 'À vie', 'eleve');

INSERT INTO feedbacks (type, message, email, status) VALUES
('experience', 'Je suis cœliaque depuis 5 ans, le régime sans gluten a changé ma vie !', '', 'approuve'),
('suggestion', 'Ajoutez l\'EPIPEN dans les traitements d\'urgence pour les allergies sévères !', '', 'approuve'),
('erreur', 'Le yaourt nature contient moins de lactose que le lait, à préciser.', '', 'approuve'),
('alternative', 'Alternative au pain : pain à base de farine de riz ou de sarrasin.', '', 'approuve');