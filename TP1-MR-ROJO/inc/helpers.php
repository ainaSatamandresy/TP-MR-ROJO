<?php
/**
 * Fonctions utiles pour le projet
 */

/**
 * Convertir un titre en slug (minuscules, tirets, sans accents)
 */
function generateSlug($texte) {
    // Convertir en minuscules
    $texte = strtolower($texte);
    
    // Remplacer les accents
    $accents = array(
        'à' => 'a', 'â' => 'a', 'ä' => 'a', 'á' => 'a',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
        'ì' => 'i', 'î' => 'i', 'ï' => 'i', 'í' => 'i',
        'ò' => 'o', 'ô' => 'o', 'ö' => 'o', 'ó' => 'o',
        'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ú' => 'u',
        'ç' => 'c',
        'œ' => 'oe', 'æ' => 'ae'
    );
    
    $texte = strtr($texte, $accents);
    
    // Remplacer les espaces et caractères spéciaux par des tirets
    $texte = preg_replace('/[^a-z0-9-]/', '-', $texte);
    
    // Supprimer les tirets multiples
    $texte = preg_replace('/-+/', '-', $texte);
    
    // Supprimer les tirets au début et à la fin
    $texte = trim($texte, '-');
    
    return $texte;
}

/**
 * Vérifier si l'utilisateur est authentifié (admin)
 */
function isAuthenticated() {
    return isset($_SESSION['admin']);
}

/**
 * Rediriger vers la page de connexion si non authentifié
 */
function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: /admin/');
        exit;
    }
}

/**
 * Échapper les caractères spéciaux pour affichage HTML
 */
function escapeHtml($texte) {
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}

/**
 * Valider une adresse email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Fonction de pagination
 */
function getPagination($page, $totalItems, $itemsPerPage = 10) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    $page = max(1, min($page, $totalPages));
    $offset = ($page - 1) * $itemsPerPage;
    
    return [
        'page' => $page,
        'totalPages' => $totalPages,
        'offset' => $offset,
        'itemsPerPage' => $itemsPerPage
    ];
}

/**
 * Obtenir tous les articles
 */
function getAllArticles(PDO $pdo) {
    $sql = "SELECT a.*, c.nom as categorie_nom 
            FROM article a 
            LEFT JOIN categorie c ON a.id_categorie = c.id 
            ORDER BY a.date_publication DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

/**
 * Obtenir un article par ID
 */
function getArticleById(PDO $pdo, $id) {
    $sql = "SELECT a.*, c.nom as categorie_nom 
            FROM article a 
            LEFT JOIN categorie c ON a.id_categorie = c.id 
            WHERE a.id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

/**
 * Créer un article
 */
function createArticle(PDO $pdo, $data) {
    $sql = "INSERT INTO article (titre, contenu, slug, id_categorie) 
            VALUES (:titre, :contenu, :slug, :id_categorie)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'titre' => $data['titre'],
        'contenu' => $data['contenu'],
        'slug' => $data['slug'],
        'id_categorie' => $data['id_categorie']
    ]);
    return $pdo->lastInsertId();
}

/**
 * Mettre à jour un article
 */
function updateArticle(PDO $pdo, $id, $data) {
    $sql = "UPDATE article 
            SET titre = :titre, contenu = :contenu, slug = :slug, id_categorie = :id_categorie 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'titre' => $data['titre'],
        'contenu' => $data['contenu'],
        'slug' => $data['slug'],
        'id_categorie' => $data['id_categorie']
    ]);
}

/**
 * Supprimer un article
 */
function deleteArticle(PDO $pdo, $id) {
    $sql = "DELETE FROM article WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}

/**
 * Obtenir toutes les catégories
 */
function getAllCategories(PDO $pdo) {
    $sql = "SELECT * FROM categorie ORDER BY nom ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

/**
 * Obtenir une catégorie avec le nombre d'articles
 */
function getCategoriesWithCount(PDO $pdo) {
    $sql = "SELECT c.*, COUNT(a.id) as article_count 
            FROM categorie c 
            LEFT JOIN article a ON c.id = a.id_categorie 
            GROUP BY c.id 
            ORDER BY c.nom ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

/**
 * Obtenir une catégorie par ID
 */
function getCategoryById(PDO $pdo, $id) {
    $sql = "SELECT * FROM categorie WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

/**
 * Créer une catégorie
 */
function createCategory(PDO $pdo, $nom, $slug) {
    $sql = "INSERT INTO categorie (nom, slug, description) 
            VALUES (:nom, :slug, :description)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => $nom,
        'slug' => $slug,
        'description' => ''
    ]);
    return $pdo->lastInsertId();
}

/**
 * Mettre à jour une catégorie
 */
function updateCategory(PDO $pdo, $id, $nom, $slug) {
    $sql = "UPDATE categorie SET nom = :nom, slug = :slug WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'nom' => $nom,
        'slug' => $slug
    ]);
}

/**
 * Supprimer une catégorie (vérifier s'il y a des articles)
 */
function deleteCategory(PDO $pdo, $id) {
    // Vérifier les articles liés
    $sql = "SELECT COUNT(*) as count FROM article WHERE id_categorie = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetch();
    
    if ($result['count'] > 0) {
        return false; // Impossible de supprimer
    }
    
    $sql = "DELETE FROM categorie WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return true;
}

/**
 * Vérifier si un slug existe déjà (sauf pour l'ID donné)
 */
function isSlugExists(PDO $pdo, $slug, $excludeId = null, $table = 'categorie') {
    if ($table === 'categorie') {
        $sql = "SELECT COUNT(*) as count FROM categorie WHERE slug = :slug";
        if ($excludeId) {
            $sql .= " AND id != :id";
        }
    } else {
        $sql = "SELECT COUNT(*) as count FROM article WHERE slug = :slug";
        if ($excludeId) {
            $sql .= " AND id != :id";
        }
    }
    
    $stmt = $pdo->prepare($sql);
    $params = ['slug' => $slug];
    if ($excludeId) {
        $params['id'] = $excludeId;
    }
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result['count'] > 0;
}

/**
 * Obtenir les statistiques du dashboard
 */
function getDashboardStats(PDO $pdo) {
    $stats = [];
    
    // Nombre d'articles
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM article");
    $stats['articles_count'] = $stmt->fetch()['count'];
    
    // Nombre de catégories
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM categorie");
    $stats['categories_count'] = $stmt->fetch()['count'];
    
    // Nombre d'utilisateurs
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM utilisateur");
    $stats['users_count'] = $stmt->fetch()['count'];
    
    // Derniers articles
    $stmt = $pdo->query("SELECT id, titre, date_publication FROM article ORDER BY date_publication DESC LIMIT 5");
    $stats['latest_articles'] = $stmt->fetchAll();
    
    return $stats;
}
?>
