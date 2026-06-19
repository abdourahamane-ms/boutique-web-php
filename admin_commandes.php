<?php
require_once 'admin_check.php';
require_once 'connexion.php';

$statutFiltre = $_GET['statut'] ?? '';
$params = [];

$sql = 'SELECT commande.*, utilisateur.nom AS nom_client
        FROM commande
        INNER JOIN utilisateur ON commande.id_utilisateur = utilisateur.id_utilisateur';

if ($statutFiltre !== '') {
    $sql .= ' WHERE commande.statut = ?';
    $params[] = $statutFiltre;
}

$sql .= ' ORDER BY commande.date_commande DESC';

$requete = $pdo->prepare($sql);
$requete->execute($params);
$commandes = $requete->fetchAll();

$statuts = ['En preparation', 'Expediee', 'Livree', 'Annulee'];

require_once 'header.php';
?>
<section class="page-header admin-header">
    <p class="sur-titre">Administration</p>
    <h1>Commandes clients</h1>
    <p>Filtrer les commandes et modifier leur statut.</p>
</section>

<form method="GET" class="filtres petit-filtre">
    <select name="statut">
        <option value="">Tous les statuts</option>
        <?php foreach ($statuts as $statut): ?>
            <option value="<?= e($statut) ?>" <?= $statutFiltre === $statut ? 'selected' : '' ?>><?= e($statut) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filtrer</button>
    <a class="btn-reset" href="admin_commandes.php">Reset</a>
</form>

<section class="table-card">
    <?php if (empty($commandes)): ?>
        <div class="bloc-vide"><p>Aucune commande trouvee.</p></div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>Client</th>
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
                        <td><?= e($commande['nom_client']) ?></td>
                        <td><?= e($commande['date_commande']) ?></td>
                        <td><?= prix($commande['total']) ?></td>
                        <td><span class="badge-statut"><?= e($commande['statut']) ?></span></td>
                        <td><a class="btn-detail" href="admin_commande.php?id=<?= $commande['id_commande'] ?>">Ouvrir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
<?php require_once 'footer.php'; ?>
