-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 03, 2025 at 12:48 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proj`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dyscypliny`
--

CREATE TABLE `dyscypliny` (
  `dyscyplina_id` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL,
  `typ` enum('indywidualna','zespołowa') NOT NULL,
  `opis` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dyscypliny`
--

INSERT INTO `dyscypliny` (`dyscyplina_id`, `nazwa`, `typ`, `opis`) VALUES
(1, 'Bieg na 100m', 'indywidualna', 'Sprint krótki, zawody na stadionie'),
(2, 'Pływanie stylem dowolnym', 'indywidualna', 'Zawody na basenie 50m'),
(3, 'Skok w dal', 'indywidualna', 'Konkurencja techniczna lekkoatletyczna'),
(4, 'Hokej na lodzie', 'zespołowa', 'Najszybszy sport na świecie'),
(5, 'Bieg maratoński', 'indywidualna', 'Bieg na dystansie 42.195 km'),
(6, 'Pływanie stylem motylkowym', 'indywidualna', 'Styl pływacki wymagający koordynacji'),
(7, 'Koszykówka', 'zespołowa', 'Sport drużynowy z piłką'),
(8, 'Siatkówka', 'zespołowa', 'Sport drużynowy na boisku podzielonym siatką');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kluby`
--

CREATE TABLE `kluby` (
  `klub_id` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL,
  `kraj` varchar(50) DEFAULT NULL,
  `data_zalozenia` date DEFAULT NULL,
  `adres_siedziby` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kluby`
--

INSERT INTO `kluby` (`klub_id`, `nazwa`, `kraj`, `data_zalozenia`, `adres_siedziby`) VALUES
(1, 'Warszawski Klub Lekkoatletyczny', 'Polska', '1980-05-15', 'ul. Sportowa 1, Warszawa'),
(2, 'Krakowska Akademia Pływania', 'Polska', '1995-10-20', 'ul. Basenowa 5, Kraków'),
(3, 'Cracovia Kraków', 'Polska', '1906-02-10', 'ul. Legionowa 118, Kraków'),
(11, 'dasdas', 'dsadas', '2133-12-12', 'dasdas'),
(12, 'Górnik Zabrze', 'Polska', '1948-12-14', 'ul. Roosevelta 81, Zabrze'),
(13, 'Legia Warszawa', 'Polska', '1916-03-05', 'ul. Łazienkowska 3, Warszawa'),
(14, 'AZS AWF Warszawa', 'Polska', '1950-01-10', 'ul. Marymoncka 34, Warszawa');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sportowcy`
--

CREATE TABLE `sportowcy` (
  `sportowiec_id` int(11) NOT NULL,
  `imie` varchar(50) NOT NULL,
  `nazwisko` varchar(50) NOT NULL,
  `data_urodzenia` date NOT NULL,
  `plec` enum('M','K') NOT NULL,
  `kraj_pochodzenia` varchar(50) DEFAULT NULL,
  `klub_id` int(11) DEFAULT NULL,
  `trener_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sportowcy`
--

INSERT INTO `sportowcy` (`sportowiec_id`, `imie`, `nazwisko`, `data_urodzenia`, `plec`, `kraj_pochodzenia`, `klub_id`, `trener_id`) VALUES
(1, 'Anna', 'Kowalska', '1995-03-15', 'K', 'Polska', 1, 1),
(2, 'Jan', 'Nowak', '1990-07-22', 'M', 'Polska', 2, 2),
(3, 'Ewa', 'Zielińska', '1998-11-30', 'K', 'Polska', 1, 1),
(4, 'Michał', 'Wójcik', '1992-05-10', 'M', 'Polska', 3, 3),
(5, 'Kamil', 'Ziobro', '1992-05-10', 'M', 'Polska', 3, 3),
(16, 'Michał ', 'Jaracz', '0000-00-00', 'M', NULL, NULL, NULL),
(18, 'Marcin', 'Lewandowski', '1991-06-13', 'M', 'Polska', 12, 4),
(19, 'Agnieszka', 'Nowicka', '1996-09-25', 'K', 'Polska', 13, 5),
(20, 'Tomasz', 'Kamiński', '1994-02-18', 'M', 'Polska', 14, 6),
(21, 'Karolina', 'Zając', '1997-07-30', 'K', 'Polska', 12, 4),
(22, 'Piotr', 'Dąbrowski', '1993-11-08', 'M', 'Polska', 13, 5),
(23, 'Adam', 'Malinowski', '1994-08-12', 'M', 'Polska', 1, 1),
(24, 'Katarzyna', 'Woźniak', '1996-04-25', 'K', 'Polska', 2, 2),
(25, 'Piotr', 'Nowicki', '1993-11-08', 'M', 'Polska', 3, 3),
(26, 'Magdalena', 'Lis', '1997-02-14', 'K', 'Polska', 12, 4),
(27, 'Marek', 'Kowal', '1995-07-30', 'M', 'Polska', 13, 5);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sportowiec_dyscyplina`
--

CREATE TABLE `sportowiec_dyscyplina` (
  `sportowiec_id` int(11) NOT NULL,
  `dyscyplina_id` int(11) NOT NULL,
  `data_rozpoczecia` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sportowiec_dyscyplina`
--

INSERT INTO `sportowiec_dyscyplina` (`sportowiec_id`, `dyscyplina_id`, `data_rozpoczecia`) VALUES
(1, 1, '2015-01-10'),
(1, 3, '2016-03-05'),
(2, 2, '2014-09-12'),
(3, 1, '2017-04-20'),
(4, 4, '2018-01-15'),
(5, 4, '2018-01-15'),
(18, 5, '2018-03-10'),
(19, 7, '2019-05-15'),
(20, 8, '2020-02-20'),
(21, 5, '2019-01-12'),
(22, 7, '2020-06-18'),
(23, 1, '2016-05-15'),
(23, 3, '2017-03-10'),
(24, 2, '2015-09-20'),
(25, 4, '2018-02-15'),
(26, 5, '2019-04-12'),
(27, 7, '2020-07-18');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `trenerzy`
--

CREATE TABLE `trenerzy` (
  `trener_id` int(11) NOT NULL,
  `imie` varchar(50) NOT NULL,
  `nazwisko` varchar(50) NOT NULL,
  `dyscyplina_id` int(11) DEFAULT NULL,
  `data_rozpoczecia_kariery` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trenerzy`
--

INSERT INTO `trenerzy` (`trener_id`, `imie`, `nazwisko`, `dyscyplina_id`, `data_rozpoczecia_kariery`) VALUES
(1, 'Adam', 'Nowak', 1, '2000-09-01'),
(2, 'Maria', 'Wiśniewska', 2, '1998-06-15'),
(3, 'Piotr', 'Kowalski', 4, '2010-03-10'),
(4, 'Katarzyna', 'Lis', 5, '2015-08-20'),
(5, 'Marek', 'Kowalczyk', 7, '2018-04-15'),
(6, 'Joanna', 'Wójcik', 8, '2019-01-10');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wyniki`
--

CREATE TABLE `wyniki` (
  `wynik_id` int(11) NOT NULL,
  `zawody_id` int(11) DEFAULT NULL,
  `sportowiec_id` int(11) DEFAULT NULL,
  `pozycja` int(11) DEFAULT NULL,
  `wynik` varchar(50) DEFAULT NULL,
  `notatki` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wyniki`
--

INSERT INTO `wyniki` (`wynik_id`, `zawody_id`, `sportowiec_id`, `pozycja`, `wynik`, `notatki`) VALUES
(1, 1, 1, 1, '10.95s', 'Nowy rekord życiowy'),
(2, 1, 3, 5, '11.20s', NULL),
(3, 2, 2, 2, '23.45s', 'Awans do finału'),
(4, 3, 4, NULL, '3:2', 'Mecz grupowy'),
(5, 3, 5, NULL, '3:2', 'Mecz grupowy'),
(6, 4, 4, 1, '4:1', 'Wygrany finał pucharu'),
(7, 4, 5, 1, '4:1', 'Wygrany finał pucharu'),
(14, 5, 18, NULL, NULL, 'Zgłoszony do udziału'),
(15, 5, 21, NULL, NULL, 'Zgłoszony do udziału'),
(16, 7, 19, NULL, NULL, 'Rezerwowy w drużynie'),
(17, 7, 22, NULL, NULL, 'Podstawowy zawodnik'),
(18, 9, 1, NULL, NULL, 'Zgłoszony do biegów eliminacyjnych'),
(19, 9, 3, NULL, NULL, 'Zgłoszony do biegów eliminacyjnych'),
(20, 1, 1, 1, '10.89s', 'Złoty medal - rekord życiowy'),
(21, 1, 3, 3, '11.05s', 'Brązowy medal'),
(22, 2, 2, 1, '22.98s', 'Złoty medal - nowy rekord zawodów'),
(23, 4, 4, 1, '5:2', 'Złoty medal - finał pucharu'),
(24, 4, 5, 1, '5:2', 'Złoty medal - finał pucharu'),
(25, 6, 24, 2, '24.12s', 'Srebrny medal - mistrzostwa Europy'),
(26, 8, 25, 3, NULL, 'Brązowy medal - Liga Światowa'),
(27, 5, 26, 1, '2:18:45', 'Złoty medal - maraton'),
(28, 7, 27, 2, '89:78', 'Srebrny medal - turniej pucharowy'),
(29, 9, 23, 3, '10.92s', 'Brązowy medal - mistrzostwa świata');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zawody`
--

CREATE TABLE `zawody` (
  `zawody_id` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL,
  `dyscyplina_id` int(11) DEFAULT NULL,
  `data_rozpoczecia` date NOT NULL,
  `data_zakonczenia` date DEFAULT NULL,
  `miejsce` varchar(100) DEFAULT NULL,
  `organizator` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zawody`
--

INSERT INTO `zawody` (`zawody_id`, `nazwa`, `dyscyplina_id`, `data_rozpoczecia`, `data_zakonczenia`, `miejsce`, `organizator`) VALUES
(1, 'Mistrzostwa Polski w Lekkoatletyce', 1, '2023-06-10', '2023-06-12', 'Stadion Narodowy, Warszawa', 'Polski Związek Lekkoatletyki'),
(2, 'Puchar Polski w Pływaniu', 2, '2023-07-15', '2023-07-16', 'AquaPark, Kraków', 'Polski Związek Pływacki'),
(3, 'Polska liga hokeja na lodzie', 4, '2023-09-10', '2024-04-10', 'Lodowiska, Polska', 'PZHL'),
(4, 'Puchar Polski w hokeju na lodzie', 4, '2024-12-28', '2024-12-30', 'Lodowisko, Bytom', 'PZHL'),
(5, 'Maraton Warszawski', 5, '2025-09-28', '2025-09-28', 'Warszawa', 'Fundacja Maraton Warszawski'),
(6, 'Mistrzostwa Europy w Pływaniu', 2, '2025-08-10', '2025-08-17', 'Budapeszt, Węgry', 'LEN'),
(7, 'Turniej Koszykówki o Puchar Polski', 7, '2025-10-15', '2025-10-20', 'Hala Torwar, Warszawa', 'Polski Związek Koszykówki'),
(8, 'Liga Światowa w Siatkówce', 8, '2025-06-01', '2025-07-15', 'Różne miasta', 'FIVB'),
(9, 'Mistrzostwa Świata w Lekkoatletyce', 1, '2025-08-20', '2025-08-30', 'Tokio, Japonia', 'World Athletics');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `dyscypliny`
--
ALTER TABLE `dyscypliny`
  ADD PRIMARY KEY (`dyscyplina_id`);

--
-- Indeksy dla tabeli `kluby`
--
ALTER TABLE `kluby`
  ADD PRIMARY KEY (`klub_id`);

--
-- Indeksy dla tabeli `sportowcy`
--
ALTER TABLE `sportowcy`
  ADD PRIMARY KEY (`sportowiec_id`),
  ADD KEY `klub_id` (`klub_id`),
  ADD KEY `trener_id` (`trener_id`);

--
-- Indeksy dla tabeli `sportowiec_dyscyplina`
--
ALTER TABLE `sportowiec_dyscyplina`
  ADD PRIMARY KEY (`sportowiec_id`,`dyscyplina_id`),
  ADD KEY `dyscyplina_id` (`dyscyplina_id`);

--
-- Indeksy dla tabeli `trenerzy`
--
ALTER TABLE `trenerzy`
  ADD PRIMARY KEY (`trener_id`),
  ADD KEY `dyscyplina_id` (`dyscyplina_id`);

--
-- Indeksy dla tabeli `wyniki`
--
ALTER TABLE `wyniki`
  ADD PRIMARY KEY (`wynik_id`),
  ADD KEY `zawody_id` (`zawody_id`),
  ADD KEY `sportowiec_id` (`sportowiec_id`);

--
-- Indeksy dla tabeli `zawody`
--
ALTER TABLE `zawody`
  ADD PRIMARY KEY (`zawody_id`),
  ADD KEY `dyscyplina_id` (`dyscyplina_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dyscypliny`
--
ALTER TABLE `dyscypliny`
  MODIFY `dyscyplina_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `kluby`
--
ALTER TABLE `kluby`
  MODIFY `klub_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sportowcy`
--
ALTER TABLE `sportowcy`
  MODIFY `sportowiec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `trenerzy`
--
ALTER TABLE `trenerzy`
  MODIFY `trener_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `wyniki`
--
ALTER TABLE `wyniki`
  MODIFY `wynik_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `zawody`
--
ALTER TABLE `zawody`
  MODIFY `zawody_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sportowcy`
--
ALTER TABLE `sportowcy`
  ADD CONSTRAINT `sportowcy_ibfk_1` FOREIGN KEY (`klub_id`) REFERENCES `kluby` (`klub_id`),
  ADD CONSTRAINT `sportowcy_ibfk_2` FOREIGN KEY (`trener_id`) REFERENCES `trenerzy` (`trener_id`);

--
-- Constraints for table `sportowiec_dyscyplina`
--
ALTER TABLE `sportowiec_dyscyplina`
  ADD CONSTRAINT `sportowiec_dyscyplina_ibfk_1` FOREIGN KEY (`sportowiec_id`) REFERENCES `sportowcy` (`sportowiec_id`),
  ADD CONSTRAINT `sportowiec_dyscyplina_ibfk_2` FOREIGN KEY (`dyscyplina_id`) REFERENCES `dyscypliny` (`dyscyplina_id`);

--
-- Constraints for table `trenerzy`
--
ALTER TABLE `trenerzy`
  ADD CONSTRAINT `trenerzy_ibfk_1` FOREIGN KEY (`dyscyplina_id`) REFERENCES `dyscypliny` (`dyscyplina_id`);

--
-- Constraints for table `wyniki`
--
ALTER TABLE `wyniki`
  ADD CONSTRAINT `wyniki_ibfk_1` FOREIGN KEY (`zawody_id`) REFERENCES `zawody` (`zawody_id`),
  ADD CONSTRAINT `wyniki_ibfk_2` FOREIGN KEY (`sportowiec_id`) REFERENCES `sportowcy` (`sportowiec_id`);

--
-- Constraints for table `zawody`
--
ALTER TABLE `zawody`
  ADD CONSTRAINT `zawody_ibfk_1` FOREIGN KEY (`dyscyplina_id`) REFERENCES `dyscypliny` (`dyscyplina_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
