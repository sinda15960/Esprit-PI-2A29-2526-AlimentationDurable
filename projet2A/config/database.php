-- Création de la base de données
CREATE DATABASE IF NOT EXISTS nutriflow_ai;
USE nutriflow_ai;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    age INT,
    weight DECIMAL(5,2),
    height DECIMAL(5,2),
    dietary_preference VARCHAR(50),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertion d'un admin avec email _admin@gmail.com (mot de passe: admin123)
INSERT INTO users (username, email, password, full_name, role) 
VALUES ('admin', 'admin_admin@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Insertion d'un utilisateur normal (mot de passe: user123)
INSERT INTO users (username, email, password, full_name, role) 
VALUES ('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', 'user');

-- Insertion d'un autre utilisateur normal pour test
INSERT INTO users (username, email, password, full_name, role) 
VALUES ('jane_smith', 'jane@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', 'user');
