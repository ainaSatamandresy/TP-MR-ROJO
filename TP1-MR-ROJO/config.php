<?php
/**
 * Configuration générale du projet
 */

// Constantes du site
define('SITE_NAME', 'Actualités Guerre en Iran');
define('SITE_URL', getenv('SITE_URL') ?: 'http://localhost');
define('SITE_LANG', 'fr');

// Configuration base de données (remplacée par les variables d'environnement dans db.php)
define('DB_HOST', getenv('DB_HOST') ?: 'postgres');
define('DB_USER', getenv('DB_USER') ?: 'iran_user');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'iran_password');
define('DB_NAME', getenv('DB_NAME') ?: 'iran_news');
define('DB_PORT', getenv('DB_PORT') ?: 5432);

// Configuration de pagination
define('ITEMS_PER_PAGE', 10);
define('FEATURED_ARTICLES_LIMIT', 3);
define('LATEST_ARTICLES_LIMIT', 6);

// Configuration de session
define('SESSION_LIFETIME', 3600); // 1 heure
define('SESSION_NAME', 'iran_admin');

// Configuration des fichiers
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_MIME_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Chemins
defined('ROOT_PATH') || define('ROOT_PATH', dirname(__DIR__));
defined('INC_PATH') || define('INC_PATH', ROOT_PATH . '/inc');
defined('FRONT_PATH') || define('FRONT_PATH', ROOT_PATH . '/front');
defined('BACK_PATH') || define('BACK_PATH', ROOT_PATH . '/back');

// Mode debug
define('DEBUG_MODE', getenv('DEBUG') === 'true');

// Email (optionnel pour les futures fonctionnalités)
define('CONTACT_EMAIL', getenv('CONTACT_EMAIL') ?: 'contact@example.com');
define('ADMIN_EMAIL', getenv('ADMIN_EMAIL') ?: 'admin@example.com');

// Pagination par défaut
define('SEARCH_RESULTS_PER_PAGE', 10);
define('ADMIN_ITEMS_PER_PAGE', 20);

// SEO
define('SEO_TITLE_SUFFIX', ' | ' . SITE_NAME);
define('SEO_DEFAULT_DESCRIPTION', 'Actualités et informations sur la situation en Iran - Géopolitique, politique et relations internationales');
define('SEO_OG_IMAGE', SITE_URL . '/assets/img/og-image.png');

// Timezone
date_default_timezone_set('Europe/Paris');

// Affichage des erreurs en développement
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}
?>
