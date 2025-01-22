-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 21 jan. 2025 à 13:28
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mocha`
--

-- --------------------------------------------------------

--
-- Structure de la table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `customers`
--

INSERT INTO `customers` (`customer_id`, `email`, `password`, `first_name`, `last_name`, `phone`, `created_at`) VALUES
(1, 'j.lasnierconfolant@gmail.com', '$2y$10$HAcNJMiglamDPjx8XQd6eeGw8fQcSlLBR3qJUgGrdLse4AIy3mn8q', 'Justine', 'Lasnier-Confolant', '0695985810', '2025-01-19 16:54:16');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_datetime` datetime DEFAULT current_timestamp(),
  `pickup_datetime` datetime DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`order_id`, `status`, `total_amount`, `order_datetime`, `pickup_datetime`, `customer_id`) VALUES
(8, 'pending', 13.10, '2025-01-20 18:27:36', '2025-01-21 21:30:00', 1),
(10, 'pending', 14.40, '2025-01-20 18:44:32', '2025-01-08 20:44:00', 1),
(11, 'pending', 0.00, '2025-01-20 18:45:00', '2025-01-08 18:44:00', 1),
(12, 'pending', 14.40, '2025-01-20 18:47:46', '2025-01-02 18:47:00', 1),
(13, 'pending', 0.00, '2025-01-20 18:47:59', '2025-01-08 18:47:00', 1),
(14, 'pending', 3.50, '2025-01-21 13:27:59', '2025-01-17 13:27:00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sugar` enum('faible','moyen','élevé') NOT NULL,
  `size` enum('petit','moyen','grand') NOT NULL,
  `customization` text DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `quantity`, `sugar`, `size`, `customization`, `unit_price`, `price`, `order_id`, `product_id`) VALUES
(8, 2, 'moyen', '', NULL, 4.80, 9.60, 8, 52),
(9, 1, 'élevé', '', NULL, 3.50, 3.50, 8, 53),
(10, 3, 'faible', '', NULL, 4.80, 14.40, 10, 52),
(11, 3, 'élevé', '', NULL, 4.80, 14.40, 12, 52),
(12, 1, 'moyen', '', NULL, 3.50, 3.50, 14, 53);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('Cafés','Thés et infusions','Boissons végétales','Lattes','Smoothies','Viennoiserie','Pâtisserie','Sandwiches') NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `category`, `price`, `available`, `image_url`) VALUES
(51, 'Latte', 'Café onctueux avec du lait chaud et une fine couche de mousse.', 'Lattes', 4.50, 1, 'images/latte.png'),
(52, 'Cappuccino', 'Espresso garni d\'une mousse de lait épaisse et légère.', 'Cafés', 4.80, 1, 'images/cappuccino.png'),
(53, 'Americano', 'Café dilué à l\'eau chaude pour un goût plus doux.', 'Cafés', 3.50, 1, 'images/americano.png'),
(54, 'Espresso', 'Un café court et intense, idéal pour un coup de boost.', 'Cafés', 2.50, 1, 'images/espresso.png'),
(55, 'Cortado', 'Espresso adouci avec une petite quantité de lait chaud.', 'Cafés', 3.00, 1, 'images/cortado.png'),
(56, 'Macchiato', 'Espresso avec une touche de mousse de lait.', 'Cafés', 3.20, 1, 'images/macchiato.png'),
(57, 'Mocha', 'Un mélange délicieux de café, de chocolat et de lait.', 'Lattes', 5.00, 0, 'images/mocha.png'),
(58, 'Affogato', 'Espresso versé sur une boule de glace vanille.', 'Cafés', 4.00, 1, 'images/affogato.png'),
(59, 'Iced coffee', 'Café rafraîchissant servi avec des glaçons.', 'Cafés', 3.80, 1, 'images/iced_coffee.png');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
