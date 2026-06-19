<?php
require_once 'auth_check.php';
require_once 'connexion.php';

$requete = $pdo->prepare('SELECT * FROM commande WHERE id_utilisateur = ? ORDER BY date_commande DESC');
$requete->execute([$_SESSION['user_id']]);
$commandes = $requete->fetchAll();

require_once 'header.php';
?>
<section class="page-header">
    <p class="sur-titre">Espace client</p>
    <h1>Bonjour <?= e($_SESSION['user_nom']) ?></h1>
    <p>Ici, le client peut retrouver ses anciennes commandes et suivre leur statut.</p>
</section>

<section class="section-bloc">
    <div class="titre-ligne">
        <div>
            <p class="sur-titre">Historique</p>
            <h2>Mes commandes</h2>
        </div>
        <a href="index.php#catalogue">Continuer mes achats</a>
    </div>

    <?php if (empty($commandes)): ?>
        <div class="bloc-vide">
            <h3>Aucune commande pour le moment</h3>
            <p>Votre historique apparaitra ici apres votre premier achat.</p>
        </div>
    <?php else: ?>
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td>#<?= (int) $commande['id_commande'] ?></td>
                            <td><?= e($commande['date_commande']) ?></td>
                            <td><?= prix($commande['total']) ?></td>
                            <td><span class="badge-statut"><?= e($commande['statut']) ?></span></td>
                            <td><a class="btn-detail" href="commande.php?id=<?= $commande['id_commande'] ?>">Voir</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require_once 'footer.php'; ?>
