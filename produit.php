<?php
require_once 'connexion.php';
require_once 'functions.php';

$idProduit = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$requete = $pdo->prepare("SELECT produit.*, categorie.nom AS nom_categorie
                         FROM produit
                         LEFT JOIN categorie ON produit.id_categorie = categorie.id_categorie
                         WHERE produit.id_produit = ? AND produit.actif = 1");
$requete->execute([$idProduit]);
$produit = $requete->fetch();

if (!$produit) {
    message('Produit introuvable.', 'erreur');
    rediriger('index.php');
}

$requete = $pdo->prepare("SELECT * FROM produit
                         WHERE id_categorie = ? AND id_produit != ? AND actif = 1
                         ORDER BY id_produit DESC LIMIT 3");
$requete->execute([$produit['id_categorie'], $idProduit]);
$similaires = $requete->fetchAll();

require_once 'header.php';
?>
<section class="detail-produit">
    <div class="detail-image">
        <img src="<?= image_produit($produit['image']) ?>" alt="<?= e($produit['nom']) ?>">
    </div>
    <div class="detail-infos">
        <a class="retour" href="index.php#catalogue">← Retour au catalogue</a>
        <span class="categorie"><?= e($produit['nom_categorie']) ?></span>
        <h1><?= e($produit['nom']) ?></h1>
        <p class="description-longue"><?= nl2br(e($produit['description'])) ?></p>

        <div class="prix-detail"><?= prix($produit['prix']) ?></div>

        <?php if ($produit['stock'] > 0): ?>
            <p class="stock ok">Disponible - <?= (int) $produit['stock'] ?> en stock</p>
            <form method="POST" action="ajouter.php" class="form-ajout-detail">
                <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
                <label>Quantite</label>
                <input type="number" name="quantite" value="1" min="1" max="<?= (int) $produit['stock'] ?>">
                <button type="submit">Ajouter au panier</button>
            </form>
        <?php else: ?>
            <p class="stock vide">Produit actuellement indisponible.</p>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($similaires)): ?>
<section class="section-bloc">
    <div class="titre-ligne">
        <div>
            <p class="sur-titre">Dans la meme categorie</p>
            <h2>Produits similaires</h2>
        </div>
    </div>
    <div class="grille-mini">
        <?php foreach ($similaires as $item): ?>
            <a class="mini-produit lien-mini" href="produit.php?id=<?= $item['id_produit'] ?>">
                <img src="<?= image_produit($item['image']) ?>" alt="<?= e($item['nom']) ?>">
                <div>
                    <h3><?= e($item['nom']) ?></h3>
                    <p><?= prix($item['prix']) ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
<?php require_once 'footer.php'; ?>
