<?php
require_once 'connexion.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type_formulaire = isset($_POST['type_formulaire']) ? $_POST['type_formulaire'] : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';

    if ($type_formulaire == 'connexion') {
        $requete = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $requete->execute(array($email));
        $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            $_SESSION['utilisateur'] = $utilisateur;
            header('Location: index.php');
            exit;
        } else {
            $message = 'Email ou mot de passe incorrect.';
        }
    }

    if ($type_formulaire == 'inscription') {
        $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';

        if ($nom == '' || $email == '' || $mot_de_passe == '') {
            $message = 'Veuillez remplir tous les champs.';
        } else {
            $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $requete = $pdo->prepare("INSERT INTO utilisateur(nom, email, mot_de_passe, role) VALUES (?, ?, ?, 'client')");

            try {
                $requete->execute(array($nom, $email, $mot_de_passe_hash));
                $message = 'Compte créé. Vous pouvez maintenant vous connecter.';
            } catch (PDOException $erreur) {
                $message = 'Cet email est déjà utilisé.';
            }
        }
    }
}

require_once 'header.php';
?>

<section class="deux-colonnes">
    <div class="bloc">
        <p class="petit-titre">Espace client</p>
        <h1>Connexion</h1>

        <?php if ($message != '') { ?>
            <p class="message-simple"><?php echo proteger($message); ?></p>
        <?php } ?>

        <form method="post" class="formulaire-simple">
            <input type="hidden" name="type_formulaire" value="connexion">
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" required>
            <button type="submit">Se connecter</button>
        </form>

        <div class="aide-connexion">
            <p><strong>Compte admin :</strong> admin@novashop.test / admin123</p>
            <p><strong>Compte client :</strong> client@novashop.test / client123</p>
        </div>
    </div>

    <div class="bloc">
        <p class="petit-titre">Nouveau client</p>
        <h2>Créer un compte</h2>

        <form method="post" class="formulaire-simple">
            <input type="hidden" name="type_formulaire" value="inscription">
            <label>Nom</label>
            <input type="text" name="nom" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" required>
            <button type="submit">Créer mon compte</button>
        </form>
    </div>
</section>

<?php require_once 'footer.php'; ?>
