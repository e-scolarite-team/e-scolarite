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
-- Structure de la table `etudiants`
--

CREATE TABLE IF NOT EXISTS `etudiants` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code_appog` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cne` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_naissance` datetime NOT NULL,
  `ville_naissance` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nom_ar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom_ar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sexe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `annee_inscription` int(11) NOT NULL,
  `annee_depart` int(11) DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `indx_code_apo` (`code_appog`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `etudiants`
--

INSERT INTO `etudiants` (`id`, `code_appog`, `cne`, `cin`, `date_naissance`, `ville_naissance`, `nom`, `prenom`, `nom_ar`, `prenom_ar`, `sexe`, `annee_inscription`, `annee_depart`, `adresse`) VALUES
('1', '1', '333333333', 'H453267', '1988-03-25 05:19:19', 'Casa', 'Mestari', 'Hasan', '', '', 'M', 2011, NULL, 'Rue 67 N 8 Hai Rahma'),
('2', '2', '444444444444444444', '444444444444444', '2014-03-24 00:00:00', 'Settat', 'Salim', 'Biari', '', '', 'M', 2011, NULL, ''),
('3', '33333333', '3333333333', '3333333', '2014-03-25 00:00:00', 'Settat', 'Hamza', 'Kornani', '', '', 'M', 2011, NULL, ''),
('4', '444444444', '444444', '444444444', '2014-03-25 00:00:00', 'Settat', 'karim', 'benani', '', '', 'M', 2011, NULL, ''),
('5', '5555555555555', '555555555', '55555555555', '2014-03-24 00:00:00', 'safi', 'Bogari', 'Jalal', '', '', 'M', 2011, NULL, ''),
('6', '66666666', '66666666', '66666666', '2014-03-24 00:00:00', 'safi', 'Berdadi', 'Mahdi', '', '', 'M', 2011, NULL, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
