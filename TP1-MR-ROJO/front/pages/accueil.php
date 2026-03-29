<?php
/**
 * Page Accueil - FrontOffice
 */

try {
    // Récupérer les catégories
    $categories = getAllCategories($pdo);

    // Récupérer les articles mis en avant
    $sql = "SELECT * FROM article ORDER BY date_publication DESC LIMIT 3";
    $stmt = $pdo->query($sql);
    $featured_articles = $stmt->fetchAll();

    // Récupérer les derniers articles
    $sql = "SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
            FROM article a
            LEFT JOIN categorie c ON a.id_categorie = c.id
            ORDER BY a.date_publication DESC
            LIMIT 6";
    $stmt = $pdo->query($sql);
    $latest_articles = $stmt->fetchAll();
} catch (Exception $e) {
    $featured_articles = [];
    $latest_articles = [];
    $categories = [];
}

$page_description = 'Actualités et informations sur la situation en Iran - Géopolitique, politique et relations internationales';
?>

<header class="front-hero">
    <div class="container">
        <h1><?php echo SITE_NAME; ?></h1>
        <p><?php echo escapeHtml($page_description); ?></p>
    </div>
</header>

<nav class="front-nav">
    <div class="container">
        <ul>
            <li><a href="/">Accueil</a></li>
            <li><a href="/actualites/">Actualités</a></li>
            <li><a href="/recherche/">Recherche</a></li>
            <li><a href="/contact/">Contact</a></li>
        </ul>
    </div>
</nav>

<section class="container">
    <?php if (!empty($featured_articles)): ?>
        <section>
            <h2>À la une</h2>
            <div class="articles-grid">
                <?php foreach ($featured_articles as $article): ?>
                    <article>
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

    <?php if (!empty($latest_articles)): ?>
        <section>
            <h2>Dernières actualités</h2>
            <div class="articles-grid">
                <?php foreach ($latest_articles as $article): ?>
                    <article>
                        <div class="content">
                            <h3><a href="/article/<?php echo $article['id']; ?>/<?php echo escapeHtml($article['slug']); ?>">
                                <?php echo escapeHtml($article['titre']); ?>
                            </a></h3>
                            <div class="meta">
                                <time datetime="<?php echo $article['date_publication']; ?>">
                                    <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?>
                                </time>
                                <?php if (!empty($article['categorie_nom']) && !empty($article['categorie_slug'])): ?>
                                    | <a href="/categorie/<?php echo escapeHtml($article['categorie_slug']); ?>/">
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
</section>

<footer class="front-footer">
    <div class="container">
        <p>&copy; 2026 <?php echo SITE_NAME; ?> - Tous droits réservés</p>
        <p><a href="/">Accueil</a> | <a href="/mentions-legales/">Mentions légales</a> | <a href="/contact/">Contact</a></p>
    </div>
</footer>
