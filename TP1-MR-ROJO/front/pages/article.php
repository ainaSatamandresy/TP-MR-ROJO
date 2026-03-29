<?php
/**
 * Page detail article - FrontOffice
 * URL attendue: /article/{id}/{slug}
 */

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$slug = trim($_GET['slug'] ?? '');
$article = null;

if ($id > 0) {
    $article = getArticleById($pdo, $id);
}

if (!$article) {
    http_response_code(404);
    ?>
    <section class="article-page">
        <h1>Article non trouve</h1>
        <p>L'article demande n'existe pas ou a ete supprime.</p>
        <p><a href="/">Retour a l'accueil</a></p>
    </section>
    <?php
    return;
}

$expectedSlug = $article['slug'] ?? '';
if ($expectedSlug !== '' && $slug !== '' && $slug !== $expectedSlug) {
    header('Location: /article/' . (int) $article['id'] . '/' . rawurlencode($expectedSlug), true, 301);
    exit;
}

$contentHtml = sanitizeRichHtml($article['contenu'] ?? '');
$categoryName = $article['categorie_nom'] ?? '';
?>

<article class="article-page">
    <h1><?php echo escapeHtml($article['titre']); ?></h1>

    <p class="article-meta">
        Publie le <?php echo date('d/m/Y a H:i', strtotime($article['date_publication'])); ?>
        <?php if ($categoryName !== ''): ?>
            | Categorie: <?php echo escapeHtml($categoryName); ?>
        <?php endif; ?>
    </p>

    <div class="article-content">
        <?php echo $contentHtml; ?>
    </div>
</article>
