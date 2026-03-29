<?php
/**
 * Liste des actualités
 */

$currentPage = max(1, (int) ($_GET['p'] ?? 1));
$perPage = 10;
$offset = ($currentPage - 1) * $perPage;

$stmtCount = $pdo->query('SELECT COUNT(*) FROM article');
$totalArticles = (int) $stmtCount->fetchColumn();
$totalPages = max(1, (int) ceil($totalArticles / $perPage));

$sql = "SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
        FROM article a
        LEFT JOIN categorie c ON a.id_categorie = c.id
        ORDER BY a.date_publication DESC
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll();
?>

<section class="container">
    <h2>Actualités</h2>

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

        <?php if ($totalPages > 1): ?>
            <div class="categories-list">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a class="category-btn" href="/actualites/?p=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">Aucune actualité disponible.</div>
    <?php endif; ?>
</section>
