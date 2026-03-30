# Derniers changements

Date: 2026-03-30
Projet: TP1-MR-ROJO
Perimetre: FrontOffice + BackOffice + Documentation

## 1. Accessibilite et SEO du BackOffice
Objectif:
- Corriger les alertes Lighthouse sur les pages admin.

Actions:
- Ajout de `meta description` sur les pages BO.
- Harmonisation `meta robots` selon le besoin SEO de la demande.
- Ajout de landmark principal `main` sur les pages admin.
- Modales de suppression rendues accessibles (`role="dialog"`, `aria-modal`, `aria-labelledby`, `aria-describedby`, `aria-hidden`).
- Accessibilite TinyMCE amelioree avec titre/label de l'iframe.

Fichiers impactes:
- `back/dashboard.php`
- `back/pages/articles.php`
- `back/pages/categories.php`
- `back/login.php`

## 2. Contraste et lisibilite (BO)
Objectif:
- Ameliorer la lisibilite et reduire les warnings de contraste.

Actions:
- Ajustement des couleurs de textes secondaires.
- Ajustement des couleurs des alertes de succes.
- Ajustement des couleurs de boutons d'action.

Fichiers impactes:
- `assets/css/back/common.css`
- `assets/css/back/dashboard.css`
- `assets/css/back/login.css`

## 3. Performance BackOffice
Objectif:
- Limiter le blocage du rendu et optimiser les medias.

Actions:
- Chargement de TinyMCE en `defer`.
- Initialisation TinyMCE apres disponibilite du DOM.
- Ajout de `loading="lazy"` et `decoding="async"` sur les images non critiques.

Fichiers impactes:
- `back/pages/articles.php`

## 4. Theme unifie FO/BO
Objectif:
- Aligner toutes les pages sur la palette du header d'accueil FO.

Actions:
- Variables CSS de theme centralisees (`:root`).
- Header/Footer FO globaux unifies sur toutes les routes FO.
- Sidebar BO passe au meme theme de couleur.

Fichiers impactes:
- `assets/css/style.css`
- `assets/css/front/accueil.css`
- `assets/css/back/common.css`
- `assets/css/back/login.css`
- `index.php`
- `inc/header.php`
- `inc/footer.php`
- `front/pages/accueil.php`

## 5. Remplacement des emojis par des icones (BO)
Objectif:
- Rendre la navigation admin plus professionnelle et cohérente.

Actions:
- Emojis remplaces par icones SVG inline dans les sidebars.
- Etats focus clavier visibles sur les liens de navigation.

Fichiers impactes:
- `back/dashboard.php`
- `back/pages/articles.php`
- `back/pages/categories.php`
- `assets/css/back/common.css`

## 6. Images FO + ALT + style editorial
Objectif:
- Afficher les images d'articles sur l'accueil et en detail article, avec bonnes pratiques d'optimisation.

Actions:
- Ajout d'images de couverture sur les cartes d'accueil (`A la une` + `Dernieres actualites`).
- Ajout d'image principale grand format en haut de la page detail article.
- Ajout d'attributs `alt` explicites sur les images affichees.
- Optimisation des images:
  - `loading` (lazy/eager selon priorite),
  - `decoding="async"`,
  - `fetchpriority` sur image principale,
  - dimensions `width`/`height` pour limiter le CLS.
- Optimisation du contenu riche:
  - ajout automatique d'un alt fallback,
  - ajout auto de `loading="lazy"` et `decoding="async"` sur les images du contenu.

Fichiers impactes:
- `front/pages/accueil.php`
- `front/pages/article.php`
- `front/functions/article.php`

## 7. Amelioration visuelle du FrontOffice
Objectif:
- Se rapprocher d'un rendu editorial type presse.

Actions:
- Refonte de la grille accueil avec carte principale dominante.
- Mise en avant de la hierarchie typographique.
- Traitement des `h2` en style sous-titre de section (rubrique).
- Ajout de bordures sur les cartes articles et sur la page detail article.

Fichiers impactes:
- `assets/css/front/accueil.css`
- `assets/css/front/article.css`

## 8. Documentation ajoutee
Objectif:
- Clarifier les etapes de mise en oeuvre FO et BO.

Actions:
- Creation des guides d'etapes dedies.

Fichiers crees:
- `doc/ETAPES_FRONT_OFFICE.md`
- `doc/ETAPES_BACK_OFFICE.md`

## 9. Etat de validation
- Verification d'erreurs editeur sur les fichiers modifies: OK (pas d'erreurs detectees).
- Validation CLI PHP non executee localement si binaire `php` indisponible dans le terminal hote.

## Resume rapide
- Accessibilite et SEO renforces.
- Theme visuel coherent FO/BO.
- Navigation BO modernisee (icones SVG).
- Rendu FO plus editorial et plus riche visuellement.
- Images FO affichees avec alt + optimisation Lighthouse.
