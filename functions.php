<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($texte) {
    return htmlspecialchars($texte ?? '', ENT_QUOTES, 'UTF-8');
}

function prix($montant) {
    return number_format((float) $montant, 2, ',', ' ') . ' €';
}

function est_connecte() {
    return isset($_SESSION['user_id']);
}

function est_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function nombre_articles_panier() {
    if (!isset($_SESSION['panier'])) {
        return 0;
    }
    return array_sum($_SESSION['panier']);
}

function rediriger($page) {
    header('Location: ' . $page);
    exit;
}

function message($texte, $type = 'succes') {
    $_SESSION['message'] = $texte;
    $_SESSION['message_type'] = $type;
}

function lire_message() {
    if (!isset($_SESSION['message'])) {
        return '';
    }

    $type = $_SESSION['message_type'] ?? 'succes';
    $texte = $_SESSION['message'];

    unset($_SESSION['message'], $_SESSION['message_type']);

    return '<div class="message ' . e($type) . '">' . e($texte) . '</div>';
}

function image_produit($chemin) {
    if ($chemin !== '' && file_exists($chemin)) {
        return e($chemin);
    }
    return 'images/produit-defaut.svg';
}
?>
