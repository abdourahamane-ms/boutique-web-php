# New-Tech Boutique

New-Tech Boutique est un petit site e-commerce realise en PHP et MySQL. Le projet garde une structure simple, proche de ce que je sais expliquer : pages PHP, formulaires HTML, sessions, requetes SQL et un fichier CSS.

Le but n'est pas de faire une boutique professionnelle complete, mais d'avoir un projet propre et utile pour montrer les bases du developpement web cote serveur.

## Fonctionnalites

- inscription et connexion utilisateur ;
- catalogue de produits affiche depuis MySQL ;
- barre de recherche par nom ou description ;
- ajout au panier ;
- modification des quantites du panier ;
- validation d'une commande avec adresse de livraison ;
- page de confirmation ;
- page administrateur simple ;
- ajout de produits depuis l'admin ;
- modification du stock ;
- suppression d'un produit ;
- consultation des commandes.

## Technologies utilisees

- HTML
- CSS
- PHP
- MySQL / SQL

Il n'y a pas de JavaScript et pas de framework. Le projet reste volontairement simple.

## Installation avec XAMPP

1. Copier le dossier du projet dans `htdocs`.
2. Lancer Apache et MySQL avec XAMPP.
3. Ouvrir phpMyAdmin.
4. Importer le fichier `database.sql`.
5. Ouvrir le site :

```text
http://localhost/boutique_php/
```

Selon le nom du dossier, l'URL peut changer. Par exemple :

```text
http://localhost/boutique-web-php/
```

## Comptes de test

Compte administrateur :

```text
Email : admin@newtech.test
Mot de passe : admin123
```

Compte client :

```text
Email : client@newtech.test
Mot de passe : client123
```

## Organisation du projet

```text
index.php          catalogue et recherche
login.php          connexion
register.php       inscription
panier.php         panier
paiement.php       validation de commande
confirmation.php   confirmation apres commande
admin.php          page administrateur
connexion.php      connexion a la base MySQL
header.php         haut du site
footer.php         bas du site
style.css          design du site
database.sql       base de donnees
images/            images des produits
```

## Remarque

Le paiement est simule. Le projet sert surtout a montrer la logique d'une boutique : utilisateur, produits, panier, commandes et administration simple.
