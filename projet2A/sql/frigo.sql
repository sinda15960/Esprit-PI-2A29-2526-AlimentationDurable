CREATE DATABASE frigo_intelligent CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE frigo_intelligent;

CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255)
);

CREATE TABLE produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL DEFAULT 0,
    quantite INT NOT NULL DEFAULT 0,
    date_expiration DATE,
    categorie_id INT,
    image VARCHAR(255),
    FOREIGN KEY (categorie_id) REFERENCES categorie(id) ON DELETE SET NULL
);

CREATE TABLE commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_client VARCHAR(150) NOT NULL,
    telephone VARCHAR(8) NOT NULL,
    adresse TEXT NOT NULL,
    methode_paiement ENUM('especes','carte','virement') NOT NULL,
    total DECIMAL(10,2) DEFAULT 0,
    statut ENUM('en_attente','confirmee','annulee') DEFAULT 'en_attente',
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE commande_produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commande(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produit(id) ON DELETE CASCADE
);

INSERT INTO categorie (nom, description) VALUES 
('Fruits', 'Fruits frais et de saison'),
('Légumes', 'Légumes frais du marché'),
('Produits laitiers', 'Lait, fromage, yaourt'),
('Viandes', 'Viandes et volailles'),
('Boissons', 'Eaux, jus, sodas');