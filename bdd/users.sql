-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 28 juin 2021 à 19:45
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `acoteq`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_societe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_siren` int(11) NOT NULL,
  `user_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_code_postal` int(11) NOT NULL,
  `user_ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_pays` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_mdp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_inscription` datetime NOT NULL,
  `user_connexion` datetime NOT NULL,
  `login_fail` int(11) DEFAULT NULL,
  `user_blocked` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unblock_time` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `user_nom`, `user_prenom`, `user_societe`, `user_siren`, `user_role`, `user_adresse`, `user_code_postal`, `user_ville`, `user_pays`, `user_email`, `user_mdp`, `user_inscription`, `user_connexion`, `login_fail`, `user_blocked`, `unblock_time`) VALUES
(1, 'BOYER', 'Isabelle', 'Iqaten', 123456789, 'client', '14 rue de suresnes', 92000, 'Nanterre', 'France', 'i_boyer@iqaten.com', '$2y$10$Ro/a0Iq.oc7pBuboaDodx.S15PBF6XsvhOq6owLIOOAjm4DGzssUq', '2021-06-29 09:53:44', '2021-06-29 09:53:44', NULL, NULL, NULL),
(2, 'SMITH', 'Adam', 'Iqaten', 123456789, 'client', '14 rue de suresnes', 92000, 'Nanterre', 'France', 'smith@iqaten.com', '$2y$10$DSdGrUmt7dFE7TnEYv1HWe0iha5PYtpExfrGJHwQSM70yLAD2hnHq', '2021-06-22 16:26:54', '2021-06-28 20:51:03', NULL, NULL, NULL),
(3, 'MULLER', 'George', 'Iqaten', 123456789, 'client', '14 rue de suresnes', 92000, 'Nanterre', 'France', 'muller@iqaten.com', '$2y$10$Xm9HlvqVLK6Sxb97lfMpruxDlmkSUKPJ4eZTgK9yTe1.WUpn/iEJa', '2021-06-22 16:28:40', '2021-06-28 20:15:09', NULL, NULL, NULL),
(4, 'WILLIAMS', 'Paul', 'Iqaten', 123456789, 'client', '14 rue de suresnes', 92000, 'Nanterre', 'France', 'williams@iqaten.com', '$2y$10$Bnx33UxAT64nNr0qVRIAsuhIcNIRxdhUERxTY4HuZdNBJTzwKpZiW', '2021-06-22 16:31:17', '2021-06-28 20:46:37', NULL, NULL, NULL),
(5, 'BECKER', 'David', 'Iqaten', 123456789, 'client', '14 rue de suresnes', 92000, 'Nanterre', 'France', 'becker@iqaten.com', '$2y$10$datiJtO9KT4fa6jPlpuWU.GPSa1AKenD7D3uVe4H/zu8cbGs4sOXW', '2021-06-22 16:32:55', '2021-06-28 20:49:14', NULL, NULL, NULL),
(6, 'TAYLOR', 'Marc', 'Iqaten', 123456789, 'client', '14 rue de suresnes', 92000, 'Nanterre', 'France', 'taylor@iqaten.com', '$2y$10$sz85IyN9QIqIipHz0TpkXeqtgDio7HtgIMBPrF7vffW9Jp9uaQlJm', '2021-06-27 14:49:04', '2021-06-27 14:49:04', NULL, NULL, NULL),
(7, 'BROWN', 'Michael', 'Thermo_2021', 987654321, 'fournisseur', '58 avenue de paris', 75001, 'Paris 01', 'France', 'brown@gmail.fr', '$2y$10$Tcj84Uhxjb.jBgCt4c4EE.lT3QIITmoLsg7YFHAQ.sPQwhQn8YIRC', '2021-06-22 16:36:38', '2021-06-22 16:36:38', NULL, NULL, NULL),
(8, 'MORRIS', 'Robert', 'Iso', 876543219, 'fournisseur', '31 rue du générale leclerc', 80000, 'Amiens', 'France', 'morris_robert77@hotmail.info', '$2y$10$Fte1KaFoZ90/UlG6sUcLIuASHS3lk1w/t2P7W2ZNzDhV3/25zBCD2', '2021-06-22 16:39:44', '2021-06-22 16:39:44', NULL, NULL, NULL),
(9, 'HALL', 'Richard', 'Btp_99', 234567891, 'client', '28 Rue Pasteur', 91120, 'Palaiseau', 'France', 'hall-richard85@free.fr', '$2y$10$4Xrji3cRVWAeLA2gBE4VUOQ9j2zJlctMJxAHZp5ZcQTmDExN/vc3q', '2021-06-22 16:44:08', '2021-06-22 16:44:08', NULL, NULL, NULL),
(10, 'MILLER', 'Thomas', 'Btp_99', 234567891, 'client', '28 Rue Pasteur', 91120, 'Palaiseau', 'France', 'sthomas@yahoo.com', '$2y$10$UQnn9STolu2XJztrDOmmuezjwA0M1nEzWAtijRmeRVSC.ufKwJ7Le', '2021-06-22 16:52:31', '2021-06-22 16:52:31', NULL, NULL, NULL),
(11, 'HERNANDEZ', 'Scott', 'Btp_99', 234567891, 'client', '28 Rue Pasteur', 91120, 'Palaiseau', 'France', 'hernandez@outlook.fr', '$2y$10$vAYv3o3K/sd0vLIlcup2qeat7reB8Ku5nV5a.zgO7qDTuNj2OdXGa', '2021-06-27 15:02:57', '2021-06-27 15:02:57', NULL, NULL, NULL),
(12, 'DUPONT', 'Eric', 'Iqaten', 123456789, 'client', '14 rue de suresnes', 92000, 'Nanterre', 'France', 'eric_dupont@iqaten.com', '$2y$10$LjPUh4b5LwLOloxnjLkzq.hS6G5f6UQI4122niFr8tGqQ4YhEoTHe', '2021-06-28 21:01:02', '2021-06-28 21:01:52', NULL, NULL, NULL),
(13, 'DUBOIS', 'Jeanne', 'Iqaten', 123456789, 'client', '14 rue de suresnes', 92000, 'Nanterre', 'France', 'jeanne_2000@iqaten.com', '$2y$10$pCw1i/gLEyyx3nF1p2tJW.7gg5j1Bgo7Z2auw/Ecvrl8YHidmF3la', '2021-06-28 21:44:31', '2021-06-28 21:44:31', NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
