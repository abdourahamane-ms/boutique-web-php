<?php
require_once 'admin_check.php';
require_once 'connexion.php';

$produits = $pdo->query('SELECT produit.*, categorie.nom AS nom_categorie
                         FROM produit
                         LEFT JOIN categorie ON produit.id_categorie = categorie.id_categorie
                         ORDER BY produit.id_produit DESC')->fetchAll();

require_once 'header.php';
?>
<section class="page-header admin-header">
    <p class="sur-titre">Administration</p>
    <h1>Gestion des produits</h1>
    <p>Ajouter, modifier ou masquer les produits visibles dans la boutique.</p>
</section>

<div class="actions-page">
    <a class="btn-principal" href="admin_produit_form.php">Ajouter un produit</a>
    <a class="btn-secondaire sombre" href="admin.php">Retour admin</a>
</div>

<section class="table-card">
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Categorie</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Etat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $produit): ?>
                <tr>
                    <td class="td-produit">
                        <img src="<?= image_produit($produit['image']) ?>" alt="<?= e($produit['nom']) ?>">
                        <strong><?= e($produit['nom']) ?></strong>
                    </td>
                    <td><?= e($produit['nom_categorie']) ?></td>
                    <td><?= prix($produit['prix']) ?></td>
                    <td><?= (int) $produit['stock'] ?></td>
                    <td><?= $produit['actif'] ? 'Visible' : 'Masque' ?></td>
                    <td class="td-actions">
                        <a class="btn-detail" href="admin_produit_form.php?id=<?= $produit['id_produit'] ?>">Modifier</a>
                        <form method="POST" action="admin_supprimer_produit.php" class="form-inline">
                            <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
                            <button class="btn-danger" type="submit">Masquer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php require_once 'footer.php'; ?>
