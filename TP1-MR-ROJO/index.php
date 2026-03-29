<?php
/**
 * Point d'entrée principal du site
 * Routeur central pour les pages fronoffice
 */

session_start();

// Chargement de la configuration
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/front/functions/accueil.php';
require_once __DIR__ . '/front/functions/article.php';
require_once __DIR__ . '/front/functions/listing.php';

// Déterminer la page à afficher
$page = $_GET['page'] ?? 'accueil';
$page = preg_replace('/[^a-z0-9_-]/', '', $page); // Sécurité : nettoyer l'input

// Routes disponibles
$routes = [
    'accueil',
    'actualites',
    'categorie',
    'article',
    'recherche',
    'contact'
];

// Vérifier la route
if (!in_array($page, $routes)) {
    $page = 'accueil';
}

$pageTitles = [
    'accueil' => 'Accueil - Iran News',
    'actualites' => 'Actualites - Iran News',
    'categorie' => 'Categorie - Iran News',
    'article' => 'Article - Iran News',
    'recherche' => 'Recherche - Iran News',
    'contact' => 'Contact - Iran News'
];

$pageStylesMap = [
    'accueil' => ['/assets/css/front/accueil.css'],
    'actualites' => ['/assets/css/front/accueil.css'],
    'categorie' => ['/assets/css/front/accueil.css'],
    'recherche' => ['/assets/css/front/accueil.css'],
    'contact' => ['/assets/css/front/accueil.css'],
    'article' => ['/assets/css/front/article.css']
];

$title = $pageTitles[$page] ?? 'Iran News';
$page_styles = $pageStylesMap[$page] ?? [];
$hide_global_header = ($page === 'accueil');
$hide_global_footer = ($page === 'accueil');

// Inclure l'en-tête
require_once 'inc/header.php';

// Charger la page appropriée
$pageFile = 'front/pages/' . $page . '.php';
if (file_exists($pageFile)) {
    require_once $pageFile;
} else {
    // Page non trouvée - afficher la page d'accueil
    require_once 'front/pages/accueil.php';
}

// Inclure le pied de page
require_once 'inc/footer.php';
?>
