<?php
/**
 * Page de connexion - Administration
 */

session_start();

require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/functions/login.php';

// Rediriger si déjà connecté
if (isAuthenticated()) {
    header('Location: /admin/dashboard/');
    exit;
}

$error = '';
$success = '';

// Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginResult = processAdminLogin($pdo, $_POST);
    $error = $loginResult['error'] ?? '';
    $success = $loginResult['success'] ?? '';
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration — Connexion | Guerre en Iran</title>
    <meta name="description" content="Connexion a l'espace d'administration pour gérer les contenus du site.">
    <meta name="robots" content="index, follow">
    <link rel="stylesheet" href="/assets/css/back/login.css">
</head>
<body>
    <main class="login-container" id="main-content">
        <div class="login-header">
            <h1>Administration</h1>
            <p>Connexion au tableau de bord</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo escapeHtml($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo escapeHtml($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="admin@example.com"
                    required
                    autofocus
                >
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required
                >
            </div>
            
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        
        <div class="credentials-info">
            <strong>Identifiants par défaut :</strong><br>
            Email : <strong>admin@example.com</strong><br>
            Mot de passe : <strong>admin123</strong>
        </div>
        
        <div class="login-footer">
            <p>© 2026 - Actualités Guerre en Iran</p>
        </div>
    </main>
</body>
</html>
