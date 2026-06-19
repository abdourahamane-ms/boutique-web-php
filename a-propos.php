<?php
require_once 'functions.php';
require_once 'header.php';
?>
<section class="page-header">
    <p class="sur-titre">A propos du projet</p>
    <h1>Une boutique en ligne faite sans framework</h1>
    <p>
        Ce projet a ete construit avec les bases vues en cours et en pratique : HTML, CSS, PHP, SQL, sessions, formulaires, boucles, conditions et requetes preparees.
    </p>
</section>

<section class="grille-info">
    <article>
        <h2>Objectif</h2>
        <p>Creer un site e-commerce plus realiste qu'un simple catalogue : le client peut chercher un produit, voir une fiche detaillee, ajouter au panier et passer une commande.</p>
    </article>
    <article>
        <h2>Choix technique</h2>
        <p>Le site reste volontairement simple : pas de JavaScript, pas de framework, pas de bibliotheque externe. Tout passe par des pages PHP et des formulaires classiques.</p>
    </article>
    <article>
        <h2>Partie admin</h2>
        <p>L'administrateur peut ajouter, modifier ou masquer un produit, suivre les commandes et changer leur statut.</p>
    </article>
</section>

<section class="section-bloc">
    <h2>Ce que j'ai voulu travailler</h2>
    <ul class="liste-projet">
        <li>Organisation des pages PHP avec un header et un footer communs.</li>
        <li>Connexion avec mot de passe securise grace a <code>password_hash</code> et <code>password_verify</code>.</li>
        <li>Panier stocke en session PHP.</li>
        <li>Requetes SQL preparees pour eviter les erreurs et mieux securiser les donnees.</li>
        <li>Interface plus proche d'une vraie boutique que d'un simple exercice.</li>
    </ul>
</section>
<?php require_once 'footer.php'; ?>
