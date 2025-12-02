-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 02 2025 г., 13:59
-- Версия сервера: 5.7.39
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `raschet`
--

-- --------------------------------------------------------

--
-- Структура таблицы `car_brands`
--

CREATE TABLE `car_brands` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `car_brands`
--

INSERT INTO `car_brands` (`id`, `brand_name`) VALUES
(1, 'Toyota'),
(2, 'Honda'),
(3, 'Nissan'),
(4, 'Mazda'),
(5, 'Subaru'),
(6, 'Mitsubishi'),
(7, 'Lexus'),
(8, 'Infiniti'),
(9, 'Acura'),
(10, 'Hyundai'),
(11, 'Kia'),
(12, 'Genesis'),
(13, 'Volkswagen'),
(14, 'Audi'),
(15, 'BMW'),
(16, 'Mercedes-Benz'),
(17, 'Porsche'),
(18, 'Opel'),
(19, 'Renault'),
(20, 'Peugeot'),
(21, 'Citroën'),
(22, 'Fiat'),
(23, 'Alfa Romeo'),
(24, 'Skoda'),
(25, 'Seat'),
(26, 'Volvo'),
(27, 'Saab'),
(28, 'Ford'),
(29, 'Chevrolet'),
(30, 'Cadillac'),
(31, 'GMC'),
(32, 'Buick'),
(33, 'Jeep'),
(34, 'Chrysler'),
(35, 'Dodge'),
(36, 'RAM'),
(37, 'Lincoln'),
(38, 'Tesla'),
(39, 'Land Rover'),
(40, 'Range Rover'),
(41, 'Jaguar'),
(42, 'Mini'),
(43, 'Suzuki'),
(44, 'Geely'),
(45, 'Chery'),
(46, 'Haval'),
(47, 'GAC'),
(48, 'BYD'),
(49, 'TANK'),
(50, 'UAZ');

-- --------------------------------------------------------

--
-- Структура таблицы `car_models`
--

