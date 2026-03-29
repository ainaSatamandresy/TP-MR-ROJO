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

<style>
    .article-page {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .article-page h1 {
        margin-bottom: 0.8rem;
        line-height: 1.25;
    }

    .article-meta {
        color: #666;
        margin-bottom: 1.2rem;
        font-size: 0.95rem;
    }

    .article-content {
        line-height: 1.7;
        font-size: 1.05rem;
    }

    .article-content h1,
    .article-content h2,
    .article-content h3,
    .article-content h4,
    .article-content h5,
    .article-content h6 {
        margin-top: 1.4rem;
        margin-bottom: 0.7rem;
        line-height: 1.3;
    }

    .article-content p,
    .article-content ul,
    .article-content ol,
    .article-content blockquote {
        margin-bottom: 1rem;
    }

    .article-content blockquote {
        border-left: 4px solid #ddd;
        padding-left: 1rem;
        color: #444;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
    }
</style>

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
