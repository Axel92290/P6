-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 30 août 2025 à 19:34
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `snowtricks`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tricks_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_5F9E962A3B153154` (`tricks_id`),
  KEY `IDX_5F9E962AA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `tricks_id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(60, 32, 14, 'C\'est un de mes tricks préféré !', '2025-08-30 19:29:37', '2025-08-30 19:29:37');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240924182246', '2025-05-01 20:39:03', 4);

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

DROP TABLE IF EXISTS `reset_password_request`;
CREATE TABLE IF NOT EXISTS `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tricks`
--

DROP TABLE IF EXISTS `tricks`;
CREATE TABLE IF NOT EXISTS `tricks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `chapo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_E1D902C1A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tricks`
--

INSERT INTO `tricks` (`id`, `user_id`, `name`, `created_at`, `updated_at`, `chapo`, `description`) VALUES
(23, 12, 'Ollie', '2025-08-30 18:32:34', '2025-08-30 18:32:35', 'Le premier trick que tout snowboarder doit maîtriser.', 'L’ollie consiste à faire décoller la planche du sol sans l’aide d’une bosse ou d’un tremplin. Le rider fléchit ses jambes, appuie sur le tail (l’arrière) pour comprimer le snowboard, puis ramène ses genoux vers la poitrine pour décoller. C’est la base de tous les sauts et il permet de franchir de petits obstacles ou d’amorcer des rotations plus complexes.'),
(24, 12, 'Nose Grab', '2025-08-30 18:47:35', '2025-08-30 18:47:35', 'Un grab classique qui ajoute du style aux sauts.', 'Pendant un saut, le rider attrape l’avant (nose) de sa planche avec sa main avant. Cela demande une bonne flexion des jambes pour ramener la planche suffisamment près. Simple visuellement mais très stylé lorsqu’il est bien tenu.'),
(25, 12, 'Indy Grab', '2025-08-30 18:47:46', '2025-08-30 18:47:46', 'L’un des grabs les plus emblématiques du snowboard freestyle.', 'L’Indy Grab consiste à attraper la carre frontside de la board (côté orteils) avec la main arrière, entre les fixations. C’est un grab accessible mais toujours apprécié pour son esthétique. Les riders avancés l’accompagnent d’une extension des jambes ou d’une torsion pour plus de style.'),
(26, 12, 'Tail Grab', '2025-08-30 18:47:55', '2025-08-30 18:47:56', 'L’opposé du nose grab, tout aussi stylé.', 'Dans ce trick, le rider attrape l’arrière (tail) de sa board avec la main arrière. Cela requiert un bon timing au moment du saut et une bonne amplitude pour pouvoir tendre la main jusqu’au tail. Souvent combiné avec des rotations pour donner plus d’impact visuel.'),
(27, 12, '180°', '2025-08-30 18:48:08', '2025-08-30 18:48:09', 'La première rotation à apprendre.', 'Le 180° est une rotation d’un demi-tour en l’air. Le rider part en frontside ou backside et atterrit en switch (c’est-à-dire en marche arrière par rapport à sa position de départ). C’est une étape clé avant de progresser vers des rotations plus complexes.'),
(28, 12, '360°', '2025-08-30 18:48:17', '2025-08-30 18:48:17', 'La rotation complète par excellence.', 'Le rider effectue un tour complet en l’air, en frontside (vers les orteils) ou en backside (vers les talons). Le 360° demande un bon engagement des épaules et du buste dès l’impulsion, ainsi qu’un bon contrôle à la réception. C’est l’un des tricks freestyle les plus populaires.'),
(29, 12, 'Method', '2025-08-30 18:48:25', '2025-08-30 18:48:26', 'Le grab le plus mythique du snowboard.', 'Le Method consiste à attraper la carre backside avec la main avant tout en arquant le corps et en poussant la board derrière soi. C’est un trick très stylisé et personnel, chaque rider y ajoute sa touche. Bien exécuté, il symbolise l’élégance et la créativité en snowboard.'),
(30, 12, 'Backflip', '2025-08-30 18:48:36', '2025-08-30 18:48:36', 'Le salto arrière, un classique du freestyle aérien.', 'Le rider effectue une rotation complète vers l’arrière en l’air. Le backflip nécessite confiance, engagement et une bonne maîtrise de son impulsion. C’est un trick impressionnant qui marque les spectateurs et qui reste une figure incontournable des compétitions freestyle.'),
(31, 12, 'Frontside Boardslide', '2025-08-30 18:48:46', '2025-08-30 18:48:46', 'Le slide de base sur barres et boxes.', 'Le rider monte sur un rail ou une box avec sa planche perpendiculaire, en tournant vers l’avant (frontside). Le centre de gravité doit rester bien équilibré pour maintenir le slide jusqu’au bout. C’est le trick d’entrée en matière pour le jibbing (slides sur modules).'),
(32, 12, 'Cork 540', '2025-08-30 18:48:59', '2025-08-30 18:48:59', 'La rotation désaxée qui impressionne les foules.', 'Le rider effectue une rotation de 540° (un tour et demi) avec un axe désaxé, ce qui donne une impression de vrille dans les airs. Le cork demande une très bonne technique de saut, une forte impulsion et un repère spatial parfait pour réussir la réception. C’est une figure de niveau expert, souvent réalisée en big air.');

