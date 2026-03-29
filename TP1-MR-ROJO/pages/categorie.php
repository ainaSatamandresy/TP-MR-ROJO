<?php
/**
 * Page catégorie
 */

include '../inc/header.php';
include '../functions/article.php';

$id_categorie = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$title = "Catégorie - Iran News";

if ($id_categorie > 0) {
    $articles = getInfoByCateg($id_categorie, $limit, $offset);
} else {
    $articles = [];
}
?>

<section class="categorie">
    <h1>Articles par catégorie</h1>
    
    <div class="articles-list">
        <?php foreach ($articles as $article): ?>
            <article class="article-item">
                <h2><a href="/article/<?php echo htmlspecialchars($article['slug']); ?>-<?php echo $article['id']; ?>.html">
                    <?php echo htmlspecialchars($article['titre']); ?>
                </a></h2>
                <p><?php echo date('d/m/Y', strtotime($article['date_publication'])); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
    
    <!-- Pagination ici -->
</section>

<?php include '../inc/footer.php'; ?>
