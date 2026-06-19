<?php
require_once 'connexion.php';

$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';
$categorie = isset($_GET['categorie']) ? intval($_GET['categorie']) : 0;
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'nom';

$sql = "SELECT produit.*, categorie.nom AS nom_categorie
        FROM produit
        LEFT JOIN categorie ON produit.id_categorie = categorie.id_categorie
        WHERE produit.actif = 1";
$params = array();

if ($recherche != '') {
    $sql = $sql . " AND (produit.nom LIKE :recherche OR produit.description LIKE :recherche)";
    $params['recherche'] = '%' . $recherche . '%';
}

if ($categorie > 0) {
    $sql = $sql . " AND produit.id_categorie = :categorie";
    $params['categorie'] = $categorie;
}

if ($tri == 'prix_croissant') {
    $sql = $sql . " ORDER BY produit.prix ASC";
} elseif ($tri == 'prix_decroissant') {
    $sql = $sql . " ORDER BY produit.prix DESC";
} else {
    $sql = $sql . " ORDER BY produit.nom ASC";
}

$requete = $pdo->prepare($sql);
$requete->execute($params);
$produits = $requete->fetchAll(PDO::FETCH_ASSOC);

$categories = $pdo->query("SELECT * FROM categorie ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

require_once 'header.php';
?>

<section class="bandeau-accueil">
    <div>
        <p class="petit-titre">Boutique informatique étudiante</p>
        <h1>Des produits simples pour travailler, coder et s'équiper.</h1>
        <p class="texte-bandeau">NovaShop est une boutique fictive, mais construite comme un vrai petit site : catalogue, panier, compte client et suivi des commandes.</p>
        <div class="actions-bandeau">
            <a class="bouton" href="#catalogue">Voir le catalogue</a>
            <a class="bouton-secondaire" href="panier.php">Voir mon panier</a>
        </div>
    </div>

    <div class="carte-bandeau">
        <span>Projet personnel</span>
        <strong>PHP / MySQL</strong>
        <p>Sans JavaScript, sans framework, avec un code que je peux expliquer simplement.</p>
    </div>
</section>

<section class="zone-infos">
    <div class="info-boutique">
        <strong>Catalogue</strong>
        <p>Produits rangés par catégories.</p>
    </div>
    <div class="info-boutique">
        <strong>Panier</strong>
        <p>Ajout et modification des quantités.</p>
    </div>
    <div class="info-boutique">
        <strong>Compte client</strong>
        <p>Connexion et historique des commandes.</p>
    </div>
</section>

<section class="bloc" id="catalogue">
    <div class="ligne-titre">
        <div>
            <p class="petit-titre">Nos produits</p>
            <h2>Catalogue</h2>
        </div>
        <p><?php echo count($produits); ?> produit(s) trouvé(s)</p>
    </div>

    <form method="get" class="formulaire-filtres">
        <input type="text" name="recherche" placeholder="Rechercher un produit" value="<?php echo proteger($recherche); ?>">

        <select name="categorie">
            <option value="0">Toutes les catégories</option>
            <?php foreach ($categories as $cat) { ?>
                <option value="<?php echo $cat['id_categorie']; ?>" <?php if ($categorie == $cat['id_categorie']) echo 'selected'; ?>>
                    <?php echo proteger($cat['nom']); ?>
                </option>
            <?php } ?>
        </select>

        <select name="tri">
            <option value="nom" <?php if ($tri == 'nom') echo 'selected'; ?>>Trier par nom</option>
            <option value="prix_croissant" <?php if ($tri == 'prix_croissant') echo 'selected'; ?>>Prix croissant</option>
            <option value="prix_decroissant" <?php if ($tri == 'prix_decroissant') echo 'selected'; ?>>Prix décroissant</option>
        </select>

        <button type="submit">Filtrer</button>
    </form>

    <?php if (empty($produits)) { ?>
        <div class="message-simple">Aucun produit ne correspond à votre recherche.</div>
    <?php } else { ?>
        <div class="grille-produits">
            <?php foreach ($produits as $produit) { ?>
                <article class="carte-produit">
                    <a href="produit.php?id=<?php echo $produit['id_produit']; ?>">
                        <img src="<?php echo proteger($produit['image']); ?>" alt="<?php echo proteger($produit['nom']); ?>">
                    </a>
                    <p class="etiquette-categorie"><?php echo proteger($produit['nom_categorie']); ?></p>
                    <h3><?php echo proteger($produit['nom']); ?></h3>
                    <p class="description-produit"><?php echo proteger(substr($produit['description'], 0, 95)); ?>...</p>

                    <div class="prix-stock">
                        <strong><?php echo afficher_prix($produit['prix']); ?></strong>
                        <?php if ($produit['stock'] > 0) { ?>
                            <span class="stock-ok">Stock : <?php echo $produit['stock']; ?></span>
                        <?php } else { ?>
                            <span class="stock-vide">Rupture</span>
                        <?php } ?>
                    </div>

                    <div class="actions-produit">
                        <a class="lien-detail" href="produit.php?id=<?php echo $produit['id_produit']; ?>">Détails</a>
                        <form method="post" action="panier.php">
                            <input type="hidden" name="action" value="ajouter">
                            <input type="hidden" name="id_produit" value="<?php echo $produit['id_produit']; ?>">
                            <button type="submit" <?php if ($produit['stock'] <= 0) echo 'disabled'; ?>>Ajouter</button>
                        </form>
                    </div>
                </article>
            <?php } ?>
        </div>
    <?php } ?>
</section>

<?php require_once 'footer.php'; ?>
