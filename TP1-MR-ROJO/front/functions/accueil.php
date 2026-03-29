<?php
/**
 * Logique metier de la page d'accueil front.
 */

function getFrontAccueilData(PDO $pdo): array {
    try {
        $categories = getAllCategories($pdo);

        $stmt = $pdo->query('SELECT * FROM article ORDER BY date_publication DESC LIMIT 3');
        $featuredArticles = $stmt->fetchAll();

        $sql = 'SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
                FROM article a
                LEFT JOIN categorie c ON a.id_categorie = c.id
                ORDER BY a.date_publication DESC
                LIMIT 6';
        $stmt = $pdo->query($sql);
        $latestArticles = $stmt->fetchAll();

        return [
            'categories' => $categories,
            'featured_articles' => $featuredArticles,
            'latest_articles' => $latestArticles,
            'page_description' => 'Actualites et informations sur la situation en Iran - Geopolitique, politique et relations internationales'
        ];
    } catch (Exception $e) {
        return [
            'categories' => [],
            'featured_articles' => [],
            'latest_articles' => [],
            'page_description' => 'Actualites et informations sur la situation en Iran - Geopolitique, politique et relations internationales'
        ];
    }
}
