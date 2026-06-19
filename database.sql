DROP DATABASE IF EXISTS boutique_web_php;
CREATE DATABASE boutique_web_php CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE boutique_web_php;

CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'client',
    date_creation DATETIME NOT NULL
);

CREATE TABLE categorie (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(80) NOT NULL
);

CREATE TABLE produit (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    id_categorie INT NOT NULL,
    nom VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT '',
    stock INT NOT NULL DEFAULT 0,
    actif TINYINT(1) NOT NULL DEFAULT 1,
    date_ajout DATETIME NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie)
);

CREATE TABLE commande (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    date_commande DATETIME NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    nom_livraison VARCHAR(120) NOT NULL,
    adresse TEXT NOT NULL,
    telephone VARCHAR(30) NOT NULL,
    mode_paiement VARCHAR(60) NOT NULL,
    statut VARCHAR(40) NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
);

CREATE TABLE ligne_commande (
    id_ligne INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL,
    id_produit INT NOT NULL,
    nom_produit VARCHAR(120) NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande),
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit)
);

INSERT INTO utilisateur (nom, email, mot_de_passe, role, date_creation) VALUES
('Administrateur NovaShop', 'admin@novashop.test', '$2y$12$XWHOGx/IKkvLntINZaCskeiHAiSvmwAcEws1vV5kvQ4C/vuj1dMQG', 'admin', NOW()),
('Client Demo', 'client@novashop.test', '$2y$12$XA.GBvGZE1ujZY49aOXEaOUn6HrOSKr3jdWZ3uQjC/T/trqUTUR.W', 'client', NOW());

INSERT INTO categorie (nom) VALUES
('Ordinateurs'),
('Accessoires'),
('Audio'),
('Bureau'),
('Gaming');

INSERT INTO produit (id_categorie, nom, description, prix, image, stock, actif, date_ajout) VALUES
(1, 'PC portable NovaBook 14', 'Ordinateur portable leger pour les cours, le travail et la navigation quotidienne. Il convient a un usage etudiant avec traitement de texte, recherches internet et petits projets de programmation.', 629.90, 'images/pc-portable.svg', 9, 1, NOW()),
(5, 'Pack gaming Starter', 'Pack compose d un clavier, d une souris et d un tapis. Il est pense pour demarrer avec un poste de jeu simple et propre sans depenser trop.', 89.90, 'images/pack-gaming.svg', 15, 1, NOW()),
(2, 'Clavier mecanique Compact', 'Clavier compact avec touches confortables. Pratique pour garder de la place sur le bureau et coder plus facilement.', 49.90, 'images/clavier.svg', 24, 1, NOW()),
(2, 'Souris sans fil Pulse', 'Souris sans fil simple, precise et agreable pour travailler longtemps. Bonne option pour un ordinateur portable.', 24.90, 'images/souris.svg', 32, 1, NOW()),
(3, 'Casque audio Study Pro', 'Casque confortable avec micro integre pour les cours en ligne, les reunions et les appels.', 59.90, 'images/casque.svg', 18, 1, NOW()),
(4, 'Support ordinateur Alu', 'Support de bureau pour surelever un ordinateur portable et ameliorer la position de travail.', 34.90, 'images/support.svg', 21, 1, NOW()),
(4, 'Lampe LED Bureau', 'Lampe LED simple avec plusieurs niveaux de luminosite. Utile pour travailler le soir sans fatiguer les yeux.', 29.90, 'images/lampe.svg', 12, 1, NOW()),
(3, 'Enceinte Bluetooth Mini', 'Petite enceinte portable pour ecouter de la musique dans une chambre ou un petit bureau.', 39.90, 'images/enceinte.svg', 16, 1, NOW());
