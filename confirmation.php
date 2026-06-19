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
<section class="confirmation-card">
    <p class="sur-titre">Merci pour votre commande</p>
    <h1>Commande #<?= (int) $commande['id_commande'] ?> validee</h1>
    <p>Votre commande a bien ete enregistree dans la base de donnees.</p>

    <div class="confirmation-grid">
        <div>
            <span>Statut</span>
            <strong><?= e($commande['statut']) ?></strong>
        </div>
        <div>
            <span>Total</span>
            <strong><?= prix($commande['total']) ?></strong>
        </div>
        <div>
            <span>Mode de paiement</span>
            <strong><?= e($commande['mode_paiement']) ?></strong>
        </div>
    </div>

    <h2>Articles commandes</h2>
    <div class="liste-simple">
        <?php foreach ($lignes as $ligne): ?>
            <p><?= e($ligne['nom_produit']) ?> — x<?= (int) $ligne['quantite'] ?> — <?= prix($ligne['prix_unitaire']) ?></p>
        <?php endforeach; ?>
    </div>

    <div class="actions-page">
        <a class="btn-principal" href="compte.php">Voir mes commandes</a>
        <a class="btn-secondaire sombre" href="index.php">Retour boutique</a>
    </div>
</section>
<?php require_once 'footer.php'; ?>
