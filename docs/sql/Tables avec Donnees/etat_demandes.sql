-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 22 Mars 2014 à 23:11
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `e-scolarite`
--

-- --------------------------------------------------------

--
-- Structure de la table `etat_demandes`
--

CREATE TABLE IF NOT EXISTS `etat_demandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `demande_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `etat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `justification` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2041A42B80E95E18` (`demande_id`),
  KEY `IDX_2041A42B642B8210` (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=118 ;

--
-- Contenu de la table `etat_demandes`
--

INSERT INTO `etat_demandes` (`id`, `demande_id`, `admin_id`, `etat`, `justification`, `created_at`) VALUES
(1, 4, 1, 'Rejeter', NULL, '2014-03-16 19:42:10'),
(3, 5, 1, 'Rejeter', NULL, '2014-03-16 21:04:31'),
(4, 8, 1, 'Traiter', NULL, '2014-03-16 21:14:44'),
(5, 8, 1, 'Traiter', NULL, '2014-03-16 21:15:06'),
(17, 8, 1, 'Traiter', NULL, '2014-03-16 21:36:12'),
(18, 7, 1, 'Traiter', NULL, '2014-03-16 21:41:03'),
(19, 18, 1, 'Rejeter', NULL, '2014-03-16 21:41:46'),
(20, 6, 1, 'Traiter', 'TTTTTTTTTTTTTTTTTTTTT', '2014-03-16 22:29:14'),
(21, 24, 1, 'Rejeter', NULL, '2014-03-16 22:37:34'),
(22, 24, 1, 'Rejeter', NULL, '2014-03-16 22:39:26'),
(23, 26, 1, 'Rejeter', NULL, '2014-03-17 09:34:24'),
(24, 27, 1, 'Rejeter', NULL, '2014-03-17 09:51:37'),
(25, 27, 1, 'Rejeter', NULL, '2014-03-17 09:52:48'),
(28, 28, 1, 'Rejeter', NULL, '2014-03-17 10:29:21'),
(29, 28, 1, 'Rejeter', NULL, '2014-03-17 10:29:38'),
(30, 29, 1, 'Traiter', NULL, '2014-03-17 10:30:58'),
(31, 31, 1, 'Rejeter', NULL, '2014-03-17 12:03:51'),
(32, 32, 1, 'Traiter', NULL, '2014-03-17 12:24:35'),
(33, 33, 1, 'Traiter', NULL, '2014-03-17 12:54:19'),
(34, 34, 1, 'Traiter', 'HHHHHHHHHHH', '2014-03-17 13:13:46'),
(35, 35, 1, 'Traiter', 'AAAAAAAAAAAAAAA', '2014-03-17 13:36:38'),
(36, 36, 1, 'Traiter', 'QQQQQQQQQQQQQQQQQQ', '2014-03-17 14:05:03'),
(37, 37, 1, 'Rejeter', NULL, '2014-03-22 11:59:31'),
(38, 38, NULL, 'en attente', NULL, '2014-03-22 12:05:41'),
(39, 39, NULL, 'en attente', NULL, '2014-03-22 12:05:48'),
(41, 41, 1, 'Rejeter', NULL, '2014-03-22 12:30:36'),
(42, 42, 1, 'Rejeter', NULL, '2014-03-22 12:40:31'),
(43, 43, 1, 'Rejeter', NULL, '2014-03-22 13:53:39'),
(111, 40, NULL, 'en attente', NULL, '2014-03-22 12:05:52'),
(112, 45, 1, 'Rejeter', NULL, '2014-03-22 19:30:42'),
(113, 44, 1, 'Valider', NULL, '2014-03-22 13:54:24'),
(114, 46, 1, 'Rejeter', NULL, '2014-03-22 21:46:41'),
(115, 47, 1, 'Traiter', 'MHMHMHMHMHMHMH', '2014-03-22 22:16:37'),
(116, 48, 1, 'Traiter', '7777777777777777', '2014-03-22 22:27:15'),
(117, 49, 1, 'Valider', NULL, '2014-03-22 22:44:34');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `etat_demandes`
--
ALTER TABLE `etat_demandes`
  ADD CONSTRAINT `FK_2041A42B642B8210` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `FK_2041A42B80E95E18` FOREIGN KEY (`demande_id`) REFERENCES `demandes` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
