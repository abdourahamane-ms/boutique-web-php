<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NovaShop - Boutique informatique</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="barre-haut">
    <div class="conteneur navigation">
        <a class="logo" href="index.php">Nova<span>Shop</span></a>

        <nav class="menu">
            <a href="index.php">Boutique</a>
            <a href="panier.php">Panier (<?php echo nombre_articles_panier(); ?>)</a>

            <?php if (utilisateur_connecte()) { ?>
                <a href="mes_commandes.php">Mes commandes</a>
                <?php if (admin_connecte()) { ?>
                    <a class="lien-admin" href="admin.php">Admin</a>
                <?php } ?>
                <a href="logout.php">Déconnexion</a>
            <?php } else { ?>
                <a class="bouton-menu" href="login.php">Connexion</a>
            <?php } ?>
        </nav>
    </div>
</header>

<main class="conteneur">
