-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 22 Mars 2014 à 23:12
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
-- Structure de la table `type_demandes`
--

CREATE TABLE IF NOT EXISTS `type_demandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `libelle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `max_autorise` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Contenu de la table `type_demandes`
--

INSERT INTO `type_demandes` (`id`, `code`, `libelle`, `max_autorise`) VALUES
(1, 'CE', 'Carte d''étudiant', 1),
(2, 'A', 'Attestation', 4),
(3, 'RN', 'Relevé de notes', 4),
(4, 'B', 'Baccalauréat', 3),
(5, 'AS', 'Attestation de scolarité', 4),
(6, 'DD', 'Diplome DEUG', 3),
(7, 'DL', 'Diplome License', 4),
(8, 'AD', 'Attestation DEUG', 3),
(9, 'AL', 'Attestation License', 3),
(10, 'AP', 'Attestation de présence', 4),
(11, 'ER', 'Element a refaire', 3),
(12, '5M', '5 module', 3),
(13, 'CM', 'Changement de module', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
