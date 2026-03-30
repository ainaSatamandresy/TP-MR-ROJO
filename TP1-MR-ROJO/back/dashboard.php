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
            <div class="sidebar-logo">🎯 Administration</div>
            
            <ul class="sidebar-nav">
                <li><a href="/admin/dashboard/" class="active">📊 Tableau de bord</a></li>
                <li><a href="/admin/articles/">📄 Gérer les articles</a></li>
                <li><a href="/admin/categories/">🏷️ Gérer les catégories</a></li>
            </ul>
            
            <div class="sidebar-logout">
                <a href="<?php echo htmlspecialchars('?action=logout'); ?>" class="sidebar-logout-link">🚪 Déconnexion</a>
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
                        <a href="/admin/articles/" class="link-btn">➕ Nouvel article</a>
                        <a href="/admin/categories/" class="link-btn">➕ Nouvelle catégorie</a>
                        <a href="/?page=accueil" class="link-btn">👁️ Voir le site</a>
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
