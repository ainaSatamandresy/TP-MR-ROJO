# ✅ Checklist de Vérification - Mini-Projet Web Design

## 🐳 Phase 1: Docker & Conteneurs

- [ ] `docker-compose up -d` s'exécute sans erreurs
- [ ] Les deux services (`web` et `db`) sont en état "running"
- [ ] La base de données s'initialise correctement
- [ ] Les logs ne montrent pas d'erreurs PHP

```bash
# Vérifier
docker-compose ps
docker-compose logs db
```

## 🔌 Phase 2: Connectivité

- [ ] Le site web répond sur http://localhost
- [ ] La base de données est accessible (port 5432)
- [ ] Pas d'erreurs de connexion PDO

```bash
# Test
curl http://localhost
docker-compose exec db psql -U admin -d iran_news -c "\dt"
```

## 🔐 Phase 3: Authentification BackOffice

- [ ] Page login accessible: http://localhost/admin
- [ ] Moteur de sécurité password_verify() fonctionne
- [ ] Connexion avec admin@example.com / admin123
- [ ] Session $_SESSION['admin'] créée
- [ ] Redirection vers dashboard après connexion
- [ ] Déconnexion fonctionne correctement

**Tests:**
1. Essayer de mauvais identifiants → message d'erreur
2. Essayer les bons identifiants → redirection dashboard
3. Visiter /admin/dashboard sans authentification → redirection login
4. Cliquer déconnexion → redirection login

## 📊 Phase 4: Dashboard (back/dashboard.php)

- [ ] Affiche les statistiques (articles, catégories, utilisateurs)
- [ ] Liste les derniers articles
- [ ] Les chiffres sont corrects
- [ ] Navigation latérale fonctionne
- [ ] Responsive sur mobile

## 🏷️ Phase 5: Gestion des Catégories (back/pages/categories.php)

### Création
- [ ] Formulaire visible et accessible
- [ ] Champ "nom" requis
- [ ] Auto-génération du slug (convertir titre en slug)
- [ ] Validation du slug unique
- [ ] Création réussie → message succès
- [ ] Catégorie apparaît dans la liste

