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
-- Database: `messages`
--

-- --------------------------------------------------------

--
-- Table structure for table `28_36`
--

CREATE TABLE IF NOT EXISTS `28_36` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `msg` varchar(3000) NOT NULL,
  `seen` int(1) NOT NULL DEFAULT '0',
  `deleted` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1242 ;

--
-- Dumping data for table `28_36`
--

INSERT INTO `28_36` (`id`, `senderID`, `msg`, `seen`, `deleted`, `time`) VALUES
(1112, 36, '123', 0, '', 1426950206),
(1113, 28, '321', 0, '', 1426950214),
(1114, 36, 'qw', 0, '', 1426950686),
(1115, 28, 'wq', 0, '', 1426950690),
(1116, 28, '1', 0, '', 1426950740),
(1117, 36, '1r', 0, '', 1426950748),
(1124, 36, 'a', 0, '', 1426951365),
(1125, 36, 'jk', 0, '', 1426952166),
(1126, 36, 'a', 0, '', 1426954939),
(1127, 36, '1', 0, '', 1426955018),
(1128, 36, '12', 0, '', 1426955265),
(1129, 36, 'hiiiko', 0, '', 1427005029),
(1130, 36, 'kiki', 0, '', 1427005039),
(1131, 36, 'aa', 0, '', 1427199547),
(1132, 36, 'n', 0, '', 1427199574),
(1133, 28, 'nm', 0, '', 1427199581),
(1134, 36, 'auhy', 0, '', 1427199673),
(1135, 28, 'NN', 0, '', 1427199848),
(1137, 36, 'nm', 0, '', 1427202656),
(1138, 36, 'mn', 0, '', 1427202671),
(1139, 28, 'kl', 0, '', 1427202677),
(1140, 28, 'opiopiio', 0, '', 1427202685),
(1141, 36, 'nn', 0, '', 1427202697),
(1142, 28, 'nmnmnmnmnmnmnmnmnmnmnmnmn', 0, '', 1427202710),
(1143, 36, 'q', 0, '', 1427202767),
(1144, 28, '=D', 0, '', 1427202790),
(1145, 36, 'hiiiii', 0, '', 1427202795),
(1146, 28, 'hello', 0, '', 1427202800),
(1150, 28, 'heeee', 0, '', 1427202834),
(1151, 36, 'ooooo', 0, '', 1427202837),
(1152, 36, '12', 0, '', 1427203436),
(1153, 36, 'q', 0, '', 1427203441),
(1154, 36, 'a', 0, '', 1427203449),
(1155, 28, 'qwerty', 0, '', 1427218890),
(1157, 28, 'ml', 0, '', 1427269222),
(1158, 28, '1', 0, '', 1427291111),
(1159, 28, 'asa', 0, '', 1427296368),
(1160, 28, '123', 0, '', 1427301133),
(1161, 28, 'aaa', 0, '', 1427302670),
(1162, 28, '1', 0, '', 1427479354),
(1163, 28, '1', 0, '', 1427479375),
(1164, 28, 'a', 0, '', 1427479406),
(1165, 28, 'a', 0, '', 1427479424),
(1166, 28, 'q', 0, '', 1427479429),
(1167, 28, 'qw', 0, '', 1427479451),
(1168, 28, 'lk', 0, '', 1427479457),
(1169, 28, 'q', 0, '', 1427479509),
(1170, 28, 'a', 0, '', 1427479578),
(1171, 28, 'a', 0, '', 1427479641),
(1172, 28, 'check!@#', 0, '', 1427479689),
(1173, 28, 'aak', 0, '', 1427479702),
(1174, 28, 'aa&amp;aa', 0, '', 1427479930),
(1175, 28, 'nn', 0, '', 1427953924),
(1176, 28, 'hiiiiii', 0, '', 1427953949),
(1177, 28, '111', 0, '', 1427967270),
(1178, 28, '11', 0, '', 1427967273),
(1182, 36, '=D', 0, '', 1427968146),
(1183, 28, 'jjj', 0, '', 1427968170),
(1184, 36, 'kkkkkkkkkkkkkkkkk', 0, '', 1427968193),
(1185, 28, 'WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW', 0, '', 1427968448),
(1188, 36, 'hmm', 0, '', 1427968474),
(1193, 36, 'aa', 0, '', 1427968514),
(1194, 36, '12345', 0, '', 1427968929),
(1195, 36, '122', 0, '', 1427968977),
(1196, 36, 'qq', 0, '', 1427968983),
(1197, 36, 'xmxmxmxmxm', 0, '', 1427969033),
(1198, 28, 'mm :o', 0, '', 1428056520),
(1199, 28, ':o', 0, '', 1428056533),
(1200, 28, ':o', 0, '', 1428056542),
(1201, 28, 'nn', 0, '', 1428056550),
(1202, 28, ':o', 0, '', 1428056553),
(1203, 28, ':-) :-( =D :''(', 0, '', 1428056586),
(1204, 28, '&lt;3', 0, '', 1428056592),
(1205, 28, 'akibath =D mohamed', 0, '', 1428056607),
(1206, 28, '&lt;3', 0, '', 1428155712),
(1207, 28, '&lt;3P', 0, '', 1428155714),
(1208, 28, 'a &lt;3 &lt;3P a', 0, '', 1428155731),
(1209, 28, 'akibath mpohamed =D as\nasdd sdqkjdnqdjbwdjbqdjbwqdjbwd', 0, '', 1428155873),
(1210, 28, 'hello world =D', 0, '', 1428155904),
(1211, 28, 'hello =D weorld', 0, '', 1428155954),
(1212, 28, '&lt;3', 0, '', 1428156189),
(1213, 28, 'AA', 0, '', 1428156271),
(1214, 28, '=D', 0, '', 1428156330),
(1215, 28, 'akibath =D', 0, '', 1428156338),
(1216, 28, 'mohamed =D', 0, '', 1428156344),
(1217, 28, '=D mohamed', 0, '', 1428156352),
(1218, 28, 'akibath =D mohamed', 0, '', 1428156359),
(1219, 28, '=D', 0, '', 1428156364),
(1220, 28, 'akibath =D', 0, '', 1428156385),
(1221, 28, 'akibath =D mohamed', 0, '', 1428156403),
(1222, 28, '&lt;3', 0, '', 1428156454),
(1223, 28, '=D', 0, '', 1428156804),
(1224, 28, 'aki =D', 0, '', 1428156813),
(1225, 28, '=D =D', 0, '', 1428156845),
(1226, 28, 'jcnwjnwefnew =D =D', 0, '', 1428156855),
(1227, 28, 'njknjwf =D =D wdwqd', 0, '', 1428156862),
(1228, 28, '=D', 0, '', 1428156876),
(1229, 28, 'anything =D', 0, '', 1428156883),
(1230, 28, '=D anything', 0, '', 1428156886),
(1231, 28, 'anything =D anything', 0, '', 1428156891),
(1233, 28, 'kwfbewjvbewvw3fn =D wcieiewhfoiwjfjfwnfwofojfewofwoif =D wcjweoiewioweif =D ceeic =D sciijweic cwkjc', 0, '', 1428157069),
(1234, 28, 'akibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamedakibath =D mohamed', 0, '', 1428157209),
(1235, 28, 'akibath =D mohamed akibath =D mohamed akibath =D mohamed akibath =D mohamed akibath =D mohamed akibath =D mohamed akibath =D mohamed akibath =D mohamed akibath =D mohamed akibath =D mohamed akibath =D mohamed akibath =D mohamed', 0, '', 1428157219),
(1236, 28, 'akibath =D mohamed', 0, '', 1428157238),
(1237, 28, 'q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q q =D q', 0, '', 1428157418),
(1238, 28, 'akibath =D mohamed', 0, '', 1428157621),
(1239, 28, 'akibath =D mohamed akibath', 0, '', 1428157633),
(1241, 28, 'hii', 0, '', 1435416783);

