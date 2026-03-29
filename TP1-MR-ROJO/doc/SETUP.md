# Actualités Guerre en Iran - Mini-Projet Web Design

Site d'informations responsif avec frontoffice public et backoffice d'administration.

## 📋 Caractéristiques

- **PHP 8.2** - Langage serveur
- **PostgreSQL 15** - Base de données
- **Apache** - Serveur web avec URL Rewriting
- **Docker & Docker Compose** - Conteneurisation
- **Responsive Design** - Mobile-first
- **SEO optimisé** - Balises meta, Open Graph, JSON-LD

## 🚀 Démarrage rapide

### Prérequis
- Docker et Docker Compose installés
- ≥ 2GB RAM disponible

### Installation

1. **Cloner le projet**
```bash
git clone [votre-repo]
cd rewriting
```

2. **Démarrer les conteneurs**
```bash
docker-compose up -d
```

3. **Attendre l'initialisation** (30-60 secondes)
```bash
docker-compose logs -f db
```

4. **Accéder au site**
- Frontend: http://localhost
- Backend: http://localhost/admin

### Identifiants par défaut

| Service | Email | Mot de passe |
|---------|-------|--------------|
| Admin   | admin@example.com | admin123 |

## 📁 Structure du projet

```
rewriting/
├── docker-compose.yml      # Orchestration des conteneurs
├── Dockerfile              # Configuration du conteneur web
├── apache.conf            # Configuration Apache
├── .htaccess              # URL Rewriting
├── config.php             # Configuration globale
├── index.php              # Routeur principal
├── database.sql           # Schéma et données initiales
├── inc/                   # Fichiers d'inclusion
│   ├── db.php            # Connexion BD
│   ├── header.php        # En-tête HTML
│   ├── footer.php        # Pied de page
│   └── helpers.php       # Fonctions utiles
├── front/                 # FrontOffice (public)
│   └── pages/
│       ├── accueil.php
│       ├── actualites.php
│       ├── article.php
│       ├── categorie.php
│       ├── recherche.php
│       └── contact.php
├── back/                  # BackOffice (admin)
│   ├── login.php
│   ├── logout.php
│   ├── dashboard.php
│   └── pages/
│       ├── articles.php
│       └── categories.php
└── assets/                # CSS, JS, images
    └── img/
```

## 🔐 Sécurité

### Mesures implémentées
- ✅ Hachage des mots de passe (bcrypt)
- ✅ Gestion de session sécurisée
- ✅ Échappement HTML (htmlspecialchars)
- ✅ Requêtes préparées PDO
- ✅ En-têtes de sécurité HTTP
- ✅ Protection CSRF (en développement)

### Headers de sécurité
```apache
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
```

## 📊 Base de données

### Tables

**categorie**
- `id` (SERIAL PRIMARY KEY)
- `nom` (VARCHAR 255, UNIQUE)
- `slug` (VARCHAR 255, UNIQUE)
- `description` (TEXT)

**article**
- `id` (SERIAL PRIMARY KEY)
- `titre` (VARCHAR 255)
- `slug` (VARCHAR 255, UNIQUE)
- `contenu` (TEXT)
- `date_publication` (TIMESTAMP)
- `id_categorie` (INT, FK → categorie)
- Index: slug, date, categorie

**utilisateur**
- `id` (SERIAL PRIMARY KEY)
- `email` (VARCHAR 255, UNIQUE)
- `password` (VARCHAR 255)
- `nom` (VARCHAR 255)
- `role` (VARCHAR 20)
- `date_creation` (TIMESTAMP)

## 📝 API URLs

### FrontOffice

| URL | Description |
|-----|-------------|
| `/` | Accueil |
| `/actualites` | Liste des articles |
| `/categorie/{slug}` | Articles par catégorie |
| `/article/{id}/{slug}` | Détail d'un article |
| `/recherche?q=terme` | Recherche |
| `/contact` | Formulaire de contact |

### BackOffice (protégé)

| URL | Description |
|-----|-------------|
| `/admin` | Page de connexion |
| `/admin/dashboard` | Tableau de bord |
| `/admin/articles` | Gestion des articles |
| `/admin/categories` | Gestion des catégories |

## 🛠️ Commandes Docker utiles

```bash
# Démarrer
docker-compose up -d

# Arrêter
docker-compose down

# Voir les logs
docker-compose logs -f web
docker-compose logs -f db

# Accéder au shell PHP
docker-compose exec web bash

# Accéder à PostgreSQL
docker-compose exec db psql -U admin -d iran_news
```

## 🔄 Requêtes SQL courantes

```sql
-- Voir tous les articles avec catégories
SELECT a.*, c.nom as categorie
FROM article a
LEFT JOIN categorie c ON a.id_categorie = c.id
ORDER BY a.date_publication DESC;

-- Compter les articles par catégorie
SELECT c.nom, COUNT(a.id) as total
FROM categorie c
LEFT JOIN article a ON c.id = a.id_categorie
GROUP BY c.id, c.nom;

-- Récupérer les 5 derniers articles
SELECT * FROM article
ORDER BY date_publication DESC
LIMIT 5;
```

## 📱 Responsivité

- Desktop: 1200px+
- Tablet: 768px - 1200px
- Mobile: < 768px

## 🎯 SEO

- ✅ Titres dynamiques
- ✅ Meta descriptions uniques
- ✅ Open Graph
- ✅ JSON-LD pour articles
- ✅ Breadcrumbs
- ✅ Canonical URLs
- ✅ Balises alt sur images
- ✅ Sitemap (à générer)

## 🧪 Testing

### Test de performance
```bash
# Lighthouse (via Chrome DevTools)
1. Ouvrir http://localhost en Chrome
2. Ouvrir DevTools (F12)
3. Lighthouse > Generate report
```

### Test base de données
```bash
docker-compose exec db psql -U admin -d iran_news -c "\dt"
```

## 📅 Historique des tests

| Date | Test | Résultat |
|------|------|----------|
| 2026-03-27 | Setup Docker | ✅ OK |
| 2026-03-27 | Connexion BD | ✅ OK |
| 2026-03-27 | Admin login | ✅ OK |
| 2026-03-27 | CRUD Catégories | ✅ OK |

## 👥 Auteurs

- Développeur A: (Tâches principales)
- Développeur B: (Tâches complémentaires)

## 📞 Support

Pour les erreurs de connexion :
1. Vérifier `docker-compose logs db`
2. Attendre l'initialisation complète
3. Vérifier les identifiants en base

## 📄 Licence

Projet scolaire - Mars 2026

---

**Dernière mise à jour:** 27 Mars 2026
