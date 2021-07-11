SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `buch` (
  `BUCHID` int(11) NOT NULL,
  `NUTZERID` int(11) NOT NULL,
  `KATEGORIEID` int(11) NOT NULL,
  `UNTERKATEGORIEID` int(11) DEFAULT NULL,
  `BUCHNAME` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `BESCHREIBUNG` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ERSCHEINUNGSDATUM` date DEFAULT NULL,
  `AUTOR` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `BILD` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `BUCHINHALT` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `buch` (`BUCHID`, `NUTZERID`, `KATEGORIEID`, `UNTERKATEGORIEID`, `BUCHNAME`, `BESCHREIBUNG`, `ERSCHEINUNGSDATUM`, `AUTOR`, `BILD`, `BUCHINHALT`) VALUES
(1, 1, 2, NULL, 'Endlich mit Aktien Geld verdienen', 'Die Strategien und Techniken die Erfolg versprechen', '2012-04-01', 'Max Otte', 'img/Endlich mit Aktien Geld verdienen_ Die Strategien und Techniken, die Erfolg versprechen.jpg', 'pdf/Endlich mit Aktien Geld verdienen_ Die Strategien und Techniken, die Erfolg versprechen.pdf'),
(2, 1, 1, NULL, 'Finanzierung', 'Anleihen, Aktien, Optionen', '2015-05-01', 'Thomas Schuster, Margarita Uskova', 'img/Finanzierung_ Anleihen, Aktien, Optionen.jpg', 'pdf/Finanzierung_ Anleihen, Aktien, Optionen.pdf'),
(3, 1, 3, NULL, 'Mathematik - Motor der Wirtschaft', 'Initiative der Wirtschaft zum Jahr der Mathematik', '2008-02-01', 'Gert-Martin Greuel, Reinhold Remmert, Gerhard Rupprecht', 'img/Mathematik - Motor der Wirtschaft_ Initiative der Wirtschaft zum Jahr der Mathematik.jpg', 'pdf/Mathematik - Motor der Wirtschaft_ Initiative der Wirtschaft zum Jahr der Mathematik.pdf'),
(4, 2, 2, NULL, ' Stock Investing for Dummies ', 'Get savvy tips on todays best investment opportunities and pitfalls', '2006-01-01', 'Paul Mladjenovic', 'img/Stock investing for Dummies.jpg', 'pdf/Stock investing for Dummies.pdf'),
(5, 2, 4, 1, ' Immobilien zum halben Preis! ', 'Tipps, Tricks und Strategien, um zur eigenen Immobilie zum Schnäppchenpreis zu kommen', '2012-02-01', 'Alexander Leary', 'img/Immobilien zum halben Preis! - Tipps, Tricks und Strategien, um zur eigenen Immobilie zum Schnaeppchenpreis zu kommen.jpg', 'pdf/Immobilien zum halben Preis! - Tipps, Tricks und Strategien, um zur eigenen Immobilie zum Schnaeppchenpreis zu kommen.pdf');

CREATE TABLE `kategorie` (
  `KATEGORIEID` int(11) NOT NULL,
  `KATEGORIENAME` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `kategorie` (`KATEGORIEID`, `KATEGORIENAME`) VALUES
(1, 'Fonds'),
(2, 'Aktien'),
(3, 'Steuern'),
(4, 'Immobilien');

CREATE TABLE `kommentar` (
  `KOMMENTARID` int(11) NOT NULL,
  `BUCHID` int(11) NOT NULL,
  `NUTZERID` int(11) NOT NULL,
  `KOMMENTAR` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DATUM` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `nutzer` (
  `NUTZERID` int(11) NOT NULL,
  `NUTZERNAME` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `PASSWORT` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `nutzer` (`NUTZERID`, `NUTZERNAME`, `PASSWORT`) VALUES
(1, 'zernabze', 'ayy'),
(2, 'dilarako', 'ayy');

CREATE TABLE `unterkategorie` (
  `UNTERKATEGORIEID` int(11) NOT NULL,
  `KATEGORIEID` int(11) NOT NULL,
  `UNTERKATEGORIENAME` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `unterkategorie` (`UNTERKATEGORIEID`, `KATEGORIEID`, `UNTERKATEGORIENAME`) VALUES
(1, 4, 'Ratgeber');


ALTER TABLE `buch`
  ADD PRIMARY KEY (`BUCHID`),
  ADD KEY `FK_BUCH_HAT_KATEGORI` (`KATEGORIEID`),
  ADD KEY `FK_BUCH_KANN_HABE_UNTERKAT` (`UNTERKATEGORIEID`),
  ADD KEY `FK_BUCH_VERWALTET_NUTZER` (`NUTZERID`);

ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`KATEGORIEID`);

ALTER TABLE `kommentar`
  ADD PRIMARY KEY (`KOMMENTARID`),
  ADD KEY `FK_KOMMENTA_ENTHAELT_BUCH` (`BUCHID`),
  ADD KEY `FK_KOMMENTA_GEHOERT_Z_NUTZER` (`NUTZERID`);

ALTER TABLE `nutzer`
  ADD PRIMARY KEY (`NUTZERID`);

ALTER TABLE `unterkategorie`
  ADD PRIMARY KEY (`UNTERKATEGORIEID`),
  ADD KEY `FK_UNTERKAT_BESITZT_KATEGORI` (`KATEGORIEID`);


ALTER TABLE `buch`
  MODIFY `BUCHID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `kategorie`
  MODIFY `KATEGORIEID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `kommentar`
  MODIFY `KOMMENTARID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `nutzer`
  MODIFY `NUTZERID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `unterkategorie`
  MODIFY `UNTERKATEGORIEID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `buch`
  ADD CONSTRAINT `FK_BUCH_HAT_KATEGORI` FOREIGN KEY (`KATEGORIEID`) REFERENCES `kategorie` (`KATEGORIEID`),
  ADD CONSTRAINT `FK_BUCH_KANN_HABE_UNTERKAT` FOREIGN KEY (`UNTERKATEGORIEID`) REFERENCES `unterkategorie` (`UNTERKATEGORIEID`),
  ADD CONSTRAINT `FK_BUCH_VERWALTET_NUTZER` FOREIGN KEY (`NUTZERID`) REFERENCES `nutzer` (`NUTZERID`);

ALTER TABLE `kommentar`
  ADD CONSTRAINT `FK_KOMMENTA_ENTHAELT_BUCH` FOREIGN KEY (`BUCHID`) REFERENCES `buch` (`BUCHID`),
  ADD CONSTRAINT `FK_KOMMENTA_GEHOERT_Z_NUTZER` FOREIGN KEY (`NUTZERID`) REFERENCES `nutzer` (`NUTZERID`);

ALTER TABLE `unterkategorie`
  ADD CONSTRAINT `FK_UNTERKAT_BESITZT_KATEGORI` FOREIGN KEY (`KATEGORIEID`) REFERENCES `kategorie` (`KATEGORIEID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
