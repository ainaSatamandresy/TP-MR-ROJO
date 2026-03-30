<?php
/**
 * Page Accueil - FrontOffice
 */

require_once __DIR__ . '/../functions/accueil.php';

$homeData = getFrontAccueilData($pdo);
$categories = $homeData['categories'];
$featured_articles = $homeData['featured_articles'];
$latest_articles = $homeData['latest_articles'];
?>

<section class="container">
    <?php if (!empty($featured_articles)): ?>
        <section class="home-section">
            <h2 class="section-title">A la une</h2>
            <div class="articles-grid featured-grid">
                <?php foreach ($featured_articles as $index => $article): ?>
                    <?php $articleUrl = '/article/' . (int) $article['id'] . '/' . escapeHtml($article['slug']); ?>
                    <article class="news-card<?php echo $index === 0 ? ' lead-card' : ''; ?>">
                        <?php if (!empty($article['image'])): ?>
                            <a href="<?php echo $articleUrl; ?>" class="news-cover-link">
                                <img
                                    src="/assets/images/articles/<?php echo escapeHtml((string) $article['image']); ?>"
                                    alt="Illustration: <?php echo escapeHtml($article['titre']); ?>"
                                    class="news-cover"
                                    width="640"
                                    height="400"
                                    loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                                    decoding="async"
                                    fetchpriority="<?php echo $index === 0 ? 'high' : 'auto'; ?>"
                                >
                            </a>
                        <?php endif; ?>
                        <div class="content">
                            <h3><a href="<?php echo $articleUrl; ?>">
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
        <section class="home-section">
            <h2 class="section-title">Dernieres actualites</h2>
            <div class="articles-grid">
                <?php foreach ($latest_articles as $article): ?>
                    <?php $articleUrl = '/article/' . (int) $article['id'] . '/' . escapeHtml($article['slug']); ?>
                    <article class="news-card">
                        <?php if (!empty($article['image'])): ?>
                            <a href="<?php echo $articleUrl; ?>" class="news-cover-link">
                                <img
                                    src="/assets/images/articles/<?php echo escapeHtml((string) $article['image']); ?>"
                                    alt="Illustration: <?php echo escapeHtml($article['titre']); ?>"
                                    class="news-cover"
                                    width="640"
                                    height="400"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </a>
                        <?php endif; ?>
                        <div class="content">
                            <h3><a href="<?php echo $articleUrl; ?>">
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
