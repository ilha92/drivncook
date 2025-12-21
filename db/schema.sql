-- db/schema.sql
CREATE DATABASE IF NOT EXISTS drivncook CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE drivncook;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'franchise') NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE franchises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ville VARCHAR(100),
    telephone VARCHAR(20),
    date_entree DATE,
    chiffre_affaires DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE camions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_camion VARCHAR(50) NOT NULL,
    etat ENUM('actif', 'panne', 'maintenance') DEFAULT 'actif',
    emplacement VARCHAR(150),
    franchise_id INT,
    FOREIGN KEY (franchise_id) REFERENCES franchises(id)
);

CREATE TABLE entretiens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    camion_id INT NOT NULL,
    description TEXT,
    date_entretien DATE,
    type ENUM('entretien', 'panne') NOT NULL,
    FOREIGN KEY (camion_id) REFERENCES camions(id)
);

CREATE TABLE ventes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    franchise_id INT NOT NULL,
    date_vente DATE NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (franchise_id) REFERENCES franchises(id)
);

CREATE TABLE approvisionnements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    franchise_id INT NOT NULL,
    montant_total DECIMAL(10,2),
    pourcentage_entrepot DECIMAL(5,2),
    date_commande DATE,
    FOREIGN KEY (franchise_id) REFERENCES franchises(id)
);
 
/* 
-- db/schema.sqlm0
CREATE DATABASE IF NOT EXISTS drivncook CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE drivncook;

CREATE TABLE franchises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    ville VARCHAR(100),
    telephone VARCHAR(20),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    nom VARCHAR(100),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS  camions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    franchise_id INT NOT NULL,
    immatriculation VARCHAR(20) NOT NULL,
    modele VARCHAR(100),
	statut ENUM('actif', 'panne', 'maintenance') DEFAULT 'actif',
    FOREIGN KEY (franchise_id) REFERENCES franchises(id)
);

CREATE TABLE pannes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    camion_id INT NOT NULL,
    description TEXT NOT NULL,
    date_panne DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (camion_id) REFERENCES camions(id)
);

CREATE TABLE entretiens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    camion_id INT NOT NULL,
    description TEXT,
    date_entretien DATE,
    FOREIGN KEY (camion_id) REFERENCES camions(id)
);

CREATE TABLE ventes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    franchise_id INT NOT NULL,
    date_vente DATETIME DEFAULT CURRENT_TIMESTAMP,
    montant DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (franchise_id) REFERENCES franchises(id)
);

CREATE TABLE approvisionnements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    franchise_id INT NOT NULL,
    montant_total DECIMAL(10,2),
    pourcentage_entrepot DECIMAL(5,2),
    date_commande DATE,
    FOREIGN KEY (franchise_id) REFERENCES franchises(id)
);

CREATE TABLE entrepots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    ville VARCHAR(100) NOT NULL
);

CREATE TABLE achats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    franchise_id INT NOT NULL,
    entrepot_id INT,
    montant_total DECIMAL(10,2) NOT NULL,
    pourcentage_entrepot DECIMAL(5,2) NOT NULL,
    date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (franchise_id) REFERENCES franchises(id),
    FOREIGN KEY (entrepot_id) REFERENCES entrepots(id)
);

CREATE TABLE produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    stock INT,
    entrepot_id INT,
    FOREIGN KEY (entrepot_id) REFERENCES entrepots(id)
);

CREATE TABLE commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    franchise_id INT,
    produit_id INT,
    quantite INT,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'en attente',
    FOREIGN KEY (franchise_id) REFERENCES franchises(id),
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);

INSERT INTO admins (nom, email, mot_de_passe) VALUES ('Admin', 'admin@drivncook.com', '$2y$10$XxpPYzd9KNCRCvWx1eHRNOBevy8XQhHxNlEbQr1D072Hzh/fapMhu');

INSERT INTO entrepots (nom, ville) VALUES
('Entrepôt Paris Nord', 'Paris'),
('Entrepôt Paris Sud', 'Paris'),
('Entrepôt Est', 'Créteil'),
('Entrepôt Ouest', 'Nanterre');

