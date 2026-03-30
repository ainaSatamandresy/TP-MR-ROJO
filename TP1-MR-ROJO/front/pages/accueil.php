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
