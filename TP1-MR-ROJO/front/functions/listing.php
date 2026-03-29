<?php
/**
 * Logiques metier des pages front de listing/recherche.
 */

function getFrontActualitesData(PDO $pdo, array $queryData): array {
    $currentPage = max(1, (int) ($queryData['p'] ?? 1));
    $perPage = 10;
    $offset = ($currentPage - 1) * $perPage;

    $stmtCount = $pdo->query('SELECT COUNT(*) FROM article');
    $totalArticles = (int) $stmtCount->fetchColumn();
    $totalPages = max(1, (int) ceil($totalArticles / $perPage));

    $sql = 'SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
            FROM article a
            LEFT JOIN categorie c ON a.id_categorie = c.id
            ORDER BY a.date_publication DESC
            LIMIT :limit OFFSET :offset';

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return [
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'articles' => $stmt->fetchAll()
    ];
}

function getFrontCategorieData(PDO $pdo, array $queryData): array {
    $slug = trim((string) ($queryData['slug'] ?? ''));
    $currentPage = max(1, (int) ($queryData['p'] ?? 1));
    $perPage = 10;
    $offset = ($currentPage - 1) * $perPage;

    $state = [
        'slug' => $slug,
        'category' => null,
        'articles' => [],
        'total_pages' => 1
    ];

    if ($slug === '') {
        return $state;
    }

    $stmtCategory = $pdo->prepare('SELECT id, nom, slug FROM categorie WHERE slug = :slug LIMIT 1');
    $stmtCategory->execute(['slug' => $slug]);
    $category = $stmtCategory->fetch();

    if (!$category) {
        return $state;
    }

    $stmtCount = $pdo->prepare('SELECT COUNT(*) FROM article WHERE id_categorie = :id_categorie');
    $stmtCount->execute(['id_categorie' => $category['id']]);
    $totalArticles = (int) $stmtCount->fetchColumn();
    $totalPages = max(1, (int) ceil($totalArticles / $perPage));

    $sql = 'SELECT * FROM article
            WHERE id_categorie = :id_categorie
            ORDER BY date_publication DESC
            LIMIT :limit OFFSET :offset';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_categorie', (int) $category['id'], PDO::PARAM_INT);
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $state['category'] = $category;
    $state['articles'] = $stmt->fetchAll();
    $state['total_pages'] = $totalPages;

    return $state;
}

function getFrontRechercheData(PDO $pdo, array $queryData): array {
    $query = trim((string) ($queryData['q'] ?? ''));
    if ($query === '') {
        return ['query' => $query, 'results' => []];
    }

    $sql = 'SELECT a.*, c.nom AS categorie_nom, c.slug AS categorie_slug
            FROM article a
            LEFT JOIN categorie c ON a.id_categorie = c.id
            WHERE a.titre ILIKE :query OR a.contenu ILIKE :query
            ORDER BY a.date_publication DESC
            LIMIT 30';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['query' => '%' . $query . '%']);

    return ['query' => $query, 'results' => $stmt->fetchAll()];
}
