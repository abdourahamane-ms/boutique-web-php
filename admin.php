<?php
require_once 'admin_check.php';
require_once 'connexion.php';

$nbProduits = $pdo->query('SELECT COUNT(*) AS total FROM produit')->fetch()['total'];
$nbCommandes = $pdo->query('SELECT COUNT(*) AS total FROM commande')->fetch()['total'];
$nbClients = $pdo->query("SELECT COUNT(*) AS total FROM utilisateur WHERE role = 'client'")->fetch()['total'];
$chiffre = $pdo->query('SELECT COALESCE(SUM(total), 0) AS total FROM commande')->fetch()['total'];

$commandes = $pdo->query('SELECT commande.*, utilisateur.nom AS nom_client
                          FROM commande
                          INNER JOIN utilisateur ON commande.id_utilisateur = utilisateur.id_utilisateur
                          ORDER BY commande.date_commande DESC LIMIT 5')->fetchAll();

require_once 'header.php';
?>
<section class="page-header admin-header">
    <p class="sur-titre">Administration</p>
    <h1>Tableau de bord</h1>
    <p>Vue rapide sur les produits, les clients et les commandes de la boutique.</p>
</section>

<section class="statistiques admin-stats">
    <article><strong><?= (int) $nbProduits ?></strong><span>produits</span></article>
    <article><strong><?= (int) $nbCommandes ?></strong><span>commandes</span></article>
    <article><strong><?= (int) $nbClients ?></strong><span>clients</span></article>
    <article><strong><?= prix($chiffre) ?></strong><span>ventes simulees</span></article>
</section>

<section class="admin-actions">
    <a class="btn-principal" href="admin_produits.php">Gerer les produits</a>
    <a class="btn-secondaire sombre" href="admin_commandes.php">Voir les commandes</a>
</section>

<section class="section-bloc">
    <div class="titre-ligne">
        <div>
            <p class="sur-titre">Dernieres commandes</p>
            <h2>Suivi rapide</h2>
        </div>
    </div>

    <?php if (empty($commandes)): ?>
        <div class="bloc-vide"><p>Aucune commande enregistree pour le moment.</p></div>
    <?php else: ?>
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Commande</th>
                        <th>Client</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td>#<?= (int) $commande['id_commande'] ?></td>
                            <td><?= e($commande['nom_client']) ?></td>
                            <td><?= prix($commande['total']) ?></td>
                            <td><span class="badge-statut"><?= e($commande['statut']) ?></span></td>
                            <td><a class="btn-detail" href="admin_commande.php?id=<?= $commande['id_commande'] ?>">Ouvrir</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require_once 'footer.php'; ?>
