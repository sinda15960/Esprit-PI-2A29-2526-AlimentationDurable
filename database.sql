CREATE DATABASE IF NOT EXISTS donsolidaire_db;
USE donsolidaire_db;

CREATE TABLE IF NOT EXISTS associations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    cause VARCHAR(100) NOT NULL,
    objectif DECIMAL(10, 2) NOT NULL,
    description TEXT,
    email VARCHAR(150),
    tel VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donateur VARCHAR(255) NOT NULL,
    email VARCHAR(150),
    associationId INT,
    montant DECIMAL(10, 2) NOT NULL,
    date DATE NOT NULL,
    type ENUM('ponctuel', 'mensuel', 'annuel'),
    message TEXT,
    FOREIGN KEY (associationId) REFERENCES associations(id) ON DELETE SET NULL
);

-- Insertion des données d'exemple (Basées sur votre app.js)
INSERT INTO associations (nom, cause, objectif, description, email, tel) VALUES
('Médecins Sans Frontières', 'Sante', 264000, 'Association humanitaire internationale d''aide medicale', 'contact@msf.org', '53211586'),
('GreenHealth Tunisia', 'Alimentation saine', 165000, 'Sensibilisation a une nutrition equilibree', 'info@greenhealth.tn', '53211586'),
('NutriBalance', 'diabète, tension', 99000, 'Accompagne les personnes ayant des maladies chroniques en leur proposant des conseils nutritionnels.', 'hello@NutriBalance.fr', '53211586'),
('Croissant Rouge Tunisien', 'Aide humanitaire', 66000, 'Organisation humanitaire qui aide les personnes vulnérables à travers des actions de secours, de distribution de nourriture, de soins médicaux et de soutien social.', 'hilal.ahmar@planet.tn', '71711335');

INSERT INTO dons (donateur, email, associationId, montant, date, type, message) VALUES
('Alice Martin', 'alice@email.com', 1, 1650, '2026-01-10', 'ponctuel', 'Courage'),
('Bob Dupont', 'bob@email.com', 1, 495, '2026-02-14', 'mensuel', ''),
('Claire Moreau', 'claire@email.com', 2, 6600, '2026-01-20', 'annuel', 'Pour la planete'),
('David Laurent', 'david@email.com', 3, 248, '2026-03-05', 'ponctuel', 'Pour les enfants'),
('Alice Martin', 'alice@email.com', 2, 990, '2026-03-12', 'ponctuel', ''),
('Emilie Roux', 'emilie@email.com', 4, 330, '2026-02-28', 'mensuel', 'Pour les animaux'),
('Francois Petit', 'francois@email.com', 1, 16500, '2026-04-01', 'annuel', 'Merci'),
('Bob Dupont', 'bob@email.com', 3, 825, '2026-04-10', 'ponctuel', '');
