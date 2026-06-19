<?php
require_once 'connexion.php';
require_once 'functions.php';

if (est_connecte()) {
    rediriger('index.php');
}

$erreur = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $motdepasse = $_POST['motdepasse'] ?? '';

    $requete = $pdo->prepare('SELECT * FROM utilisateur WHERE email = ?');
    $requete->execute([$email]);
    $utilisateur = $requete->fetch();

    if ($utilisateur && password_verify($motdepasse, $utilisateur['mot_de_passe'])) {
        $_SESSION['user_id'] = $utilisateur['id_utilisateur'];
        $_SESSION['user_nom'] = $utilisateur['nom'];
        $_SESSION['user_role'] = $utilisateur['role'];
        message('Connexion reussie.');
        rediriger('index.php');
    } else {
        $erreur = 'Email ou mot de passe incorrect.';
    }
}

require_once 'header.php';
?>
<section class="auth-page">
    <div class="auth-card">
        <p class="sur-titre">Connexion</p>
        <h1>Acceder a mon compte</h1>
        <p>Connectez-vous pour passer une commande ou acceder au tableau de bord admin.</p>

        <?php if ($erreur !== ''): ?>
            <div class="erreur"><?= e($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" class="formulaire">
            <label>Email</label>
            <input type="email" name="email" value="<?= e($email) ?>" placeholder="exemple@mail.com" required>

            <label>Mot de passe</label>
            <input type="password" name="motdepasse" placeholder="Votre mot de passe" required>

            <button type="submit">Se connecter</button>
        </form>

        <p class="lien-form">Pas encore de compte ? <a href="register.php">Creer un compte</a></p>
        <div class="aide-demo">
            <p><strong>Compte demo client :</strong> client@novashop.test / client123</p>
            <p><strong>Compte demo admin :</strong> admin@novashop.test / admin123</p>
        </div>
    </div>
</section>
<?php require_once 'footer.php'; ?>
