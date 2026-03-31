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

    $fallbackAlt = trim((string) ($article['titre'] ?? 'Illustration de l article'));
    $contentHtml = sanitizeRichHtml($article['contenu'] ?? '');
    $contentHtml = optimizeArticleContentImages($contentHtml, $fallbackAlt);

    return [
        'status' => 'ok',
        'article' => $article,
        'content_html' => $contentHtml,
        'category_name' => $article['categorie_nom'] ?? ''
    ];
}

function optimizeArticleContentImages(string $html, string $fallbackAlt): string {
    if (trim($html) === '') {
        return '';
    }

    $doc = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    // Convertir en entités HTML pour préserver les caractères UTF-8 correctement
    $htmlForDom = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    $doc->loadHTML('<div id="content-root">' . $htmlForDom . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $root = $doc->getElementById('content-root');
    if (!$root) {
        return $html;
    }

    foreach ($root->getElementsByTagName('img') as $img) {
        if (trim((string) $img->getAttribute('alt')) === '') {
            $img->setAttribute('alt', $fallbackAlt);
        }

        if (!$img->hasAttribute('loading')) {
            $img->setAttribute('loading', 'lazy');
        }

        if (!$img->hasAttribute('decoding')) {
            $img->setAttribute('decoding', 'async');
        }
    }

    $optimizedHtml = '';
    foreach ($root->childNodes as $child) {
        $optimizedHtml .= $doc->saveHTML($child);
    }

    return trim($optimizedHtml);
}