-- --------------------------------------------------------

--
-- Table structure for table `28_37`
--

CREATE TABLE IF NOT EXISTS `28_37` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `msg` varchar(3000) NOT NULL,
  `deleted` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `28_37`
--

INSERT INTO `28_37` (`id`, `senderID`, `msg`, `deleted`, `time`) VALUES
(3, 28, 'secondX msg to ben', '', 1415139276),
(5, 28, 'hi\nhi', '', 1426192656),
(6, 28, 'hello\n woelsmdxd dcwqndlknckwwhdw', '', 1426192700),
(7, 28, 'njdwdwq\nwdw', '', 1426192707),
(8, 28, 'mlk', '', 1427302682),
(20, 37, 'u', '', 1428155210),
(23, 28, 'ii', '', 1428155230),
(24, 28, 'a', '', 1428155243);

-- --------------------------------------------------------

--
-- Table structure for table `28_38`
--

CREATE TABLE IF NOT EXISTS `28_38` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `msg` varchar(3000) NOT NULL,
  `deleted` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=230 ;

--
-- Dumping data for table `28_38`
--

INSERT INTO `28_38` (`id`, `senderID`, `msg`, `deleted`, `time`) VALUES
(101, 38, 'as', '', 1427270835),
(102, 38, 'zx', '', 1427270855),
(103, 28, 'as', '', 1427270858),
(104, 38, 'po', '', 1427270861),
(105, 28, 'a', '', 1427270867),
(106, 28, 'a', '', 1427270868),
(107, 28, 'a', '', 1427270869),
(108, 28, 'a', '', 1427270869),
(109, 28, 'qwertyuioppljhgfdsvbnmjhgfd', '', 1427270874),
(110, 28, 'aaa', '', 1427270877),
(111, 38, 'a', '', 1427271224),
(112, 28, '1', '', 1427271230),
(113, 38, '12', '', 1427273873),
(114, 28, '123', '', 1427273881),
(115, 28, '12345', '', 1427273967),
(116, 28, '12345', '', 1427273970),
(117, 28, '234', '', 1427273970),
(118, 28, '45', '', 1427273971),
(119, 28, '65', '', 1427273972),
(120, 28, '33', '', 1427273972),
(121, 28, '123', '', 1427273974),
(122, 28, '12', '', 1427275135),
(123, 28, '11', '', 1427275154),
(124, 28, 'HEYYY........?', '', 1427275625),
(128, 38, '1', '', 1427276754),
(129, 38, '2', '', 1427276755),
(130, 38, '3', '', 1427276756),
(131, 38, '4', '', 1427276757),
(132, 28, 'mlml', '', 1427282301),
(133, 38, 'nmk', '', 1427282876),
(134, 28, 'q', '', 1427288988),
(135, 38, 'asdd', '', 1427289057),
(136, 38, '12', '', 1427289245),
(137, 28, '1', '', 1427289297),
(138, 28, '11', '', 1427289428),
(139, 38, 'qwe', '', 1427289443),
(140, 38, 'iop', '', 1427289456),
(141, 28, '???', '', 1427289472),
(142, 38, '&quot;&quot;&quot;', '', 1427289477),
(143, 38, ''';\\['';//.', '', 1427289484),
(144, 38, '&lt;input type=&quot;text&quot;&gt;', '', 1427289496),
(145, 28, '1', '', 1427289547),
(146, 28, '1', '', 1427289761),
(147, 38, '123', '', 1427299981),
(148, 28, '123', '', 1427300042),
(149, 38, '???', '', 1427300046),
(150, 28, '????', '', 1427300053),
(151, 38, 'asd', '', 1427300058),
(152, 38, '123', '', 1427300455),
(153, 38, 'po', '', 1427300513),
(154, 38, '123', '', 1427300716),
(155, 38, '1', '', 1427300789),
(163, 38, '???', '', 1427301140),
(164, 28, ':-(', '', 1427301160),
(165, 38, ':-)', '', 1427301163),
(166, 28, '111', '', 1427302660),
(167, 28, '1', '', 1427768526),
(168, 28, 'a', '', 1427769174),
(169, 28, 'a', '', 1427769187),
(170, 28, 'a', '', 1427769729),
(171, 28, '12', '', 1427769879),
(172, 28, '90', '', 1427769989),
(173, 28, 'vbn', '', 1427770058),
(174, 28, 'a', '', 1427770108),
(175, 28, 'a', '', 1427770141),
(176, 28, 'a', '', 1427770173),
(177, 28, 'a', '', 1427770216),
(178, 28, 'z', '', 1427770289),
(179, 28, 'aki', '', 1427770557),
(180, 28, 'pp', '', 1427770650),
(181, 28, 'a', '', 1427771149),
(182, 28, 'iko', '', 1427771473),
(183, 28, 'mooo', '', 1427771482),
(184, 28, 'check', '', 1427771788),
(185, 28, 'checkagain', '', 1427771838),
(187, 28, 'cc', '', 1427771871),
(188, 28, 'working', '', 1427771878),
(189, 28, '1', '', 1427772021),
(190, 28, 'fr', '', 1427772108),
(191, 28, 'e', '', 1427772121),
(192, 28, 'a', '', 1427772659),
(193, 28, '1', '', 1427772675),
(194, 28, 'chkk', '', 1427811637),
(195, 38, '1', '', 1427811902),
(196, 38, '2', '', 1427811902),
(197, 38, '3', '', 1427811903),
(198, 38, '5', '', 1427811903),
(199, 38, '6', '', 1427811904),
(200, 38, '7', '', 1427811905),
(201, 38, '8', '', 1427811905),
(202, 38, '1', '', 1427811940),
(203, 38, '2', '', 1427811940),
(204, 38, '3', '', 1427811941),
(205, 38, '4', '', 1427811942),
(206, 38, '5', '', 1427811943),
(207, 38, '6', '', 1427811944),
(208, 38, '7', '', 1427811944),
(209, 38, '8', '', 1427811945),
(210, 38, '9', '', 1427811946),
(211, 38, 'hi', '', 1427811955),
(212, 38, 'hi', '', 1427811956),
(213, 38, 'hi', '', 1427811956),
(214, 38, 'hi', '', 1427811957),
(215, 38, 'hi', '', 1427811959),
(216, 38, 'k', '', 1427812161),
(217, 28, 'jjjj', '', 1427812169),
(218, 38, 'n', '', 1427812216),
(219, 28, 'n', '', 1427812220),
(220, 28, 'kl', '', 1427812312),
(221, 38, 'c', '', 1427812362),
(223, 28, 'pp', '', 1427953994),
(224, 28, 'llllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll', '', 1428055686),
(225, 28, '...........', '', 1428056110),
(226, 28, '......', '', 1428056113),
(227, 28, '&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;', '', 1428056128),
(228, 28, '&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&lt;&gt;&gt;&lt;&gt;', '', 1428056140),
(229, 28, '&quot;', '', 1428085956);

-- --------------------------------------------------------

--
-- Table structure for table `28_39`
--

CREATE TABLE IF NOT EXISTS `28_39` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `msg` varchar(3000) NOT NULL,
  `deleted` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `28_39`
--

INSERT INTO `28_39` (`id`, `senderID`, `msg`, `deleted`, `time`) VALUES
(1, 28, 'fifth message', '', 1414162787),
(2, 28, 'test message to mulla', '', 1414175720),
(3, 28, 'thirdX msg to mulla', '', 1415139307),
(4, 28, 'test msg to mulla', '', 1418488072),
(5, 28, 'testing chatbox to mulla', '', 1420232949),
(6, 28, 'hiii', '', 1420234012),
(7, 28, 'aa', '', 1420234030),
(8, 28, 'bb', '', 1420234061),
(9, 28, 'cc', '', 1420234140),
(10, 28, 'a', '', 1420315275),
(11, 28, '&lt;input type=&quot;text&quot; value=&quot;hii&quot;&gt;', '', 1425130549),
(12, 28, '&lt;script&gt;alert(''aa'');&lt;/script&gt;', '', 1425130566),
(13, 28, 'alert(''aa'');', '', 1425130573),
(14, 28, '&lt;bold&gt;hii&lt;/bold&gt;', '', 1425130582),
(15, 28, '&lt;div class=&quot;profileBody&quot;&gt;\n		&lt;div class=&quot;profileCard&quot;&gt;\n	&lt;img src=&quot;http://localhost/TetramandoX/userFiles/0_defaultFiles/defaultCover.jpg&quot; class=&quot;coverPic&quot;&gt;\n	&lt;div class=&quot;coverPicLinear&quot;&gt;&lt;/div&gt;\n	&lt;img src=&quot;http://localhost/TetramandoX/userFiles/28_SVKFttsCuT6GZacDk46k0awnB4CgNnu7/1423855494_AHHjGJkxDcgoApiLweHyjqMfWbBvpXyj.jpg&quot; class=&quot;profilePic&quot;&gt;\n	&lt;div class=&quot;personName&quot;&gt;Akibath Blox Mohamed (akiD)&lt;/div&gt;\n&lt;/div&gt;\n&lt;div class=&quot;profileDetailsPaneDiv&quot;&gt;\n	&lt;div class=&quot;profileDetailsPane&quot;&gt;\n		&lt;ul&gt;\n		&lt;a&gt;&lt;li class=&quot;active&quot;&gt;Profile&lt;/li&gt;&lt;/a&gt;&lt;a href=&quot;http://localhost/TetramandoX/akiD/about&quot;&gt;&lt;li&gt;About&lt;/li&gt;&lt;/a&gt;&lt;a href=&quot;http://localhost/TetramandoX/akiD/photos&quot;&gt;&lt;li&gt;Photos&lt;/li&gt;&lt;/a&gt;&lt;a href=&quot;http://localhost/TetramandoX/akiD/friends&quot;&gt;&lt;li&gt;Friends&lt;/li&gt;&lt;/a&gt;&lt;a href=&quot;http://localhost/TetramandoX/akiD/likes&quot;&gt;&lt;li&gt;Likes&lt;/li&gt;&lt;/a&gt;	\n		&lt;/ul&gt;\n	&lt;/div&gt;\n&lt;/div&gt;', '', 1425130603),
(16, 28, '/28_SVKFttsCuT6GZacDk46k0awnB4CgNnu7/1423855494_AHHjGJkxDcgoApiLweHyjqMfWbBvpXyj.jpg&quot; class=&quot;profilePic&quot;&gt; &lt;div class=&quot;personName&quot;&gt;Akibath Blox Mohamed (akiD)&lt;/div&gt; &lt;/div&gt; &lt;div class=&quot;profileDetailsPaneDiv&quot;&gt; &lt;div class=&quot;profileDetailsPane&quot;&gt; &lt;ul&gt; &lt;a&gt;&lt;li class=&quot;active&quot;&gt;Profile&lt;/li&gt;&lt;/a&gt;&lt;a href=&quot;http://localhost/TetramandoX/akiD/about&quot;&gt;&lt;li&gt;About&lt;/li&gt;&lt;/a&gt;&lt;a href=&quot;http://localhost/TetramandoX/akiD/photos&quot;&gt;&lt;li&gt;Photos&lt;/li&gt;&lt;/a&gt;&lt;a href=&quot;http://localhost/TetramandoX/akiD/friends&quot;&gt;&lt;li&gt;Friends&lt;/li&gt;&lt;/a&gt;&lt;a href=&quot;http://localhost/TetramandoX/akiD/likes&quot;&gt;&lt;li&gt;Likes&lt;/li&gt;&lt;/a&gt; &lt;/ul&gt; &lt;/d', '', 1425212571),
(17, 28, 'hii', '', 1425215970),
(20, 28, '1', '', 1427291081),
(21, 28, 'zxd', '', 1427302676);

-- --------------------------------------------------------

--
-- Table structure for table `28_40`
--

CREATE TABLE IF NOT EXISTS `28_40` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `msg` varchar(3000) NOT NULL,
  `deleted` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `28_40`
--

INSERT INTO `28_40` (`id`, `senderID`, `msg`, `deleted`, `time`) VALUES
(1, 28, 'hii', '', 1425215950),
(2, 28, 'hello', '', 1425216006),
(4, 28, 'hey', '', 1425216010),
(5, 28, 'qwery', '', 1425216104),
(6, 28, ';vgwsegkesnf', '', 1425216105),
(7, 28, 'a,cbajhfvw', '', 1425216106),
(8, 28, 'ajkbcwjaf', '', 1425216107),
(9, 28, 'sajvcsajfbc', '', 1425216108),
(10, 28, 'javcjavf', '', 1425216108),
(11, 28, 'jsfjaf', '', 1425216109),
(13, 40, '12', '', 1427302529),
(14, 40, 'popo', '', 1427302696);

-- --------------------------------------------------------

--
-- Table structure for table `36_38`
--

CREATE TABLE IF NOT EXISTS `36_38` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `msg` varchar(3000) NOT NULL,
  `deleted` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `36_38`
--

INSERT INTO `36_38` (`id`, `senderID`, `msg`, `deleted`, `time`) VALUES
(1, 36, 'seventhX msg to padox', '', 1415252594),
(2, 38, 'check', '', 1420989649),
(3, 36, 'alb to par', '', 1420989711),
(4, 38, 'par to alb', '', 1420989724),
(6, 36, 'hello world', '', 1424643209);

-- --------------------------------------------------------

--
-- Table structure for table `36_40`
--

CREATE TABLE IF NOT EXISTS `36_40` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(11) NOT NULL,
  `msg` varchar(3000) NOT NULL,
  `deleted` varchar(1) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `36_40`
--

INSERT INTO `36_40` (`id`, `senderID`, `msg`, `deleted`, `time`) VALUES
(1, 36, 'sixth msg to herculus', '', 1415252559);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
