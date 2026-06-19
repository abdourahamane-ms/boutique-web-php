<?php
session_start();

$nom_serveur = 'localhost';
$nom_base = 'boutique_web_php';
$nom_utilisateur = 'root';
$mot_de_passe_base = '';

try {
    $pdo = new PDO(
        "mysql:host=$nom_serveur;dbname=$nom_base;charset=utf8",
        $nom_utilisateur,
        $mot_de_passe_base
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $erreur) {
    die('Erreur de connexion a la base de donnees : ' . $erreur->getMessage());
}

function proteger($texte) {
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}

function afficher_prix($montant) {
    return number_format($montant, 2, ',', ' ') . ' €';
}

function utilisateur_connecte() {
    return isset($_SESSION['utilisateur']);
}

function admin_connecte() {
    return utilisateur_connecte() && $_SESSION['utilisateur']['role'] == 'admin';
}

function nombre_articles_panier() {
    $total = 0;

    if (isset($_SESSION['panier'])) {
        foreach ($_SESSION['panier'] as $quantite) {
            $total = $total + $quantite;
        }
    }

    return $total;
}
?>
