<?php
/**
 * Liste des articles par catégorie
 */

$slug = trim((string) ($_GET['slug'] ?? ''));
$currentPage = max(1, (int) ($_GET['p'] ?? 1));
$perPage = 10;
$offset = ($currentPage - 1) * $perPage;
$category = null;
$articles = [];
$totalPages = 1;

if ($slug !== '') {
    $stmtCategory = $pdo->prepare('SELECT id, nom, slug FROM categorie WHERE slug = :slug LIMIT 1');
    $stmtCategory->execute(['slug' => $slug]);
    $category = $stmtCategory->fetch();

    if ($category) {
        $stmtCount = $pdo->prepare('SELECT COUNT(*) FROM article WHERE id_categorie = :id_categorie');
        $stmtCount->execute(['id_categorie' => $category['id']]);
        $totalArticles = (int) $stmtCount->fetchColumn();
        $totalPages = max(1, (int) ceil($totalArticles / $perPage));

        $sql = "SELECT *
                FROM article
                WHERE id_categorie = :id_categorie
                ORDER BY date_publication DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id_categorie', (int) $category['id'], PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $articles = $stmt->fetchAll();
    }
}
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
