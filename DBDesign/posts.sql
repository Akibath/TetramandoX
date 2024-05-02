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
-- Database: `posts`
--

-- --------------------------------------------------------

--
-- Table structure for table `28`
--

CREATE TABLE IF NOT EXISTS `28` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postID` varchar(20) NOT NULL,
  `text` varchar(4000) NOT NULL,
  `image` varchar(64) NOT NULL,
  `type` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `28`
--

INSERT INTO `28` (`id`, `postID`, `text`, `image`, `type`, `time`) VALUES
(4, '28_4', 'hii', '', '', 1428855137),
(6, '28_6', '11', '', '', 1428937052),
(11, '28_11', 'hello', '', '', 1430729162),
(13, '28_13', '', '1434987080_qrCOVGIeeLXGhbGvihHGlCUczJkroLsC.jpg', 'C', 1434987081),
(18, '28_18', '', '1434993057_dxJuKqwmbWoBrNDEuRvaZRRoxrRHYYZY.jpg', 'C', 1434993058),
(19, '28_19', 'hii', '', '', 1435415696);

-- --------------------------------------------------------

--
-- Table structure for table `36`
--

CREATE TABLE IF NOT EXISTS `36` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postID` varchar(20) NOT NULL,
  `text` varchar(4000) NOT NULL,
  `image` varchar(64) NOT NULL,
  `type` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `37`
--

CREATE TABLE IF NOT EXISTS `37` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postID` varchar(20) NOT NULL,
  `text` varchar(4000) NOT NULL,
  `image` varchar(64) NOT NULL,
  `type` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `38`
--

CREATE TABLE IF NOT EXISTS `38` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postID` varchar(20) NOT NULL,
  `text` varchar(4000) NOT NULL,
  `image` varchar(64) NOT NULL,
  `type` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `39`
--

CREATE TABLE IF NOT EXISTS `39` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postID` varchar(20) NOT NULL,
  `text` varchar(4000) NOT NULL,
  `image` varchar(64) NOT NULL,
  `type` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `40`
--

CREATE TABLE IF NOT EXISTS `40` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postID` varchar(20) NOT NULL,
  `text` varchar(4000) NOT NULL,
  `image` varchar(64) NOT NULL,
  `type` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `45`
--

CREATE TABLE IF NOT EXISTS `45` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postID` varchar(20) NOT NULL,
  `text` varchar(4000) NOT NULL,
  `image` varchar(64) NOT NULL,
  `type` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