CREATE TABLE `car_models` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `model_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `car_models`
--

INSERT INTO `car_models` (`id`, `brand_id`, `model_name`) VALUES
(1, 1, 'Camry'),
(2, 1, 'Corolla'),
(3, 1, 'RAV4'),
(4, 1, 'Highlander'),
(5, 1, 'Land Cruiser'),
(6, 2, 'Civic'),
(7, 2, 'Accord'),
(8, 2, 'CR-V'),
(9, 2, 'Pilot'),
(10, 2, 'HR-V'),
(11, 3, 'Qashqai'),
(12, 3, 'X-Trail'),
(13, 3, 'Almera'),
(14, 3, 'Murano'),
(15, 3, 'Patrol'),
(16, 4, 'Mazda 3'),
(17, 4, 'Mazda 6'),
(18, 4, 'CX-5'),
(19, 4, 'CX-9'),
(20, 4, 'CX-30'),
(21, 5, 'Impreza'),
(22, 5, 'Forester'),
(23, 5, 'Outback'),
(24, 5, 'Legacy'),
(25, 5, 'XV'),
(26, 6, 'Outlander'),
(27, 6, 'ASX'),
(28, 6, 'Pajero'),
(29, 6, 'Lancer'),
(30, 6, 'Eclipse Cross'),
(31, 7, 'RX'),
(32, 7, 'GX'),
(33, 7, 'LX'),
(34, 7, 'NX'),
(35, 7, 'ES'),
(36, 8, 'Q50'),
(37, 8, 'QX50'),
(38, 8, 'QX60'),
(39, 8, 'QX80'),
(40, 8, 'Q70'),
(41, 9, 'RDX'),
(42, 9, 'MDX'),
(43, 9, 'TLX'),
(44, 9, 'ILX'),
(45, 9, 'ZDX'),
(46, 10, 'Solaris'),
(47, 10, 'Elantra'),
(48, 10, 'Sonata'),
(49, 10, 'Tucson'),
(50, 10, 'Santa Fe'),
(51, 11, 'Rio'),
(52, 11, 'Ceed'),
(53, 11, 'Cerato'),
(54, 11, 'Sportage'),
(55, 11, 'Sorento'),
(56, 12, 'G70'),
(57, 12, 'G80'),
(58, 12, 'G90'),
(59, 12, 'GV70'),
(60, 12, 'GV80'),
(61, 13, 'Polo'),
(62, 13, 'Passat'),
(63, 13, 'Tiguan'),
(64, 13, 'Touareg'),
(65, 13, 'Golf'),
(66, 14, 'A4'),
(67, 14, 'A6'),
(68, 14, 'Q3'),
(69, 14, 'Q5'),
(70, 14, 'Q7'),
(71, 15, '3 Series'),
(72, 15, '5 Series'),
(73, 15, '7 Series'),
(74, 15, 'X3'),
(75, 15, 'X5'),
(76, 16, 'C-Class'),
(77, 16, 'E-Class'),
(78, 16, 'S-Class'),
(79, 16, 'GLC'),
(80, 16, 'GLE'),
(81, 17, 'Macan'),
(82, 17, 'Cayenne'),
(83, 17, 'Panamera'),
(84, 17, '911'),
(85, 17, 'Taycan'),
(86, 18, 'Astra'),
(87, 18, 'Insignia'),
(88, 18, 'Corsa'),
(89, 18, 'Mokka'),
(90, 18, 'Zafira'),
(91, 19, 'Logan'),
(92, 19, 'Duster'),
(93, 19, 'Arkana'),
(94, 19, 'Sandero'),
(95, 19, 'Kaptur'),
(96, 20, '308'),
(97, 20, '408'),
(98, 20, '3008'),
(99, 20, '5008'),
(100, 20, '2008'),
(101, 21, 'C3'),
(102, 21, 'C4'),
(103, 21, 'C5 Aircross'),
(104, 21, 'Berlingo'),
(105, 21, 'C-Elysee'),
(106, 22, '500'),
(107, 22, 'Panda'),
(108, 22, 'Tipo'),
(109, 22, 'Doblo'),
(110, 22, 'Punto'),
(111, 23, 'Giulia'),
(112, 23, 'Stelvio'),
(113, 23, 'Giulietta'),
(114, 23, '159'),
(115, 23, 'Brera'),
(116, 24, 'Octavia'),
(117, 24, 'Superb'),
(118, 24, 'Kodiaq'),
(119, 24, 'Karoq'),
(120, 24, 'Fabia'),
(121, 25, 'Leon'),
(122, 25, 'Ibiza'),
(123, 25, 'Ateca'),
(124, 25, 'Toledo'),
(125, 25, 'Tarraco'),
(126, 26, 'XC60'),
(127, 26, 'XC90'),
(128, 26, 'S60'),
(129, 26, 'V60'),
(130, 26, 'XC40'),
(131, 27, '9-3'),
(132, 27, '9-5'),
(133, 27, '900'),
(134, 27, '9000'),
(135, 27, '9-4X'),
(136, 28, 'Focus'),
(137, 28, 'Mondeo'),
(138, 28, 'Kuga'),
(139, 28, 'Explorer'),
(140, 28, 'F-150'),
(141, 29, 'Cruze'),
(142, 29, 'Aveo'),
(143, 29, 'Camaro'),
(144, 29, 'Tahoe'),
(145, 29, 'Spark'),
(146, 30, 'CT5'),
(147, 30, 'CT6'),
(148, 30, 'Escalade'),
(149, 30, 'XT5'),
(150, 30, 'XT6'),
(151, 31, 'Terrain'),
(152, 31, 'Acadia'),
(153, 31, 'Yukon'),
(154, 31, 'Canyon'),
(155, 31, 'Sierra'),
(156, 32, 'Encore'),
(157, 32, 'Enclave'),
(158, 32, 'Regal'),
(159, 32, 'LaCrosse'),
(160, 32, 'Verano'),
(161, 33, 'Wrangler'),
(162, 33, 'Cherokee'),
(163, 33, 'Grand Cherokee'),
(164, 33, 'Compass'),
(165, 33, 'Renegade'),
(166, 34, '300C'),
(167, 34, 'Pacifica'),
(168, 34, 'Voyager'),
(169, 34, '200'),
(170, 34, 'Sebring'),
(171, 35, 'Charger'),
(172, 35, 'Challenger'),
(173, 35, 'Durango'),
(174, 35, 'Journey'),
(175, 35, 'Avenger'),
(176, 36, '1500'),
(177, 36, '2500'),
(178, 36, '3500'),
(179, 36, 'TRX'),
(180, 36, 'Classic'),
(181, 37, 'Navigator'),
(182, 37, 'Aviator'),
(183, 37, 'Corsair'),
(184, 37, 'Nautilus'),
(185, 37, 'MKZ'),
(186, 38, 'Model S'),
(187, 38, 'Model 3'),
(188, 38, 'Model X'),
(189, 38, 'Model Y'),
(190, 38, 'Cybertruck'),
(191, 39, 'Discovery'),
(192, 39, 'Defender'),
(193, 39, 'Range Rover Sport'),
(194, 39, 'Freelander'),
(195, 39, 'Velar'),
(196, 40, 'Evoque'),
(197, 40, 'Velar'),
(198, 40, 'Sport'),
(199, 40, 'Classic'),
(200, 40, 'Autobiography'),
(201, 41, 'XE'),
(202, 41, 'XF'),
(203, 41, 'XJ'),
(204, 41, 'F-Pace'),
(205, 41, 'F-Type'),
(206, 42, 'Cooper'),
(207, 42, 'Clubman'),
(208, 42, 'Countryman'),
(209, 42, 'Paceman'),
(210, 42, 'One'),
(211, 43, 'Swift'),
(212, 43, 'Vitara'),
(213, 43, 'Jimny'),
(214, 43, 'SX4'),
(215, 43, 'Baleno'),
(216, 44, 'Coolray'),
(217, 44, 'Atlas'),
(218, 44, 'Tugella'),
(219, 44, 'Emgrand X7'),
(220, 44, 'Monjaro'),
(221, 45, 'Tiggo 7'),
(222, 45, 'Tiggo 4'),
(223, 45, 'Tiggo 8'),
(224, 45, 'Arrizo 7'),
(225, 45, 'Arrizo 5'),
(226, 46, 'Jolion'),
(227, 46, 'F7'),
(228, 46, 'F7x'),
(229, 46, 'H9'),
(230, 46, 'Dargo'),
(231, 47, 'GS5'),
(232, 47, 'GS8'),
(233, 47, 'GN8'),
(234, 47, 'GA6'),
(235, 47, 'GS3'),
(236, 48, 'Tang'),
(237, 48, 'Song'),
(238, 48, 'Qin'),
(239, 48, 'Han'),
(240, 48, 'Yuan'),
(241, 49, '300'),
(242, 49, '500'),
(243, 49, '700'),
(244, 49, '800'),
(245, 49, '900'),
(246, 50, 'Patriot'),
(247, 50, 'Hunter'),
(248, 50, 'Bukhanka'),
(249, 50, 'Pickup'),
(250, 50, 'Profi');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', 'admin123', '2025-09-20 09:30:06'),
(2, 'ivan', 'qwerty', '2025-09-20 09:30:06'),
(3, 'anna', 'password', '2025-09-20 09:30:06');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `car_brands`
--
ALTER TABLE `car_brands`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `car_models`
--
ALTER TABLE `car_models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `car_brands`
--
ALTER TABLE `car_brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `car_models`
--
ALTER TABLE `car_models`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `car_models`
--
ALTER TABLE `car_models`
  ADD CONSTRAINT `car_models_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `car_brands` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
