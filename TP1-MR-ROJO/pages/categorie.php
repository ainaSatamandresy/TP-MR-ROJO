<?php
/**
 * Page legacy: redirection vers la nouvelle structure front/pages.
 */

$slug = trim((string) ($_GET['slug'] ?? ''));

if ($slug !== '') {
    header('Location: /categorie/' . rawurlencode($slug), true, 301);
    exit;
}

header('Location: /?page=categorie', true, 301);
exit;
