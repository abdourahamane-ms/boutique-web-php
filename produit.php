<?php
require_once 'connexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$requete = $pdo->prepare("SELECT produit.*, categorie.nom AS nom_categorie
                         FROM produit
                         LEFT JOIN categorie ON produit.id_categorie = categorie.id_categorie
                         WHERE produit.id_produit = ? AND produit.actif = 1");
$requete->execute(array($id));
$produit = $requete->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die('Produit introuvable.');
}

require_once 'header.php';
?>

<section class="bloc fiche-produit">
    <div class="image-fiche">
        <img src="<?php echo proteger($produit['image']); ?>" alt="<?php echo proteger($produit['nom']); ?>">
    </div>

    <div class="details-fiche">
        <p class="etiquette-categorie"><?php echo proteger($produit['nom_categorie']); ?></p>
        <h1><?php echo proteger($produit['nom']); ?></h1>
        <p><?php echo proteger($produit['description']); ?></p>
        <p class="gros-prix"><?php echo afficher_prix($produit['prix']); ?></p>

        <?php if ($produit['stock'] > 0) { ?>
            <p class="stock-ok">Stock disponible : <?php echo $produit['stock']; ?></p>
            <form method="post" action="panier.php" class="formulaire-ligne">
                <input type="hidden" name="action" value="ajouter">
                <input type="hidden" name="id_produit" value="<?php echo $produit['id_produit']; ?>">
                <label>Quantité</label>
                <input type="number" name="quantite" min="1" max="<?php echo $produit['stock']; ?>" value="1">
                <button type="submit">Ajouter au panier</button>
            </form>
        <?php } else { ?>
            <p class="stock-vide">Ce produit est actuellement en rupture de stock.</p>
        <?php } ?>

        <a class="lien-retour" href="index.php">← Retour au catalogue</a>
    </div>
</section>

<?php require_once 'footer.php'; ?>
