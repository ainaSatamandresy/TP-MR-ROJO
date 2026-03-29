# Restructurations du projet

Date: 2026-03-29
Projet: TP1-MR-ROJO

## Objectif
Ameliorer la structure du projet pour separer clairement:
- la presentation (pages),
- le style (CSS),
- la logique metier (fonctions),
- l'organisation front/back.

## 1) Separation CSS / PHP
Actions realisees:
- Suppression des styles inline dans les pages PHP principales.
- Creation de fichiers CSS dedies par zone (front et back).
- Mise en place d'un chargement des styles par page via le header global.

Fichiers CSS concernes:
- assets/css/front/accueil.css
- assets/css/front/article.css
- assets/css/back/common.css
- assets/css/back/login.css
- assets/css/back/dashboard.css
- assets/css/back/articles.css
- assets/css/back/categories.css

## 2) Reorganisation des pages front
Actions realisees:
- Harmonisation des pages front dans front/pages.
- Ajout/normalisation des pages:
  - accueil.php
  - article.php
  - actualites.php
  - categorie.php
  - recherche.php
  - contact.php
- Adaptation du routeur principal pour utiliser ces pages.

## 3) Reorganisation des pages back
Actions realisees:
- Nettoyage des pages d'administration pour limiter le code metier dans les vues.
- Harmonisation des pages:
  - back/login.php
  - back/dashboard.php
  - back/pages/articles.php
  - back/pages/categories.php

## 4) Extraction de la logique metier vers des fonctions
Regle appliquee:
- Les pages affichent et orchestrent.
- La logique metier est placee dans des fonctions dediees.

Emplacement final des fonctions:
- front/functions/
- back/functions/

Fonctions front ajoutees:
- getFrontAccueilData(...)
- resolveFrontArticleViewData(...)
- getFrontActualitesData(...)
- getFrontCategorieData(...)
- getFrontRechercheData(...)

Fonctions back ajoutees:
- processAdminLogin(...)
- processAdminArticlesPage(...)
- processAdminCategoriesPage(...)

## 5) Compatibilite des anciennes routes/pages
Actions realisees:
- Conversion des anciennes pages legacy de pages/ vers des redirections 301.
- Redirection vers les routes front actuelles pour conserver la compatibilite.

## 6) Resultat de la restructuration
Etat obtenu:
- Architecture plus claire entre front et back.
- Separation nette entre style, affichage et logique metier.
- Maintenance plus simple des pages et des traitements.
- Base preparee pour evolutions futures sans melanger les responsabilites.
