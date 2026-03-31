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
    image VARCHAR(255),
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


INSERT INTO utilisateur (email, password, nom, role) VALUES
('admin@example.com', 'admin123', 'Admin', 'admin');
-- Mot de passe par défaut : 'admin123'


INSERT INTO article (titre, contenu, slug, date_publication, id_categorie, vues, image)
VALUES (
'Tensions politiques croissantes en Iran',
'<h2>Une situation politique sous haute tension</h2>
<p>Depuis plusieurs semaines, <strong>l''Iran</strong> traverse une période de tensions politiques importantes. Les débats internes se multiplient entre les différentes factions du pouvoir.</p>

<h2>Des manifestations dans plusieurs villes</h2>
<p>Des manifestations ont été signalées dans plusieurs grandes villes. Les citoyens expriment leurs préoccupations face à la situation économique et aux décisions gouvernementales récentes.</p>

<h2>Réactions internationales</h2>
<p>La communauté internationale observe avec attention l''évolution de la situation, appelant au dialogue et à la stabilité dans la région.</p>',
'tensions-politiques-iran',
NOW(),
1,
0,
'article-politique-iran.jpg'
);

INSERT INTO article (titre, contenu, slug, date_publication, id_categorie, vues, image)
VALUES (
'Renforcement militaire stratégique en Iran',
'<h2>Une présence militaire accrue</h2>
<p>Les forces armées iraniennes ont récemment intensifié leur présence dans certaines zones stratégiques du pays.</p>

<h2>Des exercices militaires à grande échelle</h2>
<p>Plusieurs exercices militaires ont été organisés afin de tester la capacité de réaction des troupes. <strong>Ces démonstrations de force</strong> visent à dissuader toute menace extérieure.</p>

<h2>Un message envoyé à la région</h2>
<p>Selon les analystes, ces mouvements militaires sont également un signal adressé aux pays voisins et aux puissances internationales.</p>',
'renforcement-militaire-iran',
NOW(),
2,
0,
'article-militaire-iran.jpg'
);

INSERT INTO article (titre, contenu, slug, date_publication, id_categorie, vues, image)
VALUES (
'Impact économique des tensions en Iran',
'<h2>Une économie sous pression</h2>
<p>L''économie iranienne subit les conséquences directes des tensions politiques et militaires. L''inflation continue d''augmenter.</p>

<h2>Le marché du pétrole affecté</h2>
<p>Le secteur pétrolier, pilier de l''économie du pays, connaît des fluctuations importantes en raison des incertitudes géopolitiques.</p>

<h2>Perspectives pour les mois à venir</h2>
<p>Les experts restent prudents et anticipent une période d''instabilité économique prolongée si la situation ne s''améliore pas.</p>',
'impact-economique-iran',
NOW(),
3,
0,
'article-economie-iran.jpg'
);

INSERT INTO article (titre, contenu, slug, date_publication, id_categorie, vues, image)
VALUES (
'Escalade militaire dans le Golfe',
'<h2>Des tensions accrues dans le Golfe persique</h2>
<p>Les récentes opérations militaires ont intensifié les tensions dans le <strong>Golfe persique</strong>.</p>

<h2>Des incidents en mer</h2>
<p>Plusieurs incidents impliquant des navires ont été signalés, augmentant les inquiétudes concernant la sécurité maritime.</p>

<h2>Appels à la désescalade</h2>
<p>Plusieurs organisations internationales appellent à une désescalade rapide afin d''éviter un conflit ouvert.</p>',
'escalade-militaire-golfe',
NOW(),
2,
0,
'article-golfe.jpg'
);