<?php
require_once 'connexion.php';

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $id_produit = isset($_POST['id_produit']) ? intval($_POST['id_produit']) : 0;
    $quantite = isset($_POST['quantite']) ? intval($_POST['quantite']) : 1;

    if ($action == 'ajouter' && $id_produit > 0) {
        if (!isset($_SESSION['panier'][$id_produit])) {
            $_SESSION['panier'][$id_produit] = 0;
        }
        $_SESSION['panier'][$id_produit] = $_SESSION['panier'][$id_produit] + max(1, $quantite);
        header('Location: panier.php');
        exit;
    }

    if ($action == 'modifier' && $id_produit > 0) {
        if ($quantite <= 0) {
            unset($_SESSION['panier'][$id_produit]);
        } else {
            $_SESSION['panier'][$id_produit] = $quantite;
        }
        header('Location: panier.php');
        exit;
    }

    if ($action == 'vider') {
        $_SESSION['panier'] = array();
        header('Location: panier.php');
        exit;
    }

    if ($action == 'commander') {
        if (!utilisateur_connecte()) {
            header('Location: login.php');
            exit;
        }

        if (!empty($_SESSION['panier'])) {
            $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';

            if ($adresse == '') {
                $message_commande = 'Veuillez indiquer une adresse de livraison.';
            } else {
                $ids = array_keys($_SESSION['panier']);
                $placeholders = implode(',', array_fill(0, count($ids), '?'));

                $requete = $pdo->prepare("SELECT * FROM produit WHERE id_produit IN ($placeholders) AND actif = 1");
                $requete->execute($ids);
                $produits_commande = $requete->fetchAll(PDO::FETCH_ASSOC);

                $total = 0;
                $stock_correct = true;

                foreach ($produits_commande as $produit) {
                    $qte = $_SESSION['panier'][$produit['id_produit']];

                    if ($qte > $produit['stock']) {
                        $stock_correct = false;
                    }

                    $total = $total + ($produit['prix'] * $qte);
                }

                if ($stock_correct) {
                    $pdo->beginTransaction();

                    $ajout_commande = $pdo->prepare("INSERT INTO commande(id_utilisateur, total, adresse_livraison, statut) VALUES (?, ?, ?, 'En préparation')");
                    $ajout_commande->execute(array($_SESSION['utilisateur']['id_utilisateur'], $total, $adresse));
                    $id_commande = $pdo->lastInsertId();

                    $ajout_ligne = $pdo->prepare("INSERT INTO ligne_commande(id_commande, id_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
                    $maj_stock = $pdo->prepare("UPDATE produit SET stock = stock - ? WHERE id_produit = ?");

                    foreach ($produits_commande as $produit) {
                        $qte = $_SESSION['panier'][$produit['id_produit']];
                        $ajout_ligne->execute(array($id_commande, $produit['id_produit'], $qte, $produit['prix']));
                        $maj_stock->execute(array($qte, $produit['id_produit']));
                    }

                    $pdo->commit();
                    $_SESSION['panier'] = array();
                    header('Location: mes_commandes.php?commande=ok');
                    exit;
                } else {
                    $message_commande = 'Une quantité demandée dépasse le stock disponible.';
                }
            }
        }
    }
}

$produits_panier = array();
$total_panier = 0;

if (!empty($_SESSION['panier'])) {
    $ids = array_keys($_SESSION['panier']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $requete = $pdo->prepare("SELECT * FROM produit WHERE id_produit IN ($placeholders)");
    $requete->execute($ids);
    $produits_panier = $requete->fetchAll(PDO::FETCH_ASSOC);

    foreach ($produits_panier as $produit) {
        $qte = $_SESSION['panier'][$produit['id_produit']];
        $total_panier = $total_panier + ($produit['prix'] * $qte);
    }
}

require_once 'header.php';
?>

<section class="bloc">
    <div class="ligne-titre">
        <div>
            <p class="petit-titre">Votre sélection</p>
            <h1>Mon panier</h1>
        </div>
        <a class="bouton-secondaire" href="index.php">Continuer les achats</a>
    </div>

    <?php if (isset($message_commande)) { ?>
        <p class="message-erreur"><?php echo proteger($message_commande); ?></p>
    <?php } ?>

    <?php if (empty($produits_panier)) { ?>
        <div class="panier-vide">
            <h2>Votre panier est vide.</h2>
            <p>Ajoutez un produit depuis le catalogue pour commencer une commande.</p>
            <a class="bouton" href="index.php">Retourner à la boutique</a>
        </div>
    <?php } else { ?>
        <table class="tableau">
            <tr>
                <th>Produit</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Sous-total</th>
            </tr>
            <?php foreach ($produits_panier as $produit) {
                $qte = $_SESSION['panier'][$produit['id_produit']];
            ?>
                <tr>
                    <td><?php echo proteger($produit['nom']); ?></td>
                    <td><?php echo afficher_prix($produit['prix']); ?></td>
                    <td>
                        <form method="post" class="formulaire-ligne">
                            <input type="hidden" name="action" value="modifier">
                            <input type="hidden" name="id_produit" value="<?php echo $produit['id_produit']; ?>">
                            <input type="number" name="quantite" min="0" value="<?php echo $qte; ?>">
                            <button type="submit">OK</button>
                        </form>
                    </td>
                    <td><?php echo afficher_prix($produit['prix'] * $qte); ?></td>
                </tr>
            <?php } ?>
        </table>

        <div class="bloc-total">
            <form method="post">
                <input type="hidden" name="action" value="vider">
                <button class="bouton-danger" type="submit">Vider le panier</button>
            </form>
            <strong>Total : <?php echo afficher_prix($total_panier); ?></strong>
        </div>

        <div class="bloc-formulaire">
            <h2>Valider la commande</h2>

            <?php if (!utilisateur_connecte()) { ?>
                <p>Vous devez vous connecter avant de commander.</p>
                <a class="bouton" href="login.php">Se connecter</a>
            <?php } else { ?>
                <form method="post" class="formulaire-simple">
                    <input type="hidden" name="action" value="commander">
                    <label>Adresse de livraison</label>
                    <textarea name="adresse" rows="4" placeholder="Ex : 12 rue de Paris, 75000 Paris" required></textarea>
                    <button type="submit">Confirmer la commande</button>
                </form>
            <?php } ?>
        </div>
    <?php } ?>
</section>

<?php require_once 'footer.php'; ?>
