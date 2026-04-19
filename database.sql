DROP DATABASE IF EXISTS nutriflow_ai;
CREATE DATABASE nutriflow_ai;
USE nutriflow_ai;

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

INSERT INTO associations (name, email, phone, address, city, postal_code, siret, mission, status) VALUES
('Les Restos du Cœur', 'contact@restosducoeur.fr', '0140055555', '12 Rue Palestine', 'Tunis', '75010', '14211564', 'Lutter contre la précarité alimentaire en distribuant des repas gratuits.', 'active'),
('Secours Populaire', 'contact@secourspopulaire.fr', '0144782120', '8 Rue de la libetre', 'Tunis', '75011', '23456789', 'Agir contre la pauvreté et l\'exclusion sous toutes ses formes.', 'active'),
('Banque Alimentaire', 'contact@banquealimentaire.org', '0144756200', '21 Rue de paris', 'Tunis', '75018', '34567890', 'Collecter et redistribuer les invendus alimentaires.', 'active');

INSERT INTO dons (association_id, donor_name, donor_email, donor_phone, amount, donation_type, food_type, quantity, message, status, payment_method) VALUES
(1, 'maissa jouini', 'maissa@email.com', '53211456', 150.00, 'monetary', NULL, 0, 'Pour aider', 'confirmed', 'card'),
(2, 'Eya jouini', 'eya@email.com', '06234567', 75.50, 'monetary', NULL, 0, 'Bon courage', 'confirmed', 'paypal'),
(1, 'senda lazaar', 'senda@email.com', '06345678', 0, 'food', 'Riz et pâtes', 50, 'Aliments non périssables', 'delivered', 'card');