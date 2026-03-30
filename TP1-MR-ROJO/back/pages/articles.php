<?php
/**
 * Gestion des articles - BackOffice
 */

session_start();
require_once __DIR__ . '/../../inc/db.php';
require_once __DIR__ . '/../../inc/helpers.php';
require_once __DIR__ . '/../functions/articles.php';

requireAuth();

$state = processAdminArticlesPage($pdo, $_POST, $_GET);
$articles = $state['articles'];
$categories = $state['categories'];
$message = $state['message'];
$error = $state['error'];
$editId = $state['editId'];
$editArticle = $state['editArticle'];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des articles | Administration</title>
    <meta name="description" content="Interface d'administration pour créer, modifier et supprimer les articles du site.">
    <meta name="robots" content="index, follow">
    <link rel="stylesheet" href="/assets/css/back/common.css">
    <link rel="stylesheet" href="/assets/css/back/articles.css">
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
                <a href="/back/logout.php" class="sidebar-logout-link">🚪 Déconnexion</a>
            </div>
        </div>
        
        <!-- MAIN CONTENT -->
        <main class="main-content" id="main-content">
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
                        <form id="articleForm" method="POST" action="" enctype="multipart/form-data">
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
                                <small class="form-help-text">Format: minuscules, tirets uniquement</small>
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
                                <label for="image">Image de l'article</label>
                                <input 
                                    type="file" 
                                    id="image" 
                                    name="image" 
                                    accept="image/jpeg,image/png,image/gif,image/webp"
                                >
                                <small class="form-help-text">Formats acceptés: JPEG, PNG, GIF, WebP (max 5MB)</small>
                                <?php if ($editArticle && !empty($editArticle['image'])): ?>
                                    <div class="image-preview">
                                        <p>Image actuelle:</p>
                                        <img src="/assets/images/articles/<?php echo escapeHtml($editArticle['image']); ?>" alt="<?php echo escapeHtml($editArticle['titre']); ?>" style="max-width: 200px; max-height: 200px;" loading="lazy" decoding="async">
                                    </div>
                                <?php endif; ?>
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
                                        <th>Image</th>
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
                                            <td class="article-image">
                                                <?php if (!empty($article['image'])): ?>
                                                    <img src="/assets/images/articles/<?php echo escapeHtml($article['image']); ?>" alt="<?php echo escapeHtml($article['titre']); ?>" class="article-thumbnail" loading="lazy" decoding="async">
                                                <?php else: ?>
                                                    <span class="no-image">Pas d'image</span>
                                                <?php endif; ?>
                                            </td>
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
        </main>
    </div>
    
    <!-- MODAL DE CONFIRMATION DE SUPPRESSION -->
    <div id="deleteModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle" aria-describedby="deleteModalDescription" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header" id="deleteModalTitle">Confirmer la suppression</div>
            <div class="modal-body" id="deleteModalDescription">
                Êtes-vous sûr de vouloir supprimer l'article "<span id="articleTitle"></span>" ?
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Annuler</button>
                <form method="POST" class="inline-form">
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
            document.getElementById('deleteModal').setAttribute('aria-hidden', 'false');
            document.getElementById('articleId').value = id;
            document.getElementById('articleTitle').textContent = title;
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            document.getElementById('deleteModal').setAttribute('aria-hidden', 'true');
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

    <script src="https://cdn.tiny.cloud/1/76asbq6w8fd2frlh4vh05ptfhzkivrq7cd07sd96fcqndxg8/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous" defer></script>
    <script>
        function initTinyMceEditor() {
            if (typeof tinymce === 'undefined') {
                return;
            }

            tinymce.init({
                selector: '#contenu',
                menubar: false,
                plugins: 'lists link image',
                toolbar: 'undo redo | blocks | bold italic underline | bullist numlist blockquote | link image',
                height: 320,
                branding: false,
                aria_label: 'Editeur de contenu de l article',
                iframe_attrs: {
                    title: 'Editeur de contenu'
                },
                block_formats: 'Paragraphe=p; Titre 1=h1; Titre 2=h2; Titre 3=h3; Titre 4=h4; Titre 5=h5; Titre 6=h6',
                valid_elements: 'p,br,strong/b,em/i,u,h1,h2,h3,h4,h5,h6,ul,ol,li,blockquote,a[href|title|target|rel],img[src|alt|title|width|height|loading]'
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTinyMceEditor);
        } else {
            initTinyMceEditor();
        }

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
