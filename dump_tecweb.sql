-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Creato il: Feb 06, 2017 alle 10:59
-- Versione del server: 5.6.26
-- Versione PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tecweb`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `amministratori`
--

CREATE TABLE IF NOT EXISTS `amministratori` (
  `nome` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `domanda_sicurezza` varchar(50) DEFAULT NULL,
  `risposta_sicurezza` varchar(50) DEFAULT NULL,
  `cookie` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `amministratori`
--

INSERT INTO `amministratori` (`nome`, `email`, `password`, `domanda_sicurezza`, `risposta_sicurezza`, `cookie`) VALUES
('Admin', 'admin@gmail.com', '200ceb26807d6bf99fd6f4f0d1ca54d4', NULL, NULL, '589848ca2258d');

-- --------------------------------------------------------

--
-- Struttura della tabella `appartamenti`
--

CREATE TABLE IF NOT EXISTS `appartamenti` (
  `id` varchar(4) NOT NULL,
  `max_persone` tinyint(4) DEFAULT NULL,
  `dimensione` int(10) unsigned DEFAULT NULL,
  `descrizione` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `appartamenti`
--

INSERT INTO `appartamenti` (`id`, `max_persone`, `dimensione`, `descrizione`) VALUES
('P1A3', 4, 70, 'Appartamento di 70mq composto da una camera con letto matrimoniale e un&#039;altra cameretta con due letti da una piazza.\r\n\r\nRif: Appartamento 3 Piano 1'),
('P1A4', 2, 40, 'Appartamento di 40mq composto da un&#039;ampia camera con letto matrimoniale, televisione e divano.\r\n\r\nRif: Appartamento 4 Piano 1'),
('P2A1', 2, 40, 'Appartamento di 40mq molto carino. Composto da una camera con letto matrimoniale, bagno con doccia e un balcone con vista San Martino.\r\n\r\nRif: Appartamento 1 Piano 2'),
('P3A2', 2, 35, 'Appartamento di 35mq composto da una camera due letti da una piazza, tv e divano.\r\n\r\nRif: Appartamento 2 Piano 3'),
('S1P4', 4, 80, 'Suite di 80mq composto da due camere. Il massimo del confort lo troverete in questa suite.\r\n\r\nRif: Suite 1 Piano 4');

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazioni`
--

