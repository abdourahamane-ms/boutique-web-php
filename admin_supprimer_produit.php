<?php
require_once 'admin_check.php';
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rediriger('admin_produits.php');
}

$idProduit = isset($_POST['id_produit']) ? (int) $_POST['id_produit'] : 0;

$requete = $pdo->prepare('UPDATE produit SET actif = 0 WHERE id_produit = ?');
$requete->execute([$idProduit]);

message('Produit masque de la boutique.');
rediriger('admin_produits.php');
?>
