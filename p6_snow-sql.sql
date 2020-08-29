-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 21 août 2020 à 23:34
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.3.14

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `p6_snow`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `figure_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `author_id`, `figure_id`, `content`, `create_date`) VALUES
(114, 173, 164, 'Superbe figure à réaliser', '2020-04-18 14:11:15'),
(115, 173, 164, 'il faut laisser le maximum les peaux sur la neige, et éviter de soulever les boards à chaque pas.', '2020-04-18 14:13:29'),
(116, 173, 164, 'il faut laisser le maximum les peaux sur la neige, et éviter de soulever les skis à chaque pas.', '2020-04-18 14:22:04'),
(117, 173, 164, 'tous les tricks de rail, le plus important est de garder la board le plus à plat possible, surtout ne pas se mettre sur la carre', '2020-04-18 14:23:06'),
(118, 173, 164, 'magnifique figure pleine de sensations', '2020-04-18 14:23:46'),
(119, 173, 164, 'Attention aux genous', '2020-04-18 18:31:17');

-- --------------------------------------------------------

--
-- Structure de la table `figure`
--

CREATE TABLE `figure` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `feature_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `fig_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL,
  `editor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `figure`
--

INSERT INTO `figure` (`id`, `title`, `slug`, `feature_image`, `description`, `fig_group`, `create_date`, `update_date`, `editor_id`) VALUES
(164, '50-50box', 'snowboard-figure-50-50box', '0.jpg', 'Un 50/50 ce qui signifie que vous glissez dans l’axe de la box ou du rail', 'flip', '2020-04-18 10:11:41', '2020-04-18 10:11:41', 172),
(165, 'back180', 'snowboard-figure-back180', '1.jpg', '180 back ou mais lorsque tu est a 90° c\'est le nose de la board qui vien toucher ce coller a la neige ', 'slide', '2020-04-18 10:11:41', '2020-04-18 10:11:41', 170),
(166, 'backboard', 'snowboard-figure-backboard', '2.jpg', 'Lorsque vous lancez des virages sur votre snowboard, commencez toujours par tourner la tête', 'flip', '2020-04-18 10:11:41', '2020-04-18 10:11:41', 172),
(167, 'front180', 'snowboard-figure-front180', '3.jpg', 'Pour une rotation dite \"ouverte\" (180° frontside), votre regard devra être porté dans le sens de la glisse', 'rotation', '2020-04-18 10:11:41', '2020-04-18 10:11:41', 171),
(168, 'frontside360', 'snowboard-figure-frontside360', '4.jpg', 'On arrive les jambes bien fléchies, les épaules dans l’axe de la board, le regard pointé vers le bout du kicker', 'rotation', '2020-04-18 10:11:41', '2020-04-18 10:11:41', 170),
(169, 'frontsideshifty', 'snowboard-figure-frontsideshifty', '5.jpg', 'La main arrière vient graber la carre frontside entre les pieds. Sur un saut droit c’est un Indy Grab', 'slide', '2020-04-18 10:11:41', '2020-04-18 10:11:41', 170),
(170, 'indygrab', 'snowboard-figure-indygrab', '6.jpg', 'Vous allez combiner la technique d\'un Switch Frontside 360​à contre-rotation, avec les mouvements de haute pression', 'slide', '2020-04-18 10:11:41', '2020-04-18 10:11:41', 172),
(171, 'noseroll', 'snowboard-figure-noseroll', '7.jpg', 'Appui leger sur le nose ( avant de la board ) l\'arriere est en l\'air', 'grab', '2020-04-18 10:11:41', '2020-04-18 10:11:41', 172),
(172, 'ollie', 'snowboard-figure-ollie', '8.jpg', 'Le Ollie est une impulsion  avec déformation de la planche qui permet de faire un saut', 'slide', '2020-04-18 10:11:41', '2020-04-18 10:11:41', 170);

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `figure_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20200216162438', '2020-02-16 16:41:52'),
('20200217134306', '2020-02-17 13:43:31'),
('20200226130112', '2020-02-26 13:01:55'),
('20200302190709', '2020-03-02 19:07:51'),
('20200305122910', '2020-03-05 12:30:28');

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE `photo` (
  `id` int(11) NOT NULL,
  `figure_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `photo`
--

