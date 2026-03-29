<?php
/**
 * Logique metier de la page admin des categories.
 */

function processAdminCategoriesPage(PDO $pdo, array $postData, array $queryData): array {
    $state = [
        'categories' => getCategoriesWithCount($pdo),
        'message' => '',
        'error' => '',
        'editId' => null,
        'editCategory' => null
    ];

    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
        $action = $postData['action'] ?? '';

        if ($action === 'create') {
            $nom = trim((string) ($postData['nom'] ?? ''));
            $slug = trim((string) ($postData['slug'] ?? ''));

            $validationError = validateCategoryForm($pdo, $nom, $slug);
            if ($validationError !== '') {
                $state['error'] = $validationError;
            } else {
                try {
                    createCategory($pdo, $nom, $slug);
                    $state['message'] = 'Categorie creee avec succes';
                    $state['categories'] = getCategoriesWithCount($pdo);
                } catch (Exception $e) {
                    $state['error'] = 'Erreur : ' . $e->getMessage();
                }
            }
        }

        if ($action === 'update') {
            $id = (int) ($postData['id'] ?? 0);
            $nom = trim((string) ($postData['nom'] ?? ''));
            $slug = trim((string) ($postData['slug'] ?? ''));

            $validationError = validateCategoryForm($pdo, $nom, $slug, $id);
            if ($validationError !== '') {
                $state['error'] = $validationError;
            } else {
                try {
                    updateCategory($pdo, $id, $nom, $slug);
                    $state['message'] = 'Categorie mise a jour avec succes';
                    $state['categories'] = getCategoriesWithCount($pdo);
                } catch (Exception $e) {
                    $state['error'] = 'Erreur : ' . $e->getMessage();
                }
            }
        }

        if ($action === 'delete') {
            $id = (int) ($postData['id'] ?? 0);

            try {
                $result = deleteCategory($pdo, $id);
                if ($result) {
                    $state['message'] = 'Categorie supprimee avec succes';
                    $state['categories'] = getCategoriesWithCount($pdo);
                } else {
                    $state['error'] = 'Impossible de supprimer : des articles sont lies a cette categorie';
                }
            } catch (Exception $e) {
                $state['error'] = 'Erreur : ' . $e->getMessage();
            }
        }
    }

    if (isset($queryData['edit'])) {
        $state['editId'] = (int) $queryData['edit'];
        $state['editCategory'] = getCategoryById($pdo, $state['editId']);
    }

    return $state;
}

function validateCategoryForm(PDO $pdo, string $nom, string $slug, ?int $excludeId = null): string {
    if ($nom === '') {
        return 'Le nom est requis';
    }

    if ($slug === '' || isSlugExists($pdo, $slug, $excludeId, 'categorie')) {
        return 'Le slug doit etre unique';
    }

    return '';
}
