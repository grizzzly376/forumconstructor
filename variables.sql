-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Мар 22 2017 г., 16:01
-- Версия сервера: 5.5.25
-- Версия PHP: 5.2.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `itsforum`
--

-- --------------------------------------------------------

--
-- Структура таблицы `variables`
--

CREATE TABLE IF NOT EXISTS `variables` (
  `key` text NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `variables`
--

INSERT INTO `variables` (`key`, `value`) VALUES
('tpp', '20'),
('ppp', '15'),
('mpp', '10'),
('fname', 'FORUMNAME'),
('styles', ''),
('script_top', ''),
('adv', 'false'),
('avatarx', '150'),
('avatary', '150'),
('bgimage', ''),
('logo', ''),
('styles_file', ''),
('scripts_file', ''),
('Info', '');

INSERT INTO `users` (`id`, `group`, `name`, `email`, `passhash`, `salt`, `info`, `color`, `active`, `ban`, `reg_date`, `avatar`) VALUES

(1, 3, 'admin', '', '0d03d82b38e113235438d28869ee5e68', '4c123531a9fb73e550ef45308c48820e', '', 'red', '2017-03-22 12:54:28', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1)

;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
