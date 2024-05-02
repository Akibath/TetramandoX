-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2015 at 06:57 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `postpromotion`
--

-- --------------------------------------------------------

--
-- Table structure for table `28_4`
--

CREATE TABLE IF NOT EXISTS `28_4` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `commentText` varchar(4000) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `28_4`
--

INSERT INTO `28_4` (`id`, `personID`, `type`, `commentText`, `time`) VALUES
(1, 28, 'C', '111', 1428855143),
(2, 28, 'C', 'aa', 1428855743),
(3, 36, 'L', '', 1428855760),
(4, 38, 'H', '', 1428855774),
(5, 39, 'C', 'hii', 1428855792),
(6, 37, 'C', '====', 1428855806),
(7, 40, 'L', '', 1428855871),
(8, 36, 'C', '1', 1430315192),
(9, 28, 'C', '1', 1430476928),
(10, 28, 'C', '1', 1430476928),
(11, 28, 'C', '1', 1430476929);

-- --------------------------------------------------------

--
-- Table structure for table `28_6`
--

CREATE TABLE IF NOT EXISTS `28_6` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `commentText` varchar(4000) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `personID` (`personID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

--
-- Dumping data for table `28_6`
--

INSERT INTO `28_6` (`id`, `personID`, `type`, `commentText`, `time`) VALUES
(1, 28, 'C', '11', 1428937070),
(15, 36, 'C', 'aa', 1428993592),
(16, 36, 'C', 'a', 1428993769),
(17, 36, 'C', '1', 1428993872),
(18, 36, 'C', 'z', 1428993879),
(19, 36, 'C', 'a', 1428993891),
(20, 36, 'C', 'a', 1428993944),
(21, 36, 'C', 'a', 1428994056),
(22, 36, 'C', '1', 1428994061),
(23, 36, 'C', '1', 1428995283),
(24, 36, 'C', 'a', 1428995290),
(25, 36, 'C', 'z', 1428995296),
(26, 36, 'C', 'a', 1428995311),
(27, 36, 'C', 'a', 1428995356),
(28, 36, 'C', 'a', 1428995360),
(29, 36, 'C', '1', 1428995366),
(33, 36, 'H', '', 1428995736),
(34, 36, 'C', '1', 1429022931),
(35, 36, 'C', '1', 1429023784),
(36, 36, 'C', '1', 1429023801),
(41, 36, 'C', 'WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW', 1429024119),
(42, 28, 'C', 'pp', 1429951970);

-- --------------------------------------------------------

--
-- Table structure for table `28_11`
--

CREATE TABLE IF NOT EXISTS `28_11` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `commentText` varchar(4000) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `personID` (`personID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `28_13`
--

CREATE TABLE IF NOT EXISTS `28_13` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `commentText` varchar(4000) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `personID` (`personID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `28_13`
--

INSERT INTO `28_13` (`id`, `personID`, `type`, `commentText`, `time`) VALUES
(1, 28, 'L', '', 1435415722);

-- --------------------------------------------------------

--
-- Table structure for table `28_18`
--

CREATE TABLE IF NOT EXISTS `28_18` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `commentText` varchar(4000) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `personID` (`personID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `28_19`
--

CREATE TABLE IF NOT EXISTS `28_19` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `commentText` varchar(4000) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `personID` (`personID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `28_19`
--

INSERT INTO `28_19` (`id`, `personID`, `type`, `commentText`, `time`) VALUES
(1, 28, 'C', 'jjj', 1435415709),
(3, 28, 'H', '', 1435415713),
(4, 28, 'C', 'ppp', 1435416530);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `28_6`
--
ALTER TABLE `28_6`
  ADD CONSTRAINT `28_6_ibfk_1` FOREIGN KEY (`personID`) REFERENCES `users`.`userlogin` (`id`);

--
-- Constraints for table `28_11`
--
ALTER TABLE `28_11`
  ADD CONSTRAINT `28_11_ibfk_1` FOREIGN KEY (`personID`) REFERENCES `users`.`userlogin` (`id`);

--
-- Constraints for table `28_13`
--
ALTER TABLE `28_13`
  ADD CONSTRAINT `28_13_ibfk_1` FOREIGN KEY (`personID`) REFERENCES `users`.`userlogin` (`id`);

--
-- Constraints for table `28_18`
--
ALTER TABLE `28_18`
  ADD CONSTRAINT `28_18_ibfk_1` FOREIGN KEY (`personID`) REFERENCES `users`.`userlogin` (`id`);

--
-- Constraints for table `28_19`
--
ALTER TABLE `28_19`
  ADD CONSTRAINT `28_19_ibfk_1` FOREIGN KEY (`personID`) REFERENCES `users`.`userlogin` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
