# NovaShop - boutique PHP/MySQL

NovaShop est une boutique en ligne fictive que j'ai réalisée pour m'entraîner avec les bases du développement web : HTML, CSS, PHP et MySQL.

J'ai volontairement gardé une structure simple. Le projet ne contient pas de JavaScript, pas de framework et pas de bibliothèque externe. L'objectif est d'avoir un site qui ressemble à une vraie petite boutique, tout en restant compréhensible avec mon niveau actuel.

## Ce que l'on peut faire sur le site

- consulter un catalogue de produits ;
- rechercher un produit ;
- filtrer par catégorie ;
- trier les produits par nom ou par prix ;
- voir la fiche détaillée d'un produit ;
- ajouter des produits au panier ;
- modifier les quantités du panier ;
- créer un compte client ;
- se connecter ;
- valider une commande simple ;
- consulter ses commandes ;
- gérer les produits et les commandes depuis une page administrateur.

## Technologies utilisées

- HTML
- CSS
- PHP procédural
- MySQL / SQL
- un petit script Python basique pour vérifier une liste de produits

Le fichier Python est dans le dossier `scripts`. Il ne fait pas fonctionner le site. C'est juste un petit outil séparé pour m'entraîner avec les listes, les dictionnaires, les boucles et les conditions.

## Organisation du projet

```text
index.php              catalogue et page d'accueil
produit.php            fiche d'un produit
panier.php             panier et validation de commande
login.php              connexion et inscription
logout.php             déconnexion
mes_commandes.php      commandes du client connecté
admin.php              page administrateur simple
connexion.php          connexion MySQL + petites fonctions utiles
header.php             haut du site
footer.php             bas du site
style.css              design du site
database.sql           base de données
scripts/               petit script Python séparé
images/                images SVG des produits
```

Les petites fonctions PHP sont dans `connexion.php` pour éviter d'avoir un fichier `fonctions.php` séparé. J'ai fait ce choix pour garder la logique proche d'un petit projet PHP classique.

## Installation en local

1. Copier le projet dans le dossier `htdocs` de XAMPP.
2. Ouvrir phpMyAdmin.
3. Importer le fichier `database.sql`.
4. Lancer Apache et MySQL dans XAMPP.
5. Ouvrir le site avec :

```text
http://localhost/boutique-web-php/
```

La base de données s'appelle :

```text
boutique_web_php
```

## Comptes de test

Compte administrateur :

```text
Email : admin@novashop.test
Mot de passe : admin123
```

Compte client :

```text
Email : client@novashop.test
Mot de passe : client123
```

## Limites du projet

Le paiement est simulé. Il n'y a pas de vrai paiement bancaire, pas d'envoi d'email et pas de tableau de bord avancé. Le projet sert surtout à montrer que je sais relier des pages PHP avec une base MySQL et organiser un petit parcours e-commerce.
