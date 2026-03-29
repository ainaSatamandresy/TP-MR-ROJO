<?php
/**
 * Page Accueil - FrontOffice
 */

// La base de données est déjà connectée dans le routeur principal

try {
    // Récupérer les catégories
    $categories = getAllCategories($pdo);
    
    // Récupérer les articles mis en avant
    $sql = "SELECT * FROM article ORDER BY date_publication DESC LIMIT 3";
    $stmt = $pdo->query($sql);
    $featured_articles = $stmt->fetchAll();
    
    // Récupérer les derniers articles
    $sql = "SELECT a.*, c.nom as categorie_nom FROM article a 
            LEFT JOIN categorie c ON a.id_categorie = c.id 
            ORDER BY a.date_publication DESC LIMIT 6";
    $stmt = $pdo->query($sql);
    $latest_articles = $stmt->fetchAll();
    
} catch (Exception $e) {
    $featured_articles = [];
    $latest_articles = [];
    $categories = [];
}

// Métadonnées SEO
$page_title = 'Accueil | ' . SITE_NAME;
$page_description = 'Actualités et informations sur la situation en Iran - Géopolitique, politique et relations internationales';
$page_lang = 'fr';

?>

<!DOCTYPE html>
<html lang="<?php echo $page_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo escapeHtml(substr($page_description, 0, 160)); ?>">
    <meta property="og:title" content="<?php echo escapeHtml($page_title); ?>">
    <meta property="og:description" content="<?php echo escapeHtml(substr($page_description, 0, 160)); ?>">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/assets/img/og-image.png">
    <meta property="og:type" content="website">
    <link rel="canonical" href="<?php echo SITE_URL; ?>/">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 40px;
        }
        
        header h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }
        
        header p {
            font-size: 18px;
            opacity: 0.9;
        }
        
        nav {
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        nav ul {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            padding: 0 20px;
        }
        
        nav li {
            margin-right: 30px;
        }
        
        nav a {
            display: block;
            padding: 15px 0;
            color: #333;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            transition: border-color 0.3s;
        }
        
        nav a:hover {
            border-bottom-color: #667eea;
        }
        
        section {
            margin-bottom: 40px;
        }
        
        section h2 {
            font-size: 32px;
            margin-bottom: 20px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        article {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        article:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }
        
        article img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background-color: #ddd;
        }
        
        article .content {
            padding: 20px;
        }
        
        article h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        article h3 a {
            color: #333;
            text-decoration: none;
        }
        
        article h3 a:hover {
            color: #667eea;
        }
        
        article .meta {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
        }
        
        article .excerpt {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .categories-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .category-btn {
            background: #667eea;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .category-btn:hover {
            background: #5568d3;
        }
        
        footer {
            background: #333;
            color: white;
            padding: 40px 0;
            margin-top: 60px;
            text-align: center;
        }
        
        footer p {
            margin: 10px 0;
            font-size: 14px;
        }
        
        footer a {
            color: #667eea;
            text-decoration: none;
        }
        
        footer a:hover {
            text-decoration: underline;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        @media (max-width: 768px) {
            header h1 {
                font-size: 32px;
            }
            
            header p {
                font-size: 16px;
            }
            
            nav ul {
                padding: 0 10px;
            }
            
            nav li {
                margin-right: 15px;
            }
            
            .articles-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1><?php echo SITE_NAME; ?></h1>
            <p><?php echo escapeHtml($page_description); ?></p>
        </div>
    </header>
    
    <nav>
        <div class="container">
            <ul>
                <li><a href="/">Accueil</a></li>
                <li><a href="/actualites/">Actualités</a></li>
                <li><a href="/recherche/">Recherche</a></li>
                <li><a href="/contact/">Contact</a></li>
            </ul>
        </div>
    </nav>
    
    <main class="container">
        <!-- SECTION À LA UNE -->
        <?php if (!empty($featured_articles)): ?>
            <section>
                <h2>À la une</h2>
                <div class="articles-grid">
                    <?php foreach ($featured_articles as $article): ?>
                        <article>
                            <img src="<?php echo SITE_URL; ?>/assets/img/placeholder.jpg" 
                                 alt="<?php echo escapeHtml($article['titre']); ?>"
                                 loading="lazy">
                            <div class="content">
                                <h3><a href="/article/<?php echo $article['id']; ?>/<?php echo escapeHtml($article['slug']); ?>">
                                    <?php echo escapeHtml($article['titre']); ?>
                                </a></h3>
                                <div class="meta">
                                    <time datetime="<?php echo $article['date_publication']; ?>">
                                        <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?>
                                    </time>
                                </div>
                                <p class="excerpt"><?php echo escapeHtml(excerptFromHtml($article['contenu'], 150)); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
        
        <!-- SECTION CATÉGORIES -->
        <section>
            <h2>Catégories</h2>
            <div class="categories-list">
                <a href="/actualites/" class="category-btn">Toutes les actualités</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="/categorie/<?php echo escapeHtml($cat['slug']); ?>/" class="category-btn">
                        <?php echo escapeHtml($cat['nom']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
        
        <!-- SECTION DERNIÈRES ACTUALITÉS -->
        <?php if (!empty($latest_articles)): ?>
            <section>
                <h2>Dernières actualités</h2>
                <div class="articles-grid">
                    <?php foreach ($latest_articles as $article): ?>
                        <article>
                            <img src="<?php echo SITE_URL; ?>/assets/img/placeholder.jpg" 
                                 alt="<?php echo escapeHtml($article['titre']); ?>"
                                 loading="lazy">
                            <div class="content">
                                <h3><a href="/article/<?php echo $article['id']; ?>/<?php echo escapeHtml($article['slug']); ?>">
                                    <?php echo escapeHtml($article['titre']); ?>
                                </a></h3>
                                <div class="meta">
                                    <time datetime="<?php echo $article['date_publication']; ?>">
                                        <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?>
                                    </time>
                                    <?php if ($article['categorie_nom']): ?>
                                        | <a href="/categorie/<?php echo escapeHtml($article['slug']); ?>/">
                                            <?php echo escapeHtml($article['categorie_nom']); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <p class="excerpt"><?php echo escapeHtml(excerptFromHtml($article['contenu'], 150)); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else: ?>
            <section>
                <div class="empty-state">
                    <p>Aucun article pour le moment. Revenez bientôt !</p>
                </div>
            </section>
        <?php endif; ?>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2026 <?php echo SITE_NAME; ?> - Tous droits réservés</p>
            <p><a href="/">Accueil</a> | <a href="/mentions-legales/">Mentions légales</a> | <a href="/contact/">Contact</a></p>
        </div>
    </footer>
</body>
</html>
