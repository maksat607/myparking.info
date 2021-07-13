-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 13 2021 г., 08:49
-- Версия сервера: 8.0.19
-- Версия PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `car_storage_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `car_types`
--

CREATE TABLE `car_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parking_price` double(8,2) DEFAULT NULL,
  `rank` int DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `car_types`
--

INSERT INTO `car_types` (`id`, `name`, `parking_price`, `rank`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Легковой автомобиль', 68.00, 1, 1, NULL, NULL),
(2, 'Грузовик', 500.00, 0, 1, NULL, NULL),
(3, 'Мотоцикл', 150.00, 0, 1, NULL, NULL),
(4, 'Фургон', 68.00, 0, 0, NULL, NULL),
(5, 'Прочее', 500.00, 0, 1, NULL, NULL),
(6, 'Автобус', 300.00, 0, 1, NULL, NULL),
(7, 'Прицеп', 300.00, 0, 1, NULL, NULL),
(8, 'Автопоезд/ТС на сцепке', 350.00, 0, 1, NULL, NULL),
(9, 'Бульдозеры', NULL, 0, 1, NULL, NULL),
(10, 'Автопогрузчики', NULL, 0, 1, NULL, NULL),
(11, 'Экскаваторы', NULL, 0, 1, NULL, NULL),
(12, 'Сельхоз-техника', NULL, 0, 1, NULL, NULL),
(13, 'Коммунальная', NULL, 0, 1, NULL, NULL),
(14, 'Самопогрузчики', NULL, 0, 1, NULL, NULL),
(15, 'Строительная техника', NULL, 0, 1, NULL, NULL),
(16, 'Автокраны', NULL, 0, 1, NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `car_types`
--
ALTER TABLE `car_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `car_types`
--
ALTER TABLE `car_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
