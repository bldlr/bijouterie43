-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 21 avr. 2020 à 00:16
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bijouterie2`
--

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

CREATE TABLE `departement` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `departement`
--

INSERT INTO `departement` (`id`, `nom`, `code`, `region_id`) VALUES
(1, 'Ain', '1', 3),
(2, 'Aisne', '2', 7),
(3, 'Allier', '3', 3),
(4, 'Alpes-de-Haute-Provence', '4', 13),
(5, 'Hautes-Alpes', '5', 13),
(6, 'Alpes-Maritimes', '6', 13),
(7, 'Ardèche', '7', 1),
(8, 'Ardennes', '8', 6),
(9, 'Ariège', '9', 11),
(10, 'Aube', '10', 6),
(11, 'Aude', '11', 11),
(12, 'Aveyron', '12', 11),
(13, 'Bouches-du-Rhône', '13', 13),
(14, 'Calvados', '14', 9),
(15, 'Cantal', '15', 1),
(16, 'Charente', '16', 10),
(17, 'Charente-Maritime', '17', 10),
(18, 'Cher', '18', 4),
(19, 'Corrèze', '19', 10),
(20, 'Corse-du-Sud', '2A', 5),
(21, 'Haute-Corse', '2B', 5),
(22, 'Côte-d\'Or', '21', 2),
(23, 'Côtes-d\'Armor', '22', 3),
(24, 'Creuse', '23', 10),
(25, 'Dordogne', '24', 10),
(26, 'Doubs', '25', 2),
(27, 'Drôme', '26', 1),
(28, 'Eure', '27', 9),
(29, 'Eure-et-Loir', '28', 4),
(30, 'Finistère', '29', 3),
(31, 'Gard', '30', 11),
(32, 'Haute-Garonne', '31', 11),
(33, 'Gers', '32', 11),
(34, 'Gironde', '33', 10),
(35, 'Hérault', '34', 11),
(36, 'Ile-et-Vilaine', '35', 3),
(37, 'Indre', '36', 4),
(38, 'Indre-et-Loire', '37', 4),
(39, 'Isère', '38', 1),
(40, 'Jura', '39', 2),
(41, 'Landes', '40', 10),
(42, 'Loir-et-Cher', '41', 4),
(43, 'Loire', '42', 1),
(44, 'Haute-Loire', '43', 1),
(45, 'Loire-Atlantique', '44', 12),
(46, 'Loiret', '45', 4),
(47, 'Lot', '46', 11),
(48, 'Lot-et-Garonne', '47', 10),
(49, 'Lozère', '48', 11),
(50, 'Maine-et-Loire', '49', 12),
(51, 'Manche', '50', 9),
(52, 'Marne', '51', 6),
(53, 'Haute-Marne', '52', 6),
(54, 'Mayenne', '53', 12),
(55, 'Meurthe-et-Moselle', '54', 6),
(56, 'Meuse', '55', 6),
(57, 'Morbihan', '56', 3),
(58, 'Moselle', '57', 6),
(59, 'Nièvre', '58', 2),
(60, 'Nord', '59', 7),
(61, 'Oise', '60', 7),
(62, 'Orne', '61', 9),
(63, 'Pas-de-Calais', '62', 7),
(64, 'Puy-de-Dôme', '63', 1),
(65, 'Pyrénées-Atlantiques', '64', 10),
(66, 'Hautes-Pyrénées', '65', 11),
(67, 'Pyrénées-Orientales', '66', 11),
(68, 'Bas-Rhin', '67', 6),
(69, 'Haut-Rhin', '68', 6),
(70, 'Rhône', '69', 1),
(71, 'Haute-Saône', '70', 2),
(72, 'Saône-et-Loire', '71', 2),
(73, 'Sarthe', '72', 12),
(74, 'Savoie', '73', 1),
(75, 'Haute-Savoie', '74', 1),
(76, 'Paris', '75', 8),
(77, 'Seine-Maritime', '76', 9),
(78, 'Seine-et-Marne', '77', 8),
(79, 'Yvelines', '78', 8),
(80, 'Deux-Sèvres', '79', 10),
(81, 'Somme', '80', 7),
(82, 'Tarn', '81', 11),
(83, 'Tarn-et-Garonne', '82', 11),
(84, 'Var', '83', 13),
(85, 'Vaucluse', '84', 13),
(86, 'Vendée', '85', 12),
(87, 'Vienne', '86', 10),
(88, 'Haute-Vienne', '87', 10),
(89, 'Vosges', '88', 6),
(90, 'Yonne', '89', 2),
(91, 'Territoire de Belfort', '90', 2),
(92, 'Essonne', '91', 8),
(93, 'Hauts-de-Seine', '92', 8),
(94, 'Seine-Saint-Denis', '93', 8),
(95, 'Val-de-Marne', '94', 8),
(96, 'Val d\'oise', '95', 8);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `departement`
--
ALTER TABLE `departement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C1765B6398260155` (`region_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `departement`
--
ALTER TABLE `departement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `departement`
--
ALTER TABLE `departement`
  ADD CONSTRAINT `FK_C1765B6398260155` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
