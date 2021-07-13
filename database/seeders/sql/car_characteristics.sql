-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 13 2021 г., 08:47
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
-- Структура таблицы `car_characteristics`
--

CREATE TABLE `car_characteristics` (
  `id` bigint NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rank` int DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `car_characteristics`
--

INSERT INTO `car_characteristics` (`id`, `name`, `rank`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Кузов', 0, 1, NULL, NULL),
(2, 'Тип кузова', 0, 1, NULL, NULL),
(3, 'Количество дверей', 0, 1, NULL, NULL),
(4, 'Количество мест', 0, 1, NULL, NULL),
(5, 'Длина', 0, 1, NULL, NULL),
(6, 'Ширина', 0, 1, NULL, NULL),
(7, 'Высота', 0, 1, NULL, NULL),
(8, 'Колёсная база', 0, 1, NULL, NULL),
(9, 'Колея передняя', 0, 1, NULL, NULL),
(10, 'Колея задняя', 0, 1, NULL, NULL),
(11, 'Двигатель', 0, 1, NULL, NULL),
(12, 'Тип двигателя', 0, 1, NULL, NULL),
(13, 'Объем двигателя', 0, 1, NULL, NULL),
(14, 'Мощность двигателя', 0, 1, NULL, NULL),
(15, 'Обороты максимальной мощности', 0, 1, NULL, NULL),
(16, 'Максимальный крутящий момент', 0, 1, NULL, NULL),
(17, 'Тип впуска', 0, 1, NULL, NULL),
(18, 'Газораспределительный механизм', 0, 1, NULL, NULL),
(19, 'Расположение цилиндров', 0, 1, NULL, NULL),
(20, 'Количество цилиндров', 0, 1, NULL, NULL),
(21, 'Степень сжатия', 0, 1, NULL, NULL),
(22, 'Марка топлива', 0, 1, NULL, NULL),
(23, 'Трансмиссия и управление', 0, 1, NULL, NULL),
(24, 'Тип КПП', 0, 1, NULL, NULL),
(26, 'Количество передач', 0, 1, NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `car_characteristics`
--
ALTER TABLE `car_characteristics`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `car_characteristics`
--
ALTER TABLE `car_characteristics`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1572;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
