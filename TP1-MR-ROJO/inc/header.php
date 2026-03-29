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
    <?php if (!empty($page_styles) && is_array($page_styles)): ?>
        <?php foreach ($page_styles as $stylesheet): ?>
            <link rel="stylesheet" href="<?php echo escapeHtml((string) $stylesheet); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <?php if (empty($hide_global_header)): ?>
        <header>
            <nav>
                <h1><a href="/">Iran News</a></h1>
                <ul>
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/actualites/">Actualités</a></li>
                    <li><a href="/recherche/">Recherche</a></li>
                    <li><a href="/contact/">Contact</a></li>
                </ul>
            </nav>
        </header>
    <?php endif; ?>
    <main>
