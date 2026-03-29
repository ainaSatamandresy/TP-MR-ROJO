<?php
/**
 * Logique metier de la page de connexion admin.
 */

function processAdminLogin(PDO $pdo, array $postData): array {
    $email = trim((string) ($postData['email'] ?? ''));
    $password = trim((string) ($postData['password'] ?? ''));

    if ($email === '' || $password === '') {
        return ['error' => 'Email et mot de passe requis', 'success' => ''];
    }

    try {
        $sql = 'SELECT id, email, password, nom, role FROM utilisateur WHERE email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['error' => 'Email ou mot de passe incorrect', 'success' => ''];
        }

        // Compatible hash (recommande) et valeur legacy en clair.
        $storedPassword = (string) ($user['password'] ?? '');
        $isValid = password_verify($password, $storedPassword) || hash_equals($storedPassword, $password);

        if (!$isValid) {
            return ['error' => 'Email ou mot de passe incorrect', 'success' => ''];
        }

        session_regenerate_id(true);
        $_SESSION['admin'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'nom' => $user['nom'],
            'role' => $user['role']
        ];

        header('Location: /admin/dashboard/');
        exit;
    } catch (Exception $e) {
        return ['error' => 'Erreur lors de la connexion : ' . $e->getMessage(), 'success' => ''];
    }
}
