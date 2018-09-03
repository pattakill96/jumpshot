-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Set 03, 2018 alle 16:36
-- Versione del server: 10.1.24-MariaDB
-- Versione PHP: 7.0.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jumpshot`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `amministratori`
--

CREATE TABLE `amministratori` (
  `id` int(11) NOT NULL,
  `username` int(11) NOT NULL,
  `password` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `carrello`
--

CREATE TABLE `carrello` (
  `id` int(11) NOT NULL,
  `utente` int(11) NOT NULL,
  `prodotto` int(11) NOT NULL,
  `taglia` varchar(11) NOT NULL,
  `prezzo` int(11) NOT NULL,
  `ordinato` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `carrelloext`
--

CREATE TABLE `carrelloext` (
  `id` int(11) NOT NULL,
  `utente` int(11) NOT NULL,
  `prodotto` int(11) NOT NULL,
  `taglia` varchar(11) NOT NULL,
  `prezzo` int(11) NOT NULL,
  `ordinato` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `carrellofornitore`
--

CREATE TABLE `carrellofornitore` (
  `id` int(11) NOT NULL,
  `fornitore` int(11) NOT NULL,
  `prodotto` int(11) NOT NULL,
  `taglia` int(11) NOT NULL,
  `quantita` int(11) NOT NULL,
  `prezzo` int(11) NOT NULL,
  `ordinato` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `fornitore`
--

CREATE TABLE `fornitore` (
  `id` int(11) NOT NULL,
  `denominazione` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `indirizzo` varchar(100) NOT NULL,
  `CAP` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `immagini`
--

CREATE TABLE `immagini` (
  `immagine` varchar(100) NOT NULL,
  `prodotto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `immaginifornitore`
--

