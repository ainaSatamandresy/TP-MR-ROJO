# 📝 Plan implémentation - Tâches restantes

## ✅ Tâches complétées (BackOffice principal)

### Configuration & Infrastructure
- ✅ Docker Compose (version 3.9)
- ✅ Dockerfile (PHP 8.2-Apache avec modules)
- ✅ Apache configuration (mod_rewrite, expires, deflate, headers)
- ✅ .htaccess complet (URL rewriting + cache + sécurité)
- ✅ Configuration PostgreSQL en Docker

### Fichiers système
- ✅ config.php (constantes globales)
- ✅ db.php (connexion PDO)
- ✅ helpers.php (fonctions réutilisables)
- ✅ database.sql (schéma initial)

### BackOffice - Pages principales
- ✅ back/login.php (authentification sécurisée)
- ✅ back/logout.php (déconnexion)
- ✅ back/dashboard.php (tableau de bord)
- ✅ back/pages/categories.php (CRUD complet)
- ✅ back/pages/articles.php (CRUD complet)

### FrontOffice - Router
- ✅ index.php (routeur central)
- ✅ front/pages/accueil.php (page d'accueil)

---

## 📌 Tâches restantes par ordre de priorité

### 1️⃣ FrontOffice - Pages essentielles (PRIORITÉ HAUTE)

#### 1.1 front/pages/actualites.php
**Objectif:** Afficher tous les articles avec pagination

```php
// Récupérer les articles avec pagination
// Afficher 10 articles par page
// Ajouter navigation pagination
// Respecter structure SEO (h1, h2, meta, etc.)
```

**Fonctions à utiliser:**
- `getArticlesByCategory()` avec category_id = null (tous)
- `getPagination()` pour la pagination
- Afficher le slug dans les URL: `/article/{id}/{slug}`

**Points de contrôle:**
- [ ] Titre unique h1
- [ ] Meta description générique
- [ ] Pagination avec rel="next"/"prev"
- [ ] Alt sur toutes les images
- [ ] Responsive design

---

#### 1.2 front/pages/categorie.php
**Objectif:** Articles d'une catégorie spécifique avec pagination

```php
// Récupérer la catégorie via $_GET['slug']
// Récupérer ses articles avec pagination
// Afficher breadcrumb: Accueil > Catégorie > Titre
// Afficher liens vers autres catégories
```

**Points de contrôle:**
- [ ] Vérifier que le slug existe
- [ ] Afficher le nom de la catégorie en h1
- [ ] Pagination jusqu'à 10 articles/page
- [ ] Breadcrumb schema.org
- [ ] Canonical tag

**Détails URL:**
- URL: `/categorie/politique/?p=2`
- Redirige vers: `index.php?page=categorie&slug=politique&p=2`

---

#### 1.3 front/pages/article.php
**Objectif:** Afficher le détail complet d'un article

```php
// Récupérer article via $_GET['id'] et $_GET['slug']
// Vérifier cohérence (si slug différent → redirection 301)
// Afficher contenu complet formaté
// Afficher articles similaires (même catégorie, -3)
// Afficher breadcrumb
```

**Points de contrôle:**
- [ ] Article trouvé ou 404
- [ ] Verification slug valide
- [ ] Image principale avec alt
- [ ] Métadonnées (date, source, cat)
- [ ] JSON-LD NewsArticle
- [ ] Breadcrumb
- [ ] Articles similaires en bas (3 articles)
- [ ] Balise `<time datetime="...">` pour date

**Détail du contenu:**
- Titre en h1
- Image principale (optimisée)
- Date: `<time datetime="2026-03-27">27 mars 2026</time>`
- Source: lien externe avec `rel="noopener"`
- Contenu: peut avoir h2/h3 pour structure
- Articles similaires: même catégorie, sauf ID actuel

---

#### 1.4 front/pages/recherche.php
**Objectif:** Recherche d'articles par terme

```php
// Récupérer $_GET['q'] et nettoyer (trim, htmlspecialchars)
// Recherche: ILIKE '%{terme}%' sur titre + contenu
// Afficher résultats avec pagination
// Message "X articles trouvés" ou "Aucun résultat"
```

**Points de contrôle:**
- [ ] Chaîne de recherche nettoyée
- [ ] Requête préparée (pas de concaténation)
- [ ] Résultats avec pagination (10/page)
- [ ] Message quand aucun résultat
- [ ] Meta robots: noindex (optionnel)
- [ ] Titre dynamique: "Recherche: {terme}"

**Fonction à créer:**
```php
function searchArticles(PDO $pdo, string $query, int $page = 1): array
```

---

#### 1.5 front/pages/contact.php
**Objectif:** Formulaire de contact (optionnel mais recommandé)

```php
// Formulaire: nom, email, sujet, message
// Validation côté serveur
// Envoyer email ou stocker en BD
// Message de succès/erreur
```

**Points de contrôle:**
- [ ] Validation des champs
- [ ] Limite de spam (optionnel)
- [ ] Envoi email ou stockage BD
- [ ] Message de confirmation

---

### 2️⃣ FrontOffice - Améliorations & SEO (PRIORITÉ MOYENNE)

#### 2.1 Header & Footer personnalisés
**Fichiers:** `inc/header.php`, `inc/footer.php`

**header.php doit contenir:**
- Navigation commune
- Meta charset, viewport
- Meta description (dynamique)
- Open Graph (dynamique)
- Favicon
- CSS global

**footer.php doit contenir:**
- Copyright
- Liens utiles
- Mentions légales (lien mort ok)
- Contact

---

#### 2.2 Sitemap.xml
```php
// Créer admin/generate-sitemap.php
// Générer sitemap avec toutes les pages/articles
// Fichier: sitemap.xml à la racine
// Ajouter robots.txt
```

---

#### 2.3 Optimisations images
```
assets/img/
├── placeholder.jpg (couleur unie pour BD)
├── og-image.png (pour réseaux sociaux)
└── favicon.ico (favicon du site)
```

---

### 3️⃣ Tests & Qualité (PRIORITÉ MOYENNE)

#### 3.1 Lighthouse Audit
- Mobile (score >= 85)
- Desktop (score >= 90)
- Vérifier rapports détaillés

#### 3.2 Validation HTML
- Passer https://validator.w3.org/
- Corriger les erreurs HTML

#### 3.3 Validation CSS (optionnel)
- https://jigsaw.w3.org/css-validator/

---

### 4️⃣ Documentation (PRIORITÉ BASSE)

#### 4.1 Document technique
**À générer:**
- Captures d'écran FrontOffice (accueil, actualités, article)
- Captures d'écran BackOffice (login, dashboard, CRUD)
- Modélisation MCD/MLD (diagramme BD)
- Identifiants par défaut
- Numéros étudiants

#### 4.2 README.md et SETUP.md
- ✅ Déjà créés, à enrichir si nécessaire

---

## 🎯 Devoirs par développeur

### Développeur A (Tâches principales)
- ✅ Docker + .htaccess + Config
- ✅ Page Accueil
- ✅ Page Détail Article
- ✅ BackOffice Login
- ✅ CRUD Catégories
- 📌 **RESTE:** SEO + balises meta + tests Lighthouse

### Développeur B (Tâches complémentaires)
- 📌 Page Liste Actualités
- 📌 Page Recherche
- 📌 CRUD Articles (avancé)
- **RESTE:** Base de données (déjà ok), page Catégorie

---

## 📊 Format des pages FrontOffice

### Template structure standard

```php
<?php
// 1. Charger données
$articles = /* query */;
$category = /* query */;

// 2. Métadonnées SEO
$page_title = '...';
$page_description = '...';
$page_image = '...';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta property="og:title" content="...">
    <meta property="og:image" content="...">
    <link rel="canonical" href="...">
</head>
<body>
    <!-- Inclure header, nav, main, footer -->
</body>
</html>
```

---

## 🔗 Schéma de lien entre pages

```
accueil.php
├── → actualites.php (voir tous les articles)
├── → categorie.php/{slug} (par catégorie)
└── → article.php/{id}/{slug} (détail)

actualites.php
├── → article.php
├── → categorie.php
└── Pagination

categorie.php
├── → article.php
├── → actualites.php (tous les articles)
└── → autres catégories

article.php
├── → categorie.php (articles similaires)
├── → actualites.php (retour)
└── Breadcrumb → accueil
```

---

## ⚠️ Points critiques à respecter

1. **Sécurité:**
   - PDO requêtes préparées (pas de concaténation)
   - htmlspecialchars() à l'affichage
   - Vérifier les IDs et slugs

2. **SEO:**
   - 1 seul h1 par page
   - h1 > h2 > h3 hiérarchie
   - Meta description unique (150-160 car)
   - Alt sur TOUTES les images
   - Pas de contenu dupliqué

3. **Performance:**
   - Gzip activé (.htaccess)
   - Cache activé (mod_expires)
   - Images optimisées
   - Requêtes BD optimisées

4. **Responsive:**
   - Mobile first
   - Breakpoints: 480px, 768px, 1200px
   - Tester sur Chrome Mobile Emulation

---

## 🚀 Procédure de finalisation

1. **Avant push sur Git:**
   ```bash
   docker-compose down -v
   git add .
   git commit -m "Mini-projet Web Design complet"
   git push origin main
   ```

2. **Vérifications finales:**
   - [ ] `docker-compose up -d` fonctionne
   - [ ] Site répond sur http://localhost
   - [ ] Admin accessible sur http://localhost/admin
   - [ ] Tous les liens fonctionnent
   - [ ] Images chargent correctement
   - [ ] Pas d'erreur PHP/MySQL
   - [ ] Lighthouse >= 85 tous domaines
   - [ ] README.md complet

3. **Livrable final:**
   - [ ] ZIP du projet complet
   - [ ] Lien GitHub/**gitlab** public
   - [ ] Document technique (PDF)
   - [ ] Captures d'écran
   - [ ] Identifiants documentés

---

## 📅 Timeline recommandée

- **Jour 1-2:** Backend login + dashboard + CRUD ✅
- **Jour 3-4:** FrontOffice accueil + actualites + article
- **Jour 5:** Recherche + catégories + pages manquantes
- **Jour 6:** SEO + images + optimisations
- **Jour 7:** Tests Lighthouse + corrections
- **Jour 8-9:** Documentation + livrables
- **J-1:** Test final complet

---

**Dernière mise à jour:** 27 Mars 2026
**Livrables:** 31 Mars 2026 à 14h00
