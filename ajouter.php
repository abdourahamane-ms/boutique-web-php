<?php
require_once 'functions.php';
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rediriger('index.php');
}

$idProduit = isset($_POST['id_produit']) ? (int) $_POST['id_produit'] : 0;
$quantite = isset($_POST['quantite']) ? (int) $_POST['quantite'] : 1;

if ($quantite < 1) {
    $quantite = 1;
}

$requete = $pdo->prepare('SELECT * FROM produit WHERE id_produit = ? AND actif = 1');
$requete->execute([$idProduit]);
$produit = $requete->fetch();

if (!$produit) {
    message('Produit introuvable.', 'erreur');
    rediriger('index.php');
}

if ($produit['stock'] <= 0) {
    message('Ce produit est en rupture de stock.', 'erreur');
    rediriger('produit.php?id=' . $idProduit);
}

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$quantiteActuelle = $_SESSION['panier'][$idProduit] ?? 0;
$nouvelleQuantite = $quantiteActuelle + $quantite;

if ($nouvelleQuantite > $produit['stock']) {
    $nouvelleQuantite = (int) $produit['stock'];
    message('La quantite a ete ajustee au stock disponible.', 'erreur');
} else {
    message('Produit ajoute au panier.');
}

$_SESSION['panier'][$idProduit] = $nouvelleQuantite;

rediriger('panier.php');
?>
