-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 22 Mars 2014 à 23:10
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
-- Structure de la table `demandes`
--

CREATE TABLE IF NOT EXISTS `demandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etudiant_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type_demande_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `remarque` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `date_reponce` date DEFAULT NULL,
  `notified` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BD940CBBDDEAB1A3` (`etudiant_id`),
  KEY `IDX_BD940CBB9DEA883D` (`type_demande_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=50 ;

--
-- Contenu de la table `demandes`
--

INSERT INTO `demandes` (`id`, `etudiant_id`, `type_demande_id`, `created_at`, `updated_at`, `remarque`, `status`, `date_reponce`, `notified`) VALUES
(1, '1', 2, '2014-03-15 23:10:16', '2014-03-20 00:00:00', NULL, 1, '2014-03-20', 0),
(4, '1', 2, '2014-03-15 23:53:21', NULL, NULL, 2, NULL, 0),
(5, '1', 2, '2014-03-16 00:01:34', '2014-03-16 21:04:31', NULL, 2, NULL, 0),
(6, '1', 2, '2014-03-16 00:01:48', '2014-03-17 13:35:41', NULL, 1, '2014-03-19', 0),
(7, '1', 1, '2014-03-16 00:02:24', '2014-03-16 21:41:04', NULL, 1, '2014-03-31', 0),
(8, '1', 5, '2014-03-16 01:03:37', '2014-03-16 21:36:12', NULL, 1, '2014-03-21', 0),
(18, '1', 3, '2015-08-30 00:00:00', '2014-03-16 21:41:46', NULL, 2, NULL, 0),
(24, '1', 3, '2015-08-30 00:00:00', '2014-03-16 22:39:26', NULL, 2, NULL, 0),
(26, '1', 1, '2015-08-30 00:00:00', '2014-03-17 09:34:24', NULL, 2, NULL, 0),
(27, '1', 1, '2015-08-30 00:00:00', '2014-03-17 09:52:48', NULL, 2, NULL, 0),
(28, '1', 1, '2015-08-30 00:00:00', '2014-03-17 10:29:38', NULL, 2, NULL, 0),
(29, '1', 2, '2015-08-30 00:00:00', '2014-03-17 10:30:58', NULL, 1, '2014-03-31', 0),
(30, '1', 1, '2015-08-30 00:00:00', '2014-03-17 12:55:13', NULL, 1, NULL, 0),
(31, '1', 8, '2015-08-30 00:00:00', '2014-03-17 12:23:36', NULL, 1, NULL, 0),
(32, '1', 10, '2015-08-30 00:00:00', '2014-03-17 12:29:04', NULL, 1, '2014-03-02', 0),
(33, '1', 7, '2015-08-30 00:00:00', '2014-03-17 12:55:30', NULL, 1, '2014-05-28', 0),
(34, '1', 6, '2015-08-30 00:00:00', '2014-03-17 13:19:53', NULL, 1, '2013-08-07', 0),
(35, '1', 4, '2015-08-30 00:00:00', '2014-03-17 13:37:24', NULL, 1, '2014-12-31', 0),
(36, '1', 4, '2015-08-30 00:00:00', '2014-03-17 14:05:43', NULL, 1, '2011-09-21', 0),
(37, '1', 10, '2015-08-30 00:00:00', '2014-03-22 11:59:50', NULL, 2, NULL, 0),
(38, '1', 10, '2015-08-30 00:00:00', NULL, NULL, 0, NULL, 0),
(39, '1', 10, '2015-08-30 00:00:00', NULL, NULL, 0, NULL, 0),
(40, '1', 10, '2015-08-30 00:00:00', NULL, NULL, 0, NULL, 0),
(41, '1', 13, '2014-03-22 12:30:36', '2014-03-22 22:40:51', 'Session1   DFS3M200   Session 2 DFS4M400', 2, NULL, 0),
(42, '2', 13, '2014-03-22 12:40:31', '2014-03-22 22:40:08', '1SMTRDFS5M100-2SMTRDFS6M200', 2, NULL, 0),
(43, '3', 13, '2014-03-22 13:53:39', '2014-03-22 22:40:42', '1SMTRDFS3M200', 2, NULL, 0),
(44, '4', 13, '2014-03-22 13:54:24', '2014-03-22 21:04:58', '-2SMTRDFS4M300', 1, NULL, 0),
(45, '6', 13, '2014-03-22 19:30:42', '2014-03-22 21:28:52', 'Session 1  : DFS3M100 /   Session 2  : DFS4M400', 2, NULL, 0),
(46, '1', 8, '2015-08-30 00:00:00', '2014-03-22 22:24:01', NULL, 2, NULL, 0),
(47, '1', 2, '2014-03-22 22:16:37', '2014-03-22 22:23:12', NULL, 1, '2014-03-31', 0),
(48, '1', 2, '2014-03-22 22:27:14', '2014-03-22 22:27:43', NULL, 1, '2014-03-31', 0),
(49, '1', 13, '2014-03-22 22:44:34', '2014-03-22 22:45:03', 'Session 1  : DFS3M100 /   Session 2  : DFS4M300', 1, NULL, 0);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `demandes`
--
ALTER TABLE `demandes`
  ADD CONSTRAINT `demandes_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `demandes_ibfk_2` FOREIGN KEY (`type_demande_id`) REFERENCES `type_demandes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
