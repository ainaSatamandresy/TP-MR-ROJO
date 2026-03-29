<?php
/**
 * Page détail d'un article
 */

include '../inc/header.php';
include '../inc/helpers.php';
include '../functions/article.php';

// Récupérer l'ID de l'article depuis l'URL réécrite
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $article = getArticleById($id);
    
    if ($article) {
        $title = htmlspecialchars($article['titre']) . " - Iran News";
    } else {
        $title = "Article non trouvé";
    }
} else {
    $title = "Article non trouvé";
}
?>

<?php if (isset($article) && $article): ?>
    <article class="article-detail">
        <h1><?php echo htmlspecialchars($article['titre']); ?></h1>
        <p class="meta">Publié le <?php echo date('d/m/Y à H:i', strtotime($article['date_publication'])); ?></p>
        
        <div class="article-content">
            <?php echo sanitizeRichHtml($article['contenu']); ?>
        </div>
    </article>
<?php else: ?>
    <div class="error">
        <h1>Article non trouvé</h1>
        <p>L'article que vous cherchez n'existe pas ou a été supprimé.</p>
        <a href="/">Retour à l'accueil</a>
    </div>
<?php endif; ?>

<?php include '../inc/footer.php'; ?>
