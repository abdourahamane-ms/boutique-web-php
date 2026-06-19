<?php
require_once 'functions.php';
$nombrePanier = nombre_articles_panier();
$pageActuelle = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NovaShop - Boutique high-tech</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <a href="index.php" class="logo">Nova<span>Shop</span></a>

        <nav class="menu">
            <a class="<?= $pageActuelle === 'index.php' ? 'actif' : '' ?>" href="index.php">Boutique</a>
            <a class="<?= $pageActuelle === 'a-propos.php' ? 'actif' : '' ?>" href="a-propos.php">A propos</a>
            <?php if (est_connecte()): ?>
                <a class="<?= $pageActuelle === 'panier.php' ? 'actif' : '' ?>" href="panier.php">Panier (<?= $nombrePanier ?>)</a>
                <a class="<?= $pageActuelle === 'compte.php' ? 'actif' : '' ?>" href="compte.php">Mon compte</a>
                <?php if (est_admin()): ?>
                    <a class="admin-link <?= substr($pageActuelle, 0, 5) === 'admin' ? 'actif' : '' ?>" href="admin.php">Admin</a>
                <?php endif; ?>
                <a href="logout.php">Deconnexion</a>
            <?php else: ?>
                <a class="<?= $pageActuelle === 'login.php' ? 'actif' : '' ?>" href="login.php">Connexion</a>
                <a class="bouton-menu" href="register.php">Inscription</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<?= lire_message(); ?>

<main class="container">
