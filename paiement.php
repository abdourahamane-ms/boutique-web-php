<?php
require_once 'auth_check.php';
require_once 'connexion.php';

$panier = $_SESSION['panier'] ?? [];
$articles = [];
$total = 0;
$erreur = '';

foreach ($panier as $idProduit => $quantite) {
    $requete = $pdo->prepare('SELECT * FROM produit WHERE id_produit = ? AND actif = 1');
    $requete->execute([(int) $idProduit]);
    $produit = $requete->fetch();

    if ($produit && $quantite > 0) {
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

if (empty($articles)) {
    message('Votre panier est vide.', 'erreur');
    rediriger('panier.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomLivraison = trim($_POST['nom_livraison'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $modePaiement = $_POST['mode_paiement'] ?? 'Paiement a la livraison';

    if ($nomLivraison === '' || $adresse === '' || $ville === '' || $telephone === '') {
        $erreur = 'Veuillez remplir tous les champs de livraison.';
    } else {
        try {
            $pdo->beginTransaction();

            foreach ($articles as $article) {
                $requeteStock = $pdo->prepare('SELECT stock FROM produit WHERE id_produit = ? FOR UPDATE');
                $requeteStock->execute([$article['produit']['id_produit']]);
                $stockActuel = $requeteStock->fetch();

                if (!$stockActuel || $stockActuel['stock'] < $article['quantite']) {
                    throw new Exception('Stock insuffisant pour ' . $article['produit']['nom']);
                }
            }

            $adresseComplete = $adresse . ', ' . $ville;
            $requete = $pdo->prepare('INSERT INTO commande (id_utilisateur, date_commande, total, nom_livraison, adresse, telephone, mode_paiement, statut) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)');
            $requete->execute([
                $_SESSION['user_id'],
                $total,
                $nomLivraison,
                $adresseComplete,
                $telephone,
                $modePaiement,
                'En preparation'
            ]);

            $idCommande = $pdo->lastInsertId();

            foreach ($articles as $article) {
                $requete = $pdo->prepare('INSERT INTO ligne_commande (id_commande, id_produit, nom_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?, ?)');
                $requete->execute([
                    $idCommande,
                    $article['produit']['id_produit'],
                    $article['produit']['nom'],
                    $article['quantite'],
                    $article['produit']['prix']
                ]);

                $requete = $pdo->prepare('UPDATE produit SET stock = stock - ? WHERE id_produit = ?');
                $requete->execute([$article['quantite'], $article['produit']['id_produit']]);
            }

            $pdo->commit();
            unset($_SESSION['panier']);

            message('Commande enregistree avec succes.');
            rediriger('confirmation.php?id=' . $idCommande);
        } catch (Exception $e) {
            $pdo->rollBack();
            $erreur = $e->getMessage();
        }
    }
}

require_once 'header.php';
?>
<section class="page-header">
    <p class="sur-titre">Validation</p>
    <h1>Finaliser la commande</h1>
    <p>Le paiement est simule pour ce projet. Aucune carte bancaire n'est demandee.</p>
</section>

<div class="paiement-layout">
    <section class="form-card">
        <h2>Adresse de livraison</h2>

        <?php if ($erreur !== ''): ?>
            <div class="erreur"><?= e($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" class="formulaire">
            <label>Nom pour la livraison</label>
            <input type="text" name="nom_livraison" value="<?= e($_SESSION['user_nom'] ?? '') ?>" required>

            <label>Adresse</label>
            <input type="text" name="adresse" placeholder="Numero et nom de rue" required>

            <label>Ville</label>
            <input type="text" name="ville" placeholder="Ex : Paris" required>

            <label>Telephone</label>
            <input type="text" name="telephone" placeholder="06 00 00 00 00" required>

            <label>Mode de paiement</label>
            <select name="mode_paiement">
                <option>Paiement a la livraison</option>
                <option>Carte bancaire simulee</option>
                <option>Retrait en boutique</option>
            </select>

            <button type="submit">Confirmer la commande</button>
        </form>
    </section>

    <aside class="resume-card">
        <h2>Recapitulatif</h2>
        <?php foreach ($articles as $article): ?>
            <div class="recap-produit">
                <span><?= e($article['produit']['nom']) ?> x<?= (int) $article['quantite'] ?></span>
                <strong><?= prix($article['sous_total']) ?></strong>
            </div>
        <?php endforeach; ?>
        <div class="resume-ligne total"><span>Total</span><strong><?= prix($total) ?></strong></div>
    </aside>
</div>
<?php require_once 'footer.php'; ?>
