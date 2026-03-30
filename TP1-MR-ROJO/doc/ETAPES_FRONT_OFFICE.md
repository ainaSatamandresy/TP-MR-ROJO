# Etapes FrontOffice

Ce document decrit les etapes a suivre pour construire, harmoniser et valider le FrontOffice.

## 1. Preparation
1. Verifier que Docker est lance et que le projet repond sur `/`.
2. Verifier les routes disponibles: `accueil`, `actualites`, `categorie`, `article`, `recherche`, `contact`.
3. Verifier que le header et le footer globaux sont inclus via les templates communs.

## 2. Theme visuel unifie
1. Utiliser la palette du header accueil comme source de verite:
   - primaire: `#1a3a52`
   - accent: `#D4876A`
2. Definir ces couleurs dans les variables CSS globales (`:root`).
3. Appliquer le meme style de navigation et de footer sur toutes les pages FO.
4. Eviter les styles dupliques dans chaque page; centraliser dans les CSS partages.

## 3. Structure HTML et accessibilite
1. Garder une structure semantique: `header`, `nav`, `main`, `footer`.
2. Utiliser un seul `h1` par page.
3. Ajouter `aria-current="page"` sur le lien actif dans la navigation.
4. Verifier les contrastes texte/fond (WCAG AA).
5. Ajouter `alt` explicite sur les images.

## 4. SEO minimal par page
1. Definir un `title` unique pour chaque page.
2. Definir une `meta description` specifique par page.
3. Utiliser `meta robots` adapte (`index, follow` pour FO public).
4. Verifier la coherence des URLs canoniques selon le routeur.

## 5. Optimisation performance (Lighthouse)
1. Charger les scripts non critiques avec `defer`.
2. Utiliser `loading="lazy"` et `decoding="async"` pour les images non critiques.
3. Limiter les CSS redondants et preferer des fichiers communs.
4. Eviter les blocs JS inline lourds et privilegier un script central.

## 6. Validation finale
1. Tester les pages FO en desktop et mobile.
2. Lancer Lighthouse sur:
   - Accueil
   - Liste actualites
   - Detail article
   - Recherche
3. Corriger les points critiques:
   - Accessibilite
   - SEO
   - Performance
   - Best Practices

## 7. Checklist de livraison
- [ ] Header/footer communs sur toutes les pages FO
- [ ] Theme visuel coherent avec la page accueil
- [ ] Aucun contraste critique Lighthouse
- [ ] Meta description presente sur chaque page
- [ ] Score Lighthouse stable et documente
