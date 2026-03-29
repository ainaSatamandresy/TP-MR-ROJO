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
    <meta name="robots" content="noindex, nofollow">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-logo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-nav {
            list-style: none;
        }
        
        .sidebar-nav li {
            margin-bottom: 10px;
        }
        
        .sidebar-nav a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 4px;
            transition: background-color 0.3s;
            font-size: 14px;
        }
        
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-logout {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .main-content {
            flex: 1;
            overflow-y: auto;
        }
        
        .header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 24px;
            color: #333;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-info span {
            font-size: 14px;
            color: #666;
        }
        
        .content {
            padding: 30px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #667eea;
        }
        
        .stat-card.articles {
            border-left-color: #667eea;
        }
        
        .stat-card.categories {
            border-left-color: #764ba2;
        }
        
        .stat-card.users {
            border-left-color: #f093fb;
        }
        
        .stat-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        
        .quick-links {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .quick-links h2 {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        
        .links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .link-btn {
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
            font-weight: 500;
            transition: transform 0.3s, box-shadow 0.3s;
            display: block;
        }
        
        .link-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .recent-articles {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .recent-articles h2 {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        
        .article-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .article-item:last-child {
            border-bottom: none;
        }
        
        .article-title {
            font-weight: 500;
            color: #333;
        }
        
        .article-date {
            font-size: 12px;
            color: #999;
        }
        
        .empty-message {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .header {
                flex-direction: column;
                gap: 10px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
                <a href="<?php echo htmlspecialchars('?action=logout'); ?>" style="color: white; text-decoration: none; padding: 10px 15px; display: block; border-radius: 4px; background: rgba(255, 255, 255, 0.1);">🚪 Déconnexion</a>
            </div>
        </div>
        
        <!-- MAIN CONTENT -->
        <div class="main-content">
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
        </div>
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
