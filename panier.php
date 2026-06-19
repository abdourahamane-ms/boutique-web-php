<?php
require_once 'connexion.php';
require_once 'functions.php';

$panier = $_SESSION['panier'] ?? [];
$articles = [];
$total = 0;

foreach ($panier as $idProduit => $quantite) {
    $requete = $pdo->prepare('SELECT * FROM produit WHERE id_produit = ? AND actif = 1');
    $requete->execute([(int) $idProduit]);
    $produit = $requete->fetch();

    if ($produit) {
        if ($quantite > $produit['stock']) {
            $quantite = (int) $produit['stock'];
            $_SESSION['panier'][$idProduit] = $quantite;
        }

        if ($quantite > 0) {
            $sousTotal = $produit['prix'] * $quantite;
            $total += $sousTotal;
            $articles[] = [
                'produit' => $produit,
                'quantite' => $quantite,
                'sous_total' => $sousTotal
            ];
        }
    }
}

require_once 'header.php';
?>
<section class="page-header">
    <p class="sur-titre">Commande</p>
    <h1>Mon panier</h1>
    <p>Modifiez les quantites avant de passer a la validation.</p>
</section>

<?php if (empty($articles)): ?>
    <div class="bloc-vide grand">
        <h2>Votre panier est vide</h2>
        <p>Ajoutez quelques produits depuis le catalogue.</p>
        <a class="btn-principal" href="index.php#catalogue">Retour a la boutique</a>
    </div>
<?php else: ?>
    <div class="panier-layout">
        <section class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantite</th>
                        <th>Sous-total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td class="td-produit">
                                <img src="<?= image_produit($article['produit']['image']) ?>" alt="<?= e($article['produit']['nom']) ?>">
                                <div>
                                    <strong><?= e($article['produit']['nom']) ?></strong>
                                    <span>Stock : <?= (int) $article['produit']['stock'] ?></span>
                                </div>
                            </td>
                            <td><?= prix($article['produit']['prix']) ?></td>
                            <td>
                                <form method="POST" action="maj_panier.php" class="quantite-form">
                                    <input type="hidden" name="id_produit" value="<?= $article['produit']['id_produit'] ?>">
                                    <input type="number" name="quantite" min="1" max="<?= (int) $article['produit']['stock'] ?>" value="<?= (int) $article['quantite'] ?>">
                                    <button name="action" value="modifier">OK</button>
                                </form>
                            </td>
                            <td><strong><?= prix($article['sous_total']) ?></strong></td>
                            <td>
                                <form method="POST" action="maj_panier.php">
                                    <input type="hidden" name="id_produit" value="<?= $article['produit']['id_produit'] ?>">
                                    <button class="btn-danger" name="action" value="supprimer">Retirer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <aside class="resume-card">
            <h2>Resume</h2>
            <div class="resume-ligne"><span>Articles</span><strong><?= count($articles) ?></strong></div>
            <div class="resume-ligne"><span>Total</span><strong><?= prix($total) ?></strong></div>
            <a class="btn-principal plein" href="paiement.php">Valider la commande</a>
            <form method="POST" action="maj_panier.php">
                <button class="btn-simple plein" name="action" value="vider">Vider le panier</button>
            </form>
        </aside>
    </div>
<?php endif; ?>
<?php require_once 'footer.php'; ?>
