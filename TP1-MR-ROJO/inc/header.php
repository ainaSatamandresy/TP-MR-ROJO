<?php
/**
 * En-tête du site
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Informations sur la situation en Iran">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Iran News'; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <h1><a href="/">Iran News</a></h1>
            <ul>
                <li><a href="/">Accueil</a></li>
                <li><a href="/?page=categorie&id=1">Politique</a></li>
                <li><a href="/?page=categorie&id=2">Militaire</a></li>
                <li><a href="/?page=categorie&id=3">Économie</a></li>
            </ul>
        </nav>
    </header>
    <main>
