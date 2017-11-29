-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vytvořeno: Stř 29. lis 2017, 17:05
-- Verze serveru: 5.7.20-0ubuntu0.16.04.1
-- Verze PHP: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databáze: `iis`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `phone_number` int(11) DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `card_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `customers`
--

INSERT INTO `customers` (`customer_id`, `first_name`, `last_name`, `phone_number`, `email`, `card_number`) VALUES
  (1, 'Jan', 'Novák', NULL, NULL, NULL),
  (2, 'Petr', 'Starý', NULL, NULL, NULL),
  (3, 'Martina', 'Falešná', NULL, NULL, 87451),
  (4, 'Pepa', 'Vomáčka', 775125713, NULL, 4475),
  (5, 'Lenka', 'Vomáčková', NULL, 'lenka@example.com', 99999),
  (6, 'Filip', 'Hodný', 457123456, 'filip@seznam.cz', 77889),
  (7, 'Jan', 'Pospíchal', 800808080, 'jan@example.com', NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `phone_number` int(11) DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `birth_number` decimal(10,0) DEFAULT NULL,
  `position_id` int(11) NOT NULL,
  `salary` int(11) DEFAULT NULL,
  `bank_account_prefix` int(11) DEFAULT NULL,
  `bank_account` decimal(10,0) DEFAULT NULL,
  `bank_code` smallint(6) DEFAULT NULL,
  `street_name` varchar(50) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `street_number` int(11) DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `zip` int(11) DEFAULT NULL,
  `username` varchar(40) COLLATE utf8mb4_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `phone_number`, `email`, `birth_number`, `position_id`, `salary`, `bank_account_prefix`, `bank_account`, `bank_code`, `street_name`, `street_number`, `city`, `zip`, `username`, `password`) VALUES
  (1, 'Tamara', 'Krestianková', 777888999, 'tamara@example.com', '9551010007', 3, 40000, 668, '123456796', 100, 'Falešná', 1, 'Brno', 60001, 'tami', '$2y$10$jAdsTbZH.wxHhBm4NqBOXOisrpXD0zRmbGTwufOKCwyNiWi7hM9mq'),
  (2, 'Petr', 'Rusiňák', NULL, 'petr@example.com', '9502090070', 3, 40000, 9769, '1000000005', 2700, 'Nová', 13, 'Brno', 60001, 'peta', '$2y$10$2kv7hhxF1L70S4KyHujgEucqUvSJ5Vzqs6tLCjyWHcjVkv4.tMsyq'),
  (3, 'Pepa', 'Novák', 776776776, 'pepa@example.com', '8002021863', 1, 18000, 0, '784319', 3030, 'Husitská', 459, 'Pardubice', 53001, 'pepa', ''),
  (4, 'Hana', 'Nováková', NULL, 'hana@example.com', '8551111558', 1, 18000, 0, '784319', 3030, 'Husitská', 459, 'Pardubice', 53001, 'hana', '$2y$10$OaNo1pXz4FaCAxSaPRtFguCXOlY8pFjXwl9BipFdn9gGnTXNhPzZS');

-- --------------------------------------------------------

--
-- Struktura tabulky `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredience_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `unit` varchar(5) COLLATE utf8mb4_czech_ci NOT NULL DEFAULT 'kg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `ingredients`
--

INSERT INTO `ingredients` (`ingredience_id`, `name`, `unit`) VALUES
  (1, 'Maso', 'kg'),
  (2, 'Brambory', 'kg'),
  (3, 'Rýže', 'kg'),
  (4, 'Voda', 'l'),
  (5, 'Mouka', 'kg'),
  (6, 'Mléko', 'l'),
  (7, 'Hermelín', 'ks');

-- --------------------------------------------------------

--
-- Struktura tabulky `ingredients_in_items`
--

