<?php
$serveur = "localhost";
$base = "boutique_web_php";
$utilisateur = "root";
$motdepasse = "";

try {
    $pdo = new PDO("mysql:host=$serveur;dbname=$base;charset=utf8mb4", $utilisateur, $motdepasse);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $erreur) {
    die("Erreur de connexion a la base de donnees : " . $erreur->getMessage());
}
?>
