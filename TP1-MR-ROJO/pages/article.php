<?php
/**
 * Page legacy: redirection vers la nouvelle structure front/pages.
 */

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$slug = trim((string) ($_GET['slug'] ?? ''));

if ($id > 0 && $slug !== '') {
    header('Location: /article/' . $id . '/' . rawurlencode($slug), true, 301);
    exit;
}

$params = ['page' => 'article'];

if ($id > 0) {
    $params['id'] = $id;
}

if ($slug !== '') {
    $params['slug'] = $slug;
}

$query = http_build_query($params);

header('Location: /' . ($query !== '' ? ('?' . $query) : ''), true, 301);
exit;