CREATE TABLE `ingredients_in_items` (
  `ingredience_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `ingredients_in_items`
--

INSERT INTO `ingredients_in_items` (`ingredience_id`, `item_id`, `amount`) VALUES
  (4, 1, '0.30'),
  (4, 2, '0.30'),
  (4, 3, '0.30'),
  (4, 4, '0.30'),
  (4, 5, '0.30'),
  (4, 9, '0.20'),
  (6, 9, '0.05'),
  (1, 18, '0.40'),
  (5, 18, '0.10'),
  (5, 19, '0.10'),
  (6, 19, '0.25'),
  (7, 20, '1.00'),
  (5, 20, '0.10'),
  (1, 22, '0.50'),
  (2, 23, '0.30'),
  (3, 24, '0.30'),
  (2, 26, '0.30'),
  (2, 27, '0.20'),
  (1, 29, '0.25'),
  (4, 30, '0.30'),
  (7, 31, '1.50');

-- --------------------------------------------------------

--
-- Struktura tabulky `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(60) COLLATE utf8mb4_czech_ci NOT NULL,
  `available` smallint(6) NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `available`, `price`, `category_id`) VALUES
  (1, 'Cibulová', 1, '20.00', 1),
  (2, 'Česneková', 1, '15.00', 1),
  (3, 'Rajská', 1, '20.00', 1),
  (4, 'Knedlíčková', 1, '20.00', 1),
  (5, 'Písmenková', 1, '20.00', 1),
  (6, 'Kofola', 1, '15.00', 2),
  (7, 'Soda', 1, '10.00', 2),
  (8, 'Pomerančový džus', 1, '20.00', 2),
  (9, 'Káva', 1, '22.00', 2),
  (10, 'Sprite', 1, '22.00', 2),
  (11, 'Pivo Kozel', 1, '19.00', 3),
  (12, 'Pivo Plzeň', 1, '35.00', 3),
  (13, 'Pivo Gambrinus', 1, '25.00', 3),
  (14, 'Víno bílé', 1, '80.00', 3),
  (15, 'Víno červené', 1, '80.00', 3),
  (16, 'Vodka', 1, '25.00', 3),
  (17, 'Rum', 1, '24.00', 3),
  (18, 'Řízek', 1, '99.00', 4),
  (19, 'Palačinky', 1, '79.00', 4),
  (20, 'Smažený sýr', 1, '75.00', 4),
  (21, 'Špagety', 1, '99.00', 4),
  (22, 'Kachna', 0, '99.00', 4),
  (23, 'Brambory', 1, '10.00', 5),
  (24, 'Rýže', 1, '10.00', 5),
  (25, 'Houskový knedlík', 1, '10.00', 5),
  (26, 'Hranolky', 1, '10.00', 5),
  (27, 'Krokety', 1, '10.00', 5),
  (28, 'Losos', 1, '120.00', 6),
  (29, 'Kuřecí plátek', 1, '90.50', 6),
  (30, 'Guláš', 1, '74.90', 6),
  (31, 'Nakládaný sýr', 1, '55.00', 7),
  (32, 'Pizza', 1, '90.00', 7);

-- --------------------------------------------------------

--
-- Struktura tabulky `item_categories`
--

CREATE TABLE `item_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `menu_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `item_categories`
--

INSERT INTO `item_categories` (`category_id`, `category_name`, `menu_order`) VALUES
  (1, 'Polévky', 1),
  (2, 'Nealkoholické nápoje', 21),
  (3, 'Alkoholické nápoje', 20),
  (4, 'Hlavní jídla', 2),
  (5, 'Přílohy', 3),
  (6, 'Denní menu', 0),
  (7, 'Minutky', 10);

-- --------------------------------------------------------

--
-- Struktura tabulky `ordered_items`
--

