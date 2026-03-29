<?php
/**
 * Gestion des articles - BackOffice
 */

session_start();
require_once __DIR__ . '/../../inc/db.php';
require_once __DIR__ . '/../../inc/helpers.php';

requireAuth();

$articles = getAllArticles($pdo);
$categories = getAllCategories($pdo);
$message = '';
$error = '';
$editId = null;
$editArticle = null;

// Traiter les actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $titre = trim($_POST['titre'] ?? '');
        $contenu = sanitizeRichHtml($_POST['contenu'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $id_categorie = (int)($_POST['id_categorie'] ?? 0);
        
        // Validation
        if (empty($titre)) {
            $error = "Le titre est requis";
        } elseif (isRichContentEmpty($contenu)) {
            $error = "Le contenu est requis";
        } elseif (empty($slug) || isSlugExists($pdo, $slug, null, 'article')) {
            $error = "Le slug doit être unique";
        } elseif ($id_categorie <= 0) {
            $error = "Une catégorie doit être sélectionnée";
        } else {
            try {
                $data = [
                    'titre' => $titre,
                    'contenu' => $contenu,
                    'slug' => $slug,
                    'id_categorie' => $id_categorie
                ];
                createArticle($pdo, $data);
                $message = "Article créé avec succès";
                // Rafraîchir la liste
                $articles = getAllArticles($pdo);
            } catch (Exception $e) {
                $error = "Erreur : " . $e->getMessage();
            }
        }
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $titre = trim($_POST['titre'] ?? '');
        $contenu = sanitizeRichHtml($_POST['contenu'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $id_categorie = (int)($_POST['id_categorie'] ?? 0);
        
        // Validation
        if (empty($titre)) {
            $error = "Le titre est requis";
        } elseif (isRichContentEmpty($contenu)) {
            $error = "Le contenu est requis";
        } elseif (empty($slug) || isSlugExists($pdo, $slug, $id, 'article')) {
            $error = "Le slug doit être unique";
        } elseif ($id_categorie <= 0) {
            $error = "Une catégorie doit être sélectionnée";
        } else {
            try {
                $data = [
                    'titre' => $titre,
                    'contenu' => $contenu,
                    'slug' => $slug,
                    'id_categorie' => $id_categorie
                ];
                updateArticle($pdo, $id, $data);
                $message = "Article mis à jour avec succès";
                // Rafraîchir la liste
                $articles = getAllArticles($pdo);
                $editId = null;
            } catch (Exception $e) {
                $error = "Erreur : " . $e->getMessage();
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        
        try {
            deleteArticle($pdo, $id);
            $message = "Article supprimé avec succès";
            $articles = getAllArticles($pdo);
        } catch (Exception $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}

// Récupérer l'article si édition
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editArticle = getArticleById($pdo, $editId);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des articles | Administration</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-logo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-nav {
            list-style: none;
        }
        
        .sidebar-nav li {
            margin-bottom: 10px;
        }
        
        .sidebar-nav a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 4px;
            transition: background-color 0.3s;
            font-size: 14px;
        }
        
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-logout {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .main-content {
            flex: 1;
            overflow-y: auto;
        }
        
        .header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .header h1 {
            font-size: 24px;
            color: #333;
        }
        
        .content {
            padding: 30px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h2 {
            font-size: 18px;
            color: #333;
        }
        
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background-color: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #5568d3;
        }
        
        .btn-danger {
            background-color: #f093fb;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #da5fef;
        }
        
        .btn-secondary {
            background-color: #ddd;
            color: #333;
        }
        
        .btn-secondary:hover {
            background-color: #ccc;
        }
        
        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .alert {
            padding: 12px 20px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-error {
            background-color: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        
        .alert-success {
            background-color: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group textarea {
            min-height: 200px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background-color: #f9f9f9;
        }
        
        th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            border-bottom: 2px solid #eee;
        }
        
        td {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        
        tr:hover {
            background-color: #f9f9f9;
        }
        
        .article-title {
            font-weight: 500;
            color: #333;
        }
        
        .article-preview {
            color: #666;
            font-size: 12px;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        
        .form-container {
            padding: 20px;
        }
        
        .actions {
            display: flex;
            gap: 5px;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }
        
        .modal.show {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: #fefefe;
            padding: 40px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
        }
        
        .modal-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        
        .modal-body {
            margin-bottom: 20px;
            color: #666;
            font-size: 14px;
        }
        
        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-logo">🎯 Administration</div>
            
            <ul class="sidebar-nav">
                <li><a href="/admin/dashboard/">📊 Tableau de bord</a></li>
                <li><a href="/admin/articles/" class="active">📄 Gérer les articles</a></li>
                <li><a href="/admin/categories/">🏷️ Gérer les catégories</a></li>
            </ul>
            
            <div class="sidebar-logout">
                <a href="/back/logout.php" style="color: white; text-decoration: none; padding: 10px 15px; display: block; border-radius: 4px; background: rgba(255, 255, 255, 0.1);">🚪 Déconnexion</a>
            </div>
        </div>
        
        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="header">
                <h1>Gestion des articles</h1>
            </div>
            
            <div class="content">
                <!-- MESSAGES -->
                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo escapeHtml($message); ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo escapeHtml($error); ?></div>
                <?php endif; ?>
                
                <!-- FORMULAIRE AJOUT/EDITION -->
                <div class="card">
                    <div class="card-header">
                        <h2><?php echo $editId ? 'Modifier l\'article' : 'Ajouter un article'; ?></h2>
                    </div>
                    
                    <div class="form-container">
                        <form id="articleForm" method="POST" action="">
                            <input type="hidden" name="action" value="<?php echo $editId ? 'update' : 'create'; ?>">
                            <?php if ($editId): ?>
                                <input type="hidden" name="id" value="<?php echo $editId; ?>">
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="titre">Titre de l'article</label>
                                <input 
                                    type="text" 
                                    id="titre" 
                                    name="titre" 
                                    placeholder="Ex: Tensions en Iran"
                                    value="<?php echo $editArticle ? escapeHtml($editArticle['titre']) : ''; ?>"
                                    required
                                    autofocus
                                >
                            </div>
                            
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input 
                                    type="text" 
                                    id="slug" 
                                    name="slug" 
                                    placeholder="Ex: tensions-en-iran"
                                    value="<?php echo $editArticle ? escapeHtml($editArticle['slug']) : ''; ?>"
                                    required
                                >
                                <small style="color: #999; font-size: 12px;">Format: minuscules, tirets uniquement</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="id_categorie">Catégorie</label>
                                <select id="id_categorie" name="id_categorie" required>
                                    <option value="">-- Sélectionner une catégorie --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" 
                                            <?php echo ($editArticle && $editArticle['id_categorie'] == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo escapeHtml($cat['nom']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="contenu">Contenu</label>
                                <textarea 
                                    id="contenu" 
                                    name="contenu" 
                                    placeholder="Contenu de l'article..."
                                ><?php echo $editArticle ? escapeHtml($editArticle['contenu']) : ''; ?></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $editId ? 'Mettre à jour' : 'Créer'; ?>
                                </button>
                                <?php if ($editId): ?>
                                    <a href="?page=articles" class="btn btn-secondary">Annuler</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- LISTE DES ARTICLES -->
                <div class="card">
                    <div class="card-header">
                        <h2>Articles existants</h2>
                    </div>
                    
                    <?php if (!empty($articles)): ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Catégorie</th>
                                        <th>Prévisualisation</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($articles as $article): ?>
                                        <tr>
                                            <td class="article-title"><?php echo escapeHtml($article['titre']); ?></td>
                                            <td><?php echo escapeHtml($article['categorie_nom'] ?? 'Sans catégorie'); ?></td>
                                            <td class="article-preview"><?php echo escapeHtml(excerptFromHtml($article['contenu'], 100)); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($article['date_publication'])); ?></td>
                                            <td>
                                                <div class="actions">
                                                    <a href="?edit=<?php echo $article['id']; ?>" class="btn btn-secondary btn-small">Éditer</a>
                                                    <button class="btn btn-danger btn-small" onclick="openDeleteModal(<?php echo $article['id']; ?>, '<?php echo escapeHtml($article['titre']); ?>')">Supprimer</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>Aucun article trouvé. Créez-en un ci-dessus.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- MODAL DE CONFIRMATION DE SUPPRESSION -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Confirmer la suppression</div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer l'article "<span id="articleTitle"></span>" ?
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Annuler</button>
                <form method="POST" style="margin: 0; display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="articleId">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function openDeleteModal(id, title) {
            document.getElementById('deleteModal').classList.add('show');
            document.getElementById('articleId').value = id;
            document.getElementById('articleTitle').textContent = title;
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }
        
        // Générer le slug automatiquement
        document.getElementById('titre').addEventListener('input', function() {
            const titre = this.value;
            const slug = titre
                .toLowerCase()
                .replace(/[àâäá]/g, 'a')
                .replace(/[èéêë]/g, 'e')
                .replace(/[ìîïí]/g, 'i')
                .replace(/[òôöó]/g, 'o')
                .replace(/[ùûüú]/g, 'u')
                .replace(/ç/g, 'c')
                .replace(/[^a-z0-9-]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+|-+$/g, '');
            document.getElementById('slug').value = slug;
        });
    </script>

    <script src="https://cdn.tiny.cloud/1/76asbq6w8fd2frlh4vh05ptfhzkivrq7cd07sd96fcqndxg8/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
    <script>
        tinymce.init({
            selector: '#contenu',
            menubar: false,
            plugins: 'lists link image',
            toolbar: 'undo redo | blocks | bold italic underline | bullist numlist blockquote | link image',
            height: 320,
            branding: false,
            block_formats: 'Paragraphe=p; Titre 1=h1; Titre 2=h2; Titre 3=h3; Titre 4=h4; Titre 5=h5; Titre 6=h6',
            valid_elements: 'p,br,strong/b,em/i,u,h1,h2,h3,h4,h5,h6,ul,ol,li,blockquote,a[href|title|target|rel],img[src|alt|title|width|height|loading]'
        });

        const articleForm = document.getElementById('articleForm');
        if (articleForm) {
            articleForm.addEventListener('submit', function(event) {
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                    const editor = tinymce.get('contenu');

                    if (editor) {
                        const textContent = editor.getContent({ format: 'text' }).replace(/\s+/g, ' ').trim();
                        const hasImage = /<img\b/i.test(editor.getContent());

                        if (!textContent && !hasImage) {
                            event.preventDefault();
                            alert('Le contenu est requis.');
                            editor.focus();
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
