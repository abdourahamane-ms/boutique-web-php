<?php
require_once 'connexion.php';

if (!utilisateur_connecte()) {
    header('Location: login.php');
    exit;
}

$requete = $pdo->prepare("SELECT * FROM commande WHERE id_utilisateur = ? ORDER BY date_commande DESC");
$requete->execute(array($_SESSION['utilisateur']['id_utilisateur']));
$commandes = $requete->fetchAll(PDO::FETCH_ASSOC);

require_once 'header.php';
?>

<section class="bloc">
    <div class="ligne-titre">
        <div>
            <p class="petit-titre">Espace client</p>
            <h1>Mes commandes</h1>
        </div>
        <a class="bouton-secondaire" href="index.php">Retour boutique</a>
    </div>

    <?php if (isset($_GET['commande'])) { ?>
        <p class="message-succes">Votre commande a bien été enregistrée.</p>
    <?php } ?>

    <?php if (empty($commandes)) { ?>
        <div class="message-simple">
            Vous n'avez pas encore passé de commande.
        </div>
    <?php } else { ?>
        <table class="tableau">
            <tr>
                <th>Numéro</th>
                <th>Date</th>
                <th>Total</th>
                <th>Statut</th>
            </tr>
            <?php foreach ($commandes as $commande) { ?>
                <tr>
                    <td>#<?php echo $commande['id_commande']; ?></td>
                    <td><?php echo $commande['date_commande']; ?></td>
                    <td><?php echo afficher_prix($commande['total']); ?></td>
                    <td><span class="badge-statut"><?php echo proteger($commande['statut']); ?></span></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</section>

<?php require_once 'footer.php'; ?>
