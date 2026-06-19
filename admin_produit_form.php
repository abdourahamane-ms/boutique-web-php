<?php
require_once 'admin_check.php';
require_once 'connexion.php';

$idProduit = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$edition = $idProduit > 0;
$erreur = '';

$categories = $pdo->query('SELECT * FROM categorie ORDER BY nom')->fetchAll();

$produit = [
    'nom' => '',
    'description' => '',
    'prix' => '',
    'stock' => '',
    'image' => '',
    'id_categorie' => '',
    'actif' => 1
];

if ($edition) {
    $requete = $pdo->prepare('SELECT * FROM produit WHERE id_produit = ?');
    $requete->execute([$idProduit]);
    $produitTrouve = $requete->fetch();

    if (!$produitTrouve) {
        message('Produit introuvable.', 'erreur');
        rediriger('admin_produits.php');
    }

    $produit = $produitTrouve;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = (float) ($_POST['prix'] ?? 0);
    $stock = (int) ($_POST['stock'] ?? 0);
    $image = trim($_POST['image'] ?? '');
    $idCategorie = (int) ($_POST['id_categorie'] ?? 0);
    $actif = isset($_POST['actif']) ? 1 : 0;

    if ($nom === '' || $description === '' || $prix <= 0 || $stock < 0 || $idCategorie <= 0) {
        $erreur = 'Veuillez remplir correctement tous les champs.';
        $produit = [
            'nom' => $nom,
            'description' => $description,
            'prix' => $prix,
            'stock' => $stock,
            'image' => $image,
            'id_categorie' => $idCategorie,
            'actif' => $actif
        ];
    } else {
        if ($edition) {
            $requete = $pdo->prepare('UPDATE produit SET nom = ?, description = ?, prix = ?, stock = ?, image = ?, id_categorie = ?, actif = ? WHERE id_produit = ?');
            $requete->execute([$nom, $description, $prix, $stock, $image, $idCategorie, $actif, $idProduit]);
            message('Produit modifie.');
        } else {
            $requete = $pdo->prepare('INSERT INTO produit (nom, description, prix, stock, image, id_categorie, actif, date_ajout) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
            $requete->execute([$nom, $description, $prix, $stock, $image, $idCategorie, $actif]);
            message('Produit ajoute.');
        }

        rediriger('admin_produits.php');
    }
}

require_once 'header.php';
?>
<section class="page-header admin-header">
    <p class="sur-titre">Administration</p>
    <h1><?= $edition ? 'Modifier un produit' : 'Ajouter un produit' ?></h1>
    <p>Formulaire volontairement simple, base sur HTML, PHP et MySQL.</p>
</section>

<section class="form-card large">
    <?php if ($erreur !== ''): ?>
        <div class="erreur"><?= e($erreur) ?></div>
    <?php endif; ?>

    <form method="POST" class="formulaire">
        <label>Nom du produit</label>
        <input type="text" name="nom" value="<?= e($produit['nom']) ?>" required>

        <label>Description</label>
        <textarea name="description" rows="5" required><?= e($produit['description']) ?></textarea>

        <div class="form-grid">
            <div>
                <label>Prix</label>
                <input type="number" step="0.01" min="0" name="prix" value="<?= e($produit['prix']) ?>" required>
            </div>
            <div>
                <label>Stock</label>
                <input type="number" min="0" name="stock" value="<?= e($produit['stock']) ?>" required>
            </div>
        </div>

        <label>Categorie</label>
        <select name="id_categorie" required>
            <option value="">Choisir</option>
            <?php foreach ($categories as $categorie): ?>
                <option value="<?= $categorie['id_categorie'] ?>" <?= (int) $produit['id_categorie'] === (int) $categorie['id_categorie'] ? 'selected' : '' ?>>
                    <?= e($categorie['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Image</label>
        <input type="text" name="image" value="<?= e($produit['image']) ?>" placeholder="images/nom-image.svg">
        <p class="aide-champ">Dans ce projet, les images sont des fichiers SVG deja places dans le dossier images.</p>

        <label class="check-ligne grande">
            <input type="checkbox" name="actif" <?= $produit['actif'] ? 'checked' : '' ?>> Produit visible dans la boutique
        </label>

        <div class="actions-page">
            <button type="submit">Enregistrer</button>
            <a class="btn-secondaire sombre" href="admin_produits.php">Annuler</a>
        </div>
    </form>
</section>
<?php require_once 'footer.php'; ?>
