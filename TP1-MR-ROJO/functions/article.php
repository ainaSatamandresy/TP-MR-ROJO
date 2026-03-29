<?php
/**
 * Fonctions pour la gestion des articles
 */

/**
 * Crée un slug à partir d'un titre
 */
function createSlug($titre) {
    $slug = strtolower($titre);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

/**
 * Récupère les articles récents
 */
function getLatestInfos($limit = 10) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM article ORDER BY date_publication DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

/**
 * Récupère les articles les plus consultés
 */
function getTopInfos($limit = 5) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM article ORDER BY vues DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

/**
 * Récupère les articles par catégorie
 */
function getInfoByCateg($id_categorie, $limit = 20, $offset = 0) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM article WHERE id_categorie = ? ORDER BY date_publication DESC LIMIT ? OFFSET ?");
    $stmt->execute([$id_categorie, $limit, $offset]);
    return $stmt->fetchAll();
}

/**
 * Récupère un article par son ID
 */
function getArticleById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM article WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Insère un nouvel article
 */
function insertArticle($titre, $contenu, $id_categorie, $slug) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO article (titre, contenu, slug, id_categorie, date_publication) VALUES (?, ?, ?, ?, NOW())");
    return $stmt->execute([$titre, $contenu, $slug, $id_categorie]);
}

/**
 * Met à jour un article
 */
function updateArticle($id, $titre, $contenu, $id_categorie, $slug) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE article SET titre = ?, contenu = ?, slug = ?, id_categorie = ? WHERE id = ?");
    return $stmt->execute([$titre, $contenu, $slug, $id_categorie, $id]);
}

/**
 * Supprime un article
 */
function deleteArticle($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM article WHERE id = ?");
    return $stmt->execute([$id]);
}
?>
