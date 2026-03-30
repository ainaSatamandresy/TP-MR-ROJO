<?php
/**
 * Logique metier de la page admin des articles.
 */

function processAdminArticlesPage(PDO $pdo, array $postData, array $queryData): array {
    $state = [
        'articles' => getAllArticles($pdo),
        'categories' => getAllCategories($pdo),
        'message' => '',
        'error' => '',
        'editId' => null,
        'editArticle' => null
    ];

    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
        $action = $postData['action'] ?? '';

        if ($action === 'create') {
            $data = [
                'titre' => trim((string) ($postData['titre'] ?? '')),
                'contenu' => sanitizeRichHtml($postData['contenu'] ?? ''),
                'slug' => trim((string) ($postData['slug'] ?? '')),
                'id_categorie' => (int) ($postData['id_categorie'] ?? 0),
                'image' => null
            ];

            // Gérer l'upload d'image
            if (isset($_FILES['image'])) {
                try {
                    $data['image'] = handleImageUpload($_FILES['image']);
                } catch (Exception $e) {
                    $state['error'] = 'Erreur lors de l\'upload de l\'image : ' . $e->getMessage();
                }
            }

            if ($state['error'] === '') {
                $validationError = validateArticleForm($pdo, $data);
                if ($validationError !== '') {
                    $state['error'] = $validationError;
                } else {
                    try {
                        createArticle($pdo, $data);
                        $state['message'] = 'Article cree avec succes';
                        $state['articles'] = getAllArticles($pdo);
                    } catch (Exception $e) {
                        $state['error'] = 'Erreur : ' . $e->getMessage();
                    }
                }
            }
        }

        if ($action === 'update') {
            $id = (int) ($postData['id'] ?? 0);
            
            // Récupérer l'article existant pour conserver l'image si pas de nouvel upload
            $existingArticle = getArticleById($pdo, $id);
            
            $data = [
                'titre' => trim((string) ($postData['titre'] ?? '')),
                'contenu' => sanitizeRichHtml($postData['contenu'] ?? ''),
                'slug' => trim((string) ($postData['slug'] ?? '')),
                'id_categorie' => (int) ($postData['id_categorie'] ?? 0),
                'image' => $existingArticle['image'] ?? null
            ];

            // Gérer l'upload d'image
            if (isset($_FILES['image'])) {
                try {
                    $data['image'] = handleImageUpload($_FILES['image'], $data['image']);
                } catch (Exception $e) {
                    $state['error'] = 'Erreur lors de l\'upload de l\'image : ' . $e->getMessage();
                }
            }

            if ($state['error'] === '') {
                $validationError = validateArticleForm($pdo, $data, $id);
                if ($validationError !== '') {
                    $state['error'] = $validationError;
                } else {
                    try {
                        updateArticle($pdo, $id, $data);
                        $state['message'] = 'Article mis a jour avec succes';
                        $state['articles'] = getAllArticles($pdo);
                    } catch (Exception $e) {
                        $state['error'] = 'Erreur : ' . $e->getMessage();
                    }
                }
            }
        }

        if ($action === 'delete') {
            $id = (int) ($postData['id'] ?? 0);
            try {
                deleteArticle($pdo, $id);
                $state['message'] = 'Article supprime avec succes';
                $state['articles'] = getAllArticles($pdo);
            } catch (Exception $e) {
                $state['error'] = 'Erreur : ' . $e->getMessage();
            }
        }
    }

    if (isset($queryData['edit'])) {
        $state['editId'] = (int) $queryData['edit'];
        $state['editArticle'] = getArticleById($pdo, $state['editId']);
    }

    return $state;
}

function validateArticleForm(PDO $pdo, array $data, ?int $excludeId = null): string {
    if (($data['titre'] ?? '') === '') {
        return 'Le titre est requis';
    }

    if (isRichContentEmpty($data['contenu'] ?? '')) {
        return 'Le contenu est requis';
    }

    $slug = (string) ($data['slug'] ?? '');
    if ($slug === '' || isSlugExists($pdo, $slug, $excludeId, 'article')) {
        return 'Le slug doit etre unique';
    }

    if ((int) ($data['id_categorie'] ?? 0) <= 0) {
        return 'Une categorie doit etre selectionnee';
    }

    return '';
}
