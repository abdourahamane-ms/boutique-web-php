<?php
require_once 'auth_check.php';
require_once 'connexion.php';

$idCommande = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$requete = $pdo->prepare('SELECT * FROM commande WHERE id_commande = ? AND id_utilisateur = ?');
$requete->execute([$idCommande, $_SESSION['user_id']]);
$commande = $requete->fetch();

if (!$commande) {
    message('Commande introuvable.', 'erreur');
    rediriger('compte.php');
}

$requete = $pdo->prepare('SELECT * FROM ligne_commande WHERE id_commande = ?');
$requete->execute([$idCommande]);
$lignes = $requete->fetchAll();

require_once 'header.php';
?>
<section class="page-header">
    <p class="sur-titre">Detail commande</p>
    <h1>Commande #<?= (int) $commande['id_commande'] ?></h1>
    <p>Statut actuel : <strong><?= e($commande['statut']) ?></strong></p>
</section>

<div class="paiement-layout">
    <section class="table-card">
        <h2>Articles</h2>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantite</th>
                    <th>Prix</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lignes as $ligne): ?>
                    <tr>
                        <td><?= e($ligne['nom_produit']) ?></td>
                        <td><?= (int) $ligne['quantite'] ?></td>
                        <td><?= prix($ligne['prix_unitaire']) ?></td>
                        <td><?= prix($ligne['prix_unitaire'] * $ligne['quantite']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <aside class="resume-card">
        <h2>Livraison</h2>
        <p><strong><?= e($commande['nom_livraison']) ?></strong></p>
        <p><?= e($commande['adresse']) ?></p>
        <p><?= e($commande['telephone']) ?></p>
        <div class="resume-ligne total"><span>Total</span><strong><?= prix($commande['total']) ?></strong></div>
    </aside>
</div>
<?php require_once 'footer.php'; ?>
