<?php
/**
 * Recherche d'articles
 */

require_once __DIR__ . '/../functions/listing.php';

$searchData = getFrontRechercheData($pdo, $_GET);
$query = $searchData['query'];
$results = $searchData['results'];
?>

<section class="container">
    <h2>Recherche</h2>

    <form method="GET" action="/recherche/" class="categories-list">
        <input type="hidden" name="page" value="recherche">
        <input type="text" name="q" value="<?php echo escapeHtml($query); ?>" placeholder="Rechercher un article" class="search-input">
        <button type="submit" class="category-btn">Rechercher</button>
    </form>

    <?php if ($query === ''): ?>
        <p>Saisissez un mot-clé pour lancer la recherche.</p>
    <?php elseif (!empty($results)): ?>
        <div class="articles-grid">
            <?php foreach ($results as $article): ?>
                <article>
                    <div class="content">
                        <h3>
                            <a href="/article/<?php echo (int) $article['id']; ?>/<?php echo escapeHtml($article['slug']); ?>">
                                <?php echo escapeHtml($article['titre']); ?>
                            </a>
                        </h3>
                        <div class="meta">
                            <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?>
                            <?php if (!empty($article['categorie_nom']) && !empty($article['categorie_slug'])): ?>
                                | <a href="/categorie/<?php echo escapeHtml($article['categorie_slug']); ?>/"><?php echo escapeHtml($article['categorie_nom']); ?></a>
                            <?php endif; ?>
                        </div>
                        <p class="excerpt"><?php echo escapeHtml(excerptFromHtml($article['contenu'], 180)); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">Aucun résultat pour "<?php echo escapeHtml($query); ?>".</div>
    <?php endif; ?>
</section>
