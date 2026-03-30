# Etapes BackOffice

Ce document decrit les etapes a suivre pour maintenir un BackOffice coherent avec le theme FrontOffice, accessible et performant.

## 1. Preparation
1. Verifier l'authentification admin (`login`, `dashboard`, `articles`, `categories`).
2. Verifier les includes communs et les styles back partages.
3. Identifier les elements de navigation du sidebar.

## 2. Alignement visuel avec le FrontOffice
1. Reprendre les couleurs du header accueil FO:
   - primaire: `#1a3a52`
   - accent: `#D4876A`
2. Appliquer cette palette au sidebar et aux elements d'action.
3. Garder une hierarchie visuelle lisible (cartes, tableaux, boutons).

## 3. Navigation BO: icones et ergonomie
1. Remplacer les emojis du sidebar par des icones SVG.
2. Garder des labels texte explicites a cote des icones.
3. Ajouter un style de focus visible pour la navigation clavier.
4. Conserver un etat actif clair (`.active`).

## 4. Accessibilite BO
1. Utiliser `main` comme landmark principal.
2. Verifier les modales:
   - `role="dialog"`
   - `aria-modal="true"`
   - `aria-labelledby`
   - `aria-describedby`
3. Verifier les contrastes des textes secondaires, alertes et boutons.
4. Verifier les champs de formulaires (`label for` + `id`).

## 5. SEO BO (selon politique projet)
1. Si le BO doit etre indexe: `meta robots: index, follow` + `meta description`.
2. Si le BO ne doit pas etre indexe: `noindex, nofollow` et exclusion via robots/policy serveur.
3. Conserver des titres de pages explicites.

## 6. Performance BO (Lighthouse)
1. Charger les scripts externes non critiques avec `defer`.
2. Ajouter `loading="lazy"` et `decoding="async"` sur les images de liste.
3. Eviter les rechargements inutiles et JS inline redondant.
4. Garder un CSS commun back pour limiter la duplication.

## 7. Validation finale
1. Tester les pages BO principales:
   - Login
   - Dashboard
   - Gestion articles
   - Gestion categories
2. Lancer Lighthouse sur au moins Dashboard et Articles.
3. Corriger les avertissements bloquants en priorite.

## 8. Checklist de livraison
- [ ] Sidebar BO sur palette FO
- [ ] Emojis supprimes et remplaces par icones
- [ ] Landmarks et ARIA conformes
- [ ] Contrastes valides
- [ ] Optimisations perf appliquees
