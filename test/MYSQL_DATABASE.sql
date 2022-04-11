-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : lun. 11 avr. 2022 à 10:12
-- Version du serveur : 8.0.28
-- Version de PHP : 8.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `MYSQL_DATABASE`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `balance` int NOT NULL DEFAULT '5000',
  `country_iso` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Client (id, name, address, country).';

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `name`, `address`, `balance`, `country_iso`) VALUES
(1, 'Romain', 'Cagnes sur Mer', 400, 'FRA'),
(2, 'Paul', 'Antibes', 5000, 'FRA');

-- --------------------------------------------------------

--
-- Structure de la table `company`
--

CREATE TABLE `company` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `balance` float NOT NULL,
  `country_iso` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `company`
--

INSERT INTO `company` (`id`, `name`, `balance`, `country_iso`) VALUES
(1, 'ldlc 2', 10000, 'GER'),
(2, 'cdiscount', 10000, 'ESP');

-- --------------------------------------------------------

--
-- Structure de la table `country`
--

CREATE TABLE `country` (
  `iso3` varchar(3) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `country`
--

INSERT INTO `country` (`iso3`, `name`) VALUES
('ESP', 'Espagne'),
('FRA', 'France'),
('GER', 'Allemagne'),
('ITA', 'Italie');

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int NOT NULL,
  `tax` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `tax`) VALUES
(1, 'ordinateur', 120, 10),
(2, 'ordinateur portable', 80, 20),
(3, 'telephone', 80, 10),
(4, 'coque telephone', 13, 10);

-- --------------------------------------------------------

--
-- Structure de la table `provider`
--

CREATE TABLE `provider` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `country_iso` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `provider`
--

INSERT INTO `provider` (`id`, `name`, `address`, `country_iso`) VALUES
(1, 'Asus', 'Paris 9ème 75009', 'FRA'),
(2, 'Apple', '12 Rue Halévy, 75009 Paris', 'FRA'),
(3, 'Xiaomi', '39 Bd de Sébastopol, 75001 Paris', 'FRA');

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id` int NOT NULL,
  `id_provider` int DEFAULT NULL,
  `id_company` int DEFAULT NULL,
  `id_client` int DEFAULT NULL,
  `id_product` int NOT NULL,
  `number` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `stock`
--

INSERT INTO `stock` (`id`, `id_provider`, `id_company`, `id_client`, `id_product`, `number`) VALUES
(18, 2, NULL, NULL, 1, 300),
(19, 2, NULL, NULL, 3, 500),
(20, 3, NULL, NULL, 3, 500),
(21, 1, NULL, NULL, 2, 200);

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

CREATE TABLE `transaction` (
  `id` int NOT NULL,
  `id_product` int NOT NULL,
  `id_provider` int DEFAULT NULL,
  `id_company` int DEFAULT NULL,
  `id_client` int DEFAULT NULL,
  `id_employee` int DEFAULT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `admin` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `admin`) VALUES
(1, 'admin', 'admin', 1),
(2, 'user', 'user', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `country`
--
ALTER TABLE `country`
  ADD UNIQUE KEY `iso3` (`iso3`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `provider`
--
ALTER TABLE `provider`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `company`
--
ALTER TABLE `company`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `provider`
--
ALTER TABLE `provider`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
