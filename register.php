<?php
require_once 'connexion.php';
require_once 'functions.php';

if (est_connecte()) {
    rediriger('index.php');
}

$erreur = '';
$nom = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $motdepasse = $_POST['motdepasse'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';

    if ($nom === '' || $email === '' || $motdepasse === '' || $confirmation === '') {
        $erreur = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Adresse email invalide.';
    } elseif (strlen($motdepasse) < 6) {
        $erreur = 'Le mot de passe doit contenir au moins 6 caracteres.';
    } elseif ($motdepasse !== $confirmation) {
        $erreur = 'Les deux mots de passe ne correspondent pas.';
    } else {
        $hash = password_hash($motdepasse, PASSWORD_DEFAULT);

        try {
            $requete = $pdo->prepare('INSERT INTO utilisateur (nom, email, mot_de_passe, role, date_creation) VALUES (?, ?, ?, ?, NOW())');
            $requete->execute([$nom, $email, $hash, 'client']);
            message('Compte cree. Vous pouvez vous connecter.');
            rediriger('login.php');
        } catch (PDOException $e) {
            $erreur = 'Cet email est deja utilise.';
        }
    }
}

require_once 'header.php';
?>
<section class="auth-page">
    <div class="auth-card">
        <p class="sur-titre">Inscription</p>
        <h1>Creer un compte client</h1>
        <p>Un compte permet de commander et de retrouver l'historique des achats.</p>

        <?php if ($erreur !== ''): ?>
            <div class="erreur"><?= e($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" class="formulaire">
            <label>Nom complet</label>
            <input type="text" name="nom" value="<?= e($nom) ?>" placeholder="Votre nom" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= e($email) ?>" placeholder="exemple@mail.com" required>

            <label>Mot de passe</label>
            <input type="password" name="motdepasse" required>

            <label>Confirmer le mot de passe</label>
            <input type="password" name="confirmation" required>

            <button type="submit">S'inscrire</button>
        </form>

        <p class="lien-form">Deja inscrit ? <a href="login.php">Se connecter</a></p>
    </div>
</section>
<?php require_once 'footer.php'; ?>
