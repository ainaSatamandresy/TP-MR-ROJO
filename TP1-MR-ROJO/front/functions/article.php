<?php
/**
 * Logique metier de la page detail article front.
 */

function resolveFrontArticleViewData(PDO $pdo, array $queryData): array {
    $id = isset($queryData['id']) ? (int) $queryData['id'] : 0;
    $slug = trim((string) ($queryData['slug'] ?? ''));

    if ($id <= 0) {
        return ['status' => 'not_found'];
    }

    $article = getArticleById($pdo, $id);
    if (!$article) {
        return ['status' => 'not_found'];
    }

    $expectedSlug = (string) ($article['slug'] ?? '');
    if ($expectedSlug !== '' && $slug !== '' && $slug !== $expectedSlug) {
        return [
            'status' => 'redirect',
            'url' => '/article/' . (int) $article['id'] . '/' . rawurlencode($expectedSlug)
        ];
    }

    return [
        'status' => 'ok',
        'article' => $article,
        'content_html' => sanitizeRichHtml($article['contenu'] ?? ''),
        'category_name' => $article['categorie_nom'] ?? ''
    ];
}