CREATE TABLE IF NOT EXISTS `prenotazioni` (
  `id` int(10) unsigned NOT NULL,
  `utente` int(10) unsigned NOT NULL,
  `data_partenza` date DEFAULT NULL,
  `data_arrivo` date DEFAULT NULL,
  `stato` enum('sospeso','arrivo','partenza') DEFAULT NULL,
  `numPersone` tinyint(3) unsigned DEFAULT NULL,
  `appartamento` varchar(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `prenotazioni`
--

INSERT INTO `prenotazioni` (`id`, `utente`, `data_partenza`, `data_arrivo`, `stato`, `numPersone`, `appartamento`) VALUES
(1, 1, '2017-02-13', '2017-02-10', 'sospeso', 3, 'P1A3'),
(2, 2, '2017-02-26', '2017-02-20', 'sospeso', 2, 'P2A1'),
(3, 3, '2017-08-02', '2017-07-28', 'sospeso', 3, 'S1P4'),
(4, 1, '2017-03-26', '2017-03-24', 'sospeso', 2, 'P1A4'),
(5, 3, '2017-05-02', '2017-05-28', 'sospeso', 3, 'S1P4'),
(6, 3, '2017-04-26', '2017-04-24', 'sospeso', 2, 'P1A4'),
(7, 2, '2017-03-26', '2017-03-20', 'sospeso', 2, 'P3A2');

-- --------------------------------------------------------

--
-- Struttura della tabella `prezzi_appartamenti`
--

CREATE TABLE IF NOT EXISTS `prezzi_appartamenti` (
  `appartamento` varchar(4) NOT NULL DEFAULT '',
  `da` date NOT NULL DEFAULT '0000-00-00',
  `a` date NOT NULL DEFAULT '0000-00-00',
  `costo_giornaliero` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `prezzi_appartamenti`
--

INSERT INTO `prezzi_appartamenti` (`appartamento`, `da`, `a`, `costo_giornaliero`) VALUES
('P1A3', '2016-09-01', '2017-03-31', 45),
('P1A3', '2017-04-01', '2017-10-31', 35),
('P1A4', '2016-09-01', '2017-03-31', 30),
('P1A4', '2017-04-01', '2017-10-31', 25),
('P2A1', '2016-09-01', '2017-03-31', 40),
('P2A1', '2017-04-01', '2017-10-31', 30),
('P3A2', '2016-09-01', '2017-03-31', 35),
('P3A2', '2017-04-01', '2017-10-31', 25),
('S1P4', '2016-09-01', '2017-03-31', 55),
('S1P4', '2017-04-01', '2017-10-31', 45);

-- --------------------------------------------------------

--
-- Struttura della tabella `servizi`
--

CREATE TABLE IF NOT EXISTS `servizi` (
  `id` tinyint(3) unsigned NOT NULL,
  `nome` varchar(30) DEFAULT NULL,
  `costo` int(11) DEFAULT NULL,
  `unita` enum('persona','appartamento','giornaliero') DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `servizi`
--

INSERT INTO `servizi` (`id`, `nome`, `costo`, `unita`) VALUES
(3, 'Checkin veloce', 5, 'persona'),
(4, 'Pulizia giornaliera', 10, 'appartamento'),
(5, 'Servizio in camera', 7, 'appartamento'),
(6, 'Cena inclusa', 35, 'appartamento'),
(7, 'Posto auto coperto', 15, 'appartamento');

-- --------------------------------------------------------

--
-- Struttura della tabella `servizi_prenotazioni`
--

CREATE TABLE IF NOT EXISTS `servizi_prenotazioni` (
  `id_prenotazione` int(10) unsigned NOT NULL DEFAULT '0',
  `id_servizio` tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `servizi_prenotazioni`
--

INSERT INTO `servizi_prenotazioni` (`id_prenotazione`, `id_servizio`) VALUES
(1, 4),
(2, 3),
(2, 4),
(2, 5),
(2, 7),
(3, 4),
(3, 5),
(4, 4),
(4, 7),
(5, 6),
(5, 7),
(6, 3),
(6, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE IF NOT EXISTS `utenti` (
  `id` int(10) unsigned NOT NULL,
  `nome` varchar(20) NOT NULL,
  `cognome` varchar(20) NOT NULL,
  `cf` varchar(20) NOT NULL,
  `piva` varchar(50) DEFAULT '',
  `password` varchar(50) NOT NULL,
  `cookie` varchar(30) DEFAULT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `nome`, `cognome`, `cf`, `piva`, `password`, `cookie`, `email`) VALUES
(1, 'Abdelilah', 'Lahmer', 'LHMBLL94E03Z330S', '', '7bb3f4d62f8dae47fa0ce42502a1ae66', '1589847ed3091e', 'ab@gmail.com'),
(2, 'Matteo', 'Maran', 'MRNMTT95M27L840Q', '', '150be5b860e60a7fc7c7d9b9815e93d1', '25897cc96ec3eb', 'teo@gmail.com'),
(3, 'Edoardo', 'Zanon', 'ZNNDRD95D07C743U', '0101010101', '83c44bd267f169a6f37be25bb49d35dc', '35897ccd134add', 'edo@gmail.com');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `amministratori`
--
ALTER TABLE `amministratori`
  ADD PRIMARY KEY (`nome`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `appartamenti`
--
ALTER TABLE `appartamenti`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appartamento` (`appartamento`),
  ADD KEY `utente` (`utente`);

--
-- Indici per le tabelle `prezzi_appartamenti`
--
ALTER TABLE `prezzi_appartamenti`
  ADD PRIMARY KEY (`appartamento`,`da`,`a`);

--
-- Indici per le tabelle `servizi`
--
ALTER TABLE `servizi`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `servizi_prenotazioni`
--
ALTER TABLE `servizi_prenotazioni`
  ADD PRIMARY KEY (`id_prenotazione`,`id_servizio`),
  ADD KEY `id_servizio` (`id_servizio`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `prenotazioni`
--
ALTER TABLE `prenotazioni`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT per la tabella `servizi`
--
ALTER TABLE `servizi`
  MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD CONSTRAINT `prenotazioni_ibfk_1` FOREIGN KEY (`appartamento`) REFERENCES `appartamenti` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prenotazioni_ibfk_2` FOREIGN KEY (`utente`) REFERENCES `utenti` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `prezzi_appartamenti`
--
ALTER TABLE `prezzi_appartamenti`
  ADD CONSTRAINT `prezzi_appartamenti_ibfk_1` FOREIGN KEY (`appartamento`) REFERENCES `appartamenti` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `servizi_prenotazioni`
--
ALTER TABLE `servizi_prenotazioni`
  ADD CONSTRAINT `servizi_prenotazioni_ibfk_1` FOREIGN KEY (`id_prenotazione`) REFERENCES `prenotazioni` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `servizi_prenotazioni_ibfk_2` FOREIGN KEY (`id_servizio`) REFERENCES `servizi` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
