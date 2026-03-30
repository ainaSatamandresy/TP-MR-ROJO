<?php
/**
 * Tableau de bord administration
 */

session_start();
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';

requireAuth();

$stats = getDashboardStats($pdo);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord | Administration</title>
    <meta name="description" content="Tableau de bord d'administration pour gérer les contenus, catégories et statistiques du site.">
    <meta name="robots" content="index, follow">
    <link rel="stylesheet" href="/assets/css/back/common.css">
    <link rel="stylesheet" href="/assets/css/back/dashboard.css">
</head>
<body>
    <div class="admin-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-logo">
                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                <span>Administration</span>
            </div>
            
            <ul class="sidebar-nav">
                <li><a href="/admin/dashboard/" class="active"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z"/></svg><span class="sidebar-link-label">Tableau de bord</span></a></li>
                <li><a href="/admin/articles/"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M6 2h9l5 5v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm8 1.5V8h4.5L14 3.5zM8 11h8v2H8v-2zm0 4h8v2H8v-2z"/></svg><span class="sidebar-link-label">Gerer les articles</span></a></li>
                <li><a href="/admin/categories/"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M10 3H4a1 1 0 0 0-1 1v6l9 9a1 1 0 0 0 1.4 0l5.6-5.6a1 1 0 0 0 0-1.4L10 3zm-3 5a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg><span class="sidebar-link-label">Gerer les categories</span></a></li>
            </ul>
            
            <div class="sidebar-logout">
                <a href="<?php echo htmlspecialchars('?action=logout'); ?>" class="sidebar-logout-link"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M10 17l1.4-1.4L8.8 13H20v-2H8.8l2.6-2.6L10 7l-5 5 5 5zM4 4h8V2H4a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h8v-2H4V4z"/></svg><span class="sidebar-link-label">Deconnexion</span></a>
            </div>
        </div>
        
        <!-- MAIN CONTENT -->
        <main class="main-content" id="main-content">
            <div class="header">
                <h1>Tableau de bord</h1>
                <div class="user-info">
                    <span><?php echo escapeHtml($_SESSION['admin']['nom']); ?></span>
                </div>
            </div>
            
            <div class="content">
                <!-- STATISTIQUES -->
                <div class="stats-grid">
                    <div class="stat-card articles">
                        <div class="stat-label">Articles</div>
                        <div class="stat-number"><?php echo $stats['articles_count']; ?></div>
                    </div>
                    
                    <div class="stat-card categories">
                        <div class="stat-label">Catégories</div>
                        <div class="stat-number"><?php echo $stats['categories_count']; ?></div>
                    </div>
                    
                    <div class="stat-card users">
                        <div class="stat-label">Utilisateurs</div>
                        <div class="stat-number"><?php echo $stats['users_count']; ?></div>
                    </div>
                </div>
                
                <!-- LIENS RAPIDES -->
                <div class="quick-links">
                    <h2>Accès rapide</h2>
                    <div class="links-grid">
                        <a href="/admin/articles/" class="link-btn">Nouvel article</a>
                        <a href="/admin/categories/" class="link-btn">Nouvelle categorie</a>
                        <a href="/?page=accueil" class="link-btn">Voir le site</a>
                    </div>
                </div>
                
                <!-- DERNIERS ARTICLES -->
                <div class="recent-articles">
                    <h2>Derniers articles</h2>
                    <?php if (!empty($stats['latest_articles'])): ?>
                        <?php foreach ($stats['latest_articles'] as $article): ?>
                            <div class="article-item">
                                <div>
                                    <div class="article-title"><?php echo escapeHtml($article['titre']); ?></div>
                                    <div class="article-date"><?php echo date('d/m/Y à H:i', strtotime($article['date_publication'])); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-message">Aucun article pour le moment</div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Gérer la déconnexion
        if (window.location.search.includes('action=logout')) {
            // Effacer la session côté client
            fetch('<?php echo htmlspecialchars('/admin/logout.php'); ?>', { method: 'POST' })
                .then(() => {
                    window.location.href = '/admin/';
                });
        }
    </script>
</body>
</html>
