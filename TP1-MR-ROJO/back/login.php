<?php
/**
 * Page de connexion - Administration
 */

session_start();

require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/helpers.php';

// Rediriger si déjà connecté
if (isAuthenticated()) {
    header('Location: /admin/dashboard/');
    exit;
}

$error = '';
$success = '';

// Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Validation
    if (empty($email) || empty($password)) {
        $error = "Email et mot de passe requis";
    } else {
        try {
            // Récupérer l'utilisateur
            $sql = "SELECT id, email, password, nom, role FROM utilisateur WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();
            
            // Vérifier les identifiants
            if ($user ) {
                // Authentification réussie
                session_regenerate_id(true); // Sécurité : régénérer l'ID session
                $_SESSION['admin'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'nom' => $user['nom'],
                    'role' => $user['role']
                ];
                
                header('Location: /admin/dashboard/');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect";
            }
        } catch (Exception $e) {
            $error = "Erreur lors de la connexion : " . $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration — Connexion | Guerre en Iran</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="/assets/css/back/login.css">
</head>
<body>
    <div class="login-container">
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
    </div>
</body>
</html>
