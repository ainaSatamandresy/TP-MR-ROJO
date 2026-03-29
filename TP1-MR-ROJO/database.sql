-- ===================================
-- Base de données PostgreSQL : iran_news
-- ===================================

-- ===================================
-- Table : categorie
-- ===================================

DROP TABLE IF EXISTS article CASCADE;
DROP TABLE IF EXISTS utilisateur CASCADE;
DROP TABLE IF EXISTS categorie CASCADE;

CREATE TABLE categorie (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT
);

-- ===================================
-- Table : article
-- ===================================

CREATE TABLE article (
    id SERIAL PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    slug VARCHAR(255) NOT NULL,
    date_publication TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_categorie INTEGER NOT NULL REFERENCES categorie(id) ON DELETE CASCADE,
    vues INTEGER DEFAULT 0
);

-- Création des index
CREATE INDEX idx_article_slug ON article(slug);
CREATE INDEX idx_article_date ON article(date_publication);
CREATE INDEX idx_article_categorie ON article(id_categorie);

-- ===================================
-- Table : utilisateur (pour BackOffice)
-- ===================================

CREATE TABLE utilisateur (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(255),
    role VARCHAR(20) DEFAULT 'editor',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===================================
-- Insertion de données de test
-- ===================================

INSERT INTO categorie (nom, slug, description) VALUES
('Politique', 'politique', 'Actualités politiques'),
('Militaire', 'militaire', 'Affaires militaires'),
('Économie', 'economie', 'Informations économiques');

INSERT INTO article (titre, contenu, slug, id_categorie) VALUES
('Crise politique en Iran', 'Texte Article 1...', 'crise-politique-iran', 1),
('Tensions militaires escalade', 'Texte Article 2...', 'tensions-militaires-escalade', 2),
('Impact économique de la situation', 'Texte Article 3...', 'impact-economique', 3);

INSERT INTO utilisateur (email, password, nom, role) VALUES
('admin@example.com', '$2y$10$AJayeZNKNKYXDw3YyTq/zuKA3zJKqH9BZpFfQEXEVqjvzj7LAYbmm', 'Admin', 'admin');
-- Mot de passe par défaut : 'admin123'
