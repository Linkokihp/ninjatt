-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 12. Okt 2021 um 21:08
-- Server-Version: 10.4.21-MariaDB
-- PHP-Version: 7.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `ninjatt`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `UserId` int(11) NOT NULL,
  `UserName` varchar(15) NOT NULL,
  `UserMail` varchar(50) NOT NULL,
  `UserPassword` text NOT NULL,
  `UserNinja` varchar(11) NOT NULL,
  `OnlineState` int(1) NOT NULL,
  `X` int(11) NOT NULL,
  `Y` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`UserId`, `UserName`, `UserMail`, `UserPassword`, `UserNinja`, `OnlineState`, `X`, `Y`) VALUES
(15, 'Akali', 'akali@ionia.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'default', 0, 0, 0),
(16, 'Martinja', 'martinja@ninjatt.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'default', 0, 0, 0),
(17, 'Usagi Yojimbo', 'shrouded@moon.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'default', 0, 0, 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
