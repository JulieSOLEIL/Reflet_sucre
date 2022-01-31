-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 31 jan. 2022 à 23:24
-- Version du serveur : 10.4.21-MariaDB
-- Version de PHP : 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `reflet_sucre`
--

-- --------------------------------------------------------

--
-- Structure de la table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `address`
--

INSERT INTO `address` (`id`, `user_id`, `name`, `firstname`, `lastname`, `company`, `address`, `postal`, `city`, `country`, `phone`) VALUES
(1, 5, '82 Boulevard Masséna', 'Julie', 'SUN', NULL, '82 Boulevard Masséna, Appart 307', '75013', 'PARIS (PARIS 13)', 'FR', '0601281540'),
(2, 5, '15 boulevard arago', 'Julie', 'SUN', NULL, '15 boulevard arago, code 1236 puis 9351, 4e etage porte droite', '75013', 'Paris', 'FR', '0601281540');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Entremets'),
(2, 'Tartes'),
(3, 'Gâteau de voyage'),
(4, 'Macarons');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220119165533', '2022-01-19 17:56:44', 33),
('DoctrineMigrations\\Version20220119173739', '2022-01-19 18:38:10', 26),
('DoctrineMigrations\\Version20220124152134', '2022-01-24 16:21:58', 34),
('DoctrineMigrations\\Version20220125150835', '2022-01-25 16:08:53', 167),
('DoctrineMigrations\\Version20220126143345', '2022-01-26 15:33:54', 230),
('DoctrineMigrations\\Version20220131160035', '2022-01-31 17:00:46', 152);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `illustration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `pastry_part` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `category_id`, `name`, `slug`, `illustration`, `subtitle`, `description`, `price`, `pastry_part`) VALUES
(1, 4, 'Tiramisu Mac\'', 'Tiramisu Mac\'', '31c4e284ae27e430f08af4cbb42ca336cc1ec09a.jpg', 'Un macaron façon tiramisu', 'Macaron ganache chocolat avec liqueur et à la crème de mascarpone.\r\nIngrédients: chocolat noir, liqueur de chocolat, mascarpone, crème liquide, poudre d\'amande, poudre cacao, sucre, sucre glace, blanc d\'œufs, liqueur amaretto.', 1000, 'vendu par 6'),
(2, 4, 'Le Citron Mac\'', 'Le Citron Mac\'', '3182039b7e8cda019788cbb744af9e8fdc036928.png', 'Macaron acidulé au citron', 'Macaron garni d\'une crème montée au jus de citron jaune et ses zestes. \r\nIngrédients: Poudre amande, sucre glace, sucre cristal, crème liquide, jus de citron jaune, zestes de citron jaune,  œufs entiers, lait, maïzena, chocolat blanc.', 1000, 'Vendu par 6'),
(3, 2, 'Tarte Banana', 'Tarte Banana', '8b9fac77997c21ab3f4664d181a7c00f6b450f6a.jpg', 'La banane à l\'état pur', 'Tartelette individuelle garni d\'une crème montée légère à la vanille, et surmonté de lamelles de bananes et une fine couche de sirop de sucre.\r\nIngrédients: farine de blé, œufs entiers, sucre, sel, beurre, banane, eau, gousse de vanille, crème liquide, gélatine animale, lait.', 450, 'Vendu à l\'unité'),
(4, 2, 'Tarte Capuccino', 'Tarte Capuccino', '184fca5e7cf5a5699c99ec37e9470a876cd19886.jpg', 'Une tartelette aux arômes brutes de café italien', 'Tartelette individuelle à la ganache montée café, praliné amande et noisette café, avec un croustillant café.\r\n\r\nIngrédients: farine de blé, maïzena, sucre, sel, amandes, noisettes, café italien, grue de cacao, poudre cacao, crème liquide, gélatine animale, beurre, beurre de cacao, œufs entiers, chocolat noir, chocolat blanc.', 560, 'Vendu à l\'unité'),
(5, 2, 'Tarte citron meringuée au sarrasin', 'Tarte citron meringuée au sarrasin', '456e9fa453bdcc7e605e4a12f2e05c3e944f36f6.jpg', 'Mariage du citron et du sarrasin', 'Tartelette individuelle faite à partir d\'une pâte sucrée au sarrasin et ses graines torréfiées, accompagnée d\'une crème de citron, surmontée d\'une belle meringue aux zestes et décorée de quelques graines torréfiées de sarrasins.\r\n\r\nIngrédients:  farine de blé, farine de sarrasin, graines torréfiées de sarrasin, beurre, sucre, sel, œufs entiers, crème liquide, jus de citrons, zestes.', 450, 'Vendu à la part'),
(6, 1, 'Charlotte aux fruits rouges', 'Charlotte aux fruits rouges', '507ac79273042c7b36966309c23ac621e88e3a11.jpg', 'Un gâteau léger aux fruits rouges', 'Entremets construit avec une base de génoise et une crème diplomate vanille et garni de fruits rouges.\r\nIngrédients: farine de blé, sucre, beurre, œufs, crème liquide, lait, gélatine animale, gousse de vanille, fraises, myrtilles, groseilles, framboises, mûres.', 4000, 'Vendu pour 6 à 8 parts'),
(7, 1, 'Number Cake', 'Number Cake', 'bbf3ddd03ead8da7ad7226ec70adc084a35b3e9b.jpg', 'Entremets idéal pour un anniversaire', 'Entremets garni d\'une base en pâte sucrée noisettes, surmonté d\'une crème diplomate vanille, fruits de saison, et de macarons. \r\nPossibilité de modifier la taille de l\'entremets.\r\nIngrédients: farine de blé, poudre noisettes, maïzena, gousse de vanille, sucre, beurre, sel, œufs, crème liquide, lait, gélatine animale, fruits de saison, macarons(poudre amande, lait, sucre, œufs, chocolat noir, chocolat blanc, crème liquide).', 7000, 'Vendu pour 15 à 20 parts'),
(8, 3, 'Cannelé authentique', 'Cannelé authentique', 'c13838e0b85f9ccebaceaa823e01c719650e9efa.png', 'Cannelé au bon de vanille et de rhum', 'Des cannelés authentiques au bon goût de vanille et de rhum brun cuits dans les moules en cuivre.\r\nIngrédients: Farine de blé, lait, œufs, beurre, sucre, sel, vanille, rhum brun, trace de cires d\'abeilles.', 200, 'Vendu à l\'unité'),
(9, 3, 'Banana bread', 'Banana bread', '5c7a91088141272c830ca5a5d7159fcb3f8587cf.jpg', 'Moelleux banana bread aux saveurs épicés', 'Ingrédients: farine de blé, poudre amande, levure chimique, noix, bananes, lait, jus de citron, miel, épices, sucre, sel, œufs.', 350, 'Vendu à l\'unité');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`) VALUES
(1, 'sun.julie0@gmail.com', '[]', '$2y$13$hTO7L1WvfjSojuaCgSHu1.J1PVPg0VJ5/hCK8O17fiWtSQyuMtzoa', 'Julie', 'SUN'),
(4, 'amal@gmail.com', '[]', '$2y$13$MVpHhh/lf1FmuIEr4U6PyOD2U0UJjCwKiIr0UVaZG32wHQy.mbq3G', 'Amal', 'Khan'),
(5, 'julie@gmail.com', '[]', '$2y$13$cjeuWe8m4/J3cyQ.wGr7m.U2X33cMK0nN.i/nAcgsyTo0Dj1qDnGi', 'Julie', 'SUN'),
(6, 'chloe@gmail.com', '[]', '$2y$13$9If06/u.7a3eNMTtHYBTH.n/NdVSCJJrd39GwOSnYmrNDwiIP9Cva', 'Chloé', 'Badaroux');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D4E6F81A76ED395` (`user_id`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04AD12469DE2` (`category_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `FK_D4E6F81A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
