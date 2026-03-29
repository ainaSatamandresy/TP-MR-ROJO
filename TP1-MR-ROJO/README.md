# 📌 Mini-Projet Web Design – SEO & URL Rewriting

## 🎯 Objectif
Créer un site d'informations sur la guerre en Iran avec :
- FrontOffice (affichage des articles)
- BackOffice (gestion des contenus)
- Optimisation SEO (structure + URL rewriting)
- **Docker + PostgreSQL**

---

## 📁 Structure du projet

```
/
├── docker-compose.yml           # Orchestration des services
├── Dockerfile                   # Image PHP 8.2 + Apache
├── apache.conf                  # Configuration Apache
├── .env                         # Variables d'environnement
├── database.sql                 # Script PostgreSQL
├── index.php                    # Point d'entrée
├── .htaccess                    # URL rewriting
├── /inc                         # Fichiers d'inclusion
│   ├── db.php                   # Connexion PostgreSQL
│   ├── header.php               # En-tête HTML
│   └── footer.php               # Pied de page
├── /functions                   # Fonctions métier
│   └── article.php              # Fonctions articles
├── /pages                       # Pages du site
│   ├── home.php                 # Accueil
│   ├── article.php              # Détail article
│   └── categorie.php            # Liste par catégorie
├── /backoffice                  # Gestion administrative
├── /assets                      # Ressources statiques
│   ├── /css
│   │   └── style.css
│   ├── /js
│   │   └── script.js
│   └── /images
└── README.md
```

---

## 🚀 Installation avec Docker

### Prérequis
- Docker
- Docker Compose

### 1. Démarrer les services
```bash
docker-compose up -d
```

Cela va :
- Créer et démarrer le container PostgreSQL
- Créer et démarrer le container PHP + Apache
- Initialiser la base de données avec `database.sql`

### 2. Vérifier que tout fonctionne
```bash
# Vérifier les services
docker-compose ps

# Voir les logs
docker-compose logs -f
```

### 3. Accéder au site
```
http://localhost
```

### 4. Modifier les variables d'environnement (optionnel)
Éditer `.env` et relancer :
```bash
docker-compose restart
```

---

## 🔗 URLs du projet

### FrontOffice
- **Accueil** : `http://localhost/`
- **Article** : `http://localhost/article/titre-slug-15.html`
- **Catégorie** : `http://localhost/categorie/politique`

### BackOffice (À développer)
- `/backoffice/login.php`
- `/backoffice/articles/`
- `/backoffice/categories/`

---

## 📊 Accès à la base de données

### Via CLI
```bash
docker-compose exec postgres psql -U iran_user -d iran_news
```

### Via un client graphique
- Host: `localhost`
- Port: `5432`
- User: `iran_user`
- Password: `iran_password`
- Database: `iran_news`

---

## 📝 Commandes utiles

### Démarrer les services
```bash
docker-compose up -d
```

### Arrêter les services
```bash
docker-compose down
```

### Reconstruire les images
```bash
docker-compose up -d --build
```

### Voir les logs en direct
```bash
docker-compose logs -f php
docker-compose logs -f postgres
```

### Exécuter une commande dans un container
```bash
docker-compose exec php php -v
docker-compose exec postgres psql -U iran_user -d iran_news
```

---

## 🔧 Configuration Apache & URL Rewriting

Le `.htaccess` active les URL réécrites pour :
- `/article/titre-slug-15.html` → `pages/article.php?id=15`
- `/categorie/politique` → `pages/categorie.php?slug=politique`

**Note** : `mod_rewrite` est activé dans le Dockerfile.

---

## 📝 Notes
- PostgreSQL est utilisé comme base de données
- PHP 8.2 avec Apache 2.4
- Les données de test sont importées automatiquement
- Le mot de passe admin par défaut est `admin123`

---

## 👥 Répartition
- **Personne 1** : FrontOffice + SEO
- **Personne 2** : BackOffice + Base de données