CREATE TABLE `immaginifornitore` (
  `immagine` varchar(100) NOT NULL,
  `prodotto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordinefornitore`
--

CREATE TABLE `ordinefornitore` (
  `id` int(11) NOT NULL,
  `amministratore` int(11) NOT NULL,
  `fornitore` int(11) NOT NULL,
  `totale` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordinepagatoext`
--

CREATE TABLE `ordinepagatoext` (
  `ordine` int(11) NOT NULL,
  `spedizione` int(11) NOT NULL,
  `pagamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini`
--

CREATE TABLE `ordini` (
  `id` int(11) NOT NULL,
  `utente` int(11) NOT NULL,
  `totale` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordiniext`
--

CREATE TABLE `ordiniext` (
  `id` int(11) NOT NULL,
  `utente` int(11) NOT NULL,
  `totale` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordinipagati`
--

CREATE TABLE `ordinipagati` (
  `ordine` int(11) NOT NULL,
  `spedizione` varchar(60) NOT NULL,
  `pagamento` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotti`
--

CREATE TABLE `prodotti` (
  `id` int(11) NOT NULL,
  `marca` varchar(15) NOT NULL,
  `modello` varchar(20) NOT NULL,
  `tipologia` varchar(20) NOT NULL,
  `immagine` int(11) NOT NULL,
  `prezzo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `prodottifornitore`
--

CREATE TABLE `prodottifornitore` (
  `id` int(11) NOT NULL,
  `marca` varchar(30) NOT NULL,
  `modello` varchar(30) NOT NULL,
  `prezzo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `saldi`
--

CREATE TABLE `saldi` (
  `percentuale` int(11) NOT NULL,
  `prodotto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `taglieprodotti`
--

CREATE TABLE `taglieprodotti` (
  `scarpa` int(11) NOT NULL,
  `taglia` int(11) NOT NULL,
  `quantita` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `cognome` varchar(20) NOT NULL,
  `indirizzo` varchar(100) NOT NULL,
  `citta` varchar(20) NOT NULL,
  `CAP` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `tagliaScarpe` int(11) NOT NULL,
  `tagliaCanotte` varchar(3) NOT NULL,
  `tagliaCappelli` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `utentiext`
--

CREATE TABLE `utentiext` (
  `id` int(11) NOT NULL,
  `token` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `amministratori`
--
ALTER TABLE `amministratori`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `carrello`
--
ALTER TABLE `carrello`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utente` (`utente`),
  ADD KEY `prodotto` (`prodotto`);

--
-- Indici per le tabelle `carrelloext`
--
ALTER TABLE `carrelloext`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utente` (`utente`),
  ADD KEY `prodotto` (`prodotto`);

--
-- Indici per le tabelle `carrellofornitore`
--
ALTER TABLE `carrellofornitore`
  ADD KEY `fornitore` (`fornitore`),
  ADD KEY `prodotto` (`prodotto`);

--
-- Indici per le tabelle `fornitore`
--
ALTER TABLE `fornitore`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `immagini`
--
ALTER TABLE `immagini`
  ADD KEY `prodotto` (`prodotto`);

--
-- Indici per le tabelle `immaginifornitore`
--
ALTER TABLE `immaginifornitore`
  ADD KEY `prodotto` (`prodotto`);

--
-- Indici per le tabelle `ordinefornitore`
--
ALTER TABLE `ordinefornitore`
  ADD PRIMARY KEY (`id`),
  ADD KEY `amministratore` (`amministratore`),
  ADD KEY `fornitore` (`fornitore`);

--
-- Indici per le tabelle `ordinepagatoext`
--
ALTER TABLE `ordinepagatoext`
  ADD KEY `ordine` (`ordine`);

--
-- Indici per le tabelle `ordini`
--
ALTER TABLE `ordini`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utente` (`utente`);

--
-- Indici per le tabelle `ordiniext`
--
ALTER TABLE `ordiniext`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utente` (`utente`);

--
-- Indici per le tabelle `ordinipagati`
--
ALTER TABLE `ordinipagati`
  ADD KEY `ordine` (`ordine`);

--
-- Indici per le tabelle `prodotti`
--
ALTER TABLE `prodotti`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `prodottifornitore`
--
ALTER TABLE `prodottifornitore`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `saldi`
--
ALTER TABLE `saldi`
  ADD KEY `prodotto` (`prodotto`);

--
-- Indici per le tabelle `taglieprodotti`
--
ALTER TABLE `taglieprodotti`
  ADD KEY `scarpa` (`scarpa`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utentiext`
--
ALTER TABLE `utentiext`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `carrello`
--
ALTER TABLE `carrello`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `utentiext`
--
ALTER TABLE `utentiext`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `carrello`
--
ALTER TABLE `carrello`
  ADD CONSTRAINT `carrello_ibfk_1` FOREIGN KEY (`utente`) REFERENCES `utenti` (`id`),
  ADD CONSTRAINT `carrello_ibfk_2` FOREIGN KEY (`prodotto`) REFERENCES `prodotti` (`id`);

--
-- Limiti per la tabella `carrelloext`
--
ALTER TABLE `carrelloext`
  ADD CONSTRAINT `carrelloext_ibfk_1` FOREIGN KEY (`utente`) REFERENCES `utentiext` (`id`),
  ADD CONSTRAINT `carrelloext_ibfk_2` FOREIGN KEY (`prodotto`) REFERENCES `prodotti` (`id`);

--
-- Limiti per la tabella `carrellofornitore`
--
ALTER TABLE `carrellofornitore`
  ADD CONSTRAINT `carrellofornitore_ibfk_1` FOREIGN KEY (`fornitore`) REFERENCES `fornitore` (`id`),
  ADD CONSTRAINT `carrellofornitore_ibfk_2` FOREIGN KEY (`prodotto`) REFERENCES `prodottifornitore` (`id`);

--
-- Limiti per la tabella `immagini`
--
ALTER TABLE `immagini`
  ADD CONSTRAINT `immagini_ibfk_1` FOREIGN KEY (`prodotto`) REFERENCES `prodotti` (`id`);

--
-- Limiti per la tabella `immaginifornitore`
--
ALTER TABLE `immaginifornitore`
  ADD CONSTRAINT `immaginifornitore_ibfk_1` FOREIGN KEY (`prodotto`) REFERENCES `prodottifornitore` (`id`);

--
-- Limiti per la tabella `ordinefornitore`
--
ALTER TABLE `ordinefornitore`
  ADD CONSTRAINT `ordinefornitore_ibfk_1` FOREIGN KEY (`fornitore`) REFERENCES `fornitore` (`id`),
  ADD CONSTRAINT `ordinefornitore_ibfk_2` FOREIGN KEY (`amministratore`) REFERENCES `amministratori` (`id`);

--
-- Limiti per la tabella `ordinepagatoext`
--
ALTER TABLE `ordinepagatoext`
  ADD CONSTRAINT `ordinepagatoext_ibfk_1` FOREIGN KEY (`ordine`) REFERENCES `ordiniext` (`id`);

--
-- Limiti per la tabella `ordini`
--
ALTER TABLE `ordini`
  ADD CONSTRAINT `ordini_ibfk_1` FOREIGN KEY (`utente`) REFERENCES `utenti` (`id`);

--
-- Limiti per la tabella `ordiniext`
--
ALTER TABLE `ordiniext`
  ADD CONSTRAINT `ordiniext_ibfk_1` FOREIGN KEY (`utente`) REFERENCES `utentiext` (`id`);

--
-- Limiti per la tabella `ordinipagati`
--
ALTER TABLE `ordinipagati`
  ADD CONSTRAINT `ordinipagati_ibfk_1` FOREIGN KEY (`ordine`) REFERENCES `ordini` (`id`);

--
-- Limiti per la tabella `saldi`
--
ALTER TABLE `saldi`
  ADD CONSTRAINT `saldi_ibfk_1` FOREIGN KEY (`prodotto`) REFERENCES `prodotti` (`id`);

--
-- Limiti per la tabella `taglieprodotti`
--
ALTER TABLE `taglieprodotti`
  ADD CONSTRAINT `taglieprodotti_ibfk_1` FOREIGN KEY (`scarpa`) REFERENCES `prodotti` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
