-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 20 2024 г., 19:01
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `lab7`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `login` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mail` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `login`, `password`, `mail`, `registration_date`, `role`) VALUES
(13, 'Администратор', 'admin', 'ce7c1272d62000003a47251a318cf9d8', 'admin@mail.ru', '2024-04-02 14:53:35', 1),
(12, 'hihihihihi', 'qqqqq', '9737b1cd0fa8efe42bae29712dde78ce', 'qqqqq@qqq.qq', '2024-04-02 14:50:08', 1),
(42, 'Ан', 'mavenara', 'cdd45f37474f4cc2406bd587a30cf8b4', 'aaa@net.ru', '2024-05-08 11:34:43', 1),
(19, 'Derr', 'Ferte', '0a6d94b58c831e4b8aaa8eee15977d0f', 'Deer@gmail.com', '2024-04-03 11:47:47', 0),
(29, 'lknwelnwlekcnwlk', 'lkencwenlcwekcwn', '2ac73b825644019d67a107bb2a65d98f', 'kwldnqwdklqn@qmwdnqmwdqwnd', '2024-04-26 14:41:41', 0),
(38, 'РоманБезуглов', 'qweqweqw', '9737b1cd0fa8efe42bae29712dde78ce', 'bezuglov.r@inbox.ru', '2024-05-04 21:43:39', 0),
(25, 'Frog', 'Frofi', '9737b1cd0fa8efe42bae29712dde78ce', 'frogi@mail.ru', '2024-04-25 17:25:32', 0),
(27, 'testuser', 'testuser', '9737b1cd0fa8efe42bae29712dde78ce', 'testuser@mail.ru', '2024-04-25 17:33:13', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
