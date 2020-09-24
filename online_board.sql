-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 24 2020 г., 10:45
-- Версия сервера: 10.3.13-MariaDB-log
-- Версия PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `online_board`
--

-- --------------------------------------------------------

--
-- Структура таблицы `sprints`
--

CREATE TABLE `sprints` (
  `id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Идентификатор спринта',
  `is_start` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Запущен ли спринт',
  `is_close` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Закрыт ли спринт',
  `is_work` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'В работе ли спринт'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL COMMENT 'Идентификатор задачи',
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Заголовок задачи',
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Описание задачи',
  `estimation` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '-' COMMENT 'Оценка задачи',
  `is_close` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Закрыта ли задача',
  `sprint_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '-' COMMENT 'Идентификатор спринта'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `sprints`
--
ALTER TABLE `sprints`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор задачи';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
