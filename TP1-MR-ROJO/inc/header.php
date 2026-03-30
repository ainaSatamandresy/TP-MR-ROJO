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
    <meta name="description" content="<?php echo escapeHtml($meta_description ?? SEO_DEFAULT_DESCRIPTION); ?>">
    <meta name="robots" content="index, follow">
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
        <header class="site-header">
            <div class="front-hero">
                <div class="container">
                    <h1><a href="/"><?php echo escapeHtml((string) SITE_NAME); ?></a></h1>
                    <p>Actualites, analyses et informations sur la situation en Iran.</p>
                </div>
            </div>
            <nav class="front-nav" aria-label="Navigation principale">
                <div class="container">
                    <ul>
                        <li><a href="/"<?php echo (($page ?? '') === 'accueil') ? ' aria-current="page"' : ''; ?>>Accueil</a></li>
                        <li><a href="/actualites/"<?php echo (($page ?? '') === 'actualites') ? ' aria-current="page"' : ''; ?>>Actualites</a></li>
                        <li><a href="/recherche/"<?php echo (($page ?? '') === 'recherche') ? ' aria-current="page"' : ''; ?>>Recherche</a></li>
                        <li><a href="/contact/"<?php echo (($page ?? '') === 'contact') ? ' aria-current="page"' : ''; ?>>Contact</a></li>
                    </ul>
                </div>
            </nav>
        </header>
    <?php endif; ?>
    <main>
