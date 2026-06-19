<?php
require_once 'connexion.php';

if (!admin_connecte()) {
    header('Location: login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action == 'ajouter_produit') {
        $nom = trim($_POST['nom']);
        $description = trim($_POST['description']);
        $prix = floatval($_POST['prix']);
        $stock = intval($_POST['stock']);
        $categorie = intval($_POST['id_categorie']);

        if ($nom != '' && $prix > 0 && $stock >= 0) {
            $requete = $pdo->prepare("INSERT INTO produit(nom, description, prix, stock, image, id_categorie, actif)
                                     VALUES (?, ?, ?, ?, 'images/produit-defaut.svg', ?, 1)");
            $requete->execute(array($nom, $description, $prix, $stock, $categorie));
            $message = 'Produit ajouté au catalogue.';
        } else {
            $message = 'Le nom, le prix ou le stock est incorrect.';
        }
    }

    if ($action == 'changer_stock') {
        $id_produit = intval($_POST['id_produit']);
        $stock = intval($_POST['stock']);

        if ($stock >= 0) {
            $requete = $pdo->prepare("UPDATE produit SET stock = ? WHERE id_produit = ?");
            $requete->execute(array($stock, $id_produit));
            $message = 'Stock mis à jour.';
        }
    }

    if ($action == 'masquer_produit') {
        $id_produit = intval($_POST['id_produit']);
        $requete = $pdo->prepare("UPDATE produit SET actif = 0 WHERE id_produit = ?");
        $requete->execute(array($id_produit));
        $message = 'Produit masqué du catalogue.';
    }

    if ($action == 'changer_statut') {
        $id_commande = intval($_POST['id_commande']);
        $statut = $_POST['statut'];
        $requete = $pdo->prepare("UPDATE commande SET statut = ? WHERE id_commande = ?");
        $requete->execute(array($statut, $id_commande));
        $message = 'Statut de commande mis à jour.';
    }
}

$categories = $pdo->query("SELECT * FROM categorie ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

$produits = $pdo->query("SELECT produit.*, categorie.nom AS nom_categorie
                         FROM produit
                         LEFT JOIN categorie ON produit.id_categorie = categorie.id_categorie
                         ORDER BY produit.actif DESC, produit.nom ASC")->fetchAll(PDO::FETCH_ASSOC);

$commandes = $pdo->query("SELECT commande.*, utilisateur.nom AS nom_client
                          FROM commande
                          LEFT JOIN utilisateur ON commande.id_utilisateur = utilisateur.id_utilisateur
                          ORDER BY commande.date_commande DESC")->fetchAll(PDO::FETCH_ASSOC);

require_once 'header.php';
?>

<section class="bloc">
    <div class="ligne-titre">
        <div>
            <p class="petit-titre">Gestion simple</p>
            <h1>Administration</h1>
        </div>
        <a class="bouton-secondaire" href="index.php">Voir la boutique</a>
    </div>

    <p>J'ai volontairement gardé une seule page d'administration pour que le projet reste facile à comprendre.</p>

    <?php if ($message != '') { ?>
        <p class="message-simple"><?php echo proteger($message); ?></p>
    <?php } ?>
</section>

<section class="deux-colonnes">
    <div class="bloc">
        <h2>Ajouter un produit</h2>

        <form method="post" class="formulaire-simple">
            <input type="hidden" name="action" value="ajouter_produit">

            <label>Nom du produit</label>
            <input type="text" name="nom" required>

            <label>Description</label>
            <textarea name="description" rows="4"></textarea>

            <label>Prix</label>
            <input type="number" step="0.01" name="prix" required>

            <label>Stock</label>
            <input type="number" name="stock" value="1" required>

            <label>Catégorie</label>
            <select name="id_categorie">
                <?php foreach ($categories as $cat) { ?>
                    <option value="<?php echo $cat['id_categorie']; ?>"><?php echo proteger($cat['nom']); ?></option>
                <?php } ?>
            </select>

            <button type="submit">Ajouter le produit</button>
        </form>
    </div>

    <div class="bloc">
        <h2>Commandes</h2>

        <?php if (empty($commandes)) { ?>
            <p>Aucune commande pour le moment.</p>
        <?php } ?>

        <?php foreach ($commandes as $commande) { ?>
            <form method="post" class="ligne-admin">
                <input type="hidden" name="action" value="changer_statut">
                <input type="hidden" name="id_commande" value="<?php echo $commande['id_commande']; ?>">

                <span>#<?php echo $commande['id_commande']; ?> - <?php echo proteger($commande['nom_client']); ?> - <?php echo afficher_prix($commande['total']); ?></span>

                <select name="statut">
                    <option <?php if ($commande['statut'] == 'En préparation') echo 'selected'; ?>>En préparation</option>
                    <option <?php if ($commande['statut'] == 'Envoyée') echo 'selected'; ?>>Envoyée</option>
                    <option <?php if ($commande['statut'] == 'Terminée') echo 'selected'; ?>>Terminée</option>
                </select>

                <button type="submit">OK</button>
            </form>
        <?php } ?>
    </div>
</section>

<section class="bloc">
    <h2>Produits du catalogue</h2>

    <table class="tableau">
        <tr>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Prix</th>
            <th>Stock</th>
            <th>Action</th>
        </tr>
        <?php foreach ($produits as $produit) { ?>
            <tr>
                <td>
                    <?php echo proteger($produit['nom']); ?>
                    <?php if ($produit['actif'] == 0) { ?>
                        <span class="stock-vide">Masqué</span>
                    <?php } ?>
                </td>
                <td><?php echo proteger($produit['nom_categorie']); ?></td>
                <td><?php echo afficher_prix($produit['prix']); ?></td>
                <td>
                    <form method="post" class="formulaire-ligne">
                        <input type="hidden" name="action" value="changer_stock">
                        <input type="hidden" name="id_produit" value="<?php echo $produit['id_produit']; ?>">
                        <input type="number" name="stock" min="0" value="<?php echo $produit['stock']; ?>">
                        <button type="submit">OK</button>
                    </form>
                </td>
                <td>
                    <?php if ($produit['actif'] == 1) { ?>
                        <form method="post">
                            <input type="hidden" name="action" value="masquer_produit">
                            <input type="hidden" name="id_produit" value="<?php echo $produit['id_produit']; ?>">
                            <button class="bouton-danger" type="submit">Masquer</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</section>

<?php require_once 'footer.php'; ?>
