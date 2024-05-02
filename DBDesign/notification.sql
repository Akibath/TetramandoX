-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2015 at 06:58 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `notification`
--

-- --------------------------------------------------------

--
-- Table structure for table `28`
--

CREATE TABLE IF NOT EXISTS `28` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `postID` varchar(20) NOT NULL,
  `commentID` int(11) NOT NULL,
  `topMenuSeen` int(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `28`
--

INSERT INTO `28` (`id`, `senderID`, `type`, `postID`, `commentID`, `topMenuSeen`, `time`) VALUES
(1, 36, 'L', '28_6', 0, 0, 1421320406),
(35, 36, 'C', '28_6', 41, 0, 1429024119),
(36, 36, 'C', '28_7', 1, 0, 1429540921),
(38, 36, 'C', '28_4', 8, 0, 1430315192);

-- --------------------------------------------------------

--
-- Table structure for table `36`
--

CREATE TABLE IF NOT EXISTS `36` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `postID` varchar(20) NOT NULL,
  `commentID` int(11) NOT NULL,
  `topMenuSeen` int(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `senderID` (`senderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `37`
--

CREATE TABLE IF NOT EXISTS `37` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `postID` varchar(20) NOT NULL,
  `commentID` int(11) NOT NULL,
  `topMenuSeen` int(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `senderID` (`senderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `38`
--

CREATE TABLE IF NOT EXISTS `38` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `postID` varchar(20) NOT NULL,
  `commentID` int(11) NOT NULL,
  `topMenuSeen` int(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `senderID` (`senderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `39`
--

CREATE TABLE IF NOT EXISTS `39` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `postID` varchar(20) NOT NULL,
  `commentID` int(11) NOT NULL,
  `topMenuSeen` int(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `senderID` (`senderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `40`
--

CREATE TABLE IF NOT EXISTS `40` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `postID` varchar(20) NOT NULL,
  `commentID` int(11) NOT NULL,
  `topMenuSeen` int(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `senderID` (`senderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `36`
--
ALTER TABLE `36`
  ADD CONSTRAINT `36_ibfk_1` FOREIGN KEY (`senderID`) REFERENCES `users`.`userlogin` (`id`);

--
-- Constraints for table `37`
--
ALTER TABLE `37`
  ADD CONSTRAINT `37_ibfk_1` FOREIGN KEY (`senderID`) REFERENCES `users`.`userlogin` (`id`);

--
-- Constraints for table `38`
--
ALTER TABLE `38`
  ADD CONSTRAINT `38_ibfk_1` FOREIGN KEY (`senderID`) REFERENCES `users`.`userlogin` (`id`);

--
-- Constraints for table `39`
--
ALTER TABLE `39`
  ADD CONSTRAINT `39_ibfk_1` FOREIGN KEY (`senderID`) REFERENCES `users`.`userlogin` (`id`);

--
-- Constraints for table `40`
--
ALTER TABLE `40`
  ADD CONSTRAINT `40_ibfk_1` FOREIGN KEY (`senderID`) REFERENCES `users`.`userlogin` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
