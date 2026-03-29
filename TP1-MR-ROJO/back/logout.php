<?php
/**
 * Déconnexion
 */

session_start();

// Détruire la session
$_SESSION = [];
session_destroy();

// Rediriger vers la page de connexion
header('Location: /admin/');
exit;
?>
