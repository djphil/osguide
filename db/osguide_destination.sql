-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 14 Février 2016 à 19:31
-- Version du serveur :  5.6.21
-- Version de PHP :  5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `tests`
--

-- --------------------------------------------------------

--
-- Structure de la table `osguide_destination`
--

CREATE TABLE IF NOT EXISTS `osguide_destination` (
`id` int(16) unsigned NOT NULL,
  `region_name` varchar(64) NOT NULL,
  `owner_name` varchar(64) NOT NULL,
  `owner_uuid` varchar(36) NOT NULL,
  `object_name` varchar(64) NOT NULL,
  `object_uuid` varchar(36) NOT NULL,
  `categorie_name` varchar(32) NOT NULL,
  `local_position` varchar(16) NOT NULL,
  `http_server_url` varchar(128) NOT NULL,
  `agents_online` int(4) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `osguide_destination`
--
ALTER TABLE `osguide_destination`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `osguide_destination`
--
ALTER TABLE `osguide_destination`
MODIFY `id` int(16) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
