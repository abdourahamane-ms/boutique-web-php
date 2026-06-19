<?php
require_once 'functions.php';
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rediriger('panier.php');
}

$action = $_POST['action'] ?? '';
$idProduit = isset($_POST['id_produit']) ? (int) $_POST['id_produit'] : 0;

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if ($action === 'vider') {
    unset($_SESSION['panier']);
    message('Panier vide.');
    rediriger('panier.php');
}

if (!isset($_SESSION['panier'][$idProduit])) {
    rediriger('panier.php');
}

if ($action === 'supprimer') {
    unset($_SESSION['panier'][$idProduit]);
    message('Produit retire du panier.');
    rediriger('panier.php');
}

$quantite = isset($_POST['quantite']) ? (int) $_POST['quantite'] : 1;

$requete = $pdo->prepare('SELECT stock FROM produit WHERE id_produit = ?');
$requete->execute([$idProduit]);
$produit = $requete->fetch();

if (!$produit || $quantite <= 0) {
    unset($_SESSION['panier'][$idProduit]);
} else {
    if ($quantite > $produit['stock']) {
        $quantite = (int) $produit['stock'];
        message('Quantite ajustee au stock disponible.', 'erreur');
    } else {
        message('Panier mis a jour.');
    }
    $_SESSION['panier'][$idProduit] = $quantite;
}

rediriger('panier.php');
?>