-- --------------------------------------------------------

--
-- Structure de la table `tricks_photo`
--

DROP TABLE IF EXISTS `tricks_photo`;
CREATE TABLE IF NOT EXISTS `tricks_photo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tricks_id` int NOT NULL,
  `path` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_first` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_CD87BA713B153154` (`tricks_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tricks_photo`
--

INSERT INTO `tricks_photo` (`id`, `tricks_id`, `path`, `is_first`, `created_at`, `updated_at`) VALUES
(34, 23, 'Ollie-68b3465cd2d28.jpg', 0, '2025-08-30 18:43:40', NULL),
(35, 23, 'ollie2-1-68b34729ca90d.jpg', 1, '2025-08-30 18:47:05', NULL),
(36, 24, 'NoseGrab-68b348075be31.jpg', 0, '2025-08-30 18:50:47', NULL),
(37, 24, 'nosetail2-68b34832f31d8.jpg', 1, '2025-08-30 18:51:31', NULL),
(38, 25, 'Trick-Indy-Grab-68b34daeafaeb.jpg', 0, '2025-08-30 19:14:54', NULL),
(39, 25, 'IndyGrab-68b34dca1a849.jpg', 1, '2025-08-30 19:15:22', NULL),
(40, 26, 'Trick-Tail-Grab-68b34e368bf50.jpg', 0, '2025-08-30 19:17:10', NULL),
(41, 26, 'tailgrab-1-68b34e4685696.jpg', 1, '2025-08-30 19:17:26', NULL),
(42, 27, '180-68b34e8192030.jpg', 0, '2025-08-30 19:18:25', NULL),
(43, 27, 'FS180-68b34e95c42a9.jpg', 1, '2025-08-30 19:18:45', NULL),
(44, 28, '360-68b34ed296cf1.jpg', 0, '2025-08-30 19:19:46', NULL),
(45, 28, '360-68b34f0154d40.jpg', 1, '2025-08-30 19:20:33', NULL),
(46, 29, 'method-68b34f439a0da.jpg', 1, '2025-08-30 19:21:39', NULL),
(47, 29, '21-large-68b34f50d24cf.jpg', 0, '2025-08-30 19:21:52', NULL),
(48, 30, 'how-to-backflip-snowboard-800-68b34f80eacfd.jpg', 0, '2025-08-30 19:22:40', NULL),
(49, 30, 'Backflip-68b34fa8d39ed.jpg', 1, '2025-08-30 19:23:20', NULL),
(50, 31, 'Whitelines-95-gap-to-fronslide-boardslide-68b34fef337c5.jpg', 1, '2025-08-30 19:24:31', NULL),
(51, 31, 'FS-Boardslide-620x413-68b34ffc575b7.jpg', 0, '2025-08-30 19:24:44', NULL),
(52, 32, 'maxresdefault-68b35052e1d97.jpg', 1, '2025-08-30 19:26:10', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tricks_video`
--

DROP TABLE IF EXISTS `tricks_video`;
CREATE TABLE IF NOT EXISTS `tricks_video` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tricks_id` int NOT NULL,
  `path` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_A5F7E4453B153154` (`tricks_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tricks_video`
--

INSERT INTO `tricks_video` (`id`, `tricks_id`, `path`, `created_at`, `updated_at`) VALUES
(32, 23, '<iframe width=\"1250\" height=\"703\" src=\"https://www.youtube.com/embed/N4hCpTt30GE\" title=\"How To Ollie and Improve Your Snowboard Tricks\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>', '2025-08-30 18:42:43', NULL),
(33, 24, '<iframe width=\"1250\" height=\"703\" src=\"https://www.youtube.com/embed/gZFWW4Vus-Q\" title=\"How To Nose Grab - Snowboarding Tricks\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>', '2025-08-30 18:49:47', NULL),
(34, 25, '<iframe width=\"1250\" height=\"703\" src=\"https://www.youtube.com/embed/iKkhKekZNQ8\" title=\"How to Indy Grab - Snowboarding Tricks Regular\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>', '2025-08-30 19:14:08', NULL),
(35, 26, '<iframe width=\"1250\" height=\"703\" src=\"https://www.youtube.com/embed/id8VKl9RVQw\" title=\"How to Tail Grab - Snowboarding Tricks\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>', '2025-08-30 19:15:52', NULL),
(36, 27, '<iframe width=\"1250\" height=\"703\" src=\"https://www.youtube.com/embed/XyARvRQhGgk\" title=\"How to Twist 180 on a Snowboard - Snowboarding Tricks\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>', '2025-08-30 19:18:57', NULL),
(37, 28, '<iframe width=\"1250\" height=\"703\" src=\"https://www.youtube.com/embed/CmC62IHOdE4\" title=\"Faire un 360 en snowboard ! Châtel snowpark\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>', '2025-08-30 19:20:46', NULL),
(38, 29, '<iframe width=\"1250\" height=\"703\" src=\"https://www.youtube.com/embed/_hxLS2ErMiY\" title=\"How to Method Grab on a Snowboard - (Regular) Methods Trick Tip\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>', '2025-08-30 19:21:24', NULL),
(39, 30, '<iframe width=\"1250\" height=\"703\" src=\"https://www.youtube.com/embed/5bpzng08nzk\" title=\"How To Backflip on a Snowboard - Snowboarding Tricks\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>', '2025-08-30 19:22:45', NULL),
(40, 31, '<iframe width=\"1250\" height=\"703\" src=\"https://www.youtube.com/embed/ault8bhj3KY\" title=\"How to Frontside Boardslide on a Snowboard - Snowboarding Tricks\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>', '2025-08-30 19:23:47', NULL),
(41, 32, '<iframe width=\"1250\" height=\"703\" src=\"https://www.youtube.com/embed/P5ZI-d-eHsI\" title=\"How To Backside Cork 540 On A Snowboard\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" allowfullscreen></iframe>', '2025-08-30 19:25:13', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `username`) VALUES
(12, 'admin@test.com', '[\"ROLE_ADMIN\"]', '$2y$13$5.JVgkC0urENpCqgGT8q1uucDIKz3g0C3My.YP/pSPTM.0F7E1kW.', 0, 'Admin'),
(13, 'axel@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$10sIzdYUcSSQJLDK.GJdquUxEWyWZAy3zMoIXFtvmG1OwAdPFE3C.', 1, 'Axel290'),
(14, 'user1@test.com', '[\"ROLE_USER\"]', '$2y$13$qOazk.Uj7NbS48kxzcRFCO1zEOdpkWoan8Ix.s.S4Kiii8nvv.Oke', 1, 'Maxime123');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `FK_5F9E962A3B153154` FOREIGN KEY (`tricks_id`) REFERENCES `tricks` (`id`),
  ADD CONSTRAINT `FK_5F9E962AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `tricks`
--
ALTER TABLE `tricks`
  ADD CONSTRAINT `FK_E1D902C1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `tricks_photo`
--
ALTER TABLE `tricks_photo`
  ADD CONSTRAINT `FK_CD87BA713B153154` FOREIGN KEY (`tricks_id`) REFERENCES `tricks` (`id`);

--
-- Contraintes pour la table `tricks_video`
--
ALTER TABLE `tricks_video`
  ADD CONSTRAINT `FK_A5F7E4453B153154` FOREIGN KEY (`tricks_id`) REFERENCES `tricks` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
