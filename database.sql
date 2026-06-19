CREATE DATABASE IF NOT EXISTS boutique_web_php CHARACTER SET utf8 COLLATE utf8_general_ci;
USE boutique_web_php;

DROP TABLE IF EXISTS ligne_commande;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS produit;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS utilisateur;

CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'client',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categorie (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE produit (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255) DEFAULT 'images/produit-defaut.svg',
    id_categorie INT,
    actif TINYINT(1) DEFAULT 1,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie)
);

CREATE TABLE commande (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    adresse_livraison TEXT,
    statut VARCHAR(50) DEFAULT 'En préparation',
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
);

CREATE TABLE ligne_commande (
    id_ligne INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande),
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit)
);

INSERT INTO categorie(nom) VALUES
('Ordinateurs'),
('Accessoires'),
('Audio'),
('Bureau');

INSERT INTO utilisateur(nom, email, mot_de_passe, role) VALUES
('Administrateur NovaShop', 'admin@novashop.test', '$2y$12$9zGW85GxUkVX/.5pxx0OIepHtutMhxt9t8kjSFFgMdd9BAgaSmTK6', 'admin'),
('Client Test', 'client@novashop.test', '$2y$12$8/VjXYV9zRXTZT9IJuFWweX9XFt8tCr9AoqpTyXURExPMrJQAdKrW', 'client');

INSERT INTO produit(nom, description, prix, stock, image, id_categorie, actif) VALUES
('PC portable NovaBook 14', 'Ordinateur portable simple pour les cours, le web et les projets étudiants.', 629.90, 8, 'images/pc-portable.svg', 1, 1),
('Pack gaming Starter', 'Pack clavier, souris et tapis pour commencer une petite installation gaming.', 89.90, 15, 'images/pack-gaming.svg', 2, 1),
('Clavier mécanique Compact', 'Clavier compact confortable pour coder, rédiger et travailler.', 49.90, 20, 'images/clavier.svg', 2, 1),
('Souris sans fil Pulse', 'Souris légère et pratique pour un ordinateur portable.', 24.90, 30, 'images/souris.svg', 2, 1),
('Casque audio Study Pro', 'Casque audio adapté aux appels, aux cours en ligne et à la musique.', 59.90, 12, 'images/casque.svg', 3, 1),
('Lampe de bureau LED', 'Lampe simple pour travailler le soir sans fatiguer les yeux.', 34.90, 10, 'images/lampe.svg', 4, 1);