### Edition
- [ ] Lien "Éditer" affiche le formulaire pré-rempli
- [ ] Modification du nom et slug
- [ ] Validation slug unique (sauf l'ID actuel)
- [ ] Mise à jour confirmée

### Suppression
- [ ] Bouton "Supprimer" affiche une confirmation modale
- [ ] Impossible de supprimer si articles liés
- [ ] Suppression réussie → message succès
- [ ] Catégorie disparaît de la liste

### Validations
- [ ] Slug vide → erreur
- [ ] Slug avec caractères spéciaux → conversion correcte
- [ ] Slug déjà existant → erreur
- [ ] Formulaire avec données malveillantes → échappement HTML

**Données à tester:**
- Catégorie: "Politique" → slug: "politique"
- Catégorie: "Affaires Militaires" → slug: "affaires-militaires"
- Catégorie: "Éco@nomie" → slug: "economie"

## 📄 Phase 6: Gestion des Articles (back/pages/articles.php)

### Création
- [ ] Formulaire visible
- [ ] Champs requis: titre, contenu, catégorie, slug
- [ ] Sélecteur de catégorie avec options disponibles
- [ ] Auto-génération du slug
- [ ] Création réussie → message succès

### Edition
- [ ] Affiche formulaire pré-rempli
- [ ] Modifie article sans créer de doublon
- [ ] Slug validé (unique sauf l'ID actuel)

### Suppression
- [ ] Confirmation avant suppression
- [ ] Suppression réussie
- [ ] Article disparaît de la liste

### Validations
- [ ] Titre vide → erreur
- [ ] Contenu vide → erreur
- [ ] Slug non unique → erreur
- [ ] Pas de catégorie → erreur

## 🌐 Phase 7: FrontOffice - URL Rewriting (.htaccess)

- [ ] `/` → redirige vers accueil
- [ ] `/actualites` → page actualités
- [ ] `/categorie/politique` → articles de la catégorie
- [ ] `/article/1/titre-slug` → détail de l'article
- [ ] `/recherche?q=terme` → résultats recherche
- [ ] `/admin` → page login admin
- [ ] `/admin/dashboard` → dashboard
- [ ] `/admin/articles` → gestion articles
- [ ] `/admin/categories` → gestion catégories

**Test:**
```bash
curl -I http://localhost/actualites
curl http://localhost/categorie/politique | grep -i "categorie"
```

## 📱 Phase 8: FrontOffice - Accueil (front/pages/accueil.php)

- [ ] Page charge sans erreurs
- [ ] Titre h1 unique présent
- [ ] Meta description correcte (150-160 caractères)
- [ ] Section "À la une" avec 3 articles
- [ ] Section "Catégories" avec tous les liens
- [ ] Section "Dernières actualités" avec 6 articles
- [ ] Images avec attributs alt corrects
- [ ] Navigation responsive
- [ ] Footer avec copyright

### SEO
- [ ] `<title>` unique et descriptif
- [ ] `<meta description>` présente
- [ ] Open Graph tags (og:title, og:description, og:image)
- [ ] `<html lang="fr">` présent
- [ ] Aucune image sans alt

## 🔍 Phase 9: Performance & SEO

### Lighthouse (Chrome DevTools)
- [ ] ⚡ Performance: ≥ 85
- [ ] 📱 Mobile: ≥ 90
- [ ] ♿ Accessibility: ≥ 90
- [ ] 🔍 SEO: ≥ 90
- [ ] ⚙️ Best Practices: ≥ 90

### Points à vérifier
- [ ] Compression Gzip activée (.htaccess)
- [ ] Cache navigateur configuré (mod_expires)
- [ ] Headers de sécurité présents
- [ ] Pas d'erreurs console (F12)
- [ ] Images optimisées
- [ ] CSS/JS minifiés (optionnel)

## 🛡️ Phase 10: Sécurité

- [ ] PDO: requêtes préparées utilisées (pas de concaténation)
- [ ] HTML: escapeHtml() utilisé à l'affichage
- [ ] Sessions: session_regenerate_id() après login
- [ ] Mots de passe: password_hash(PASSWORD_BCRYPT)
- [ ] Headers: X-Content-Type-Options, X-Frame-Options
- [ ] CSRF: tokens (à implémenter si nécessaire)
- [ ] URLs: filtrage des caractères dangereux

**Tests:**
```php
// Injection SQL test (ne doit PAS fonctionner)
http://localhost/article/1' OR '1'='1/test

// Injection XSS (doit être échappée)
http://localhost/?q=<script>alert('xss')</script>
```

## 💾 Phase 11: Base de données

```bash
# Vérifier les tables
docker-compose exec db psql -U admin -d iran_news -c "\dt"

# Vérifier les données
docker-compose exec db psql -U admin -d iran_news -c "SELECT * FROM categorie;"
docker-compose exec db psql -U admin -d iran_news -c "SELECT * FROM article;"
docker-compose exec db psql -U admin -d iran_news -c "SELECT * FROM utilisateur;"

# Vérifier les contraintes
docker-compose exec db psql -U admin -d iran_news -c "\d article"
```

**Résultats attendus:**
- Catégories: Politique, Militaire, Économie
- Articles: 3 articles de test
- Utilisateurs: admin@example.com avec hash bcrypt

## 📋 Phase 12: Fichiers & Structure

```bash
# Vérifier que tous les fichiers sont présents
ls -la rewriting/
ls -la rewriting/back/pages/
ls -la rewriting/front/pages/
ls -la rewriting/inc/

# Fichiers critiques
cat rewriting/.htaccess      # Rewriting règles
cat rewriting/docker-compose.yml
cat rewriting/Dockerfile
cat rewriting/apache.conf
```

## 🚨 Dépannage

### Les conteneurs ne démarrent pas
```bash
docker-compose logs -f
docker-compose down -v
docker-compose up -d
```

### Erreur "connexion refusée"
```bash
# Attendre l'initialisation BD (30-60 sec)
docker-compose logs db | grep "ready to accept"
```

### Page blanche
```bash
# Vérifier les erreurs PHP
docker-compose exec web tail -f /var/log/apache2/error.log
```

### 404 sur URL rewriting
```bash
# Vérifier .htaccess et mod_rewrite
docker-compose exec web a2enmod rewrite
docker-compose exec web /etc/init.d/apache2 reload
```

### Erreur "BadFunctionCallException" avec PDO
```bash
# Vérifier que la BD est prête
docker-compose exec db pg_isready
```

## 📦 Livrables finaux

- [ ] Code source sur GitHub/GitLab (dépôt public)
- [ ] Fichiers README.md et SETUP.md
- [ ] docker-compose.yml et Dockerfile fonctionnels
- [ ] Base de données initialisée
- [ ] FrontOffice complet (min. page accueil)
- [ ] BackOffice fonctionnel (login + CRUD catégories)
- [ ] URL Rewriting actif
- [ ] Scores Lighthouse ≥ 85 (tous les tests)
- [ ] Document technique (captures d'écran, MCD/MLD)
- [ ] Identifiants par défaut documentés

## ✨ Points bonus

- [ ] Page 404 personnalisée
- [ ] Recherche d'articles fonctionnelle
- [ ] Pagination sur les listes
- [ ] Système de commentaires
- [ ] Envoi d'emails
- [ ] Export PDF articles
- [ ] Version anglaise (i18n)
- [ ] Dark mode
- [ ] Analytics (GA)
- [ ] Sitemap.xml généré

---

**À signer une fois tous les tests validés:** 

Testeur A: _____________ Date: _______
Testeur B: _____________ Date: _______

**Document mis à jour:** 27 Mars 2026