INSERT INTO `photo` (`id`, `figure_id`, `title`, `filename`, `created_date`) VALUES
(73, 164, 'snow1', 'snow1-5e9b6dc3d044e.jpeg', '2020-04-18 23:14:43'),
(74, 164, 'snow2', 'snow2-5e9b6f1954e4c.jpeg', '2020-04-18 23:20:25'),
(75, 164, 'snow3', 'snow3-5e9b6f31f2e84.jpeg', '2020-04-18 23:20:49'),
(76, 164, 'snow4', 'snow4-5e9b6f4966766.jpeg', '2020-04-18 23:21:13'),
(77, 164, 'snow5', 'snow5-5e9b6fda21d14.jpeg', '2020-04-18 23:23:38'),
(78, 164, 'snow6', 'snow6-5e9b6feee3fb3.jpeg', '2020-04-18 23:23:58');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `picture`, `role`, `status`, `token`, `username`, `password`) VALUES
(170, 'Freda', 'Emard', 'mavis.conroy@ebert.com', 'default-avatar.png', 'ROLE_USER', 1, '0', 'ckunde', '$2y$13$g9wFLdaQGNBkofeLp7w9heU1MrBJtxIQ4ipJZpBD/No3qcXGrfPSm'),
(171, 'Ursula', 'Macejkovic', 'grimes.ozella@hotmail.com', 'default-avatar.png', 'ROLE_USER', 1, '0', 'ybeier', '$2y$13$7ZkfLgnRFYS42HjP2j5tAer5liWCYUGzJnVr623k7P.li7Ld11DGS'),
(172, 'Eloisa', 'Schoen', 'yvonrueden@doyle.com', 'default-avatar.png', 'ROLE_USER', 1, '0', 'qquitzon', '$2y$13$1bgQqX9JUrfSTVwwTLjFR.YUM4I066wc.mV4GLBoQ44Jg0WzXTZne'),
(173, 'bili', 'defer', 'bj@gg.hh', 'default-avatar.png', 'ROLE_USER', 1, '0', 'bojo', '$2y$13$J1d/mXiBA9cDKygmw/aDXupluuavPOWCXmzbnQVGqCoNHiahQGiBS'),
(175, 'jean', 'dupont', 'jdupont@mail.com', 'default-avatar.png', 'ROLE_USER', 1, 'c_m5ez84c0OjT9O7KvuUv15LlYi3ZJFg389wCheqZv8', 'jide', '$2y$13$Yf0BqnmheIj0LdgTO3I3HuztMDNTj.RKsSQq1oDwf/02YJiBbZVLe'),
(176, 'jean', 'durand', 'jd@mail.com', 'default-avatar.png', 'ROLE_USER', 1, '0', 'jedea', '$2y$13$jh8yGgJ22i3aVlAD4ZcNPOK9WSlO1c3dXvW.BRVH8Kbe7tfjg/VYy'),
(177, 'sveta', 'sveta', 'sveta@fr.fr', 'default-avatar.png', 'ROLE_WAIT', 0, '_jZGqc0v5Bp2MeQkG7hwnEeY1ONlwbI706xtT58uZ6o', 'sveta', '$2y$13$1TpGhstV5eIMascqaFjave179e7k09T0GymC3Jew7UdLst2cBjtmG'),
(184, 'fil', 'defer', 'fil@defer.fr', 'default-avatar.png', 'ROLE_WAIT', 0, '2LIbUDYqnUGe-9Smk-rgM8-gSfdtBw_Sm-aAUlrhrLc', 'filou', '$2y$13$2N9NJ9tUf25XxjfCSsCWj..3kQMTGfSS6xvGXn/Szn7cHDIoQzyBe');

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `figure_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `figure_id`, `title`, `created_date`, `url`) VALUES
(38, 166, 'backlog video11', '2020-04-18 13:46:36', 'https://www.youtube.com/embed/R2Cp1RumorU'),
(40, 164, 'vid2', '2020-04-18 23:19:58', 'https://www.youtube.com/embed/R2Cp1RumorU'),
(41, 164, 'vid3', '2020-04-18 23:28:46', 'https://www.youtube.com/embed/R2Cp1RumorU'),
(42, 164, 'vid6', '2020-04-19 10:00:52', 'https://www.youtube.com/embed/R2Cp1RumorU'),
(43, 164, 'vid5', '2020-04-19 10:02:03', 'https://www.youtube.com/embed/R2Cp1RumorU'),
(44, 164, 'vid7', '2020-04-19 10:02:30', 'https://www.youtube.com/embed/R2Cp1RumorU'),
(45, 164, 'vid9', '2020-04-19 10:41:07', 'https://www.youtube.com/embed/R2Cp1RumorU'),
(46, 164, 'vid299', '2020-04-19 12:42:32', 'https://www.youtube.com/embed/R2Cp1RumorU');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526CF675F31B` (`author_id`),
  ADD KEY `IDX_9474526C5C011B5` (`figure_id`);

--
-- Index pour la table `figure`
--
ALTER TABLE `figure`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_2F57B37A2B36786B` (`title`),
  ADD KEY `IDX_2F57B37A6995AC4C` (`editor_id`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6A2CA10C5C011B5` (`figure_id`);

--
-- Index pour la table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_14B784185C011B5` (`figure_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CC7DA2C5C011B5` (`figure_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT pour la table `figure`
--
ALTER TABLE `figure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT pour la table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C5C011B5` FOREIGN KEY (`figure_id`) REFERENCES `figure` (`id`),
  ADD CONSTRAINT `FK_9474526CF675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `figure`
--
ALTER TABLE `figure`
  ADD CONSTRAINT `FK_2F57B37A6995AC4C` FOREIGN KEY (`editor_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `FK_6A2CA10C5C011B5` FOREIGN KEY (`figure_id`) REFERENCES `figure` (`id`);

--
-- Contraintes pour la table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `FK_14B784185C011B5` FOREIGN KEY (`figure_id`) REFERENCES `figure` (`id`);

--
-- Contraintes pour la table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `FK_7CC7DA2C5C011B5` FOREIGN KEY (`figure_id`) REFERENCES `figure` (`id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
