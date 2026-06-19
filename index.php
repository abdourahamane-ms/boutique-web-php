<?php
require_once 'connexion.php';
require_once 'functions.php';

$recherche = trim($_GET['recherche'] ?? '');
$categorie = isset($_GET['categorie']) ? (int) $_GET['categorie'] : 0;
$tri = $_GET['tri'] ?? 'recent';
$stockSeulement = isset($_GET['stock']);

$categories = $pdo->query('SELECT * FROM categorie ORDER BY nom')->fetchAll();

$sql = "SELECT produit.*, categorie.nom AS nom_categorie
        FROM produit
        LEFT JOIN categorie ON produit.id_categorie = categorie.id_categorie
        WHERE produit.actif = 1";
$params = [];

if ($recherche !== '') {
    $sql .= " AND (produit.nom LIKE ? OR produit.description LIKE ?)";
    $params[] = '%' . $recherche . '%';
    $params[] = '%' . $recherche . '%';
}

if ($categorie > 0) {
    $sql .= " AND produit.id_categorie = ?";
    $params[] = $categorie;
}

if ($stockSeulement) {
    $sql .= " AND produit.stock > 0";
}

if ($tri === 'prix_asc') {
    $sql .= " ORDER BY produit.prix ASC";
} elseif ($tri === 'prix_desc') {
    $sql .= " ORDER BY produit.prix DESC";
} elseif ($tri === 'nom') {
    $sql .= " ORDER BY produit.nom ASC";
} else {
    $sql .= " ORDER BY produit.id_produit DESC";
}

$requete = $pdo->prepare($sql);
$requete->execute($params);
$produits = $requete->fetchAll();

$selection = $pdo->query("SELECT * FROM produit WHERE actif = 1 AND stock > 0 ORDER BY id_produit DESC LIMIT 3")->fetchAll();

require_once 'header.php';
?>
<section class="hero boutique-hero">
    <div class="hero-texte">
        <p class="badge">Boutique high-tech</p>
        <h1>Du materiel utile pour travailler, jouer et etudier.</h1>
        <p>
            NovaShop est une petite boutique en ligne construite en PHP et MySQL. Le but est de proposer un vrai parcours client : catalogue, recherche, panier, commande et suivi.
        </p>
        <div class="hero-actions">
            <a class="btn-principal" href="#catalogue">Voir le catalogue</a>
            <a class="btn-secondaire" href="a-propos.php">Lire le projet</a>
        </div>
    </div>
    <div class="hero-card">
        <span>Livraison simulee</span>
        <strong>48h</strong>
        <p>Projet demo, pense comme une vraie boutique mais sans paiement reel.</p>
    </div>
</section>

<section class="statistiques">
    <article><strong><?= count($produits) ?></strong><span>produits affiches</span></article>
    <article><strong><?= count($categories) ?></strong><span>categories</span></article>
    <article><strong><?= $nombrePanier ?></strong><span>article(s) panier</span></article>
</section>

<?php if (!empty($selection)): ?>
<section class="section-bloc">
    <div class="titre-ligne">
        <div>
            <p class="sur-titre">Selection rapide</p>
            <h2>Nouveautes disponibles</h2>
        </div>
        <a href="#catalogue">Tout voir</a>
    </div>
    <div class="grille-mini">
        <?php foreach ($selection as $produit): ?>
            <article class="mini-produit">
                <img src="<?= image_produit($produit['image']) ?>" alt="<?= e($produit['nom']) ?>">
                <div>
                    <h3><?= e($produit['nom']) ?></h3>
                    <p><?= prix($produit['prix']) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section id="catalogue" class="section-bloc">
    <div class="titre-ligne">
        <div>
            <p class="sur-titre">Catalogue</p>
            <h2>Rechercher un produit</h2>
        </div>
    </div>

    <form method="GET" class="filtres">
        <input type="text" name="recherche" value="<?= e($recherche) ?>" placeholder="Ex : clavier, casque, ordinateur...">

        <select name="categorie">
            <option value="0">Toutes les categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id_categorie'] ?>" <?= $categorie === (int) $cat['id_categorie'] ? 'selected' : '' ?>>
                    <?= e($cat['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="tri">
            <option value="recent" <?= $tri === 'recent' ? 'selected' : '' ?>>Plus recents</option>
            <option value="prix_asc" <?= $tri === 'prix_asc' ? 'selected' : '' ?>>Prix croissant</option>
            <option value="prix_desc" <?= $tri === 'prix_desc' ? 'selected' : '' ?>>Prix decroissant</option>
            <option value="nom" <?= $tri === 'nom' ? 'selected' : '' ?>>Nom du produit</option>
        </select>

        <label class="check-ligne">
            <input type="checkbox" name="stock" <?= $stockSeulement ? 'checked' : '' ?>> En stock
        </label>

        <button type="submit">Filtrer</button>
        <a class="btn-reset" href="index.php#catalogue">Reset</a>
    </form>

    <?php if (empty($produits)): ?>
        <div class="bloc-vide">
            <h3>Aucun produit trouve</h3>
            <p>Essayez avec un autre mot ou une autre categorie.</p>
        </div>
    <?php else: ?>
        <div class="grille-produits">
            <?php foreach ($produits as $produit): ?>
                <article class="carte-produit">
                    <a href="produit.php?id=<?= $produit['id_produit'] ?>" class="image-lien">
                        <img src="<?= image_produit($produit['image']) ?>" alt="<?= e($produit['nom']) ?>">
                    </a>
                    <div class="produit-contenu">
                        <span class="categorie"><?= e($produit['nom_categorie']) ?></span>
                        <h3><?= e($produit['nom']) ?></h3>
                        <p><?= e(substr($produit['description'], 0, 95)) ?>...</p>
                        <div class="produit-bas">
                            <strong><?= prix($produit['prix']) ?></strong>
                            <?php if ($produit['stock'] > 0): ?>
                                <span class="stock ok">Stock : <?= (int) $produit['stock'] ?></span>
                            <?php else: ?>
                                <span class="stock vide">Indisponible</span>
                            <?php endif; ?>
                        </div>
                        <div class="actions-produit">
                            <a class="btn-detail" href="produit.php?id=<?= $produit['id_produit'] ?>">Details</a>
                            <form method="POST" action="ajouter.php">
                                <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
                                <input type="hidden" name="quantite" value="1">
                                <button type="submit" <?= $produit['stock'] <= 0 ? 'disabled' : '' ?>>Ajouter</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require_once 'footer.php'; ?>
