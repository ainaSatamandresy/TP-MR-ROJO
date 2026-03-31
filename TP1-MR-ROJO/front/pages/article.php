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
$articleLead = excerptFromHtml($article['contenu'] ?? '', 190);
?>

<article class="article-page">
    <header class="article-header">
        <h1><?php echo escapeHtml($article['titre']); ?></h1>

        <?php if ($articleLead !== ''): ?>
            <h2 class="article-subtitle"><?php echo escapeHtml($articleLead); ?></h2>
        <?php endif; ?>

        <p class="article-meta">
            Publie le <?php echo date('d/m/Y a H:i', strtotime($article['date_publication'])); ?>
            <?php if ($categoryName !== ''): ?>
                | Categorie: <?php echo escapeHtml($categoryName); ?>
            <?php endif; ?>
        </p>
    </header>

    <?php if (!empty($article['image'])): ?>
        <figure class="article-main-figure">
            <img
                src="/assets/images/articles/<?php echo escapeHtml((string) $article['image']); ?>"
                alt="Illustration principale: <?php echo escapeHtml($article['titre']); ?>"
                class="article-main-image"
                width="1200"
                height="675"
                loading="eager"
                decoding="async"
                fetchpriority="high"
            >
        </figure>
    <?php endif; ?>

    <div class="article-content">
        <?php echo $contentHtml; ?>
        
    </div>
</article>
