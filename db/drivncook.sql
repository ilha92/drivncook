-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 04 jan. 2026 à 14:15
-- Version du serveur : 8.0.27
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `drivncook`
--

-- --------------------------------------------------------

--
-- Structure de la table `achats`
--

DROP TABLE IF EXISTS `achats`;
CREATE TABLE IF NOT EXISTS `achats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `franchise_id` int NOT NULL,
  `entrepot_id` int DEFAULT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `pourcentage_entrepot` decimal(5,2) NOT NULL,
  `date_achat` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `franchise_id` (`franchise_id`),
  KEY `entrepot_id` (`entrepot_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `achats`
--

INSERT INTO `achats` (`id`, `franchise_id`, `entrepot_id`, `montant_total`, `pourcentage_entrepot`, `date_achat`) VALUES
(22, 24, 32, '8000.00', '80.00', '2026-01-04 14:56:18'),
(21, 24, 34, '7500.00', '80.00', '2026-01-04 14:53:05'),
(20, 24, 34, '7500.00', '80.00', '2026-01-04 14:53:00'),
(19, 24, 34, '7500.00', '80.00', '2026-01-04 14:52:42'),
(18, 25, 1, '5600.00', '80.00', '2026-01-04 11:48:21'),
(17, 24, 1, '5600.00', '80.00', '2026-01-04 11:38:35');

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `email`, `mot_de_passe`, `nom`, `date_creation`) VALUES
(1, 'admin@drivncook.com', '$2y$10$XxpPYzd9KNCRCvWx1eHRNOBevy8XQhHxNlEbQr1D072Hzh/fapMhu', 'Admin', '2025-12-15 23:36:38');

-- --------------------------------------------------------

--
-- Structure de la table `approvisionnements`
--

DROP TABLE IF EXISTS `approvisionnements`;
CREATE TABLE IF NOT EXISTS `approvisionnements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `franchise_id` int NOT NULL,
  `montant_total` decimal(10,2) DEFAULT NULL,
  `pourcentage_entrepot` decimal(5,2) DEFAULT NULL,
  `date_commande` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `franchise_id` (`franchise_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `camions`
--

DROP TABLE IF EXISTS `camions`;
CREATE TABLE IF NOT EXISTS `camions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `immatriculation` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `modele` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `statut` enum('actif','panne','maintenance') COLLATE utf8mb4_general_ci DEFAULT 'actif',
  `franchise_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `immatriculation` (`immatriculation`),
  KEY `franchise_id` (`franchise_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `camions`
--

INSERT INTO `camions` (`id`, `immatriculation`, `modele`, `statut`, `franchise_id`, `created_at`) VALUES
(25, '888-884', 'Renault Master', 'actif', 25, '2026-01-04 10:56:30'),
(23, '888-888', 'Mercedes-Benz Sprinter', 'actif', 18, '2026-01-02 15:21:08');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `franchise_id` int NOT NULL,
  `produit_id` int NOT NULL,
  `quantite` int NOT NULL,
  `date_commande` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'en attente',
  PRIMARY KEY (`id`),
  KEY `franchise_id` (`franchise_id`),
  KEY `produit_id` (`produit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `franchise_id`, `produit_id`, `quantite`, `date_commande`, `statut`) VALUES
(13, 14, 13, 50, '2025-12-27 00:05:13', 'validée'),
(14, 18, 14, 10, '2025-12-29 12:15:05', 'en attente'),
(21, 18, 20, 10, '2025-12-29 13:34:35', 'validée'),
(22, 22, 20, 30, '2025-12-31 00:54:12', 'en attente'),
(23, 18, 21, 45, '2026-01-01 20:33:31', 'en attente'),
(24, 18, 21, 5, '2026-01-01 21:56:26', 'en attente'),
(30, 25, 25, 20, '2026-01-04 11:57:15', 'validée'),
(31, 24, 26, 25, '2026-01-04 14:50:18', 'validée');

-- --------------------------------------------------------

--
-- Structure de la table `entrepots`
--

