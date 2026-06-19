<?php
require_once 'admin_check.php';
require_once 'connexion.php';

$idCommande = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$statuts = ['En preparation', 'Expediee', 'Livree', 'Annulee'];

$requete = $pdo->prepare('SELECT commande.*, utilisateur.nom AS nom_client, utilisateur.email
                         FROM commande
                         INNER JOIN utilisateur ON commande.id_utilisateur = utilisateur.id_utilisateur
                         WHERE commande.id_commande = ?');
$requete->execute([$idCommande]);
$commande = $requete->fetch();

if (!$commande) {
    message('Commande introuvable.', 'erreur');
    rediriger('admin_commandes.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveauStatut = $_POST['statut'] ?? $commande['statut'];

    if (in_array($nouveauStatut, $statuts)) {
        $requete = $pdo->prepare('UPDATE commande SET statut = ? WHERE id_commande = ?');
        $requete->execute([$nouveauStatut, $idCommande]);
        message('Statut mis a jour.');
        rediriger('admin_commande.php?id=' . $idCommande);
    }
}

$requete = $pdo->prepare('SELECT * FROM ligne_commande WHERE id_commande = ?');
$requete->execute([$idCommande]);
$lignes = $requete->fetchAll();

require_once 'header.php';
?>
<section class="page-header admin-header">
    <p class="sur-titre">Administration</p>
    <h1>Commande #<?= (int) $commande['id_commande'] ?></h1>
    <p>Client : <?= e($commande['nom_client']) ?> - <?= e($commande['email']) ?></p>
</section>

<div class="paiement-layout">
    <section class="table-card">
        <h2>Articles commandes</h2>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantite</th>
                    <th>Prix unitaire</th>
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
        <h2>Suivi</h2>
        <p><strong>Total :</strong> <?= prix($commande['total']) ?></p>
        <p><strong>Livraison :</strong><br><?= e($commande['adresse']) ?></p>
        <p><strong>Telephone :</strong> <?= e($commande['telephone']) ?></p>

        <form method="POST" class="formulaire mini-form">
            <label>Statut</label>
            <select name="statut">
                <?php foreach ($statuts as $statut): ?>
                    <option value="<?= e($statut) ?>" <?= $commande['statut'] === $statut ? 'selected' : '' ?>><?= e($statut) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Mettre a jour</button>
        </form>
    </aside>
</div>
<?php require_once 'footer.php'; ?>
