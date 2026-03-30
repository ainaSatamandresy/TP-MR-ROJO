<?php
/**
 * Gestion des catégories - BackOffice
 */

session_start();
require_once __DIR__ . '/../../inc/db.php';
require_once __DIR__ . '/../../inc/helpers.php';
require_once __DIR__ . '/../functions/categories.php';

requireAuth();

$state = processAdminCategoriesPage($pdo, $_POST, $_GET);
$categories = $state['categories'];
$message = $state['message'];
$error = $state['error'];
$editId = $state['editId'];
$editCategory = $state['editCategory'];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des catégories | Administration</title>
    <meta name="description" content="Interface d'administration pour gérer les catégories du site et leur organisation.">
    <meta name="robots" content="index, follow">
    <link rel="stylesheet" href="/assets/css/back/common.css">
    <link rel="stylesheet" href="/assets/css/back/categories.css">
</head>
<body>
    <div class="admin-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-logo">
                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                <span>Administration</span>
            </div>
            
            <ul class="sidebar-nav">
                <li><a href="/admin/dashboard/"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z"/></svg><span class="sidebar-link-label">Tableau de bord</span></a></li>
                <li><a href="/admin/articles/"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M6 2h9l5 5v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm8 1.5V8h4.5L14 3.5zM8 11h8v2H8v-2zm0 4h8v2H8v-2z"/></svg><span class="sidebar-link-label">Gerer les articles</span></a></li>
                <li><a href="/admin/categories/" class="active"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M10 3H4a1 1 0 0 0-1 1v6l9 9a1 1 0 0 0 1.4 0l5.6-5.6a1 1 0 0 0 0-1.4L10 3zm-3 5a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg><span class="sidebar-link-label">Gerer les categories</span></a></li>
            </ul>
            
            <div class="sidebar-logout">
                <a href="/back/logout.php" class="sidebar-logout-link"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M10 17l1.4-1.4L8.8 13H20v-2H8.8l2.6-2.6L10 7l-5 5 5 5zM4 4h8V2H4a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h8v-2H4V4z"/></svg><span class="sidebar-link-label">Deconnexion</span></a>
            </div>
        </div>
        
        <!-- MAIN CONTENT -->
        <main class="main-content" id="main-content">
            <div class="header">
                <h1>Gestion des catégories</h1>
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
                        <h2><?php echo $editId ? 'Modifier la catégorie' : 'Ajouter une catégorie'; ?></h2>
                    </div>
                    
                    <div class="form-container">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="<?php echo $editId ? 'update' : 'create'; ?>">
                            <?php if ($editId): ?>
                                <input type="hidden" name="id" value="<?php echo $editId; ?>">
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="nom">Nom de la catégorie</label>
                                <input 
                                    type="text" 
                                    id="nom" 
                                    name="nom" 
                                    placeholder="Ex: Politique"
                                    value="<?php echo $editCategory ? escapeHtml($editCategory['nom']) : ''; ?>"
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
                                    placeholder="Ex: politique"
                                    value="<?php echo $editCategory ? escapeHtml($editCategory['slug']) : ''; ?>"
                                    required
                                >
                                <small class="form-help-text">Format: minuscules, tirets uniquement</small>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $editId ? 'Mettre à jour' : 'Créer'; ?>
                                </button>
                                <?php if ($editId): ?>
                                    <a href="?page=categories" class="btn btn-secondary">Annuler</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- LISTE DES CATÉGORIES -->
                <div class="card">
                    <div class="card-header">
                        <h2>Catégories existantes</h2>
                    </div>
                    
                    <?php if (!empty($categories)): ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Slug</th>
                                        <th>Articles</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $cat): ?>
                                        <tr>
                                            <td><?php echo escapeHtml($cat['nom']); ?></td>
                                            <td><code><?php echo escapeHtml($cat['slug']); ?></code></td>
                                            <td><?php echo (int)$cat['article_count']; ?></td>
                                            <td>
                                                <div class="actions">
                                                    <a href="?edit=<?php echo $cat['id']; ?>" class="btn btn-secondary btn-small">Éditer</a>
                                                    <button class="btn btn-danger btn-small" onclick="openDeleteModal(<?php echo $cat['id']; ?>, '<?php echo escapeHtml($cat['nom']); ?>')">Supprimer</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>Aucune catégorie trouvée. Créez-en une ci-dessus.</p>
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
                Êtes-vous sûr de vouloir supprimer la catégorie "<span id="categoryName"></span>" ?
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Annuler</button>
                <form method="POST" class="inline-form">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="categoryId">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function openDeleteModal(id, name) {
            document.getElementById('deleteModal').classList.add('show');
            document.getElementById('deleteModal').setAttribute('aria-hidden', 'false');
            document.getElementById('categoryId').value = id;
            document.getElementById('categoryName').textContent = name;
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            document.getElementById('deleteModal').setAttribute('aria-hidden', 'true');
        }
        
        // Générer le slug automatiquement (optionnel)
        document.getElementById('nom').addEventListener('input', function() {
            const nom = this.value;
            const slug = nom
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
</body>
</html>
