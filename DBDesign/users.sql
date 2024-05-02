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
-- Database: `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_info`
--

CREATE TABLE IF NOT EXISTS `account_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `directoryName` varchar(44) NOT NULL,
  `mailActivationCode` varchar(64) NOT NULL,
  `timezoneOffset` int(11) NOT NULL,
  `secretQuest1` varchar(64) NOT NULL,
  `secretQuest2` varchar(64) NOT NULL,
  `secretQuest3` varchar(64) NOT NULL,
  `secretAns1` varchar(32) NOT NULL,
  `secretAns2` varchar(32) NOT NULL,
  `secretAns3` varchar(32) NOT NULL,
  `secretHint1` varchar(16) NOT NULL,
  `secretHint2` varchar(16) NOT NULL,
  `secretHint3` varchar(16) NOT NULL,
  `lastPassword` varchar(64) NOT NULL,
  `passwordChanged` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_info_ibfk_1` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `account_info`
--

INSERT INTO `account_info` (`id`, `userID`, `directoryName`, `mailActivationCode`, `timezoneOffset`, `secretQuest1`, `secretQuest2`, `secretQuest3`, `secretAns1`, `secretAns2`, `secretAns3`, `secretHint1`, `secretHint2`, `secretHint3`, `lastPassword`, `passwordChanged`) VALUES
(1, 28, '28_SVKFttsCuT6GZacDk46k0awnB4CgNnu7', '', -330, '', '', '', '', '', '', '', '', '', '', 0),
(2, 36, '36_YKYNnX22yfKKXPsrSO91fgHTIpU5l0eT', '', -330, '', '', '', '', '', '', '', '', '', '', 0),
(3, 37, '37_VIvAQLMLRxitrV0gmH2lNp1JdyGu78VY', '37', -330, '', '', '', '', '', '', '', '', '', '', 0),
(4, 38, '38_KJHFEoUrG9l9lRf0ivUe2at5TTG8TWF9', '38', -330, '', '', '', '', '', '', '', '', '', '', 0),
(5, 39, '39_6Ter1WwDwVtbUPlAXrZAM1VJLM3QO2Dw', '39', -330, '', '', '', '', '', '', '', '', '', '', 0),
(6, 40, '40_uEB32NAjuEm0mFO53kGtrjlLu7Z9lWyN', '40', -330, '', '', '', '', '', '', '', '', '', '', 0),
(7, 45, '45_NHVQCu7N7sAjaCiTlS7KqvcYfPz0UfLJ', '', -330, '', '', '', '', '', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ajax`
--

CREATE TABLE IF NOT EXISTS `ajax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `loggedIn` int(11) NOT NULL,
  `topMenu` int(11) NOT NULL,
  `lastSeen` int(11) NOT NULL,
  `unseenMsgs` int(11) NOT NULL,
  `unseenNotifs` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `ajax`
--

INSERT INTO `ajax` (`id`, `userID`, `loggedIn`, `topMenu`, `lastSeen`, `unseenMsgs`, `unseenNotifs`) VALUES
(1, 28, 1438700171, 1426326873, 1438700171, 1027, 0),
(3, 36, 1433152720, 1426326192, 1433152720, 1027, 0),
(4, 38, 1428855768, 1426326872, 1428855768, 0, 0),
(5, 37, 1428855798, 0, 1428855798, 0, 0),
(6, 39, 1428855783, 0, 1428855784, 0, 0),
(7, 40, 1428855816, 0, 1428855816, 0, 0),
(8, 45, 1431280607, 0, 1431280607, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `chatboxs`
--

CREATE TABLE IF NOT EXISTS `chatboxs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `chatbox1` int(11) DEFAULT NULL,
  `chatbox2` int(11) DEFAULT NULL,
  `chatbox3` int(11) DEFAULT NULL,
  `chatbox4` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `chatboxs`
--

INSERT INTO `chatboxs` (`id`, `userID`, `chatbox1`, `chatbox2`, `chatbox3`, `chatbox4`) VALUES
(1, 28, NULL, NULL, NULL, NULL),
(2, 36, NULL, NULL, NULL, NULL),
(3, 37, NULL, NULL, NULL, NULL),
(4, 38, NULL, NULL, NULL, NULL),
(5, 39, NULL, NULL, NULL, NULL),
(6, 40, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chatoff`
--

CREATE TABLE IF NOT EXISTS `chatoff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `personID` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `status` varchar(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  KEY `personID` (`personID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cookies`
--

CREATE TABLE IF NOT EXISTS `cookies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `c_userID` varchar(64) NOT NULL,
  `c_token` varchar(64) NOT NULL,
  `expires` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Dumping data for table `cookies`
--

INSERT INTO `cookies` (`id`, `userID`, `c_userID`, `c_token`, `expires`) VALUES
(46, 28, 'y8SldHWjmDVEASgI9iTox9XouCtG2OAPmBIvQdzBU', 'INOPdTYfIaYuKQeILU4CzxTFiw7rZ226', 1439304251),
(49, 36, '', '', 0),
(50, 38, '', '', 0),
(52, 39, '', '', 0),
(53, 40, '', '', 0),
(55, 37, '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `friendreq`
--

CREATE TABLE IF NOT EXISTS `friendreq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reqFrom` int(11) NOT NULL,
  `reqTo` int(11) NOT NULL,
  `seen` int(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `friendreq_ibfk_1` (`reqFrom`),
  KEY `friendreq_ibfk_2` (`reqTo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userOne` int(11) NOT NULL,
  `userTwo` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `friends_ibfk_1` (`userOne`),
  KEY `friends_ibfk_2` (`userTwo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `userOne`, `userTwo`, `time`) VALUES
(25, 40, 37, 3);

-- --------------------------------------------------------

--
-- Table structure for table `lastmessage`
--

CREATE TABLE IF NOT EXISTS `lastmessage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msgFrom` int(11) NOT NULL,
  `msgTo` int(11) NOT NULL,
  `seen` int(1) NOT NULL DEFAULT '0',
  `seenTime` int(11) NOT NULL,
  `topMenuSeen` int(11) NOT NULL DEFAULT '0',
  `lastSeenMsgID` int(11) NOT NULL,
  `deleted` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lastmessage_ibfk_1` (`msgFrom`),
  KEY `lastmessage_ibfk_2` (`msgTo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=216 ;

--
-- Dumping data for table `lastmessage`
--

INSERT INTO `lastmessage` (`id`, `msgFrom`, `msgTo`, `seen`, `seenTime`, `topMenuSeen`, `lastSeenMsgID`, `deleted`, `time`) VALUES
(15, 36, 40, 0, 0, 1, 0, '', 1426348829),
(174, 36, 38, 0, 0, 1, 0, '', 1426348848),
(197, 40, 28, 0, 1427308279, 1, 14, '', 1427302696),
(198, 28, 39, 0, 0, 0, 21, '', 1427302676),
(211, 28, 36, 0, 0, 0, 1241, '', 1435416783),
(214, 28, 37, 0, 0, 0, 24, '', 1428155243),
(215, 28, 38, 0, 0, 0, 229, '', 1428085956);

-- --------------------------------------------------------

--
-- Table structure for table `pageinfo`
--

CREATE TABLE IF NOT EXISTS `pageinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `adminID` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `category` varchar(32) NOT NULL,
  `dob` int(11) NOT NULL,
  `about` varchar(300) NOT NULL,
  `profilePic` varchar(64) NOT NULL,
  `coverPic` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pageinfo`
--

INSERT INTO `pageinfo` (`id`, `userID`, `adminID`, `name`, `category`, `dob`, `about`, `profilePic`, `coverPic`) VALUES
(1, 45, 28, 'Bitch Please', 'Fun & Entertainment', 832204800, 'blah blah blah', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `personalinfo`
--

CREATE TABLE IF NOT EXISTS `personalinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `firstName` varchar(32) NOT NULL,
  `middleName` varchar(32) NOT NULL,
  `lastName` varchar(32) NOT NULL,
  `nickName` varchar(8) NOT NULL,
  `gender` int(1) NOT NULL,
  `dob` int(11) NOT NULL,
  `profilePic` varchar(64) NOT NULL,
  `coverPic` varchar(64) NOT NULL,
  `currentCountry` varchar(32) NOT NULL,
  `currentState` varchar(32) NOT NULL,
  `currentCity` varchar(32) NOT NULL,
  `workComp` varchar(32) NOT NULL,
  `workPost` varchar(32) NOT NULL,
  `mobileNumber` int(10) DEFAULT NULL,
  `secMail` varchar(128) NOT NULL,
  `interestedGender` varchar(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `personalinfo`
--

INSERT INTO `personalinfo` (`id`, `userID`, `firstName`, `middleName`, `lastName`, `nickName`, `gender`, `dob`, `profilePic`, `coverPic`, `currentCountry`, `currentState`, `currentCity`, `workComp`, `workPost`, `mobileNumber`, `secMail`, `interestedGender`) VALUES
(1, 28, 'Akibath', 'Blox', 'Mohamed', 'akiD', 1, 832204800, '', '1434993057_dxJuKqwmbWoBrNDEuRvaZRRoxrRHYYZY.jpg', 'India', 'Tamil nadu', 'Cuddalore', 'TetramandoX', 'Designer', 2147483647, 'akibath@gmail.com', 'F'),
(2, 36, 'Albedo', '', 'Genix', 'alB', 1, 832197600, '', '', 'Australia', 'Victoria', 'Melbourne', 'Ornix Corporation', 'Chairman and CEO', 36, 'albedo@gmail.com', 'M'),
(3, 37, 'Ben', '', 'Tennison', '', 1, 1013641200, '', '', '', '', '', '', '', 37, 'ben10@gmail.com', 'M'),
(4, 38, 'Paradox', '', 'Pentagon', '', 1, 1106327200, '', '', '', '', '', '', '', 38, 'paradox@gmail.com', 'F'),
(5, 39, 'Mulla', '', 'Bhai', '', 1, 902204800, '', '', '', '', '', '', '', 39, 'mulla@gmail.com', 'B'),
(6, 40, 'Herculus', '', 'Pentagon', '', 0, 832204800, '', '', '', '', '', '', '', 40, 'pentagon@gmail.com', 'M');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `turnOffChat` varchar(1) NOT NULL DEFAULT 'N',
  `chatbarPosition` int(1) NOT NULL DEFAULT '0',
  `nameChanged` int(11) NOT NULL,
  `usernameChanged` int(11) NOT NULL,
  `passwordChanged` int(11) NOT NULL,
  `loginNotif` varchar(1) NOT NULL,
  `basicPrivacy` varchar(1) NOT NULL,
  `postPermission` varchar(1) NOT NULL DEFAULT 'e',
  `reqPermission` varchar(1) NOT NULL DEFAULT 'e',
  `chatPermission` varchar(1) NOT NULL DEFAULT 'e',
  `friendListPermission` varchar(1) NOT NULL DEFAULT 'e',
  `likesListPermission` varchar(1) NOT NULL DEFAULT 'e',
  PRIMARY KEY (`id`),
  KEY `settings_ibfk_1` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `userID`, `turnOffChat`, `chatbarPosition`, `nameChanged`, `usernameChanged`, `passwordChanged`, `loginNotif`, `basicPrivacy`, `postPermission`, `reqPermission`, `chatPermission`, `friendListPermission`, `likesListPermission`) VALUES
(1, 28, 'N', 0, 1424724957, 1423384666, 0, 'T', 'E', 'E', 'E', 'F', 'E', 'E'),
(2, 36, 'N', 1, 0, 0, 0, 'T', 'E', 'E', 'E', 'E', 'E', 'E'),
(3, 37, 'N', 1, 0, 0, 0, '', 'E', 'E', 'E', 'E', 'E', 'E'),
(4, 38, 'N', 0, 0, 0, 0, '', 'E', 'E', 'E', 'E', 'E', 'E'),
(5, 39, 'N', 0, 0, 0, 0, '', 'E', 'E', 'E', 'E', 'E', 'E'),
(6, 40, 'N', 0, 0, 0, 0, '', 'E', 'E', 'E', 'E', 'E', 'E');

-- --------------------------------------------------------

--
-- Table structure for table `spam_block`
--

CREATE TABLE IF NOT EXISTS `spam_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `personID` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `spam&blocked_users_ibfk_1` (`userID`),
  KEY `spam&blocked_users_ibfk_2` (`personID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `userlogin`
--

CREATE TABLE IF NOT EXISTS `userlogin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `type` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `userlogin`
--

INSERT INTO `userlogin` (`id`, `username`, `password`, `email`, `type`, `time`) VALUES
(28, 'akiD', '$2y$12$4jqJy.3dW4U50ZJGUbnLEOAp1U1NWGQffiGUs8Vk2cQNVLrtBi2TW', 'akibath@gmail.com', 'U', 0),
(36, 'albedo', '$2y$12$4jqJy.3dW4U50ZJGUbnLEOAp1U1NWGQffiGUs8Vk2cQNVLrtBi2TW', 'albedo112@gmail.com', 'U', 0),
(37, 'ben', '$2y$12$cCWnwH.s7O8yhn0WiLpZ/u.oGiK2YUwixKDUWqpTvHC2YIdUT7UuG', 'akibathxx@gmail.com', 'U', 0),
(38, 'paradox', '$2y$12$F0Ju6GdwqELAaYoieut7J.09p3HgRPAj2sSP25eHGIefl81sYGqaK', 'paradox@gmail.com', 'U', 0),
(39, 'mulla', '$2y$12$h/slamASaC6YpceblX0TuOQUqV3TzctfP5X3B3bGNEH1.WOzz0nk.', 'akibath1234@gmail.com', 'U', 0),
(40, 'herculus', '$2y$12$bFWVQWB5rFdIwZZGdNwh/ea4AhPAgCuMuBJQh4LKeqCzTtd.LI4Q6', 'aksds@dd.dom', 'U', 0),
(45, 'bitchPlease', '$2y$12$4jqJy.3dW4U50ZJGUbnLEOAp1U1NWGQffiGUs8Vk2cQNVLrtBi2TW', 'bitch@please.com', 'P', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_info`
--
ALTER TABLE `account_info`
  ADD CONSTRAINT `account_info_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userlogin` (`id`);

--
-- Constraints for table `ajax`
--
ALTER TABLE `ajax`
  ADD CONSTRAINT `ajax_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userlogin` (`id`);

--
-- Constraints for table `chatboxs`
--
ALTER TABLE `chatboxs`
  ADD CONSTRAINT `chatboxs_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userlogin` (`id`);

--
-- Constraints for table `chatoff`
--
ALTER TABLE `chatoff`
  ADD CONSTRAINT `chatoff_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userlogin` (`id`),
  ADD CONSTRAINT `chatoff_ibfk_2` FOREIGN KEY (`personID`) REFERENCES `userlogin` (`id`);

--
-- Constraints for table `cookies`
--
ALTER TABLE `cookies`
  ADD CONSTRAINT `cookies_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userlogin` (`id`);

--
-- Constraints for table `friendreq`
--
ALTER TABLE `friendreq`
  ADD CONSTRAINT `friendreq_ibfk_1` FOREIGN KEY (`reqFrom`) REFERENCES `userlogin` (`id`),
  ADD CONSTRAINT `friendreq_ibfk_2` FOREIGN KEY (`reqTo`) REFERENCES `userlogin` (`id`);

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`userOne`) REFERENCES `userlogin` (`id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`userTwo`) REFERENCES `userlogin` (`id`);

--
-- Constraints for table `lastmessage`
--
ALTER TABLE `lastmessage`
  ADD CONSTRAINT `lastmessage_ibfk_1` FOREIGN KEY (`msgFrom`) REFERENCES `userlogin` (`id`),
  ADD CONSTRAINT `lastmessage_ibfk_2` FOREIGN KEY (`msgTo`) REFERENCES `userlogin` (`id`);

--
-- Constraints for table `pageinfo`
--
ALTER TABLE `pageinfo`
  ADD CONSTRAINT `pageinfo_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userlogin` (`id`);

--
-- Constraints for table `personalinfo`
--
ALTER TABLE `personalinfo`
  ADD CONSTRAINT `personalinfo_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userlogin` (`id`);

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userlogin` (`id`);

--
-- Constraints for table `spam_block`
--
ALTER TABLE `spam_block`
  ADD CONSTRAINT `spam_block_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `userlogin` (`id`),
  ADD CONSTRAINT `spam_block_ibfk_2` FOREIGN KEY (`personID`) REFERENCES `userlogin` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
