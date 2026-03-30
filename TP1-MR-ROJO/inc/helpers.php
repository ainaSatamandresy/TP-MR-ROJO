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
 * Vérifier qu'une URL est sûre pour un attribut href/src
 */
function isSafeUrl($url, $allowAnchor = false) {
    $url = trim((string)$url);
    if ($url === '') {
        return false;
    }

    if ($allowAnchor && strpos($url, '#') === 0) {
        return true;
    }

    if (strpos($url, '/') === 0) {
        return true;
    }

    if (preg_match('/^(https?:|mailto:|tel:)/i', $url)) {
        return true;
    }

    return false;
}

/**
 * Sanitizer HTML basique pour contenu riche TinyMCE
 */
function sanitizeRichHtml($html) {
    $html = trim((string)$html);
    if ($html === '') {
        return '';
    }

    $allowedTags = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li', 'blockquote',
        'a', 'img'
    ];

    $dangerousTags = [
        'script', 'style', 'iframe', 'object', 'embed',
        'svg', 'math', 'form', 'input', 'button',
        'textarea', 'select', 'meta', 'link', 'base'
    ];

    $allowedAttributes = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height', 'loading']
    ];

    $doc = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $doc->loadHTML('<div id="sanitizer-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $root = $doc->getElementById('sanitizer-root');
    if (!$root) {
        return '';
    }

    $sanitizeNode = function($node) use (&$sanitizeNode, $doc, $allowedTags, $dangerousTags, $allowedAttributes) {
        for ($i = $node->childNodes->length - 1; $i >= 0; $i--) {
            $child = $node->childNodes->item($i);

            if ($child->nodeType === XML_COMMENT_NODE) {
                $node->removeChild($child);
                continue;
            }

            if ($child->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            $tag = strtolower($child->nodeName);

            if (in_array($tag, $dangerousTags, true)) {
                $node->removeChild($child);
                continue;
            }

            if (!in_array($tag, $allowedTags, true)) {
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            if ($child->hasAttributes()) {
                $attrs = [];
                foreach ($child->attributes as $attr) {
                    $attrs[] = $attr;
                }

                foreach ($attrs as $attr) {
                    $name = strtolower($attr->nodeName);
                    $value = trim($attr->nodeValue);
                    $tagAllowedAttrs = $allowedAttributes[$tag] ?? [];

                    if (strpos($name, 'on') === 0 || !in_array($name, $tagAllowedAttrs, true)) {
                        $child->removeAttributeNode($attr);
                        continue;
                    }

                    if ($tag === 'a' && $name === 'href' && !isSafeUrl($value, true)) {
                        $child->removeAttributeNode($attr);
                        continue;
                    }

                    if ($tag === 'img' && $name === 'src' && !isSafeUrl($value, false)) {
                        $child->removeAttributeNode($attr);
                        continue;
                    }

                    if ($tag === 'a' && $name === 'target' && strtolower($value) !== '_blank') {
                        $child->removeAttribute('target');
                        continue;
                    }
                }

                if ($tag === 'a' && strtolower($child->getAttribute('target')) === '_blank') {
                    $child->setAttribute('rel', 'noopener noreferrer');
                }
            }

            $sanitizeNode($child);
        }
    };

    $sanitizeNode($root);

    $cleanHtml = '';
    foreach ($root->childNodes as $child) {
        $cleanHtml .= $doc->saveHTML($child);
    }

    return trim($cleanHtml);
}

/**
 * Vérifier si un contenu riche est vide (texte absent et aucune image)
 */
function isRichContentEmpty($html) {
    $html = (string)$html;
    $hasImage = preg_match('/<img\b/i', $html) === 1;

    $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace('/\s+/u', ' ', str_replace("\xc2\xa0", ' ', $text));
    $text = trim((string)$text);

    return $text === '' && !$hasImage;
}

/**
 * Générer un extrait texte à partir d'un contenu HTML
 */
function excerptFromHtml($html, $length = 150) {
    $text = html_entity_decode(strip_tags((string)$html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace('/\s+/u', ' ', (string)$text);
    $text = trim((string)$text);

    if ($text === '') {
        return '';
    }

    if (mb_strlen($text) <= $length) {
        return $text;
    }

    return mb_substr($text, 0, $length) . '...';
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
    $sql = "INSERT INTO article (titre, contenu, slug, id_categorie, image) 
            VALUES (:titre, :contenu, :slug, :id_categorie, :image)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'titre' => $data['titre'],
        'contenu' => $data['contenu'],
        'slug' => $data['slug'],
        'id_categorie' => $data['id_categorie'],
        'image' => $data['image'] ?? null
    ]);
    return $pdo->lastInsertId();
}

/**
 * Mettre à jour un article
 */
function updateArticle(PDO $pdo, $id, $data) {
    $sql = "UPDATE article 
            SET titre = :titre, contenu = :contenu, slug = :slug, id_categorie = :id_categorie, image = :image 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'titre' => $data['titre'],
        'contenu' => $data['contenu'],
        'slug' => $data['slug'],
        'id_categorie' => $data['id_categorie'],
        'image' => $data['image'] ?? null
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

/**
 * Gérer l'upload d'image pour un article
 * @param array $file - Le fichier provenant de $_FILES['image']
 * @param string|null $oldImage - L'ancienne image à supprimer si elle existe
 * @return string|null - Le nom du fichier uploadé ou null si pas d'upload
 * @throws Exception
 */
function handleImageUpload($file, $oldImage = null) {
    // Si pas de fichier uploadé
    if (!isset($file) || !isset($file['tmp_name']) || $file['tmp_name'] === '') {
        return $oldImage; // Retourner l'ancienne image si elle existe
    }

    // Vérifier les erreurs d'upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'Le fichier dépasse la taille maximale.',
            UPLOAD_ERR_FORM_SIZE => 'Le fichier dépasse la taille maximale.',
            UPLOAD_ERR_PARTIAL => 'L\'upload a été interrompu.',
            UPLOAD_ERR_NO_FILE => 'Aucun fichier fourni.',
            UPLOAD_ERR_NO_TMP_DIR => 'Répertoire temporaire manquant.',
            UPLOAD_ERR_CANT_WRITE => 'Erreur d\'écriture sur le disque.',
            UPLOAD_ERR_EXTENSION => 'Extension PHP non autorisée.',
        ];
        throw new Exception($errorMessages[$file['error']] ?? 'Erreur d\'upload inconnue.');
    }

    // Vérifier le type MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mimeType, $allowedMimes)) {
        throw new Exception('Le fichier doit être une image (JPEG, PNG, GIF ou WebP).');
    }

    // Vérifier la taille (max 5MB)
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        throw new Exception('Le fichier dépasse 5MB.');
    }

    // Créer le répertoire s'il n'existe pas
    $uploadDir = __DIR__ . '/../assets/images/articles/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Générer un nom unique pour le fichier
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'article-' . time() . '-' . uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;

    // Déplacer le fichier uploadé
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception('Erreur lors du déplacement du fichier.');
    }

    // Supprimer l'ancienne image si elle existe et qu'on en a une nouvelle
    if ($oldImage && $oldImage !== $filename) {
        $oldPath = $uploadDir . $oldImage;
        if (file_exists($oldPath) && is_file($oldPath)) {
            unlink($oldPath);
        }
    }

    return $filename;
}
?>
