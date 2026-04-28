-- Ajouter la colonne image_url à la table allergies
ALTER TABLE allergies ADD COLUMN image_url VARCHAR(255) DEFAULT NULL AFTER gravite;

-- Ajouter la colonne vue_count pour les statistiques
ALTER TABLE allergies ADD COLUMN vue_count INT DEFAULT 0 AFTER image_url;

-- Créer la table des évaluations des traitements
CREATE TABLE IF NOT EXISTS evaluations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    traitement_id INT NOT NULL,
    note INT NOT NULL CHECK (note BETWEEN 1 AND 5),
    ip_address VARCHAR(45),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (traitement_id) REFERENCES traitements(id) ON DELETE CASCADE,
    UNIQUE KEY unique_ip_traitement (traitement_id, ip_address)
);

-- Ajouter la colonne moyenne des notes à la table traitements
ALTER TABLE traitements ADD COLUMN note_moyenne DECIMAL(2,1) DEFAULT 0 AFTER niveau_urgence;
ALTER TABLE traitements ADD COLUMN nb_notes INT DEFAULT 0 AFTER note_moyenne;

-- Mettre à jour les images des allergies existantes
UPDATE allergies SET image_url = 'https://cdn-icons-png.flaticon.com/512/1046/1046784.png' WHERE nom = 'Gluten';
UPDATE allergies SET image_url = 'https://cdn-icons-png.flaticon.com/512/3059/3059995.png' WHERE nom = 'Lactose';
UPDATE allergies SET image_url = 'https://cdn-icons-png.flaticon.com/512/4240/4240723.png' WHERE nom = 'Arachides';
UPDATE allergies SET image_url = 'https://cdn-icons-png.flaticon.com/512/3215/3215905.png' WHERE nom = 'Fruits de mer';
UPDATE allergies SET image_url = 'https://cdn-icons-png.flaticon.com/512/3031/3031041.png' WHERE nom = 'Œufs';
UPDATE allergies SET image_url = 'https://cdn-icons-png.flaticon.com/512/4194/4194830.png' WHERE nom = 'Soja';
UPDATE allergies SET image_url = 'https://cdn-icons-png.flaticon.com/512/4060/4060854.png' WHERE nom = 'Poisson';
UPDATE allergies SET image_url = 'https://cdn-icons-png.flaticon.com/512/3014/3014885.png' WHERE nom = 'Pollen';
UPDATE allergies SET image_url = 'https://cdn-icons-png.flaticon.com/512/3163/3163495.png' WHERE nom = 'Acariens';
UPDATE allergies SET image_url = 'https://cdn-icons-png.flaticon.com/512/2938/2938527.png' WHERE nom = 'Penicilline';