CREATE TABLE `ordered_items` (
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `ordered_items`
--

INSERT INTO `ordered_items` (`order_id`, `item_id`, `amount`) VALUES
  (1, 6, 1),
  (1, 18, 1),
  (1, 23, 1),
  (2, 11, 5),
  (2, 12, 3),
  (2, 17, 4),
  (3, 13, 2),
  (3, 14, 1),
  (4, 11, 1),
  (4, 20, 1),
  (4, 26, 1),
  (5, 30, 1),
  (6, 19, 3);

-- --------------------------------------------------------

--
-- Struktura tabulky `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paid` smallint(6) NOT NULL DEFAULT '0',
  `table_number` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `orders`
--

INSERT INTO `orders` (`order_id`, `date_created`, `paid`, `table_number`, `reservation_id`) VALUES
  (1, '2017-10-19 20:15:17', 1, 10, NULL),
  (2, '2017-10-19 20:15:17', 1, 15, NULL),
  (3, '2017-10-19 20:15:17', 1, 18, NULL),
  (4, '2017-06-01 14:13:00', 1, 22, 1),
  (5, '2017-10-19 20:15:17', 0, 25, NULL),
  (6, '2017-10-19 20:15:17', 0, 27, NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `positions`
--

CREATE TABLE `positions` (
  `position_id` int(11) NOT NULL,
  `position_name` varchar(60) COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `positions`
--

INSERT INTO `positions` (`position_id`, `position_name`) VALUES
  (1, 'Číšník'),
  (2, 'Vedoucí'),
  (3, 'Majitel');

-- --------------------------------------------------------

--
-- Struktura tabulky `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_reserved` datetime NOT NULL,
  `customer_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `date_created`, `date_reserved`, `customer_id`, `employee_id`) VALUES
  (1, '2017-01-01 14:00:00', '2017-06-01 15:00:00', 1, 1),
  (2, '2017-03-02 14:00:00', '2017-06-01 14:00:00', 4, 1),
  (3, '2017-03-02 14:02:00', '2017-06-01 14:00:00', 2, 1),
  (4, '2017-10-19 20:15:16', '2017-06-02 12:00:00', 1, 1),
  (5, '2017-10-19 20:15:17', '2017-06-03 10:00:00', 5, 1),
  (6, '2017-10-19 20:15:17', '2017-06-03 11:00:00', 6, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `reserved_rooms`
--

CREATE TABLE `reserved_rooms` (
  `room_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `seat_count` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `reserved_rooms`
--

INSERT INTO `reserved_rooms` (`room_id`, `reservation_id`, `seat_count`) VALUES
  (1, 1, 2),
  (1, 2, 5),
  (1, 3, 1),
  (1, 4, 50),
  (2, 4, 30),
  (3, 4, 30),
  (2, 5, 5),
  (1, 6, 30),
  (2, 6, 20);

-- --------------------------------------------------------

--
-- Struktura tabulky `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `capacity` smallint(6) NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `tables_from` smallint(6) NOT NULL,
  `tables_to` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `rooms`
--

INSERT INTO `rooms` (`room_id`, `capacity`, `description`, `tables_from`, `tables_to`) VALUES
  (1, 50, 'Hlavní místnost', 1, 50),
  (2, 30, 'Zahrádka', 51, 80),
  (3, 30, 'Salónek', 81, 110);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Klíče pro tabulku `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `birth_number` (`birth_number`),
  ADD KEY `position_id` (`position_id`);

--
-- Klíče pro tabulku `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredience_id`);

--
-- Klíče pro tabulku `ingredients_in_items`
--
ALTER TABLE `ingredients_in_items`
  ADD KEY `ingredience_id` (`ingredience_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Klíče pro tabulku `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Klíče pro tabulku `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Klíče pro tabulku `ordered_items`
--
ALTER TABLE `ordered_items`
  ADD PRIMARY KEY (`order_id`,`item_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Klíče pro tabulku `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Klíče pro tabulku `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`position_id`);

--
-- Klíče pro tabulku `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Klíče pro tabulku `reserved_rooms`
--
ALTER TABLE `reserved_rooms`
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Klíče pro tabulku `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pro tabulku `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pro tabulku `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredience_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pro tabulku `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT pro tabulku `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pro tabulku `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pro tabulku `positions`
--
ALTER TABLE `positions`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pro tabulku `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pro tabulku `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`) ON UPDATE CASCADE;

--
-- Omezení pro tabulku `ingredients_in_items`
--
ALTER TABLE `ingredients_in_items`
  ADD CONSTRAINT `ingredients_in_items_ibfk_1` FOREIGN KEY (`ingredience_id`) REFERENCES `ingredients` (`ingredience_id`),
  ADD CONSTRAINT `ingredients_in_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);

--
-- Omezení pro tabulku `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `item_categories` (`category_id`);

--
-- Omezení pro tabulku `ordered_items`
--
ALTER TABLE `ordered_items`
  ADD CONSTRAINT `ordered_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `ordered_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);

--
-- Omezení pro tabulku `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`);

--
-- Omezení pro tabulku `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Omezení pro tabulku `reserved_rooms`
--
ALTER TABLE `reserved_rooms`
  ADD CONSTRAINT `reserved_rooms_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`),
  ADD CONSTRAINT `reserved_rooms_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);