DROP TABLE IF EXISTS `entrepots`;
CREATE TABLE IF NOT EXISTS `entrepots` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `actif` tinyint DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entrepots`
--

INSERT INTO `entrepots` (`id`, `nom`, `ville`, `prix`, `actif`) VALUES
(32, 'EntrepotParis', 'Paris15', '8000.00', 1),
(34, 'EntrepotParisSud', 'Nanterre', '7500.00', 1),
(35, 'EntrepotCreteil', 'Creteil', '4500.00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `entretiens`
--

DROP TABLE IF EXISTS `entretiens`;
CREATE TABLE IF NOT EXISTS `entretiens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `camion_id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `date_entretien` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `camion_id` (`camion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entretiens`
--

INSERT INTO `entretiens` (`id`, `camion_id`, `nom`, `description`, `date_entretien`) VALUES
(17, 1, 'FranchiseLyon', 'dzda', '2026-01-03'),
(16, 1, 'Hamel', 'dzda', '2026-01-01'),
(15, 1, 'Hamel', 'dzda', '2026-01-01'),
(14, 1, 'Hamel', 'dzda', '2026-01-01'),
(13, 1, 'Hamel', 'dzda', '2026-01-01'),
(12, 1, 'test', 'tefzf', '2026-01-01'),
(11, 1, 'test', 'tefzf', '2026-01-01'),
(10, 1, 'test', 'tefzf', '2026-01-01'),
(18, 1, 'Vidange', 'zdzada', '2026-01-03');

-- --------------------------------------------------------

--
-- Structure de la table `franchises`
--

DROP TABLE IF EXISTS `franchises`;
CREATE TABLE IF NOT EXISTS `franchises` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ville` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_entree` date NOT NULL,
  `droit_entree` enum('refuse','accepte') COLLATE utf8mb4_general_ci DEFAULT 'refuse',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `franchises`
--

INSERT INTO `franchises` (`id`, `nom`, `email`, `mot_de_passe`, `ville`, `telephone`, `date_entree`, `droit_entree`, `created_at`) VALUES
(24, 'FranchiseMatis', 'matis@gmail.com', '$2y$10$pOWmj0ozeYW6KyFYCHr1uehVCQBFJbAe2Fp7pvyYQYx3e4mUv6zVy', 'Puteaux', '0768377524', '2026-01-04', 'accepte', '2026-01-03 23:43:50'),
(18, 'FranchisesParis', 'iliashamel041@gmail.com', '$2y$10$77s1mUBozKHgy9roqt5aKOeI2tlO8eHj2nkVHUlIHCm8jRgxzk/le', 'Nanterre', '0768377522', '2025-12-28', 'accepte', '2025-12-28 13:01:39'),
(23, 'FranchiseLyon', 'test4@gmail.com', '$2y$10$P1F7hO2gAg35wskq9dbaGe2souQ6To0pTtHyd9//ZCCePjlTL.gjS', 'Nanterre', '0768377527', '2026-01-03', 'accepte', '2026-01-03 20:17:02'),
(25, 'FranchiseJed', 'jed@gmail.com', '$2y$10$RiNT2COU4p7QBNwXk3fKZOCT1qWfMGYoo33RpTX1se1t.i5tku/Z6', 'Boulogne', '0768377527', '2026-01-04', 'accepte', '2026-01-04 09:47:24');

-- --------------------------------------------------------

--
-- Structure de la table `pannes`
--

DROP TABLE IF EXISTS `pannes`;
CREATE TABLE IF NOT EXISTS `pannes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `camion_id` int NOT NULL,
  `date_panne` date NOT NULL,
  `type_panne` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `reparée` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `camion_id` (`camion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pannes`
--

INSERT INTO `pannes` (`id`, `camion_id`, `date_panne`, `type_panne`, `description`, `reparée`, `created_at`) VALUES
(1, 2, '2025-12-18', 'moteur', 'casse moteur\r\n', 1, '2025-12-17 23:51:34'),
(2, 3, '2025-12-18', 'moteur', 'zdazd', 1, '2025-12-17 23:54:31'),
(3, 4, '2025-12-18', 'moteur', 'ssa', 1, '2025-12-18 00:04:29'),
(4, 16, '2025-12-19', 'moteur', 'azda', 0, '2025-12-18 23:38:58'),
(5, 17, '2025-12-19', 'moteur', 'test', 0, '2025-12-19 22:52:26'),
(6, 1, '0000-00-00', '', 'test\r\n', 0, '2025-12-19 22:54:36'),
(7, 1, '0000-00-00', '', 'test144\r\n', 0, '2025-12-19 23:27:55'),
(8, 17, '2025-12-20', 'moteurgre', 'ege', 0, '2025-12-19 23:28:05'),
(9, 17, '2025-12-21', 'moteur', 'azdada', 0, '2025-12-21 20:11:57'),
(10, 18, '2025-12-21', 'Frein', 'probleme de frein arriere', 0, '2025-12-21 20:42:25'),
(11, 19, '2025-07-04', 'Moteur', 'panne', 0, '2025-12-24 22:56:44'),
(12, 21, '2025-12-31', 'moteur', 'rtgreg', 0, '2025-12-31 00:03:26'),
(13, 24, '2004-04-14', 'moteur', 'dzada', 0, '2026-01-03 21:26:20');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL,
  `entrepot_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entrepot_id` (`entrepot_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `prix`, `stock`, `entrepot_id`) VALUES
(22, 'test', '5.00', 0, 1),
(23, 'coca', '10.00', 0, 3),
(24, 'test5', '5.00', 50, 2),
(25, 'Tomate', '2.00', 10, 1),
(26, 'Burger', '5.00', 0, 22),
(27, 'Burger', '5.00', 30, 22),
(29, 'Burger', '10.00', 50, 32);

-- --------------------------------------------------------

--
-- Structure de la table `ventes`
--

DROP TABLE IF EXISTS `ventes`;
CREATE TABLE IF NOT EXISTS `ventes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `franchise_id` int NOT NULL,
  `date_vente` date NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `franchise_id` (`franchise_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ventes`
--

INSERT INTO `ventes` (`id`, `franchise_id`, `date_vente`, `nom`, `montant`) VALUES
(1, 14, '2027-12-26', '', '500.00'),
(2, 14, '2025-12-25', '', '400.00'),
(3, 16, '2025-12-24', '', '400.00'),
(4, 22, '2025-12-31', '', '520.00'),
(5, 18, '2026-01-01', 'Hamel', '100.00'),
(6, 18, '2004-04-14', 'test', '520.00'),
(7, 23, '2004-07-14', 'test', '500.00'),
(8, 24, '2026-01-04', 'test', '655.00'),
(9, 25, '2026-01-02', 'test', '500.00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
