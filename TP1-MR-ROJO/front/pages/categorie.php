<?php
/**
 * Liste des articles par catégorie
 */

require_once __DIR__ . '/../functions/listing.php';

$categorieData = getFrontCategorieData($pdo, $_GET);
$slug = $categorieData['slug'];
$category = $categorieData['category'];
$articles = $categorieData['articles'];
$totalPages = $categorieData['total_pages'];
?>

<section class="container">
    <?php if ($category): ?>
        <h2>Catégorie: <?php echo escapeHtml($category['nom']); ?></h2>

        <?php if (!empty($articles)): ?>
            <div class="articles-grid">
                <?php foreach ($articles as $article): ?>
                    <article>
                        <div class="content">
                            <h3>
                                <a href="/article/<?php echo (int) $article['id']; ?>/<?php echo escapeHtml($article['slug']); ?>">
                                    <?php echo escapeHtml($article['titre']); ?>
                                </a>
                            </h3>
                            <div class="meta"><?php echo date('d/m/Y', strtotime($article['date_publication'])); ?></div>
                            <p class="excerpt"><?php echo escapeHtml(excerptFromHtml($article['contenu'], 180)); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="categories-list">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="category-btn" href="/categorie/<?php echo escapeHtml($category['slug']); ?>/?p=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">Aucun article dans cette catégorie.</div>
        <?php endif; ?>
    <?php else: ?>
        <h2>Catégorie introuvable</h2>
        <p>La catégorie demandée n'existe pas.</p>
    <?php endif; ?>
</section>
