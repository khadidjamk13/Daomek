-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 01 mai 2024 à 21:32
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `PFE`
--

-- --------------------------------------------------------

--
-- Structure de la table `agence`
--

CREATE TABLE `agence` (
  `id_a` int(11) NOT NULL,
  `nom_a` text NOT NULL,
  `localisation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bien`
--

CREATE TABLE `bien` (
  `id_b` int(11) NOT NULL,
  `type_b` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bien`
--

INSERT INTO `bien` (`id_b`, `type_b`) VALUES
(1, 'Appartement'),
(2, 'Bungalow'),
(3, 'Ferme'),
(4, 'Bureau'),
(5, 'Immeuble'),
(6, 'Studio'),
(7, 'Villa'),
(8, 'Terrain'),
(9, 'Duplex');

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

CREATE TABLE `favoris` (
  `id_f` int(24) NOT NULL,
  `id` int(24) DEFAULT NULL,
  `id_p` int(24) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Images`
--

CREATE TABLE `Images` (
  `id` int(11) NOT NULL,
  `path` varchar(200) NOT NULL,
  `id_p` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Images`
--

INSERT INTO `Images` (`id`, `path`, `id_p`) VALUES
(44, 'Images/a32.jpg', 16),
(45, 'Images/a33.jpg', 16),
(46, 'Images/a34.jpg', 16),
(47, 'Images/a35.jpg\r\n', 16),
(48, 'Images/a3.jpg', 16),
(49, 'Images/v1.jpg', 17),
(50, 'Images/v12.jpg', 17),
(51, 'Images/a21.jpg', 16),
(52, 'Images/a2.jpg', 18),
(53, 'Images/a22.jpg', 18),
(54, 'Images/a23.jpg', 18),
(55, 'Images/a24.jpg', 18),
(56, 'Images/a25.jpg', 18),
(57, 'Images/a4.jpg', 19),
(58, 'Images/a42.jpg', 19),
(59, 'Images/a45.jpg', 19),
(60, 'Images/a43.jpg', 19),
(62, 'Images/image_6631974abc0ac.jpg', 21),
(63, 'Images/image_6631975bdff81.jpg', 22),
(64, 'Images/image_6632198845c79.jpg', 23),
(65, 'Images/image_6632198846139.jpg', 23);

-- --------------------------------------------------------

--
-- Structure de la table `operation`
--

CREATE TABLE `operation` (
  `id_o` int(11) NOT NULL,
  `type_o` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `operation`
--

INSERT INTO `operation` (`id_o`, `type_o`) VALUES
(1, 'Cherche achat'),
(2, 'Location'),
(3, 'Colocation'),
(4, 'Echange');

-- --------------------------------------------------------

--
-- Structure de la table `proprietes`
--

CREATE TABLE `proprietes` (
  `id_p` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `id_b` int(11) NOT NULL,
  `id_o` int(11) NOT NULL,
  `prix` int(11) NOT NULL,
  `piece` int(10) NOT NULL,
  `superficie` int(11) NOT NULL,
  `id_w` int(11) NOT NULL,
  `emplacement` varchar(255) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `proprietes`
--

INSERT INTO `proprietes` (`id_p`, `titre`, `description`, `id_b`, `id_o`, `prix`, `piece`, `superficie`, `id_w`, `emplacement`, `id`) VALUES
(16, 'appartement à vendre', '', 1, 1, 20000000, 4, 160, 31, 'mobilart,oran', 19),
(17, 'villa à vendre', '', 7, 1, 30000000, 5, 160, 23, 'elbouni,annaba', 20),
(18, 'appartement à vendre bejaia', '', 1, 1, 18000000, 4, 160, 6, 'akbou,bejaia', 21),
(19, 'appartement', '', 1, 2, 30000, 3, 90, 16, 'haidra,alger', 22),
(21, 'appartement à vendre', 'appartementvgdjjhb nv ns', 1, 1, 180000, 3, 100, 9, 'hbejehjdc', 23),
(22, 'appartement à vendre', 'appartementvgdjjhb nv ns', 1, 1, 180000, 3, 100, 9, 'hbejehjdc', 23),
(23, 'hrgfed', 'gref', 1, 1, 180000, 2, 111, 26, 'gre', 23);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `email` varchar(230) NOT NULL,
  `password` varchar(60) NOT NULL,
  `date_naissance` date NOT NULL,
    `photo_profile` varchar(230) NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `nom`, `prenom`, `email`, `password`, `date_naissance`) VALUES
(19, 'khadidjamk', 'Mekadim', 'Khadidja', 'khadidja@gmail.com', '123', '2003-09-13'),
(20, 'mounira', 'Daoudi', 'Mounira', 'mounira@gmail.com', '123', '2003-07-21'),
(21, 'baya', 'issolah', 'baya', 'baya@gmail.com', '123', '2003-06-18'),
(22, 'lina', 'Ballalou', 'lina', 'lina@gmail.com', '123', '2002-12-30'),
(23, 'zekkar', 'douaa', 'douaa', 'douaa@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-04-11');

-- --------------------------------------------------------

--
-- Structure de la table `wilaya`
--

CREATE TABLE `wilaya` (
  `id_w` int(11) NOT NULL,
  `nom_w` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `wilaya`
--

INSERT INTO `wilaya` (`id_w`, `nom_w`) VALUES
(1, 'Adrar'),
(2, 'Chlef'),
(3, 'Laghouat'),
(4, 'Oum El Bouaghi'),
(5, 'Batna'),
(6, 'Béjaïa'),
(7, 'Biskra'),
(8, 'Béchar'),
(9, 'Blida'),
(10, 'Bouira'),
(11, 'Tamanrasset'),
(12, 'Tébessa'),
(13, 'Tlemcen'),
(14, 'Tiaret'),
(15, 'Tizi Ouzou'),
(16, 'Alger'),
(17, 'Djelfa'),
(18, 'Jijel'),
(19, 'Sétif'),
(20, 'Saida'),
(21, 'Skikda'),
(22, 'Sidi Bel Abbès'),
(23, 'Annaba'),
(24, 'Guelma'),
(25, 'Constantine'),
(26, 'Médéa'),
(27, 'Mostaganem'),
(28, 'Msila'),
(29, 'Mascara'),
(30, 'Ouargla'),
(31, 'Oran'),
(32, 'El Bayadh'),
(33, 'Illizi'),
(34, 'Bordj Bou Arréridj'),
(35, 'Boumerdès'),
(36, 'El Tarf'),
(37, 'Tindouf'),
(38, 'Tissemsilt'),
(39, 'El Oued'),
(40, 'Khenchela'),
(41, 'Souk Ahras'),
(42, 'Tipaza'),
(43, 'Mila'),
(44, 'Aïn Defla'),
(45, 'Naâma'),
(46, 'Aïn Témouchent'),
(47, 'Ghardaïa'),
(48, 'Relizane'),
(49, 'Timimoun'),
(50, 'Bordj Badji Mokhtar'),
(51, 'Ouled Djellal'),
(52, 'Béni Abbès'),
(53, 'In Salah'),
(54, 'In Guezzam'),
(55, 'Touggourt'),
(56, 'Djanet'),
(57, 'El M\'Ghair'),
(58, 'El Meniaa');
--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agence`
--
ALTER TABLE `agence`
  ADD PRIMARY KEY (`id_a`);

--
-- Index pour la table `bien`
--
ALTER TABLE `bien`
  ADD PRIMARY KEY (`id_b`),
  ADD KEY `type_b` (`type_b`(768)),
  ADD KEY `id_b` (`id_b`);

--
-- Index pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`id_f`);

--
-- Index pour la table `Images`
--
ALTER TABLE `Images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_p` (`id_p`);

--
-- Index pour la table `operation`
--
ALTER TABLE `operation`
  ADD PRIMARY KEY (`id_o`);

--
-- Index pour la table `proprietes`
--
ALTER TABLE `proprietes`
  ADD PRIMARY KEY (`id_p`),
  ADD KEY `id_b` (`id_b`),
  ADD KEY `id_o` (`id_o`),
  ADD KEY `id_w` (`id_w`),
  ADD KEY `id` (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `wilaya`
--
ALTER TABLE `wilaya`
  ADD PRIMARY KEY (`id_w`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agence`
--
ALTER TABLE `agence`
  MODIFY `id_a` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `bien`
--
ALTER TABLE `bien`
  MODIFY `id_b` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `favoris`
--
ALTER TABLE `favoris`
  MODIFY `id_f` int(24) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Images`
--
ALTER TABLE `Images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT pour la table `operation`
--
ALTER TABLE `operation`
  MODIFY `id_o` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `proprietes`
--
ALTER TABLE `proprietes`
  MODIFY `id_p` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `wilaya`
--
ALTER TABLE `wilaya`
  MODIFY `id_w` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Images`
--
ALTER TABLE `Images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`id_p`) REFERENCES `proprietes` (`id_p`);

--
-- Contraintes pour la table `proprietes`
--
ALTER TABLE `proprietes`
  ADD CONSTRAINT `id` FOREIGN KEY (`id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `proprietes_ibfk_1` FOREIGN KEY (`id_b`) REFERENCES `bien` (`id_b`),
  ADD CONSTRAINT `proprietes_ibfk_2` FOREIGN KEY (`id_o`) REFERENCES `operation` (`id_o`),
  ADD CONSTRAINT `proprietes_ibfk_3` FOREIGN KEY (`id_w`) REFERENCES `wilaya` (`id_w`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
