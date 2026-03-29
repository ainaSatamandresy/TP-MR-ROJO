<?php
/**
 * Page detail article - FrontOffice
 * URL attendue: /article/{id}/{slug}
 */

require_once __DIR__ . '/../functions/article.php';

$viewData = resolveFrontArticleViewData($pdo, $_GET);

if ($viewData['status'] === 'redirect') {
    header('Location: ' . $viewData['url'], true, 301);
    exit;
}

if ($viewData['status'] !== 'ok') {
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

$article = $viewData['article'];
$contentHtml = $viewData['content_html'];
$categoryName = $viewData['category_name'];
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
