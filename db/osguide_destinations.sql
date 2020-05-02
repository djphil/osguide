-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 21 Janvier 2017 à 14:18
-- Version du serveur :  10.1.19-MariaDB
-- Version de PHP :  5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `opensim`
--

-- --------------------------------------------------------

--
-- Structure de la table `osguide_destinations`
--

CREATE TABLE `osguide_destinations` (
    `id` int(16) UNSIGNED NOT NULL,
    `region_name` varchar(64) NOT NULL,
    `owner_name` varchar(64) NOT NULL,
    `owner_uuid` varchar(36) NOT NULL,
    `object_name` varchar(64) NOT NULL,
    `object_uuid` varchar(36) NOT NULL,
    `categorie_name` varchar(32) NOT NULL,
    `local_position` varchar(16) NOT NULL,
    `http_server_url` varchar(128) NOT NULL,
    `agents_online` int(4) NOT NULL,
    `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `osguide_destinations`
--
ALTER TABLE `osguide_destinations`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `osguide_destinations`
--
ALTER TABLE `osguide_destinations`
    MODIFY `id` int(16) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
