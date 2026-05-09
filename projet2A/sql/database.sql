-- Création de la base de données
CREATE DATABASE IF NOT EXISTS nutriflow_ai;
USE nutriflow_ai;

-- Table des recettes
CREATE TABLE IF NOT EXISTS recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    ingredients TEXT NOT NULL,
    prep_time INT NOT NULL,
    cook_time INT NOT NULL,
    difficulty ENUM('facile', 'moyen', 'difficile') DEFAULT 'moyen',
    calories INT,
    protein DECIMAL(5,2),
    carbs DECIMAL(5,2),
    fats DECIMAL(5,2),
    image_url VARCHAR(500),
    is_vegan BOOLEAN DEFAULT FALSE,
    is_vegetarian BOOLEAN DEFAULT FALSE,
    is_gluten_free BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des instructions
CREATE TABLE IF NOT EXISTS instructions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT NOT NULL,
    step_number INT NOT NULL,
    description TEXT NOT NULL,
    tip TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);

-- Insertion de données exemple
INSERT INTO recipes (title, description, ingredients, prep_time, cook_time, difficulty, calories, protein, carbs, fats, is_vegan, is_vegetarian, is_gluten_free) VALUES
('Bowl Végétal aux Quinoa et Légumes Rôtis', 'Un bowl équilibré riche en protéines végétales et légumes de saison', 'Quinoa, Courgettes, Poivrons, Oignons rouges, Huile d''olive, Épices, Tofu fumé', 15, 25, 'moyen', 450, 15.5, 65.2, 12.3, 1, 1, 1),
('Curry de Lentilles Corail', 'Un curry crémeux et réconfortant, riche en fer et protéines', 'Lentilles corail, Lait de coco, Tomates, Oignons, Ail, Gingembre, Épices, Coriandre', 10, 30, 'facile', 380, 18.2, 52.4, 8.5, 1, 1, 1),
('Salade de Pâtes Complètes au Pesto', 'Une salade fraîche et nourrissante, parfaite pour l''été', 'Pâtes complètes, Pesto maison, Tomates cerises, Roquette, Pignons de pin, Parmesan végétal', 15, 10, 'facile', 520, 14.8, 68.5, 18.2, 0, 1, 0);


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

-- Insertion d'un autre utilisateur normal pour test
INSERT INTO users (username, email, password, full_name, role) 
VALUES ('jane_smith', 'jane@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', 'user');
