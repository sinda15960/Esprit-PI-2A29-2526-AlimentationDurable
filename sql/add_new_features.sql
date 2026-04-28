-- =====================================================
-- Nouvelles tables pour NutriFlow AI
-- Fonctionnalités : Audit Log, Profil Allergique, Chatbot
-- =====================================================

-- 1. TABLE DES LOGS (Audit trail)
CREATE TABLE IF NOT EXISTS logs (
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
    INDEX idx_created (created_at DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. TABLE DES PROFILS ALLERGIQUES
CREATE TABLE IF NOT EXISTS user_profiles (
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

-- 3. TABLE DES CONVERSATIONS CHATBOT
CREATE TABLE IF NOT EXISTS chatbot_conversations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id VARCHAR(255) NOT NULL,
    user_message TEXT NOT NULL,
    bot_response TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session (session_id),
    INDEX idx_created (created_at DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. TABLE POUR STOCKER LES COMPARAISONS (optionnel)
CREATE TABLE IF NOT EXISTS allergy_comparisons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id VARCHAR(255) NOT NULL,
    allergy_id_1 INT NOT NULL,
    allergy_id_2 INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (allergy_id_1) REFERENCES allergies(id) ON DELETE CASCADE,
    FOREIGN KEY (allergy_id_2) REFERENCES allergies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. AJOUTER UNE COLONNE POUR LES MÉDICAMENTS D'URGENCE DANS TRAITEMENTS
ALTER TABLE traitements ADD COLUMN medicaments_urgence VARCHAR(255) DEFAULT NULL AFTER medicaments;

-- Mettre à jour les traitements existants avec médicaments d'urgence
UPDATE traitements SET medicaments_urgence = 'EpiPen, Antihistaminiques' WHERE niveau_urgence = 'eleve';
UPDATE traitements SET medicaments_urgence = 'Antihistaminiques' WHERE niveau_urgence = 'moyen';
UPDATE traitements SET medicaments_urgence = 'Aucun spécifique' WHERE niveau_urgence = 'faible';