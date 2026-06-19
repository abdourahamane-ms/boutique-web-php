# NovaShop - boutique web PHP/MySQL

NovaShop est une boutique en ligne que j'ai realisee pour m'entrainer sur les bases du developpement web : HTML, CSS, PHP et MySQL.

Je voulais eviter de faire seulement une page qui affiche des produits. J'ai donc ajoute un vrai parcours plus proche d'un site e-commerce : recherche dans le catalogue, fiche produit, panier, commande, espace client et petite interface administrateur.

## Fonctionnalites

Cote client :

- affichage d'un catalogue de produits depuis la base MySQL ;
- recherche par mot-cle ;
- filtre par categorie ;
- tri par prix ou par nom ;
- fiche detaillee pour chaque produit ;
- panier en session PHP ;
- modification des quantites ;
- validation d'une commande avec adresse de livraison ;
- historique des commandes dans l'espace client.

Cote administrateur :

- tableau de bord simple ;
- ajout d'un produit ;
- modification d'un produit ;
- masquage d'un produit ;
- suivi des commandes ;
- modification du statut d'une commande.

## Technologies utilisees

- HTML
- CSS
- PHP procedural
- MySQL
- SQL
- un petit script Python basique dans le dossier `scripts`, seulement pour montrer comment je peux preparer/verifier des donnees simples

Il n'y a pas de JavaScript, pas de framework PHP et pas de librairie externe. Le but etait de rester sur des notions que je sais expliquer.

## Installation en local

1. Placer le dossier du projet dans `htdocs` si vous utilisez XAMPP, ou dans le dossier web de votre serveur local.
2. Creer la base de donnees avec le fichier `database.sql`.
3. Verifier les informations de connexion dans `connexion.php`.
4. Lancer le site depuis le navigateur.

Par defaut, la base s'appelle :

```sql
boutique_web_php
```

## Comptes de demonstration

Compte client :

```text
Email : client@novashop.test
Mot de passe : client123
```

Compte administrateur :

```text
Email : admin@novashop.test
Mot de passe : admin123
```

## Organisation rapide

```text
index.php                  page d'accueil et catalogue
produit.php                detail d'un produit
panier.php                 panier client
paiement.php               validation de commande simulee
compte.php                 espace client
admin.php                  tableau de bord admin
admin_produits.php         gestion des produits
admin_commandes.php        gestion des commandes
connexion.php              connexion a MySQL
functions.php              petites fonctions utiles
database.sql               structure et donnees de test
style.css                  design du site
```

## Ce que ce projet m'a fait travailler

- les formulaires HTML relies a PHP ;
- les sessions PHP pour le panier et la connexion ;
- les requetes preparees avec PDO ;
- la relation entre plusieurs tables SQL ;
- l'organisation d'un projet web simple ;
- la difference entre une page de test et une interface utilisable.

## Limites connues

Le paiement est volontairement simule. Le projet n'est pas connecte a un vrai service bancaire et il n'y a pas encore d'envoi d'email automatique. Pour un vrai site en production, il faudrait ajouter plus de securite, des tests et un vrai systeme de paiement.
