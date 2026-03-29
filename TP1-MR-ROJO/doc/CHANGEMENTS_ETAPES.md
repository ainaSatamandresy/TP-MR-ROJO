# Journal des changements (etape par etape)

Date: 2026-03-29
Projet: rewriting

## Etape 1 - Documentation du workflow du site
Objectif:
- Documenter le fonctionnement FrontOffice/BackOffice + technique (rewriting, htaccess, infra).

Actions:
- Creation du fichier WORKFLOW.md.
- Description des flux utilisateur et admin.
- Description de la couche technique (.htaccess, index.php, Docker, PostgreSQL, headers, cache, gzip).

Fichiers:
- WORKFLOW.md

---

## Etape 2 - Correction d'un chemin include cassé (login admin)
Objectif:
- Corriger l'erreur de chargement de inc/db.php sur la page de connexion admin.

Actions:
- Correction des chemins relatifs dans back/login.php:
  - ../../inc/... -> ../inc/...

Fichiers:
- back/login.php

---

## Etape 3 - Passage au contenu riche TinyMCE (stockage + securisation)
Objectif:
- Stocker le HTML TinyMCE en base de donnees tout en gardant un rendu securise.

Actions:
- Ajout d'une sanitization HTML cote serveur.
- Ajout d'une verification de contenu riche non vide.
- Ajout d'un helper d'extrait texte depuis HTML.
- Integration de la sanitization sur create/update d'article.
- Activation/configuration TinyMCE dans le formulaire admin des articles.
- Adaptation des extraits en FrontOffice pour convertir HTML -> texte.
- Adaptation d'une page detail article legacy pour rendu HTML nettoye.

Fichiers:
- inc/helpers.php
- back/pages/articles.php
- front/pages/accueil.php
- pages/article.php

---

## Etape 4 - Correction erreur navigateur avec TinyMCE
Objectif:
- Corriger "An invalid form control with name='contenu' is not focusable".

Actions:
- Suppression de l'attribut required sur le textarea masque par TinyMCE.
- Ajout d'une validation JS sur submit du formulaire:
  - sync TinyMCE -> textarea,
  - blocage si contenu vide,
  - focus sur l'editeur en cas d'erreur.

Fichiers:
- back/pages/articles.php

---

## Etape 5 - Correction logs PostgreSQL (healthcheck)
Objectif:
- Corriger les logs repetes: "FATAL: database \"admin\" does not exist".

Cause:
- Le healthcheck utilisait pg_isready sans base cible.

Actions:
- Modification du healthcheck Docker Compose:
  - pg_isready -U admin
  - devient: pg_isready -U admin -d iran_news

Fichiers:
- docker-compose.yml

---

## Etape 6 - Ajout page article FrontOffice conforme rewriting
Objectif:
- Creer la vraie page front pour la route rewritee /article/{id}/{slug}.

Actions:
- Creation de front/pages/article.php.
- Chargement article par id.
- 404 si introuvable.
- Redirection 301 si slug URL != slug canonique.
- Rendu HTML securise avec sanitizeRichHtml.
- Affichage structure de contenu avec titres h1..h6.

Fichiers:
- front/pages/article.php

---

## Etape 7 - Alignement structure h1..h6 TinyMCE + sanitization
Objectif:
- Respecter la structure des donnees avec h1, h2, ..., h6.

Actions:
- Extension de la whitelist des balises dans sanitizeRichHtml pour h1..h6.
- Mise a jour config TinyMCE:
  - block_formats avec Titre 1 a Titre 6,
  - valid_elements incluant h1..h6.

Fichiers:
- inc/helpers.php
- back/pages/articles.php

---

## Resume des fichiers impactes
- WORKFLOW.md
- CHANGEMENTS_ETAPES.md
- back/login.php
- back/pages/articles.php
- docker-compose.yml
- front/pages/accueil.php
- front/pages/article.php
- inc/helpers.php
- pages/article.php

## Notes
- La base stocke bien le HTML TinyMCE (verifie via requete SQL).
- Les extraits d'accueil restent en texte (comportement voulu).
- Le rendu formate est visible sur la page detail article FrontOffice.
