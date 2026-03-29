<?php
/**
 * Page de connexion - Administration
 */

session_start();

require_once __DIR__ . '/../../inc/db.php';
require_once __DIR__ . '/../../inc/helpers.php';

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
            if ($user && password_verify($password, $user['password'])) {
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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .alert {
            padding: 12px;
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
        
        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-primary {
            background-color: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #5568d3;
        }
        
        .btn-primary:active {
            transform: scale(0.98);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
        
        .credentials-info {
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 4px;
            padding: 12px;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        
        .credentials-info strong {
            color: #333;
        }
    </style>
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
