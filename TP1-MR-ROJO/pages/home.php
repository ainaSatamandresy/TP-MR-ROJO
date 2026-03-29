<?php
/**
 * Page d'accueil
 */

$title = "Accueil - Iran News";
include '../inc/header.php';
include '../functions/article.php';

// Récupérer les articles récents
$articles = getLatestInfos(10);
?>

<section class="home">
    <h1>Dernières informations sur l'Iran</h1>
    
    <div class="articles-grid">
        <?php foreach ($articles as $article): ?>
            <article class="article-card">
                <h2><a href="/article/<?php echo htmlspecialchars($article['slug']); ?>-<?php echo $article['id']; ?>.html">
                    <?php echo htmlspecialchars($article['titre']); ?>
                </a></h2>
                <p class="meta"><?php echo date('d/m/Y', strtotime($article['date_publication'])); ?></p>
                <p><?php echo substr(htmlspecialchars($article['contenu']), 0, 150); ?>...</p>
                <a href="/article/<?php echo htmlspecialchars($article['slug']); ?>-<?php echo $article['id']; ?>.html" class="lire-plus">Lire la suite</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php include '../inc/footer.php'; ?>
