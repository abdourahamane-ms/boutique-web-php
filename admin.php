<?php
require_once "auth_check.php";
require_once "connexion.php";

if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "admin") {
    $_SESSION["message"] = "Acces reserve a l'administrateur.";
    header("Location: index.php");
    exit;
}

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajouter_produit"])) {
    $nom = trim($_POST["nom"]);
    $description = trim($_POST["description"]);
    $prix = (float) $_POST["prix"];
    $stock = (int) $_POST["stock"];
    $image = trim($_POST["image"]);

    if ($nom === "" || $description === "" || $prix <= 0 || $stock < 0) {
        $erreur = "Veuillez remplir correctement les informations du produit.";
    } else {
        $requete = $pdo->prepare("INSERT INTO produit (nom, description, prix, image, stock) VALUES (?, ?, ?, ?, ?)");
        $requete->execute([$nom, $description, $prix, $image, $stock]);
        $_SESSION["message"] = "Produit ajoute avec succes.";
        header("Location: admin.php");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["modifier_stock"])) {
    $idProduit = (int) $_POST["id_produit"];
    $stock = (int) $_POST["stock"];

    if ($stock >= 0) {
        $requete = $pdo->prepare("UPDATE produit SET stock = ? WHERE id_produit = ?");
        $requete->execute([$stock, $idProduit]);
        $_SESSION["message"] = "Stock mis a jour.";
    }

    header("Location: admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["supprimer_produit"])) {
    $idProduit = (int) $_POST["id_produit"];

    $requete = $pdo->prepare("DELETE FROM produit WHERE id_produit = ?");
    $requete->execute([$idProduit]);

    $_SESSION["message"] = "Produit supprime.";
    header("Location: admin.php");
    exit;
}

$produits = $pdo->query("SELECT * FROM produit ORDER BY id_produit DESC")->fetchAll();
$commandes = $pdo->query("SELECT commande.*, utilisateur.nom AS nom_client FROM commande INNER JOIN utilisateur ON commande.id_utilisateur = utilisateur.id_utilisateur ORDER BY date_commande DESC")->fetchAll();

require_once "header.php";
?>
<section class="section-title">
    <h1>Espace administrateur</h1>
    <p>Gestion simple des produits et consultation des commandes.</p>
</section>

<?php if ($erreur !== ""): ?>
    <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<section class="admin-grille">
    <div class="table-card">
        <h2>Ajouter un produit</h2>
        <form method="POST" class="form">
            <input type="hidden" name="ajouter_produit" value="1">

            <label>Nom du produit</label>
            <input type="text" name="nom" required>

            <label>Description</label>
            <textarea name="description" rows="4" required></textarea>

            <label>Prix</label>
            <input type="number" step="0.01" name="prix" required>

            <label>Stock</label>
            <input type="number" name="stock" required>

            <label>Image</label>
            <input type="text" name="image" placeholder="images/nom-image.jpg">

            <button type="submit">Ajouter le produit</button>
        </form>
    </div>

    <div class="table-card">
        <h2>Resume</h2>
        <p><strong><?= count($produits) ?></strong> produits dans la boutique.</p>
        <p><strong><?= count($commandes) ?></strong> commandes enregistrees.</p>
        <p>Cette page reste volontairement simple pour pouvoir etre expliquee facilement.</p>
    </div>
</section>

<section class="table-card admin-section">
    <h2>Produits</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $produit): ?>
                <tr>
                    <td><?= htmlspecialchars($produit["nom"]) ?></td>
                    <td><?= number_format($produit["prix"], 2, ',', ' ') ?> EUR</td>
                    <td>
                        <form method="POST" class="form-admin-ligne">
                            <input type="hidden" name="modifier_stock" value="1">
                            <input type="hidden" name="id_produit" value="<?= $produit["id_produit"] ?>">
                            <input type="number" name="stock" value="<?= $produit["stock"] ?>" min="0">
                            <button type="submit">OK</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="supprimer_produit" value="1">
                            <input type="hidden" name="id_produit" value="<?= $produit["id_produit"] ?>">
                            <button type="submit" class="btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<section class="table-card admin-section">
    <h2>Dernieres commandes</h2>
    <?php if (empty($commandes)): ?>
        <p>Aucune commande pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Livraison</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td>#<?= $commande["id_commande"] ?></td>
                        <td><?= htmlspecialchars($commande["nom_client"]) ?></td>
                        <td><?= htmlspecialchars($commande["date_commande"]) ?></td>
                        <td><?= number_format($commande["total"], 2, ',', ' ') ?> EUR</td>
                        <td><?= htmlspecialchars($commande["adresse"]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
<?php require_once "footer.php"; ?>
