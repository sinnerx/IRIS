-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 26, 2014 at 12:39 AM
-- Server version: 5.5.36-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `p1mgaia_iris`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `activityID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `activityType` int(11) DEFAULT NULL,
  `activityName` varchar(100) DEFAULT NULL,
  `activityAddress` text,
  `activityParticipation` int(11) DEFAULT NULL,
  `activityStartDate` date DEFAULT NULL,
  `activityEndDate` date DEFAULT NULL,
  `activityApprovedStatus` int(11) DEFAULT NULL,
  `activityCreatedUser` int(11) DEFAULT NULL,
  `activityCreatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`activityID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_article`
--

CREATE TABLE IF NOT EXISTS `activity_article` (
  `activityArticleID` int(11) NOT NULL AUTO_INCREMENT,
  `articleID` int(11) DEFAULT NULL,
  `activityID` int(11) DEFAULT NULL,
  PRIMARY KEY (`activityArticleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_budget`
--

CREATE TABLE IF NOT EXISTS `activity_budget` (
  `activityBudgedID` int(11) NOT NULL AUTO_INCREMENT,
  `activityID` int(11) DEFAULT NULL,
  PRIMARY KEY (`activityBudgedID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_comment`
--

CREATE TABLE IF NOT EXISTS `activity_comment` (
  `activityCommentID` int(11) NOT NULL AUTO_INCREMENT,
  `activityID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `activityCommentValue` varchar(100) DEFAULT NULL,
  `activityCreatedDate` datetime DEFAULT NULL,
  `activityCreatedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`activityCommentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_date`
--

CREATE TABLE IF NOT EXISTS `activity_date` (
  `activityDateID` int(11) NOT NULL AUTO_INCREMENT,
  `activityID` int(11) DEFAULT NULL,
  `activityDateValue` date DEFAULT NULL,
  PRIMARY KEY (`activityDateID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_time`
--

CREATE TABLE IF NOT EXISTS `activity_time` (
  `activityTimeID` int(11) NOT NULL AUTO_INCREMENT,
  `activityDateID` int(11) DEFAULT NULL,
  `activityTimeStart` time DEFAULT NULL,
  `activityTimeEnd` time DEFAULT NULL,
  PRIMARY KEY (`activityTimeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_user`
--

CREATE TABLE IF NOT EXISTS `activity_user` (
  `activityUserID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `activityUserValue` int(11) DEFAULT NULL,
  `activityUserCreatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`activityUserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

CREATE TABLE IF NOT EXISTS `album` (
  `albumID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `articleID` int(11) DEFAULT NULL,
  `albumName` varchar(100) DEFAULT NULL,
  `albumCreatedDate` datetime DEFAULT NULL,
  `albumCreatedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`albumID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE IF NOT EXISTS `announcement` (
  `announcementID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `announcementText` text,
  `announcementCreatedUser` int(11) DEFAULT NULL,
  `announcementCreatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`announcementID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `articleID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `articleApprovedStatus` int(11) DEFAULT NULL,
  `articleSlug` varchar(100) DEFAULT NULL,
  `articleName` varchar(100) DEFAULT NULL,
  `articleText` text,
  `articleTagID` int(11) DEFAULT NULL,
  `articlePublishedDate` datetime DEFAULT NULL,
  `articleStatus` int(11) DEFAULT NULL,
  `articleCreatedUser` int(11) DEFAULT NULL,
  `articleCreatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`articleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `article_photo`
--

CREATE TABLE IF NOT EXISTS `article_photo` (
  `photoArticleID` int(11) NOT NULL AUTO_INCREMENT,
  `photoID` int(11) DEFAULT NULL,
  `articleID` int(11) DEFAULT NULL,
  PRIMARY KEY (`photoArticleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `article_tag`
--

CREATE TABLE IF NOT EXISTS `article_tag` (
  `articleTagID` int(11) NOT NULL AUTO_INCREMENT,
  `articleTagType` int(11) DEFAULT NULL,
  `articleTagName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`articleTagID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cluster`
--

CREATE TABLE IF NOT EXISTS `cluster` (
  `clusterID` int(11) NOT NULL AUTO_INCREMENT,
  `clusterName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clusterCreatedDate` datetime DEFAULT NULL,
  `clusterCreatedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`clusterID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cluster`
--

INSERT INTO `cluster` (`clusterID`, `clusterName`, `clusterCreatedDate`, `clusterCreatedUser`) VALUES
(1, 'Sabah Cluster A', NULL, NULL),
(2, 'Sabah Cluster B', NULL, NULL),
(3, 'Sabah Cluster C', NULL, NULL),
(4, 'Sarawak', NULL, NULL),
(5, 'Semenanjung Cluster', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cluster_lead`
--

CREATE TABLE IF NOT EXISTS `cluster_lead` (
  `clusterLeadID` int(11) NOT NULL AUTO_INCREMENT,
  `clusterID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `clusterLeadStatus` int(11) DEFAULT NULL,
  `clusterLeadCreatedDate` datetime DEFAULT NULL,
  `clusterLeadCreatedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`clusterLeadID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cluster_lead`
--

INSERT INTO `cluster_lead` (`clusterLeadID`, `clusterID`, `userID`, `clusterLeadStatus`, `clusterLeadCreatedDate`, `clusterLeadCreatedUser`) VALUES
(1, 5, 168, 1, '2014-04-24 12:00:33', 1),
(2, 1, 169, 1, '2014-04-24 12:01:24', 1),
(3, 2, 170, 1, '2014-04-24 12:02:09', 1),
(4, 3, 171, 1, '2014-04-24 12:03:13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cluster_site`
--

CREATE TABLE IF NOT EXISTS `cluster_site` (
  `clusterSiteID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `clusterID` int(11) DEFAULT NULL,
  PRIMARY KEY (`clusterSiteID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=85 ;

--
-- Dumping data for table `cluster_site`
--

INSERT INTO `cluster_site` (`clusterSiteID`, `siteID`, `clusterID`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 1),
(8, 8, 1),
(9, 9, 1),
(10, 10, 1),
(11, 11, 1),
(12, 12, 1),
(13, 13, 1),
(14, 14, 1),
(15, 15, 1),
(16, 16, 1),
(17, 17, 1),
(18, 18, 1),
(19, 19, 1),
(20, 20, 2),
(21, 21, 2),
(22, 22, 2),
(23, 23, 2),
(24, 24, 2),
(25, 25, 2),
(26, 26, 2),
(27, 27, 2),
(28, 28, 2),
(29, 29, 2),
(30, 30, 2),
(31, 31, 2),
(32, 32, 2),
(33, 33, 2),
(34, 34, 2),
(35, 35, 2),
(36, 36, 2),
(37, 37, 2),
(38, 38, 3),
(39, 39, 3),
(40, 40, 3),
(41, 41, 3),
(42, 42, 3),
(43, 43, 3),
(44, 44, 3),
(45, 45, 3),
(46, 46, 3),
(47, 47, 3),
(48, 48, 3),
(49, 49, 3),
(50, 50, 3),
(51, 51, 3),
(52, 52, 3),
(53, 53, 3),
(54, 54, 3),
(55, 55, 3),
(56, 56, 3),
(57, 57, 3),
(58, 58, 4),
(59, 59, 4),
(60, 60, 4),
(61, 61, 5),
(62, 62, 5),
(63, 63, 5),
(64, 64, 5),
(65, 65, 5),
(66, 66, 5),
(67, 67, 5),
(68, 68, 5),
(69, 69, 5),
(70, 70, 5),
(71, 71, 5),
(72, 72, 5),
(73, 73, 5),
(74, 74, 5),
(75, 75, 5),
(76, 76, 5),
(77, 77, 5),
(78, 78, 5),
(79, 79, 5),
(80, 80, 5),
(81, 81, 5),
(82, 82, 5),
(83, 83, 5),
(84, 84, 5);

-- --------------------------------------------------------

--
-- Table structure for table `component`
--

CREATE TABLE IF NOT EXISTS `component` (
  `componentNo` int(11) NOT NULL AUTO_INCREMENT,
  `componentName` varchar(100) DEFAULT NULL,
  `componentRoute` varchar(100) DEFAULT NULL,
  `componentStatus` int(11) DEFAULT NULL,
  PRIMARY KEY (`componentNo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100 ;

--
-- Dumping data for table `component`
--

INSERT INTO `component` (`componentNo`, `componentName`, `componentRoute`, `componentStatus`) VALUES
(1, 'page', NULL, 1),
(2, 'Utama', '', 1),
(3, 'Aktiviti', 'activity', 0),
(4, 'Ruangan Ahli', 'members', 0),
(5, 'Hubungi Kami', 'hubungi-kami', 1),
(99, 'custom', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `contactID` int(11) NOT NULL AUTO_INCREMENT,
  `contactName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contactEmail` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contactPhoneNo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`contactID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contactID`, `contactName`, `contactEmail`, `contactPhoneNo`) VALUES
(1, 'test', 'test@nusuara.com', '0122356000'),
(2, 'sss', 'ss', 'ss'),
(3, 'Ahmad Rahimie', 'newrehmi@gmail.com', '012-6966121'),
(4, 'ssds', 'sdsds', 'dsd'),
(5, 'Ahmad Rahimie', 'test@gmail.com', '012-6966121');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `eventID` int(11) NOT NULL AUTO_INCREMENT,
  `activityID` int(11) DEFAULT NULL,
  `eventName` varchar(100) DEFAULT NULL,
  `eventType` int(11) DEFAULT NULL,
  `eventRefID` int(11) DEFAULT NULL,
  `eventCreatedUser` int(11) DEFAULT NULL,
  `eventCreatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`eventID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `menuID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `menuType` int(11) DEFAULT NULL,
  `menuName` varchar(100) DEFAULT NULL,
  `menuNo` int(11) DEFAULT NULL,
  `componentNo` int(11) DEFAULT NULL,
  `menuRefID` int(11) DEFAULT NULL,
  `menuRoute` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`menuID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=421 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menuID`, `siteID`, `menuType`, `menuName`, `menuNo`, `componentNo`, `menuRefID`, `menuRoute`) VALUES
(1, 1, 1, 'Tentang Kami', 2, 1, 1, NULL),
(2, 1, 1, 'Utama', 1, 2, 0, NULL),
(3, 1, 1, 'Activiti', 3, 3, 0, NULL),
(4, 1, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(5, 1, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(6, 2, 1, 'Tentang Kami', 2, 1, 4, NULL),
(7, 2, 1, 'Utama', 1, 2, 0, NULL),
(8, 2, 1, 'Activiti', 3, 3, 0, NULL),
(9, 2, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(10, 2, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(11, 3, 1, 'Tentang Kami', 2, 1, 7, NULL),
(12, 3, 1, 'Utama', 1, 2, 0, NULL),
(13, 3, 1, 'Activiti', 3, 3, 0, NULL),
(14, 3, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(15, 3, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(16, 4, 1, 'Tentang Kami', 2, 1, 10, NULL),
(17, 4, 1, 'Utama', 1, 2, 0, NULL),
(18, 4, 1, 'Activiti', 3, 3, 0, NULL),
(19, 4, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(20, 4, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(21, 5, 1, 'Tentang Kami', 2, 1, 13, NULL),
(22, 5, 1, 'Utama', 1, 2, 0, NULL),
(23, 5, 1, 'Activiti', 3, 3, 0, NULL),
(24, 5, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(25, 5, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(26, 6, 1, 'Tentang Kami', 2, 1, 16, NULL),
(27, 6, 1, 'Utama', 1, 2, 0, NULL),
(28, 6, 1, 'Activiti', 3, 3, 0, NULL),
(29, 6, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(30, 6, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(31, 7, 1, 'Tentang Kami', 2, 1, 19, NULL),
(32, 7, 1, 'Utama', 1, 2, 0, NULL),
(33, 7, 1, 'Activiti', 3, 3, 0, NULL),
(34, 7, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(35, 7, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(36, 8, 1, 'Tentang Kami', 2, 1, 22, NULL),
(37, 8, 1, 'Utama', 1, 2, 0, NULL),
(38, 8, 1, 'Activiti', 3, 3, 0, NULL),
(39, 8, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(40, 8, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(41, 9, 1, 'Tentang Kami', 2, 1, 25, NULL),
(42, 9, 1, 'Utama', 1, 2, 0, NULL),
(43, 9, 1, 'Activiti', 3, 3, 0, NULL),
(44, 9, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(45, 9, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(46, 10, 1, 'Tentang Kami', 2, 1, 28, NULL),
(47, 10, 1, 'Utama', 1, 2, 0, NULL),
(48, 10, 1, 'Activiti', 3, 3, 0, NULL),
(49, 10, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(50, 10, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(51, 11, 1, 'Tentang Kami', 2, 1, 31, NULL),
(52, 11, 1, 'Utama', 1, 2, 0, NULL),
(53, 11, 1, 'Activiti', 3, 3, 0, NULL),
(54, 11, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(55, 11, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(56, 12, 1, 'Tentang Kami', 2, 1, 34, NULL),
(57, 12, 1, 'Utama', 1, 2, 0, NULL),
(58, 12, 1, 'Activiti', 3, 3, 0, NULL),
(59, 12, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(60, 12, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(61, 13, 1, 'Tentang Kami', 2, 1, 37, NULL),
(62, 13, 1, 'Utama', 1, 2, 0, NULL),
(63, 13, 1, 'Activiti', 3, 3, 0, NULL),
(64, 13, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(65, 13, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(66, 14, 1, 'Tentang Kami', 2, 1, 40, NULL),
(67, 14, 1, 'Utama', 1, 2, 0, NULL),
(68, 14, 1, 'Activiti', 3, 3, 0, NULL),
(69, 14, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(70, 14, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(71, 15, 1, 'Tentang Kami', 2, 1, 43, NULL),
(72, 15, 1, 'Utama', 1, 2, 0, NULL),
(73, 15, 1, 'Activiti', 3, 3, 0, NULL),
(74, 15, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(75, 15, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(76, 16, 1, 'Tentang Kami', 2, 1, 46, NULL),
(77, 16, 1, 'Utama', 1, 2, 0, NULL),
(78, 16, 1, 'Activiti', 3, 3, 0, NULL),
(79, 16, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(80, 16, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(81, 17, 1, 'Tentang Kami', 2, 1, 49, NULL),
(82, 17, 1, 'Utama', 1, 2, 0, NULL),
(83, 17, 1, 'Activiti', 3, 3, 0, NULL),
(84, 17, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(85, 17, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(86, 18, 1, 'Tentang Kami', 2, 1, 52, NULL),
(87, 18, 1, 'Utama', 1, 2, 0, NULL),
(88, 18, 1, 'Activiti', 3, 3, 0, NULL),
(89, 18, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(90, 18, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(91, 19, 1, 'Tentang Kami', 2, 1, 55, NULL),
(92, 19, 1, 'Utama', 1, 2, 0, NULL),
(93, 19, 1, 'Activiti', 3, 3, 0, NULL),
(94, 19, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(95, 19, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(96, 20, 1, 'Tentang Kami', 2, 1, 58, NULL),
(97, 20, 1, 'Utama', 1, 2, 0, NULL),
(98, 20, 1, 'Activiti', 3, 3, 0, NULL),
(99, 20, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(100, 20, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(101, 21, 1, 'Tentang Kami', 2, 1, 61, NULL),
(102, 21, 1, 'Utama', 1, 2, 0, NULL),
(103, 21, 1, 'Activiti', 3, 3, 0, NULL),
(104, 21, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(105, 21, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(106, 22, 1, 'Tentang Kami', 2, 1, 64, NULL),
(107, 22, 1, 'Utama', 1, 2, 0, NULL),
(108, 22, 1, 'Activiti', 3, 3, 0, NULL),
(109, 22, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(110, 22, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(111, 23, 1, 'Tentang Kami', 2, 1, 67, NULL),
(112, 23, 1, 'Utama', 1, 2, 0, NULL),
(113, 23, 1, 'Activiti', 3, 3, 0, NULL),
(114, 23, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(115, 23, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(116, 24, 1, 'Tentang Kami', 2, 1, 70, NULL),
(117, 24, 1, 'Utama', 1, 2, 0, NULL),
(118, 24, 1, 'Activiti', 3, 3, 0, NULL),
(119, 24, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(120, 24, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(121, 25, 1, 'Tentang Kami', 2, 1, 73, NULL),
(122, 25, 1, 'Utama', 1, 2, 0, NULL),
(123, 25, 1, 'Activiti', 3, 3, 0, NULL),
(124, 25, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(125, 25, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(126, 26, 1, 'Tentang Kami', 2, 1, 76, NULL),
(127, 26, 1, 'Utama', 1, 2, 0, NULL),
(128, 26, 1, 'Activiti', 3, 3, 0, NULL),
(129, 26, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(130, 26, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(131, 27, 1, 'Tentang Kami', 2, 1, 79, NULL),
(132, 27, 1, 'Utama', 1, 2, 0, NULL),
(133, 27, 1, 'Activiti', 3, 3, 0, NULL),
(134, 27, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(135, 27, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(136, 28, 1, 'Tentang Kami', 2, 1, 82, NULL),
(137, 28, 1, 'Utama', 1, 2, 0, NULL),
(138, 28, 1, 'Activiti', 3, 3, 0, NULL),
(139, 28, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(140, 28, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(141, 29, 1, 'Tentang Kami', 2, 1, 85, NULL),
(142, 29, 1, 'Utama', 1, 2, 0, NULL),
(143, 29, 1, 'Activiti', 3, 3, 0, NULL),
(144, 29, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(145, 29, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(146, 30, 1, 'Tentang Kami', 2, 1, 88, NULL),
(147, 30, 1, 'Utama', 1, 2, 0, NULL),
(148, 30, 1, 'Activiti', 3, 3, 0, NULL),
(149, 30, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(150, 30, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(151, 31, 1, 'Tentang Kami', 2, 1, 91, NULL),
(152, 31, 1, 'Utama', 1, 2, 0, NULL),
(153, 31, 1, 'Activiti', 3, 3, 0, NULL),
(154, 31, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(155, 31, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(156, 32, 1, 'Tentang Kami', 2, 1, 94, NULL),
(157, 32, 1, 'Utama', 1, 2, 0, NULL),
(158, 32, 1, 'Activiti', 3, 3, 0, NULL),
(159, 32, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(160, 32, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(161, 33, 1, 'Tentang Kami', 2, 1, 97, NULL),
(162, 33, 1, 'Utama', 1, 2, 0, NULL),
(163, 33, 1, 'Activiti', 3, 3, 0, NULL),
(164, 33, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(165, 33, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(166, 34, 1, 'Tentang Kami', 2, 1, 100, NULL),
(167, 34, 1, 'Utama', 1, 2, 0, NULL),
(168, 34, 1, 'Activiti', 3, 3, 0, NULL),
(169, 34, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(170, 34, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(171, 35, 1, 'Tentang Kami', 2, 1, 103, NULL),
(172, 35, 1, 'Utama', 1, 2, 0, NULL),
(173, 35, 1, 'Activiti', 3, 3, 0, NULL),
(174, 35, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(175, 35, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(176, 36, 1, 'Tentang Kami', 2, 1, 106, NULL),
(177, 36, 1, 'Utama', 1, 2, 0, NULL),
(178, 36, 1, 'Activiti', 3, 3, 0, NULL),
(179, 36, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(180, 36, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(181, 37, 1, 'Tentang Kami', 2, 1, 109, NULL),
(182, 37, 1, 'Utama', 1, 2, 0, NULL),
(183, 37, 1, 'Activiti', 3, 3, 0, NULL),
(184, 37, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(185, 37, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(186, 38, 1, 'Tentang Kami', 2, 1, 112, NULL),
(187, 38, 1, 'Utama', 1, 2, 0, NULL),
(188, 38, 1, 'Activiti', 3, 3, 0, NULL),
(189, 38, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(190, 38, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(191, 39, 1, 'Tentang Kami', 2, 1, 115, NULL),
(192, 39, 1, 'Utama', 1, 2, 0, NULL),
(193, 39, 1, 'Activiti', 3, 3, 0, NULL),
(194, 39, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(195, 39, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(196, 40, 1, 'Tentang Kami', 2, 1, 118, NULL),
(197, 40, 1, 'Utama', 1, 2, 0, NULL),
(198, 40, 1, 'Activiti', 3, 3, 0, NULL),
(199, 40, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(200, 40, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(201, 41, 1, 'Tentang Kami', 2, 1, 121, NULL),
(202, 41, 1, 'Utama', 1, 2, 0, NULL),
(203, 41, 1, 'Activiti', 3, 3, 0, NULL),
(204, 41, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(205, 41, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(206, 42, 1, 'Tentang Kami', 2, 1, 124, NULL),
(207, 42, 1, 'Utama', 1, 2, 0, NULL),
(208, 42, 1, 'Activiti', 3, 3, 0, NULL),
(209, 42, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(210, 42, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(211, 43, 1, 'Tentang Kami', 2, 1, 127, NULL),
(212, 43, 1, 'Utama', 1, 2, 0, NULL),
(213, 43, 1, 'Activiti', 3, 3, 0, NULL),
(214, 43, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(215, 43, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(216, 44, 1, 'Tentang Kami', 2, 1, 130, NULL),
(217, 44, 1, 'Utama', 1, 2, 0, NULL),
(218, 44, 1, 'Activiti', 3, 3, 0, NULL),
(219, 44, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(220, 44, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(221, 45, 1, 'Tentang Kami', 2, 1, 133, NULL),
(222, 45, 1, 'Utama', 1, 2, 0, NULL),
(223, 45, 1, 'Activiti', 3, 3, 0, NULL),
(224, 45, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(225, 45, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(226, 46, 1, 'Tentang Kami', 2, 1, 136, NULL),
(227, 46, 1, 'Utama', 1, 2, 0, NULL),
(228, 46, 1, 'Activiti', 3, 3, 0, NULL),
(229, 46, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(230, 46, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(231, 47, 1, 'Tentang Kami', 2, 1, 139, NULL),
(232, 47, 1, 'Utama', 1, 2, 0, NULL),
(233, 47, 1, 'Activiti', 3, 3, 0, NULL),
(234, 47, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(235, 47, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(236, 48, 1, 'Tentang Kami', 2, 1, 142, NULL),
(237, 48, 1, 'Utama', 1, 2, 0, NULL),
(238, 48, 1, 'Activiti', 3, 3, 0, NULL),
(239, 48, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(240, 48, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(241, 49, 1, 'Tentang Kami', 2, 1, 145, NULL),
(242, 49, 1, 'Utama', 1, 2, 0, NULL),
(243, 49, 1, 'Activiti', 3, 3, 0, NULL),
(244, 49, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(245, 49, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(246, 50, 1, 'Tentang Kami', 2, 1, 148, NULL),
(247, 50, 1, 'Utama', 1, 2, 0, NULL),
(248, 50, 1, 'Activiti', 3, 3, 0, NULL),
(249, 50, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(250, 50, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(251, 51, 1, 'Tentang Kami', 2, 1, 151, NULL),
(252, 51, 1, 'Utama', 1, 2, 0, NULL),
(253, 51, 1, 'Activiti', 3, 3, 0, NULL),
(254, 51, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(255, 51, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(256, 52, 1, 'Tentang Kami', 2, 1, 154, NULL),
(257, 52, 1, 'Utama', 1, 2, 0, NULL),
(258, 52, 1, 'Activiti', 3, 3, 0, NULL),
(259, 52, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(260, 52, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(261, 53, 1, 'Tentang Kami', 2, 1, 157, NULL),
(262, 53, 1, 'Utama', 1, 2, 0, NULL),
(263, 53, 1, 'Activiti', 3, 3, 0, NULL),
(264, 53, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(265, 53, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(266, 54, 1, 'Tentang Kami', 2, 1, 160, NULL),
(267, 54, 1, 'Utama', 1, 2, 0, NULL),
(268, 54, 1, 'Activiti', 3, 3, 0, NULL),
(269, 54, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(270, 54, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(271, 55, 1, 'Tentang Kami', 2, 1, 163, NULL),
(272, 55, 1, 'Utama', 1, 2, 0, NULL),
(273, 55, 1, 'Activiti', 3, 3, 0, NULL),
(274, 55, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(275, 55, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(276, 56, 1, 'Tentang Kami', 2, 1, 166, NULL),
(277, 56, 1, 'Utama', 1, 2, 0, NULL),
(278, 56, 1, 'Activiti', 3, 3, 0, NULL),
(279, 56, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(280, 56, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(281, 57, 1, 'Tentang Kami', 2, 1, 169, NULL),
(282, 57, 1, 'Utama', 1, 2, 0, NULL),
(283, 57, 1, 'Activiti', 3, 3, 0, NULL),
(284, 57, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(285, 57, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(286, 58, 1, 'Tentang Kami', 2, 1, 172, NULL),
(287, 58, 1, 'Utama', 1, 2, 0, NULL),
(288, 58, 1, 'Activiti', 3, 3, 0, NULL),
(289, 58, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(290, 58, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(291, 59, 1, 'Tentang Kami', 2, 1, 175, NULL),
(292, 59, 1, 'Utama', 1, 2, 0, NULL),
(293, 59, 1, 'Activiti', 3, 3, 0, NULL),
(294, 59, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(295, 59, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(296, 60, 1, 'Tentang Kami', 2, 1, 178, NULL),
(297, 60, 1, 'Utama', 1, 2, 0, NULL),
(298, 60, 1, 'Activiti', 3, 3, 0, NULL),
(299, 60, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(300, 60, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(301, 61, 1, 'Tentang Kami', 2, 1, 181, NULL),
(302, 61, 1, 'Utama', 1, 2, 0, NULL),
(303, 61, 1, 'Activiti', 3, 3, 0, NULL),
(304, 61, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(305, 61, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(306, 62, 1, 'Tentang Kami', 2, 1, 184, NULL),
(307, 62, 1, 'Utama', 1, 2, 0, NULL),
(308, 62, 1, 'Activiti', 3, 3, 0, NULL),
(309, 62, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(310, 62, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(311, 63, 1, 'Tentang Kami', 2, 1, 187, NULL),
(312, 63, 1, 'Utama', 1, 2, 0, NULL),
(313, 63, 1, 'Activiti', 3, 3, 0, NULL),
(314, 63, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(315, 63, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(316, 64, 1, 'Tentang Kami', 2, 1, 190, NULL),
(317, 64, 1, 'Utama', 1, 2, 0, NULL),
(318, 64, 1, 'Activiti', 3, 3, 0, NULL),
(319, 64, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(320, 64, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(321, 65, 1, 'Tentang Kami', 2, 1, 193, NULL),
(322, 65, 1, 'Utama', 1, 2, 0, NULL),
(323, 65, 1, 'Activiti', 3, 3, 0, NULL),
(324, 65, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(325, 65, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(326, 66, 1, 'Tentang Kami', 2, 1, 196, NULL),
(327, 66, 1, 'Utama', 1, 2, 0, NULL),
(328, 66, 1, 'Activiti', 3, 3, 0, NULL),
(329, 66, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(330, 66, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(331, 67, 1, 'Tentang Kami', 2, 1, 199, NULL),
(332, 67, 1, 'Utama', 1, 2, 0, NULL),
(333, 67, 1, 'Activiti', 3, 3, 0, NULL),
(334, 67, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(335, 67, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(336, 68, 1, 'Tentang Kami', 2, 1, 202, NULL),
(337, 68, 1, 'Utama', 1, 2, 0, NULL),
(338, 68, 1, 'Activiti', 3, 3, 0, NULL),
(339, 68, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(340, 68, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(341, 69, 1, 'Tentang Kami', 2, 1, 205, NULL),
(342, 69, 1, 'Utama', 1, 2, 0, NULL),
(343, 69, 1, 'Activiti', 3, 3, 0, NULL),
(344, 69, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(345, 69, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(346, 70, 1, 'Tentang Kami', 2, 1, 208, NULL),
(347, 70, 1, 'Utama', 1, 2, 0, NULL),
(348, 70, 1, 'Activiti', 3, 3, 0, NULL),
(349, 70, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(350, 70, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(351, 71, 1, 'Tentang Kami', 2, 1, 211, NULL),
(352, 71, 1, 'Utama', 1, 2, 0, NULL),
(353, 71, 1, 'Activiti', 3, 3, 0, NULL),
(354, 71, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(355, 71, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(356, 72, 1, 'Tentang Kami', 2, 1, 214, NULL),
(357, 72, 1, 'Utama', 1, 2, 0, NULL),
(358, 72, 1, 'Activiti', 3, 3, 0, NULL),
(359, 72, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(360, 72, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(361, 73, 1, 'Tentang Kami', 2, 1, 217, NULL),
(362, 73, 1, 'Utama', 1, 2, 0, NULL),
(363, 73, 1, 'Activiti', 3, 3, 0, NULL),
(364, 73, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(365, 73, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(366, 74, 1, 'Tentang Kami', 2, 1, 220, NULL),
(367, 74, 1, 'Utama', 1, 2, 0, NULL),
(368, 74, 1, 'Activiti', 3, 3, 0, NULL),
(369, 74, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(370, 74, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(371, 75, 1, 'Tentang Kami', 2, 1, 223, NULL),
(372, 75, 1, 'Utama', 1, 2, 0, NULL),
(373, 75, 1, 'Activiti', 3, 3, 0, NULL),
(374, 75, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(375, 75, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(376, 76, 1, 'Tentang Kami', 2, 1, 226, NULL),
(377, 76, 1, 'Utama', 1, 2, 0, NULL),
(378, 76, 1, 'Activiti', 3, 3, 0, NULL),
(379, 76, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(380, 76, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(381, 77, 1, 'Tentang Kami', 2, 1, 229, NULL),
(382, 77, 1, 'Utama', 1, 2, 0, NULL),
(383, 77, 1, 'Activiti', 3, 3, 0, NULL),
(384, 77, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(385, 77, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(386, 78, 1, 'Tentang Kami', 2, 1, 232, NULL),
(387, 78, 1, 'Utama', 1, 2, 0, NULL),
(388, 78, 1, 'Activiti', 3, 3, 0, NULL),
(389, 78, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(390, 78, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(391, 79, 1, 'Tentang Kami', 2, 1, 235, NULL),
(392, 79, 1, 'Utama', 1, 2, 0, NULL),
(393, 79, 1, 'Activiti', 3, 3, 0, NULL),
(394, 79, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(395, 79, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(396, 80, 1, 'Tentang Kami', 2, 1, 238, NULL),
(397, 80, 1, 'Utama', 1, 2, 0, NULL),
(398, 80, 1, 'Activiti', 3, 3, 0, NULL),
(399, 80, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(400, 80, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(401, 81, 1, 'Tentang Kami', 2, 1, 241, NULL),
(402, 81, 1, 'Utama', 1, 2, 0, NULL),
(403, 81, 1, 'Activiti', 3, 3, 0, NULL),
(404, 81, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(405, 81, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(406, 82, 1, 'Tentang Kami', 2, 1, 244, NULL),
(407, 82, 1, 'Utama', 1, 2, 0, NULL),
(408, 82, 1, 'Activiti', 3, 3, 0, NULL),
(409, 82, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(410, 82, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(411, 83, 1, 'Tentang Kami', 2, 1, 247, NULL),
(412, 83, 1, 'Utama', 1, 2, 0, NULL),
(413, 83, 1, 'Activiti', 3, 3, 0, NULL),
(414, 83, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(415, 83, 1, 'Hubungi Kami', 5, 5, 0, NULL),
(416, 84, 1, 'Tentang Kami', 2, 1, 250, NULL),
(417, 84, 1, 'Utama', 1, 2, 0, NULL),
(418, 84, 1, 'Activiti', 3, 3, 0, NULL),
(419, 84, 1, 'Ruangan Ahli', 4, 4, 0, NULL),
(420, 84, 1, 'Hubungi Kami', 5, 5, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `messageID` int(11) NOT NULL AUTO_INCREMENT,
  `messageSubject` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `messageContent` text COLLATE utf8_unicode_ci,
  `messageCreatedDate` datetime DEFAULT NULL,
  `messageCreatedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`messageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`messageID`, `messageSubject`, `messageContent`, `messageCreatedDate`, `messageCreatedUser`) VALUES
(1, 'macamna nak daftar? ye?', 'ok day test ok', '2014-04-18 02:59:58', 23),
(2, 'Test Message', 'This is a test system for the new website', '2014-04-24 12:06:18', 0),
(3, 'dd', 'dd', '2014-04-24 13:26:05', 1),
(4, 'dd', 'dd', '2014-04-24 13:26:31', 1),
(5, 'Ini adalah test', 'ini semua dunia', '2014-04-24 22:13:34', 1),
(6, 'ds', 'ds', '2014-04-25 07:38:20', 0),
(7, 'macamna nak daftar?', '213', '2014-04-25 07:57:58', 1),
(8, 'Daftar site', 'OKi Doki', '2014-04-25 13:07:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `pageID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `pageApprovedStatus` int(11) DEFAULT NULL,
  `pageName` varchar(100) DEFAULT NULL,
  `pageSlug` varchar(100) DEFAULT NULL,
  `pageText` text,
  `pageType` int(11) DEFAULT NULL,
  `pageCreatedUser` int(11) DEFAULT NULL,
  `pageCreatedDate` datetime DEFAULT NULL,
  `pageUpdatedUser` int(11) DEFAULT NULL,
  `pageUpdatedDate` datetime DEFAULT NULL,
  `pageDefaultType` int(11) DEFAULT NULL,
  PRIMARY KEY (`pageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=253 ;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`pageID`, `siteID`, `pageApprovedStatus`, `pageName`, `pageSlug`, `pageText`, `pageType`, `pageCreatedUser`, `pageCreatedDate`, `pageUpdatedUser`, `pageUpdatedDate`, `pageDefaultType`) VALUES
(1, 1, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(2, 1, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(3, 1, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(4, 2, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(5, 2, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(6, 2, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(7, 3, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(8, 3, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(9, 3, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(10, 4, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(11, 4, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(12, 4, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(13, 5, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(14, 5, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(15, 5, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(16, 6, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(17, 6, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(18, 6, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(19, 7, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(20, 7, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(21, 7, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(22, 8, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(23, 8, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(24, 8, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(25, 9, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(26, 9, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(27, 9, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(28, 10, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(29, 10, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(30, 10, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(31, 11, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(32, 11, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(33, 11, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(34, 12, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(35, 12, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(36, 12, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(37, 13, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(38, 13, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(39, 13, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(40, 14, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(41, 14, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(42, 14, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(43, 15, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(44, 15, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(45, 15, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(46, 16, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(47, 16, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(48, 16, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(49, 17, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(50, 17, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(51, 17, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(52, 18, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(53, 18, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(54, 18, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(55, 19, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 1),
(56, 19, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 2),
(57, 19, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:09', NULL, NULL, 3),
(58, 20, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(59, 20, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(60, 20, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(61, 21, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(62, 21, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(63, 21, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(64, 22, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(65, 22, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(66, 22, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(67, 23, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(68, 23, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(69, 23, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(70, 24, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(71, 24, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(72, 24, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(73, 25, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(74, 25, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(75, 25, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(76, 26, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(77, 26, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(78, 26, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(79, 27, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(80, 27, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(81, 27, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(82, 28, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(83, 28, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(84, 28, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(85, 29, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(86, 29, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(87, 29, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(88, 30, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(89, 30, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(90, 30, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(91, 31, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(92, 31, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(93, 31, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(94, 32, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(95, 32, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(96, 32, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(97, 33, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(98, 33, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(99, 33, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(100, 34, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(101, 34, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(102, 34, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(103, 35, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(104, 35, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(105, 35, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(106, 36, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(107, 36, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(108, 36, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(109, 37, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 1),
(110, 37, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 2),
(111, 37, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:13', NULL, NULL, 3),
(112, 38, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(113, 38, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(114, 38, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(115, 39, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(116, 39, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(117, 39, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(118, 40, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(119, 40, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(120, 40, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(121, 41, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(122, 41, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(123, 41, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(124, 42, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(125, 42, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(126, 42, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(127, 43, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(128, 43, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(129, 43, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(130, 44, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(131, 44, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(132, 44, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(133, 45, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(134, 45, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(135, 45, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(136, 46, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(137, 46, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(138, 46, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(139, 47, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(140, 47, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(141, 47, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(142, 48, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(143, 48, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(144, 48, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(145, 49, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(146, 49, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(147, 49, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(148, 50, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(149, 50, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(150, 50, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(151, 51, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(152, 51, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(153, 51, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(154, 52, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(155, 52, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(156, 52, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(157, 53, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(158, 53, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(159, 53, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(160, 54, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(161, 54, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(162, 54, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(163, 55, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(164, 55, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(165, 55, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(166, 56, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(167, 56, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(168, 56, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(169, 57, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 1),
(170, 57, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 2),
(171, 57, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:16', NULL, NULL, 3),
(172, 58, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:20', NULL, NULL, 1),
(173, 58, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:20', NULL, NULL, 2),
(174, 58, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:20', NULL, NULL, 3),
(175, 59, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:20', NULL, NULL, 1),
(176, 59, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:20', NULL, NULL, 2),
(177, 59, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:20', NULL, NULL, 3),
(178, 60, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:20', NULL, NULL, 1),
(179, 60, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:20', NULL, NULL, 2),
(180, 60, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:20', NULL, NULL, 3),
(181, 61, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 1),
(182, 61, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 2),
(183, 61, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 3),
(184, 62, 1, '', '', '<div>Ayer-Hitam. Sebuah perkampungan yang menarik. Walaupun penulis tidak pernah ke sana, melalui bacaan wikipedia dan sebagainya. Penulis rasa ia satu tempat yang hebat. Pasti ada sungai, air terjun dan sebagainya. Bagaimanakah sesiapa yang membaca ini, tidak tersedak. Bayangkan sahaja, \\''ayer\\'' dan \\''hitam\\''. Sesiapa sahaja yang mendengarnya pasti akan merasa nostalgik.</div><div><br></div><div>Tujuan penulis menulis di sini, bukan sebenarnya sekadar mengisi kekosongan, tetapi untuk berkongsi tulisan yang tidak seberapa ini. Penulis faham, ini tulisan entah apa-apa. Tapi, biarlah ia menjadi tanda tanya, di manakah dan bagaimanakah agaknya rupa ayer hitam. Jadi, untuk mengetahuinya dengan lebih lanjut. Penulis menjemput param pembaca, menjenguk-jenguk laman p1m untuk ayer hitam. Walaupun tiada apa-apa aktiviti lagi, penulis rasa bangga sebab ada laman ini.</div><div><br></div><div>1 Malaysia 1 Ayer Hitam!</div><div><br></div><div>Eh, masih belum 200 patah perkataan lagi. Di sebabkan itu, penulis perlu menulis dengan lebih lanjut lagi tentang Ayer-Hitam. Tapi mungkin para pembaca tidak lagi berminat. Bukan apa, penulis takut peminat ayer-hitam merasa bosan, lalu meninggalkan laman ini. Untuk pengetahuan anda, kelak akan ada modul-modul yang menarik, seperti, members, aktiviti, dan sebagainya, penulis harap, para peminat boleh menunggu sehingga ke tarikh itu ya.</div>', 1, 1, '2014-04-24 11:07:31', 123, '2014-04-25 05:06:03', 1),
(185, 62, 1, '', '', 'Page ini masih baru. Sil<u>a kemaskini terlebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. </u>This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test wo<b>rldy test.Page ini ma<u>sih baru. Sila kemaskini terlebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is </u></b><u>a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.da</u>hulu. This is a test . test<b> worldy test.Page ini masih baru. Sila kemaskini terlebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a t</b>est . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.<div><br></div><div>Page ini masih baru. Sila kemaskini ter<b>lebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test w</b>orldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">Page ini masih baru. Sila kemaskini terl<b>ebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.</b></span><br></div><div><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\"><br></span></div><div>Page ini masih baru. Sila k<b>emaskini terlebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is </b>a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">Page ini masih baru<b>. Sila kemaskini terlebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.</b></span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\"><br></span></div><div><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\"><u><br></u></span></div><div><u>Page ini masih baru.<b> Sila kemaskini terlebih dahulu. This is </b>a test . test w<i>orldy test.&nbsp;dahulu. Th</i>is is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This </u>is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.\\''<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\"><br></span></div>', 1, 1, '2014-04-24 11:07:31', 123, '2014-04-24 22:47:55', 2),
(186, 62, 1, '', '', 'What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????<div><br></div><div>What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????<span style=\\"&quot;font-size:\\" 0.9em;=\\"\\" line-height:=\\"\\" 1.42857143;\\"=\\"\\">What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????</span><br></div><div><span style=\\"&quot;font-size:\\" 0.9em;=\\"\\" line-height:=\\"\\" 1.42857143;\\"=\\"\\"><br></span></div><div>What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????<span style=\\"&quot;font-size:\\" 0.9em;=\\"\\" line-height:=\\"\\" 1.42857143;\\"=\\"\\">What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????</span><span style=\\"&quot;font-size:\\" 0.9em;=\\"\\" line-height:=\\"\\" 1.42857143;\\"=\\"\\"><br></span></div>', 1, 1, '2014-04-24 11:07:31', 123, '2014-04-25 05:16:42', 3),
(187, 63, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 1),
(188, 63, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 2),
(189, 63, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 3),
(190, 64, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 1),
(191, 64, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 2),
(192, 64, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 3),
(193, 65, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 1),
(194, 65, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 2),
(195, 65, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 3),
(196, 66, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 1),
(197, 66, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 2),
(198, 66, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 3),
(199, 67, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 1),
(200, 67, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 2),
(201, 67, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 3),
(202, 68, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 1),
(203, 68, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 2),
(204, 68, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 3),
(205, 69, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 1),
(206, 69, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 2),
(207, 69, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 3),
(208, 70, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 1),
(209, 70, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 2),
(210, 70, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 3),
(211, 71, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 1),
(212, 71, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 2),
(213, 71, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 3),
(214, 72, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 1),
(215, 72, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 2),
(216, 72, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:31', NULL, NULL, 3),
(217, 73, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:34', NULL, NULL, 1),
(218, 73, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:34', NULL, NULL, 2),
(219, 73, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:34', NULL, NULL, 3),
(220, 74, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:39', NULL, NULL, 1),
(221, 74, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:39', NULL, NULL, 2),
(222, 74, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:39', NULL, NULL, 3),
(223, 75, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:39', NULL, NULL, 1),
(224, 75, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:39', NULL, NULL, 2),
(225, 75, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:39', NULL, NULL, 3),
(226, 76, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 1),
(227, 76, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 2),
(228, 76, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 3),
(229, 77, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 1),
(230, 77, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 2),
(231, 77, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 3),
(232, 78, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 1),
(233, 78, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 2),
(234, 78, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 3),
(235, 79, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 1),
(236, 79, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 2),
(237, 79, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 3),
(238, 80, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 1),
(239, 80, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 2),
(240, 80, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 3),
(241, 81, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 1),
(242, 81, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 2),
(243, 81, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:43', NULL, NULL, 3),
(244, 82, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:46', NULL, NULL, 1),
(245, 82, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:46', NULL, NULL, 2),
(246, 82, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:46', NULL, NULL, 3),
(247, 83, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:46', NULL, NULL, 1),
(248, 83, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:46', NULL, NULL, 2),
(249, 83, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:46', NULL, NULL, 3),
(250, 84, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:46', NULL, NULL, 1),
(251, 84, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:46', NULL, NULL, 2),
(252, 84, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-04-24 11:07:46', NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `page_default`
--

CREATE TABLE IF NOT EXISTS `page_default` (
  `pageDefaultID` int(11) NOT NULL AUTO_INCREMENT,
  `pageDefaultType` varchar(100) DEFAULT NULL,
  `pageDefaultName` varchar(100) DEFAULT NULL,
  `pageDefaultSlug` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`pageDefaultID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `page_default`
--

INSERT INTO `page_default` (`pageDefaultID`, `pageDefaultType`, `pageDefaultName`, `pageDefaultSlug`) VALUES
(1, '1', 'Mengenai Kami', 'mengenai-kami'),
(2, '2', 'Pengurusan', 'pengurusan'),
(3, '3', 'AJK Pi1M', 'ajk');

-- --------------------------------------------------------

--
-- Table structure for table `page_photo`
--

CREATE TABLE IF NOT EXISTS `page_photo` (
  `pagePhotoID` int(11) NOT NULL AUTO_INCREMENT,
  `pageID` int(11) DEFAULT NULL,
  `photoID` int(11) DEFAULT NULL,
  PRIMARY KEY (`pagePhotoID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `page_photo`
--

INSERT INTO `page_photo` (`pagePhotoID`, `pageID`, `photoID`) VALUES
(2, 2, 2),
(3, 1, 3),
(8, 184185, 8),
(10, 185, 10),
(11, 185185, 11),
(12, 184, 12),
(13, 186, 13),
(14, 186185, 14),
(15, 186185185, 15),
(16, 2147483647, 16),
(17, 2147483647, 17);

-- --------------------------------------------------------

--
-- Table structure for table `page_reference`
--

CREATE TABLE IF NOT EXISTS `page_reference` (
  `pageReferenceID` int(11) NOT NULL AUTO_INCREMENT,
  `pageID` int(11) DEFAULT NULL,
  `pageParentID` int(11) DEFAULT NULL,
  PRIMARY KEY (`pageReferenceID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=169 ;

--
-- Dumping data for table `page_reference`
--

INSERT INTO `page_reference` (`pageReferenceID`, `pageID`, `pageParentID`) VALUES
(1, 2, 1),
(2, 3, 1),
(3, 5, 4),
(4, 6, 4),
(5, 8, 7),
(6, 9, 7),
(7, 11, 10),
(8, 12, 10),
(9, 14, 13),
(10, 15, 13),
(11, 17, 16),
(12, 18, 16),
(13, 20, 19),
(14, 21, 19),
(15, 23, 22),
(16, 24, 22),
(17, 26, 25),
(18, 27, 25),
(19, 29, 28),
(20, 30, 28),
(21, 32, 31),
(22, 33, 31),
(23, 35, 34),
(24, 36, 34),
(25, 38, 37),
(26, 39, 37),
(27, 41, 40),
(28, 42, 40),
(29, 44, 43),
(30, 45, 43),
(31, 47, 46),
(32, 48, 46),
(33, 50, 49),
(34, 51, 49),
(35, 53, 52),
(36, 54, 52),
(37, 56, 55),
(38, 57, 55),
(39, 59, 58),
(40, 60, 58),
(41, 62, 61),
(42, 63, 61),
(43, 65, 64),
(44, 66, 64),
(45, 68, 67),
(46, 69, 67),
(47, 71, 70),
(48, 72, 70),
(49, 74, 73),
(50, 75, 73),
(51, 77, 76),
(52, 78, 76),
(53, 80, 79),
(54, 81, 79),
(55, 83, 82),
(56, 84, 82),
(57, 86, 85),
(58, 87, 85),
(59, 89, 88),
(60, 90, 88),
(61, 92, 91),
(62, 93, 91),
(63, 95, 94),
(64, 96, 94),
(65, 98, 97),
(66, 99, 97),
(67, 101, 100),
(68, 102, 100),
(69, 104, 103),
(70, 105, 103),
(71, 107, 106),
(72, 108, 106),
(73, 110, 109),
(74, 111, 109),
(75, 113, 112),
(76, 114, 112),
(77, 116, 115),
(78, 117, 115),
(79, 119, 118),
(80, 120, 118),
(81, 122, 121),
(82, 123, 121),
(83, 125, 124),
(84, 126, 124),
(85, 128, 127),
(86, 129, 127),
(87, 131, 130),
(88, 132, 130),
(89, 134, 133),
(90, 135, 133),
(91, 137, 136),
(92, 138, 136),
(93, 140, 139),
(94, 141, 139),
(95, 143, 142),
(96, 144, 142),
(97, 146, 145),
(98, 147, 145),
(99, 149, 148),
(100, 150, 148),
(101, 152, 151),
(102, 153, 151),
(103, 155, 154),
(104, 156, 154),
(105, 158, 157),
(106, 159, 157),
(107, 161, 160),
(108, 162, 160),
(109, 164, 163),
(110, 165, 163),
(111, 167, 166),
(112, 168, 166),
(113, 170, 169),
(114, 171, 169),
(115, 173, 172),
(116, 174, 172),
(117, 176, 175),
(118, 177, 175),
(119, 179, 178),
(120, 180, 178),
(121, 182, 181),
(122, 183, 181),
(123, 185, 184),
(124, 186, 184),
(125, 188, 187),
(126, 189, 187),
(127, 191, 190),
(128, 192, 190),
(129, 194, 193),
(130, 195, 193),
(131, 197, 196),
(132, 198, 196),
(133, 200, 199),
(134, 201, 199),
(135, 203, 202),
(136, 204, 202),
(137, 206, 205),
(138, 207, 205),
(139, 209, 208),
(140, 210, 208),
(141, 212, 211),
(142, 213, 211),
(143, 215, 214),
(144, 216, 214),
(145, 218, 217),
(146, 219, 217),
(147, 221, 220),
(148, 222, 220),
(149, 224, 223),
(150, 225, 223),
(151, 227, 226),
(152, 228, 226),
(153, 230, 229),
(154, 231, 229),
(155, 233, 232),
(156, 234, 232),
(157, 236, 235),
(158, 237, 235),
(159, 239, 238),
(160, 240, 238),
(161, 242, 241),
(162, 243, 241),
(163, 245, 244),
(164, 246, 244),
(165, 248, 247),
(166, 249, 247),
(167, 251, 250),
(168, 252, 250);

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `photoID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `albumID` int(11) DEFAULT NULL,
  `photoName` varchar(100) DEFAULT NULL,
  `photoCreatedDate` datetime DEFAULT NULL,
  `photoCreatedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`photoID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`photoID`, `siteID`, `albumID`, `photoName`, `photoCreatedDate`, `photoCreatedUser`) VALUES
(1, 1, 0, '21395997980.jpg', '2014-03-28 04:13:00', 23),
(2, 1, 0, '21395998154.jpg', '2014-03-28 04:15:54', 23),
(3, 1, 0, '11395998224.jpg', '2014-03-28 04:17:04', 23),
(4, 62, 0, '1841398310859.jpg', '2014-04-23 22:40:59', 23),
(5, 62, 0, '1841398310938.jpg', '2014-04-23 22:42:18', 23),
(6, 0, 0, '1841851398311068.jpg', '2014-04-23 22:44:28', 23),
(7, 62, 0, '1841398311113.jpg', '2014-04-23 22:45:13', 23),
(8, 0, 0, '1841851398311129.jpg', '2014-04-23 22:45:29', 23),
(9, 62, 0, '1841398311180.jpg', '2014-04-23 22:46:20', 23),
(10, 62, 0, '1851398311311.jpg', '2014-04-23 22:48:31', 23),
(11, 0, 0, '1851851398311345.jpg', '2014-04-23 22:49:05', 23),
(12, 62, 0, '1841398352056.jpg', '2014-04-24 10:07:36', 23),
(13, 62, 0, '1861398397550.jpg', '2014-04-24 22:45:50', 123),
(14, NULL, 0, '1861851398397647.jpg', '2014-04-24 22:47:27', 123),
(15, NULL, 0, '1861851851398397676.jpg', '2014-04-24 22:47:56', 123),
(16, NULL, 0, '1861851851841398397868.jpg', '2014-04-24 22:51:08', 123),
(17, NULL, 0, '1861851851841841398397973.jpg', '2014-04-24 22:52:53', 123);

-- --------------------------------------------------------

--
-- Table structure for table `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `siteID` int(11) NOT NULL AUTO_INCREMENT,
  `stateID` int(11) DEFAULT NULL,
  `siteName` varchar(100) DEFAULT NULL,
  `siteSlug` varchar(100) DEFAULT NULL,
  `siteCreatedUser` int(11) DEFAULT NULL,
  `siteCreatedDate` datetime DEFAULT NULL,
  `siteUpdatedUser` int(11) DEFAULT NULL,
  `siteUpdatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`siteID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=85 ;

--
-- Dumping data for table `site`
--

INSERT INTO `site` (`siteID`, `stateID`, `siteName`, `siteSlug`, `siteCreatedUser`, `siteCreatedDate`, `siteUpdatedUser`, `siteUpdatedDate`) VALUES
(1, 12, 'Kg Kopimpinan, Penampang', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(2, 12, 'Kg Duvanson Ketiau, Putatan', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(3, 12, 'Kg Kuala, Papar', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(4, 12, 'Kg Biau, Bongawan Papar', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(5, 12, 'Kg Lawa Kabajang, Beaufort (AM)', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(6, 12, 'Kg Bundu, Kuala Penyu', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(7, 12, 'Pekan Kuala Penyu', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(8, 12, 'Pekan Menumbok', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(9, 12, 'Pekan Putera Jaya', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(10, 12, 'Pekan Babagon', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(11, 12, 'Inobong (AM)', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(12, 12, 'Kg Muhibbah, Putatan', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(13, 12, 'Pekan Kg Langkuas', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(14, 12, 'Kg Belatik', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(15, 12, 'Rumah Kebajikan OKU Kimanis', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(16, 12, 'Pekan Kg. Bambangan', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(17, 12, 'Pekan Kg Lubok', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(18, 12, 'Kuala Mengalong', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(19, 12, 'Lubok Temiang (AM)', NULL, 1, '2014-04-24 11:07:09', NULL, NULL),
(20, 12, 'Kg Batu Payung, Tawau', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(21, 12, 'Kg Airport, Kunak', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(22, 12, 'Kg Hampilan, Kunak', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(23, 12, 'Kg Kadazan, Kunak', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(24, 12, 'Kg Lormalong, Kunak', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(25, 12, 'Pekan Kunak, Kunak', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(26, 12, 'Pekan Tungku, Tungku', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(27, 12, 'Pekan Beluran, Beluran', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(28, 12, 'Kg Kuala Sapi, Beluran', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(29, 12, 'Kg Bintang Mas, Beluran', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(30, 12, 'Pekan Telupid, Beluran', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(31, 12, 'Kg Wonod, Beluran', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(32, 12, 'Kg Linayukan, Tongod', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(33, 12, 'Kg Sogo Sogo, Tongod', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(34, 12, 'Pekan Tongod, Tongod', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(35, 12, 'Kg Balung Cocos, Tawau', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(36, 12, 'Layung Industrial, Lahad Datu', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(37, 12, 'Kg Bugaya, Semporna', NULL, 1, '2014-04-24 11:07:13', NULL, NULL),
(38, 12, 'Pejabat Daerah Kudat, Kudat (AM)', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(39, 12, 'Kg Tandek, Kota Marudu', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(40, 12, 'Kg Sg Damit, Tuaran', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(41, 12, 'Kg Malanggang Baru, Kiulu', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(42, 12, 'Kg Mesilou, Ranau', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(43, 12, 'Kg. Desa Aman, Ranau', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(44, 12, 'Kg Lohan, Ranau', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(45, 12, 'Kg Bongkud, Ranau', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(46, 12, 'Kg Toboh, Tambunan', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(47, 12, 'Kg Bingkor, Keningau', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(48, 12, 'Kg Sook, Keningau', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(49, 12, 'Kg Apin Apin, Keningau', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(50, 12, 'Kg Bunsit, Keningau', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(51, 12, 'Taman Sabana, Keningau', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(52, 12, 'Kg Malima, Keningau', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(53, 12, 'Kg Sawang (AM)', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(54, 12, 'Kg Suang Punggur', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(55, 12, 'Pekan Kg Serusop, Tuaran', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(56, 12, 'Kg Pamilaan', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(57, 12, 'Pekan Rampayan Laut', NULL, 1, '2014-04-24 11:07:16', NULL, NULL),
(58, 13, 'Rumah Benjamin', NULL, 1, '2014-04-24 11:07:20', NULL, NULL),
(59, 13, 'Kg. Nanga Tada', NULL, 1, '2014-04-24 11:07:20', NULL, NULL),
(60, 13, 'Kg. Muhibbah', NULL, 1, '2014-04-24 11:07:20', NULL, NULL),
(61, 1, 'Kahang Barat', NULL, 1, '2014-04-24 11:07:31', NULL, NULL),
(62, 1, 'Ayer Hitam', 'ayer-hitam', 1, '2014-04-24 11:07:31', 1, '2014-04-24 12:14:22'),
(63, 1, 'Kahang Timur', NULL, 1, '2014-04-24 11:07:31', NULL, NULL),
(64, 1, 'Bukit Tongkat', NULL, 1, '2014-04-24 11:07:31', NULL, NULL),
(65, 1, 'Palong Timur 1', NULL, 1, '2014-04-24 11:07:31', NULL, NULL),
(66, 1, 'Palong Timur 3', NULL, 1, '2014-04-24 11:07:31', NULL, NULL),
(67, 1, 'Bukit Permai', NULL, 1, '2014-04-24 11:07:31', NULL, NULL),
(68, 1, 'Bukit Batu', NULL, 1, '2014-04-24 11:07:31', NULL, NULL),
(69, 1, 'Layang-Layang', NULL, 1, '2014-04-24 11:07:31', NULL, NULL),
(70, 1, 'Hj Idris', NULL, 1, '2014-04-24 11:07:31', NULL, NULL),
(71, 1, 'Inas Utara', NULL, 1, '2014-04-24 11:07:31', NULL, NULL),
(72, 1, 'Mensudut Lama', NULL, 1, '2014-04-24 11:07:31', NULL, NULL),
(73, 2, 'Felda Bukit Tangga', NULL, 1, '2014-04-24 11:07:34', NULL, NULL),
(74, 14, 'PPR Pantai', NULL, 1, '2014-04-24 11:07:39', NULL, NULL),
(75, 14, 'PPR Kerinchi', NULL, 1, '2014-04-24 11:07:39', NULL, NULL),
(76, 5, 'Kepis', NULL, 1, '2014-04-24 11:07:43', NULL, NULL),
(77, 5, 'Jelai 4', NULL, 1, '2014-04-24 11:07:43', NULL, NULL),
(78, 5, 'Pasir Besar', NULL, 1, '2014-04-24 11:07:43', NULL, NULL),
(79, 5, 'Sg Kelamah', NULL, 1, '2014-04-24 11:07:43', NULL, NULL),
(80, 5, 'Bukit Jalor', NULL, 1, '2014-04-24 11:07:43', NULL, NULL),
(81, 5, 'Bukit Rokan', NULL, 1, '2014-04-24 11:07:43', NULL, NULL),
(82, 9, 'Felda Chuping', NULL, 1, '2014-04-24 11:07:46', NULL, NULL),
(83, 9, 'Felda Mata Air', NULL, 1, '2014-04-24 11:07:46', NULL, NULL),
(84, 9, 'Felda Rimba Mas', NULL, 1, '2014-04-24 11:07:46', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `site_editor`
--

CREATE TABLE IF NOT EXISTS `site_editor` (
  `siteEditorID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `siteID` int(11) DEFAULT NULL,
  `siteEditorCreatedUser` int(11) DEFAULT NULL,
  `siteEditorCreatedDate` int(11) DEFAULT NULL,
  PRIMARY KEY (`siteEditorID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_info`
--

CREATE TABLE IF NOT EXISTS `site_info` (
  `siteInfoID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `siteInfoLatitude` varchar(100) DEFAULT NULL,
  `siteInfoLongitude` varchar(100) DEFAULT NULL,
  `siteInfoPhone` varchar(100) DEFAULT NULL,
  `siteInfoAddress` text,
  `siteInfoDescription` text,
  `siteInfoFax` varchar(100) DEFAULT NULL,
  `siteInfoTwitterUrl` varchar(100) DEFAULT NULL,
  `siteInfoFacebookUrl` varchar(100) DEFAULT NULL,
  `siteInfoEmail` varchar(100) DEFAULT NULL,
  `siteInfoImage` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`siteInfoID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=85 ;

--
-- Dumping data for table `site_info`
--

INSERT INTO `site_info` (`siteInfoID`, `siteID`, `siteInfoLatitude`, `siteInfoLongitude`, `siteInfoPhone`, `siteInfoAddress`, `siteInfoDescription`, `siteInfoFax`, `siteInfoTwitterUrl`, `siteInfoFacebookUrl`, `siteInfoEmail`, `siteInfoImage`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 45, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 48, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 52, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 57, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 58, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 60, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 62, '3.17976678131', '101.364841461', '', '', '\r\n	          \r\n	          \r\n	          	        	        	        ', '', '', '', '', '1398421256.jpg'),
(63, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 68, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 70, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 73, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 74, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 76, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 77, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(78, 78, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(79, 79, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80, 80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(81, 81, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(82, 82, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(83, 83, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(84, 84, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `site_manager`
--

CREATE TABLE IF NOT EXISTS `site_manager` (
  `siteManagerID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `siteID` int(11) DEFAULT NULL,
  `siteManagerStatus` int(11) DEFAULT NULL,
  `siteManagerCreatedDate` datetime DEFAULT NULL,
  `siteManagerCreatedUser` int(11) DEFAULT NULL,
  `siteManagerUpdatedDate` datetime DEFAULT NULL,
  `siteManagerUpdatedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`siteManagerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=169 ;

--
-- Dumping data for table `site_manager`
--

INSERT INTO `site_manager` (`siteManagerID`, `userID`, `siteID`, `siteManagerStatus`, `siteManagerCreatedDate`, `siteManagerCreatedUser`, `siteManagerUpdatedDate`, `siteManagerUpdatedUser`) VALUES
(1, 2, 1, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(2, 3, 1, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(3, 4, 2, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(4, 5, 2, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(5, 6, 3, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(6, 7, 3, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(7, 7, 4, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(8, 8, 4, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(9, 9, 5, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(10, 10, 5, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(11, 11, 6, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(12, 12, 6, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(13, 13, 7, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(14, 14, 7, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(15, 15, 8, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(16, 16, 8, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(17, 17, 9, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(18, 18, 9, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(19, 19, 10, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(20, 20, 10, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(21, 21, 11, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(22, 22, 11, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(23, 23, 12, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(24, 24, 12, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(25, 25, 13, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(26, 26, 13, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(27, 27, 14, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(28, 28, 14, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(29, 29, 15, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(30, 30, 15, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(31, 31, 16, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(32, 32, 16, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(33, 33, 17, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(34, 34, 17, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(35, 35, 18, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(36, 36, 18, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(37, 37, 19, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(38, 38, 19, 1, '2014-04-24 11:07:09', 1, NULL, NULL),
(39, 39, 20, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(40, 40, 20, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(41, 41, 21, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(42, 42, 21, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(43, 43, 22, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(44, 44, 22, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(45, 45, 23, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(46, 46, 23, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(47, 47, 24, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(48, 48, 24, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(49, 49, 25, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(50, 50, 25, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(51, 51, 26, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(52, 52, 26, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(53, 53, 27, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(54, 54, 27, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(55, 55, 28, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(56, 56, 28, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(57, 57, 29, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(58, 58, 29, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(59, 59, 30, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(60, 60, 30, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(61, 61, 31, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(62, 62, 31, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(63, 63, 32, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(64, 64, 32, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(65, 65, 33, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(66, 66, 33, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(67, 67, 34, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(68, 68, 34, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(69, 69, 35, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(70, 70, 35, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(71, 71, 36, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(72, 72, 36, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(73, 73, 37, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(74, 74, 37, 1, '2014-04-24 11:07:13', 1, NULL, NULL),
(75, 75, 38, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(76, 76, 38, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(77, 77, 39, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(78, 78, 39, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(79, 79, 40, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(80, 80, 40, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(81, 81, 41, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(82, 82, 41, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(83, 83, 42, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(84, 84, 42, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(85, 85, 43, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(86, 86, 43, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(87, 87, 44, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(88, 88, 44, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(89, 89, 45, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(90, 90, 45, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(91, 91, 46, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(92, 92, 46, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(93, 93, 47, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(94, 94, 47, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(95, 95, 48, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(96, 96, 48, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(97, 97, 49, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(98, 98, 49, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(99, 99, 50, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(100, 100, 50, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(101, 101, 51, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(102, 102, 51, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(103, 103, 52, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(104, 104, 52, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(105, 105, 53, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(106, 106, 53, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(107, 107, 54, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(108, 108, 54, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(109, 109, 55, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(110, 110, 55, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(111, 111, 56, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(112, 112, 56, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(113, 113, 57, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(114, 114, 57, 1, '2014-04-24 11:07:16', 1, NULL, NULL),
(115, 115, 58, 1, '2014-04-24 11:07:20', 1, NULL, NULL),
(116, 116, 58, 1, '2014-04-24 11:07:20', 1, NULL, NULL),
(117, 117, 59, 1, '2014-04-24 11:07:20', 1, NULL, NULL),
(118, 118, 59, 1, '2014-04-24 11:07:20', 1, NULL, NULL),
(119, 119, 60, 1, '2014-04-24 11:07:20', 1, NULL, NULL),
(120, 120, 60, 1, '2014-04-24 11:07:20', 1, NULL, NULL),
(121, 121, 61, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(122, 122, 61, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(123, 123, 62, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(124, 124, 62, 1, '2014-04-25 19:33:36', 1, '2014-04-25 19:32:05', 1),
(125, 125, 63, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(126, 126, 63, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(127, 127, 64, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(128, 128, 64, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(129, 129, 65, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(130, 130, 65, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(131, 131, 66, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(132, 132, 66, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(133, 133, 67, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(134, 134, 67, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(135, 135, 68, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(136, 136, 68, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(137, 137, 69, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(138, 138, 69, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(139, 139, 70, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(140, 140, 70, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(141, 141, 71, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(142, 142, 71, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(143, 143, 72, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(144, 144, 72, 1, '2014-04-24 11:07:31', 1, NULL, NULL),
(145, 145, 73, 1, '2014-04-24 11:07:34', 1, NULL, NULL),
(146, 146, 73, 1, '2014-04-24 11:07:34', 1, NULL, NULL),
(147, 147, 74, 1, '2014-04-24 11:07:39', 1, NULL, NULL),
(148, 148, 74, 1, '2014-04-24 11:07:39', 1, NULL, NULL),
(149, 149, 75, 1, '2014-04-24 11:07:39', 1, NULL, NULL),
(150, 149, 75, 1, '2014-04-24 11:07:39', 1, NULL, NULL),
(151, 150, 76, 1, '2014-04-24 11:07:43', 1, NULL, NULL),
(152, 151, 76, 1, '2014-04-24 11:07:43', 1, NULL, NULL),
(153, 152, 77, 1, '2014-04-24 11:07:43', 1, NULL, NULL),
(154, 153, 77, 1, '2014-04-24 11:07:43', 1, NULL, NULL),
(155, 154, 78, 1, '2014-04-24 11:07:43', 1, NULL, NULL),
(156, 155, 78, 1, '2014-04-24 11:07:43', 1, NULL, NULL),
(157, 156, 79, 1, '2014-04-24 11:07:43', 1, NULL, NULL),
(158, 157, 79, 1, '2014-04-24 11:07:43', 1, NULL, NULL),
(159, 158, 80, 1, '2014-04-24 11:07:43', 1, NULL, NULL),
(160, 159, 80, 1, '2014-04-24 11:07:43', 1, NULL, NULL),
(161, 160, 81, 1, '2014-04-24 11:07:43', 1, NULL, NULL),
(162, 161, 81, 1, '2014-04-24 11:07:43', 1, NULL, NULL),
(163, 162, 82, 1, '2014-04-24 11:07:46', 1, NULL, NULL),
(164, 163, 82, 1, '2014-04-24 11:07:46', 1, NULL, NULL),
(165, 164, 83, 1, '2014-04-24 11:07:46', 1, NULL, NULL),
(166, 165, 83, 1, '2014-04-24 11:07:46', 1, NULL, NULL),
(167, 166, 84, 1, '2014-04-24 11:07:46', 1, NULL, NULL),
(168, 167, 84, 1, '2014-04-24 11:07:46', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `site_member`
--

CREATE TABLE IF NOT EXISTS `site_member` (
  `siteMemberID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  PRIMARY KEY (`siteMemberID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_message`
--

CREATE TABLE IF NOT EXISTS `site_message` (
  `siteMessageID` int(11) NOT NULL AUTO_INCREMENT,
  `messageID` int(11) DEFAULT NULL,
  `siteID` int(11) DEFAULT NULL,
  `contactID` int(11) DEFAULT NULL,
  `siteMessageType` int(11) DEFAULT NULL,
  `siteMessageReadStatus` int(11) DEFAULT NULL,
  `siteMessageReadUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`siteMessageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `site_message`
--

INSERT INTO `site_message` (`siteMessageID`, `messageID`, `siteID`, `contactID`, `siteMessageType`, `siteMessageReadStatus`, `siteMessageReadUser`) VALUES
(1, 1, 1, 1, 1, 0, 0),
(2, 2, 62, 1, 1, 0, 0),
(3, 3, 62, 2, 1, 0, 0),
(4, 4, 62, 2, 1, 0, 0),
(5, 5, 62, 3, 1, 0, 0),
(6, 6, 62, 4, 1, 0, 0),
(7, 7, 62, 3, 1, 0, 0),
(8, 8, 62, 5, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `site_request`
--

CREATE TABLE IF NOT EXISTS `site_request` (
  `siteRequestID` int(11) NOT NULL AUTO_INCREMENT,
  `siteRequestType` int(11) DEFAULT NULL,
  `siteID` int(11) DEFAULT NULL,
  `siteRequestRefID` int(11) DEFAULT NULL,
  `siteRequestData` text COLLATE utf8_unicode_ci,
  `siteRequestStatus` int(11) DEFAULT NULL,
  `siteRequestCreatedDate` datetime DEFAULT NULL,
  `siteRequestCreatedUser` int(11) DEFAULT NULL,
  `siteRequestUpdatedDate` datetime DEFAULT NULL,
  `siteRequestUpdatedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`siteRequestID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

--
-- Dumping data for table `site_request`
--

INSERT INTO `site_request` (`siteRequestID`, `siteRequestType`, `siteID`, `siteRequestRefID`, `siteRequestData`, `siteRequestStatus`, `siteRequestCreatedDate`, `siteRequestCreatedUser`, `siteRequestUpdatedDate`, `siteRequestUpdatedUser`) VALUES
(1, 2, 62, 184, 'a:3:{s:8:"pageText";s:10459:"<div><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">a dummy text</span></div><div><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\"><br></span></div><div><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\"><br></span></div><div><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\"><br></span></div><div><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">this is just a dummy text&nbsp;</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but a dummy text</span><span style=\\"font-size: 11px; line-height: 15.04285717010498px;\\">but&nbsp;</span><br></div>";s:15:"pageUpdatedDate";s:19:"2014-04-24 21:40:53";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-24 21:40:53', 123, NULL, NULL),
(2, 2, 62, 184, 'a:3:{s:8:"pageText";s:2229:"THis is a dummy text but a dummy text.&nbsp;THis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy text.&nbsp;THis is a dummy text but a dummy text.&nbsp;THis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy text.&nbsp;THis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy text<div><br></div><div>THis is a dummy text but a dummy text.&nbsp;THis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy text<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">THis is a dummy text but a dummy text.&nbsp;THis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy text</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">THis is a dummy text but a dummy text.&nbsp;THis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy text</span><br></div><div><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\"><br></span></div><div>THis is a dummy text but a dummy text.&nbsp;THis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy text<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">THis is a dummy text but a dummy text.&nbsp;THis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy textTHis is a dummy text but a dummy text</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\"><br></span></div>";s:15:"pageUpdatedDate";s:19:"2014-04-24 21:41:55";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-24 21:41:55', 123, NULL, NULL),
(3, 2, 62, 184, 'a:3:{s:8:"pageText";s:60:"Page ini masih baru. Sila kemaskini terlebih dahulu. ok test";s:15:"pageUpdatedDate";s:19:"2014-04-24 21:42:19";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-24 21:42:19', 123, NULL, NULL),
(4, 2, 62, 184, 'a:3:{s:8:"pageText";s:990:"Page ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok test<div><br></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok test<br></div>";s:15:"pageUpdatedDate";s:19:"2014-04-24 21:42:42";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-24 21:42:42', 123, NULL, NULL),
(5, 2, 62, 184, 'a:3:{s:8:"pageText";s:2002:"Page ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok test<div><br></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok test<br></div><div><br></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok test<div><br></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok test</div></div>";s:15:"pageUpdatedDate";s:19:"2014-04-24 21:43:06";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-24 21:43:06', 123, NULL, NULL),
(6, 2, 62, 186, 'a:3:{s:8:"pageText";s:2504:"<div>Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.<div>Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.</span></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.<br></div></div><div><br></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.<div>Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.</span></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.<br></div></div><div><br></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.<div>Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.</span></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.<br></div></div><div><br></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.<div>Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.</span></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. NO this should never be this. this is a test of many text but not working.<br></div></div><div><br></div>";s:15:"pageUpdatedDate";s:19:"2014-04-24 22:38:27";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-24 22:38:27', 123, NULL, NULL),
(7, 2, 62, 186, 'a:3:{s:8:"pageText";s:3000:"What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????<div><br></div><div>What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????</span><br></div><div><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\"><br></span></div><div>What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????</span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\"><br></span></div>";s:15:"pageUpdatedDate";s:19:"2014-04-24 22:43:41";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-24 22:43:41', 123, NULL, NULL),
(8, 2, 62, 186, 'a:3:{s:8:"pageText";s:3108:"What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????<div><br></div><div>What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????<span style=\\"\\\\&quot;font-size:\\" 0.9em;=\\"\\" line-height:=\\"\\" 1.42857143;\\\\\\"=\\"\\">What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????</span><br></div><div><span style=\\"\\\\&quot;font-size:\\" 0.9em;=\\"\\" line-height:=\\"\\" 1.42857143;\\\\\\"=\\"\\"><br></span></div><div>What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????<span style=\\"\\\\&quot;font-size:\\" 0.9em;=\\"\\" line-height:=\\"\\" 1.42857143;\\\\\\"=\\"\\">What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????</span><span style=\\"\\\\&quot;font-size:\\" 0.9em;=\\"\\" line-height:=\\"\\" 1.42857143;\\\\\\"=\\"\\"><br></span></div>";s:15:"pageUpdatedDate";s:19:"2014-04-24 22:45:49";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-24 22:45:49', 123, NULL, NULL),
(9, 2, 62, 185, 'a:3:{s:8:"pageText";s:4011:"Page ini masih baru. Sil<u>a kemaskini terlebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. </u>This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test wo<b>rldy test.Page ini ma<u>sih baru. Sila kemaskini terlebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is </u></b><u>a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.da</u>hulu. This is a test . test<b> worldy test.Page ini masih baru. Sila kemaskini terlebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a t</b>est . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.<div><br></div><div>Page ini masih baru. Sila kemaskini ter<b>lebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test w</b>orldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">Page ini masih baru. Sila kemaskini terl<b>ebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.</b></span><br></div><div><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\"><br></span></div><div>Page ini masih baru. Sila k<b>emaskini terlebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is </b>a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\">Page ini masih baru<b>. Sila kemaskini terlebih dahulu. This is a test . test worldy test.&nbsp;dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.</b></span><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\"><br></span></div><div><span style=\\"font-size: 0.9em; line-height: 1.42857143;\\"><u><br></u></span></div><div><u>Page ini masih baru.<b> Sila kemaskini terlebih dahulu. This is </b>a test . test w<i>orldy test.&nbsp;dahulu. Th</i>is is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This </u>is a test . test worldy test.dahulu. This is a test . test worldy test.dahulu. This is a test . test worldy test.\\''<span style=\\"font-size: 0.9em; line-height: 1.42857143;\\"><br></span></div>";s:15:"pageUpdatedDate";s:19:"2014-04-24 22:47:55";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-24 22:47:55', 123, NULL, NULL),
(10, 2, 62, 184, 'a:3:{s:8:"pageText";s:2308:"<div><b><u>Ayer Hitam</u></b></div><div><br></div><div><b><u>Basically,</u></b> ayer hitam ni <b>adalah suatu tempat </b>di semenanjung. Di <b>sini, biasanya </b>kami punya tempat <b>yang menarik untuk </b>bermain <b>game. Jika </b>ada masa, datanglah ke air hitam ya. ;)</div><div><br></div><b>Page ini masih baru. Sila kemaskini terlebih dahulu. ok </b>testPage ini masih baru. Sila kemaskini <b>terlebih dahulu. ok testPage ini masih </b>baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok test<div><br></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok test<br></div><div><br></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok test<div><br></div><div>Page ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok testPage ini masih baru. Sila kemaskini terlebih dahulu. ok test</div></div>";s:15:"pageUpdatedDate";s:19:"2014-04-24 22:51:07";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-24 22:51:07', 123, NULL, NULL),
(11, 2, 62, 184, 'a:3:{s:8:"pageText";s:7423:"<b style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Ayer Hitam</b><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">&nbsp;(</span><a href=\\"http://en.wikipedia.org/wiki/Chinese_language\\" title=\\"Chinese language\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Chinese</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">:&nbsp;</span><span lang=\\"zh\\" xml:lang=\\"zh\\" style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">äºšä¾æ·¡</span><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">) is a rest town&nbsp;</span><i style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">(Bandar persinggahan)</i><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">&nbsp;in&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Johor\\" title=\\"Johor\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Johor</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">,&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Malaysia\\" title=\\"Malaysia\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Malaysia</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">. Located just at the junction of route&nbsp;</span><img alt=\\"1\\" src=\\"http://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Jkr-ft1.png/30px-Jkr-ft1.png\\" width=\\"30\\" height=\\"23\\" srcset=\\"//upload.wikimedia.org/wikipedia/commons/thumb/0/04/Jkr-ft1.png/45px-Jkr-ft1.png 1.5x, //upload.wikimedia.org/wikipedia/commons/thumb/0/04/Jkr-ft1.png/60px-Jkr-ft1.png 2x\\" data-file-width=\\"400\\" data-file-height=\\"300\\" style=\\"border-style: none; margin: 0px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\"><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">&nbsp;and route&nbsp;</span><img alt=\\"50\\" src=\\"http://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Jkr-ft50.png/30px-Jkr-ft50.png\\" width=\\"30\\" height=\\"23\\" srcset=\\"//upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Jkr-ft50.png/45px-Jkr-ft50.png 1.5x, //upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Jkr-ft50.png/60px-Jkr-ft50.png 2x\\" data-file-width=\\"400\\" data-file-height=\\"300\\" style=\\"border-style: none; margin: 0px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\"><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">, it is known for its many outlets selling pottery and other crafts. It also is one of the interchange for&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/North-South_Expressway,_Malaysia\\" title=\\"North-South Expressway, Malaysia\\" class=\\"mw-redirect\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">North-South Expressway</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">. It is the border town between Batu Pahat and Kluang district. Approximately 32&nbsp;km away from&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Batu_Pahat_(city)\\" title=\\"Batu Pahat (city)\\" class=\\"mw-redirect\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Bandar Penggaram,Batu Pahat</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">, capital of&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Batu_Pahat\\" title=\\"Batu Pahat\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Batu Pahat</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">&nbsp;district and 20&nbsp;km away from&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Kluang\\" title=\\"Kluang\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Kluang</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">.</span><div><h2 style=\\"color: black; background-image: none; margin: 1em 0px 0.25em; overflow: hidden; padding: 0px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(170, 170, 170); font-size: 1.5em; font-family: \\''Linux Libertine\\'', Georgia, Times, serif; line-height: 1.3;\\"><span class=\\"mw-headline\\" id=\\"History\\">History</span><span class=\\"mw-editsection\\" style=\\"-webkit-user-select: none; font-size: small; margin-left: 1em; vertical-align: baseline; line-height: 1em; display: inline-block; white-space: nowrap; padding-right: 0.25em; unicode-bidi: -webkit-isolate; font-family: sans-serif;\\"><span class=\\"mw-editsection-bracket\\">[</span><a href=\\"http://en.wikipedia.org/w/index.php?title=Ayer_Hitam&amp;action=edit&amp;section=1\\" title=\\"Edit section: History\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">edit</a><span class=\\"mw-editsection-bracket\\">]</span></span></h2><p style=\\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.399999618530273px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\\">Ayer Hitam<sup id=\\"cite_ref-1\\" class=\\"reference\\" style=\\"line-height: 1; unicode-bidi: -webkit-isolate;\\"><a href=\\"http://en.wikipedia.org/wiki/Ayer_Hitam#cite_note-1\\" style=\\"color: rgb(11, 0, 128); background-image: none; white-space: nowrap;\\">[1]</a></sup>&nbsp;simply means Black Water. A lively town, Ayer Hitam is always bustling with passing vehicles and people who travels north and south. This place is well known for its&nbsp;<a href=\\"http://en.wikipedia.org/wiki/Ceramic\\" title=\\"Ceramic\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">ceramic</a>&nbsp;items such as flower vases in an assortment of colours, photo frames, jars, ashtrays, and other home decorative items. For a closer look, you can also watch the potters at work. Aside from quality souvenirs, Ayer Hitam is also dotted with many stalls selling local tidbits. Amongst the famous ones are prawn crackers, steamed corn, tapioca chips, and the all-time must-try â€œotak-otakâ€. These food items are fresh and prepacked for you, and sold at reasonable prices.</p></div>";s:15:"pageUpdatedDate";s:19:"2014-04-24 22:52:52";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-24 22:52:52', 123, NULL, NULL),
(12, 2, 62, 184, 'a:3:{s:8:"pageText";s:7241:"<b style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Ayer Hitam</b><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">&nbsp;(</span><a href=\\"http://en.wikipedia.org/wiki/Chinese_language\\" title=\\"Chinese language\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Chinese</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">:&nbsp;</span><span lang=\\"zh\\" xml:lang=\\"zh\\" style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">äºšä¾æ·¡</span><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">) is a rest town&nbsp;</span><i style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">(Bandar persinggahan)</i><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">&nbsp;in&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Johor\\" title=\\"Johor\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Johor</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">,&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Malaysia\\" title=\\"Malaysia\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Malaysia</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">. Located just at the junction of route&nbsp;</span><img alt=\\"1\\" src=\\"http://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Jkr-ft1.png/30px-Jkr-ft1.png\\" width=\\"30\\" height=\\"23\\" data-file-width=\\"400\\" data-file-height=\\"300\\" style=\\"border-style: none; margin: 0px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\"><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">&nbsp;and route&nbsp;</span><img alt=\\"50\\" src=\\"http://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Jkr-ft50.png/30px-Jkr-ft50.png\\" width=\\"30\\" height=\\"23\\" srcset=\\"//upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Jkr-ft50.png/45px-Jkr-ft50.png 1.5x, //upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Jkr-ft50.png/60px-Jkr-ft50.png 2x\\" data-file-width=\\"400\\" data-file-height=\\"300\\" style=\\"border-style: none; margin: 0px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\"><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">, it is known for its many outlets selling pottery and other crafts. It also is one of the interchange for&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/North-South_Expressway,_Malaysia\\" title=\\"North-South Expressway, Malaysia\\" class=\\"mw-redirect\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">North-South Expressway</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">. It is the border town between Batu Pahat and Kluang district. Approximately 32&nbsp;km away from&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Batu_Pahat_(city)\\" title=\\"Batu Pahat (city)\\" class=\\"mw-redirect\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Bandar Penggaram,Batu Pahat</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">, capital of&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Batu_Pahat\\" title=\\"Batu Pahat\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Batu Pahat</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">&nbsp;district and 20&nbsp;km away from&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Kluang\\" title=\\"Kluang\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Kluang</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">.</span><div><h2 style=\\"color: black; background-image: none; margin: 1em 0px 0.25em; overflow: hidden; padding: 0px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(170, 170, 170); font-size: 1.5em; font-family: \\''Linux Libertine\\'', Georgia, Times, serif; line-height: 1.3;\\"><span class=\\"mw-headline\\" id=\\"History\\">History</span><span class=\\"mw-editsection\\" style=\\"-webkit-user-select: none; font-size: small; margin-left: 1em; vertical-align: baseline; line-height: 1em; display: inline-block; white-space: nowrap; padding-right: 0.25em; unicode-bidi: -webkit-isolate; font-family: sans-serif;\\"><span class=\\"mw-editsection-bracket\\">[</span><a href=\\"http://en.wikipedia.org/w/index.php?title=Ayer_Hitam&amp;action=edit&amp;section=1\\" title=\\"Edit section: History\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">edit</a><span class=\\"mw-editsection-bracket\\">]</span></span></h2><p style=\\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.399999618530273px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\\">Ayer Hitam<sup id=\\"cite_ref-1\\" class=\\"reference\\" style=\\"line-height: 1; unicode-bidi: -webkit-isolate;\\"><a href=\\"http://en.wikipedia.org/wiki/Ayer_Hitam#cite_note-1\\" style=\\"color: rgb(11, 0, 128); background-image: none; white-space: nowrap;\\">[1]</a></sup>&nbsp;simply means Black Water. A lively town, Ayer Hitam is always bustling with passing vehicles and people who travels north and south. This place is well known for its&nbsp;<a href=\\"http://en.wikipedia.org/wiki/Ceramic\\" title=\\"Ceramic\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">ceramic</a>&nbsp;items such as flower vases in an assortment of colours, photo frames, jars, ashtrays, and other home decorative items. For a closer look, you can also watch the potters at work. Aside from quality souvenirs, Ayer Hitam is also dotted with many stalls selling local tidbits. Amongst the famous ones are prawn crackers, steamed corn, tapioca chips, and the all-time must-try â€œotak-otakâ€. These food items are fresh and prepacked for you, and sold at reasonable prices.</p></div>";s:15:"pageUpdatedDate";s:19:"2014-04-24 23:11:07";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-24 23:11:07', 123, NULL, NULL);
INSERT INTO `site_request` (`siteRequestID`, `siteRequestType`, `siteID`, `siteRequestRefID`, `siteRequestData`, `siteRequestStatus`, `siteRequestCreatedDate`, `siteRequestCreatedUser`, `siteRequestUpdatedDate`, `siteRequestUpdatedUser`) VALUES
(13, 2, 62, 184, 'a:3:{s:8:"pageText";s:9753:"<b style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Ayer Hitam</b><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">&nbsp;(</span><a href=\\"http://en.wikipedia.org/wiki/Chinese_language\\" title=\\"Chinese language\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Chinese</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">:&nbsp;</span><span lang=\\"zh\\" xml:lang=\\"zh\\" style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">äºšä¾æ·¡</span><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">) is a rest town&nbsp;</span><i style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">(Bandar persinggahan)</i><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">&nbsp;in&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Johor\\" title=\\"Johor\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Johor</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">,&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Malaysia\\" title=\\"Malaysia\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Malaysia</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">. Located just at the junction of route&nbsp;</span><img alt=\\"1\\" src=\\"http://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Jkr-ft1.png/30px-Jkr-ft1.png\\" width=\\"30\\" height=\\"23\\" data-file-width=\\"400\\" data-file-height=\\"300\\" style=\\"border-style: none; margin: 0px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\"><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">&nbsp;and route&nbsp;</span><img alt=\\"50\\" src=\\"http://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Jkr-ft50.png/30px-Jkr-ft50.png\\" width=\\"30\\" height=\\"23\\" srcset=\\"//upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Jkr-ft50.png/45px-Jkr-ft50.png 1.5x, //upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Jkr-ft50.png/60px-Jkr-ft50.png 2x\\" data-file-width=\\"400\\" data-file-height=\\"300\\" style=\\"border-style: none; margin: 0px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\"><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">, it is known for its many outlets selling pottery and other crafts. It also is one of the interchange for&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/North-South_Expressway,_Malaysia\\" title=\\"North-South Expressway, Malaysia\\" class=\\"mw-redirect\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">North-South Expressway</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">. It is the border town between Batu Pahat and Kluang district. Approximately 32&nbsp;km away from&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Batu_Pahat_(city)\\" title=\\"Batu Pahat (city)\\" class=\\"mw-redirect\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Bandar Penggaram,Batu Pahat</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">, capital of&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Batu_Pahat\\" title=\\"Batu Pahat\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Batu Pahat</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">&nbsp;district and 20&nbsp;km away from&nbsp;</span><a href=\\"http://en.wikipedia.org/wiki/Kluang\\" title=\\"Kluang\\" style=\\"color: rgb(11, 0, 128); background-image: none; background-color: rgb(255, 255, 255); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">Kluang</a><span style=\\"color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px; line-height: 22.399999618530273px;\\">.</span><div><h2 style=\\"color: black; background-image: none; margin: 1em 0px 0.25em; overflow: hidden; padding: 0px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(170, 170, 170); font-size: 1.5em; font-family: \\''Linux Libertine\\'', Georgia, Times, serif; line-height: 1.3;\\"><span class=\\"mw-headline\\" id=\\"History\\">History</span><span class=\\"mw-editsection\\" style=\\"-webkit-user-select: none; font-size: small; margin-left: 1em; vertical-align: baseline; line-height: 1em; display: inline-block; white-space: nowrap; padding-right: 0.25em; unicode-bidi: -webkit-isolate; font-family: sans-serif;\\"><span class=\\"mw-editsection-bracket\\">[</span><a href=\\"http://en.wikipedia.org/w/index.php?title=Ayer_Hitam&amp;action=edit&amp;section=1\\" title=\\"Edit section: History\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">edit</a><span class=\\"mw-editsection-bracket\\">]</span></span></h2><p style=\\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.399999618530273px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\\">Ayer Hitam<sup id=\\"cite_ref-1\\" class=\\"reference\\" style=\\"line-height: 1; unicode-bidi: -webkit-isolate;\\"><a href=\\"http://en.wikipedia.org/wiki/Ayer_Hitam#cite_note-1\\" style=\\"color: rgb(11, 0, 128); background-image: none; white-space: nowrap;\\">[1]</a></sup>&nbsp;simply means Black Water. A lively town, Ayer Hitam is always bustling with passing vehicles and people who travels north and south. This place is well known for its&nbsp;<a href=\\"http://en.wikipedia.org/wiki/Ceramic\\" title=\\"Ceramic\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">ceramic</a>&nbsp;items such as flower vases in an assortment of colours, photo frames, jars, ashtrays, and other home decorative items. For a closer look, you can also watch the potters at work. Aside from quality souvenirs, Ayer Hitam is also dotted with many stalls selling local tidbits. Amongst the famous ones are prawn crackers, steamed corn, tapioca chips, and the all-time must-try â€œotak-otakâ€. These food items are fresh and prepacked for you, and sold at reasonable prices.<br></p><p style=\\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.399999618530273px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\\">Located in the district of&nbsp;<a href=\\"http://en.wikipedia.org/wiki/Batu_Pahat\\" title=\\"Batu Pahat\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">Batu Pahat</a>. Before the advent of the&nbsp;<a href=\\"http://en.wikipedia.org/wiki/North%E2%80%93South_Expressway_(Malaysia)\\" title=\\"Northâ€“South Expressway (Malaysia)\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">North-South Expressway</a>, Air Hitam was a major route intersection leading to&nbsp;<a href=\\"http://en.wikipedia.org/wiki/Malacca\\" title=\\"Malacca\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">Malacca</a>&nbsp;and&nbsp;<a href=\\"http://en.wikipedia.org/wiki/Kuala_Lumpur\\" title=\\"Kuala Lumpur\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">Kuala Lumpur</a>&nbsp;going northbound,&nbsp;<a href=\\"http://en.wikipedia.org/wiki/Johor_Bahru\\" title=\\"Johor Bahru\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">Johor Bahru</a>&nbsp;and&nbsp;<a href=\\"http://en.wikipedia.org/wiki/Singapore\\" title=\\"Singapore\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">Singapore</a>going southbound, and&nbsp;<a href=\\"http://en.wikipedia.org/wiki/Kluang\\" title=\\"Kluang\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">Kluang</a>&nbsp;and&nbsp;<a href=\\"http://en.wikipedia.org/wiki/Mersing\\" title=\\"Mersing\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">Mersing</a>&nbsp;going eastbound. It was a popular rest stop for many tour buses and travellers between Singapore and Kuala Lumpur. Visitors could find souvenir shops, restaurants and locals peddling to sell their vegetables.</p><p style=\\"margin-top: 0.5em; margin-bottom: 0.5em; line-height: 22.399999618530273px; color: rgb(37, 37, 37); font-family: sans-serif; font-size: 14px;\\">The&nbsp;<a href=\\"http://en.wikipedia.org/wiki/North%E2%80%93South_Expressway_Southern_Route\\" title=\\"Northâ€“South Expressway Southern Route\\" style=\\"color: rgb(11, 0, 128); background-image: none;\\">E2 Expressway</a>&nbsp;between Ayer Hitam and Yong Peng flyover is reputed to be haunted. Drivers would feel drowsiness and uneasiness as they drive through the area at night especially when there are no other vehicles nearby and accident rates around the area are much higher than usual, despite the road lacking any high-prone accident bends.</p></div>";s:15:"pageUpdatedDate";s:19:"2014-04-25 02:36:13";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-25 02:36:13', 123, NULL, NULL),
(14, 2, 62, 184, 'a:3:{s:8:"pageText";s:1544:"<font color=\\"#252525\\" face=\\"sans-serif\\"><span style=\\"font-size: 14px; line-height: 22.399999618530273px;\\">Ayer-Hitam. Sebuah perkampungan yang menarik. Walaupun penulis tidak pernah ke sana, melalui bacaan wikipedia dan sebagainya. Penulis rasa ia satu tempat yang hebat. Pasti ada sungai, air terjun dan sebagainya. Bagaimanakah sesiapa yang membaca ini, tidak tersedak. Bayangkan sahaja, \\''ayer\\'' dan \\''hitam\\''. Sesiapa sahaja yang mendengarnya pasti akan merasa nostalgik.</span></font><div><font color=\\"#252525\\" face=\\"sans-serif\\"><span style=\\"font-size: 14px; line-height: 22.399999618530273px;\\"><br></span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\"><span style=\\"font-size: 14px; line-height: 22.399999618530273px;\\">Tujuan penulis menulis di sini, bukan sebenarnya sekadar mengisi kekosongan, tetapi untuk berkongsi tulisan yang tidak seberapa ini. Penulis faham, ini tulisan entah apa-apa. Tapi, biarlah ia menjadi tanda tanya, di manakah dan bagaimanakah agaknya rupa ayer hitam. Jadi, untuk mengetahuinya dengan lebih lanjut. Penulis menjemput param pembaca, menjenguk-jenguk laman p1m untuk ayer hitam. Walaupun tiada apa-apa aktiviti lagi, penulis rasa bangga sebab ada laman ini.</span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\"><span style=\\"font-size: 14px; line-height: 22.399999618530273px;\\"><br></span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\"><span style=\\"font-size: 14px; line-height: 22.399999618530273px;\\">1 Malaysia 1 Ayer Hitam!</span></font></div>";s:15:"pageUpdatedDate";s:19:"2014-04-25 04:54:08";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-25 04:54:08', 123, NULL, NULL),
(15, 2, 62, 184, 'a:3:{s:8:"pageText";s:2260:"<font color=\\"#252525\\" face=\\"sans-serif\\"><span style=\\"font-size: 14px; line-height: 22.399999618530273px;\\">Ayer-Hitam. Sebuah perkampungan yang menarik. Walaupun penulis tidak pernah ke sana, melalui bacaan wikipedia dan sebagainya. Penulis rasa ia satu tempat yang hebat. Pasti ada sungai, air terjun dan sebagainya. Bagaimanakah sesiapa yang membaca ini, tidak tersedak. Bayangkan sahaja, \\''ayer\\'' dan \\''hitam\\''. Sesiapa sahaja yang mendengarnya pasti akan merasa nostalgik.</span></font><div><font color=\\"#252525\\" face=\\"sans-serif\\"><span style=\\"font-size: 14px; line-height: 22.399999618530273px;\\"><br></span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\"><span style=\\"font-size: 14px; line-height: 22.399999618530273px;\\">Tujuan penulis menulis di sini, bukan sebenarnya sekadar mengisi kekosongan, tetapi untuk berkongsi tulisan yang tidak seberapa ini. Penulis faham, ini tulisan entah apa-apa. Tapi, biarlah ia menjadi tanda tanya, di manakah dan bagaimanakah agaknya rupa ayer hitam. Jadi, untuk mengetahuinya dengan lebih lanjut. Penulis menjemput param pembaca, menjenguk-jenguk laman p1m untuk ayer hitam. Walaupun tiada apa-apa aktiviti lagi, penulis rasa bangga sebab ada laman ini.</span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\"><span style=\\"font-size: 14px; line-height: 22.399999618530273px;\\"><br></span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\"><span style=\\"font-size: 14px; line-height: 22.399999618530273px;\\">1 Malaysia 1 Ayer Hitam!</span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\"><span style=\\"font-size: 14px; line-height: 22.399999618530273px;\\"><br></span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\"><span style=\\"font-size: 14px; line-height: 22.399999618530273px;\\">Eh, masih belum 200 patah perkataan lagi. Di sebabkan itu, penulis perlu menulis dengan lebih lanjut lagi tentang Ayer-Hitam. Tapi mungkin para pembaca tidak lagi berminat. Bukan apa, penulis takut peminat ayer-hitam merasa bosan, lalu meninggalkan laman ini. Untuk pengetahuan anda, kelak akan ada modul-modul yang menarik, seperti, members, aktiviti, dan sebagainya, penulis harap, para peminat boleh menunggu sehingga ke tarikh itu ya.</span></font></div>";s:15:"pageUpdatedDate";s:19:"2014-04-25 04:58:55";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-25 04:58:55', 123, NULL, NULL),
(16, 2, 62, 184, 'a:3:{s:8:"pageText";s:2218:"<font color=\\"#252525\\" face=\\"sans-serif\\" size=\\"2\\"><span style=\\"line-height: 22.399999618530273px;\\">Ayer-Hitam. Sebuah perkampungan yang menarik. Walaupun penulis tidak pernah ke sana, melalui bacaan wikipedia dan sebagainya. Penulis rasa ia satu tempat yang hebat. Pasti ada sungai, air terjun dan sebagainya. Bagaimanakah sesiapa yang membaca ini, tidak tersedak. Bayangkan sahaja, \\''ayer\\'' dan \\''hitam\\''. Sesiapa sahaja yang mendengarnya pasti akan merasa nostalgik.</span></font><div><font color=\\"#252525\\" face=\\"sans-serif\\" size=\\"2\\"><span style=\\"line-height: 22.399999618530273px;\\"><br></span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\" size=\\"2\\"><span style=\\"line-height: 22.399999618530273px;\\">Tujuan penulis menulis di sini, bukan sebenarnya sekadar mengisi kekosongan, tetapi untuk berkongsi tulisan yang tidak seberapa ini. Penulis faham, ini tulisan entah apa-apa. Tapi, biarlah ia menjadi tanda tanya, di manakah dan bagaimanakah agaknya rupa ayer hitam. Jadi, untuk mengetahuinya dengan lebih lanjut. Penulis menjemput param pembaca, menjenguk-jenguk laman p1m untuk ayer hitam. Walaupun tiada apa-apa aktiviti lagi, penulis rasa bangga sebab ada laman ini.</span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\" size=\\"2\\"><span style=\\"line-height: 22.399999618530273px;\\"><br></span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\" size=\\"2\\"><span style=\\"line-height: 22.399999618530273px;\\">1 Malaysia 1 Ayer Hitam!</span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\" size=\\"2\\"><span style=\\"line-height: 22.399999618530273px;\\"><br></span></font></div><div><font color=\\"#252525\\" face=\\"sans-serif\\" size=\\"2\\"><span style=\\"line-height: 22.399999618530273px;\\">Eh, masih belum 200 patah perkataan lagi. Di sebabkan itu, penulis perlu menulis dengan lebih lanjut lagi tentang Ayer-Hitam. Tapi mungkin para pembaca tidak lagi berminat. Bukan apa, penulis takut peminat ayer-hitam merasa bosan, lalu meninggalkan laman ini. Untuk pengetahuan anda, kelak akan ada modul-modul yang menarik, seperti, members, aktiviti, dan sebagainya, penulis harap, para peminat boleh menunggu sehingga ke tarikh itu ya.</span></font></div>";s:15:"pageUpdatedDate";s:19:"2014-04-25 05:01:09";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-25 05:01:09', 123, NULL, NULL),
(17, 2, 62, 184, 'a:3:{s:8:"pageText";s:1389:"<div>Ayer-Hitam. Sebuah perkampungan yang menarik. Walaupun penulis tidak pernah ke sana, melalui bacaan wikipedia dan sebagainya. Penulis rasa ia satu tempat yang hebat. Pasti ada sungai, air terjun dan sebagainya. Bagaimanakah sesiapa yang membaca ini, tidak tersedak. Bayangkan sahaja, \\''ayer\\'' dan \\''hitam\\''. Sesiapa sahaja yang mendengarnya pasti akan merasa nostalgik.</div><div><br></div><div>Tujuan penulis menulis di sini, bukan sebenarnya sekadar mengisi kekosongan, tetapi untuk berkongsi tulisan yang tidak seberapa ini. Penulis faham, ini tulisan entah apa-apa. Tapi, biarlah ia menjadi tanda tanya, di manakah dan bagaimanakah agaknya rupa ayer hitam. Jadi, untuk mengetahuinya dengan lebih lanjut. Penulis menjemput param pembaca, menjenguk-jenguk laman p1m untuk ayer hitam. Walaupun tiada apa-apa aktiviti lagi, penulis rasa bangga sebab ada laman ini.</div><div><br></div><div>1 Malaysia 1 Ayer Hitam!</div><div><br></div><div>Eh, masih belum 200 patah perkataan lagi. Di sebabkan itu, penulis perlu menulis dengan lebih lanjut lagi tentang Ayer-Hitam. Tapi mungkin para pembaca tidak lagi berminat. Bukan apa, penulis takut peminat ayer-hitam merasa bosan, lalu meninggalkan laman ini. Untuk pengetahuan anda, kelak akan ada modul-modul yang menarik, seperti, members, aktiviti, dan sebagainya, penulis harap, para peminat boleh menunggu sehingga ke tarikh itu ya.</div>";s:15:"pageUpdatedDate";s:19:"2014-04-25 05:06:03";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-25 05:06:03', 123, NULL, NULL),
(18, 2, 62, 186, 'a:3:{s:8:"pageText";s:3092:"What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????<div><br></div><div>What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????<span style=\\"&quot;font-size:\\" 0.9em;=\\"\\" line-height:=\\"\\" 1.42857143;\\"=\\"\\">What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????</span><br></div><div><span style=\\"&quot;font-size:\\" 0.9em;=\\"\\" line-height:=\\"\\" 1.42857143;\\"=\\"\\"><br></span></div><div>What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????<span style=\\"&quot;font-size:\\" 0.9em;=\\"\\" line-height:=\\"\\" 1.42857143;\\"=\\"\\">What is actually happening why i cannot replace!!!?????&nbsp;What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????What is actually happening why i cannot replace!!!?????</span><span style=\\"&quot;font-size:\\" 0.9em;=\\"\\" line-height:=\\"\\" 1.42857143;\\"=\\"\\"><br></span></div>";s:15:"pageUpdatedDate";s:19:"2014-04-25 05:16:42";s:15:"pageUpdatedUser";s:3:"123";}', 1, '2014-04-25 05:16:42', 123, NULL, NULL),
(19, 3, 62, 62, 'a:9:{s:13:"siteInfoPhone";s:0:"";s:11:"siteInfoFax";s:0:"";s:13:"siteInfoEmail";s:0:"";s:18:"siteInfoTwitterUrl";s:0:"";s:19:"siteInfoFacebookUrl";s:0:"";s:16:"siteInfoLatitude";s:13:"3.17976678131";s:17:"siteInfoLongitude";s:13:"101.364841461";s:15:"siteInfoAddress";s:0:"";s:19:"siteInfoDescription";s:66:"\r\n	          \r\n	          \r\n	          	        	        	        ";}', 1, '2014-04-25 05:20:56', 123, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `site_slider`
--

CREATE TABLE IF NOT EXISTS `site_slider` (
  `siteSliderID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `siteSliderType` int(11) DEFAULT NULL,
  `siteSliderName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `siteSliderImage` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `siteSliderLink` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `siteSliderTarget` int(11) DEFAULT NULL,
  `siteSliderStatus` int(11) DEFAULT NULL,
  `siteSliderCreatedDate` datetime DEFAULT NULL,
  `siteSliderCreatedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`siteSliderID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `site_slider`
--

INSERT INTO `site_slider` (`siteSliderID`, `siteID`, `siteSliderType`, `siteSliderName`, `siteSliderImage`, `siteSliderLink`, `siteSliderTarget`, `siteSliderStatus`, `siteSliderCreatedDate`, `siteSliderCreatedUser`) VALUES
(1, 0, 2, 'Program Pusat Internet 1 Malaysia Untuk Rakyat Termiskin di Bandar', '1398358685.jpg', 'http://google.com.my', 1, 1, '2014-04-24 11:58:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE IF NOT EXISTS `state` (
  `stateID` int(11) NOT NULL AUTO_INCREMENT,
  `stateName` varchar(100) DEFAULT NULL,
  `stateCode` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`stateID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

CREATE TABLE IF NOT EXISTS `training` (
  `trainingID` int(11) NOT NULL AUTO_INCREMENT,
  `activityID` int(11) DEFAULT NULL,
  `trainingName` varchar(100) DEFAULT NULL,
  `trainingType` int(11) DEFAULT NULL,
  `trainingMaxPax` int(11) DEFAULT NULL,
  `trainingTest` int(11) DEFAULT NULL,
  PRIMARY KEY (`trainingID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userIC` varchar(100) DEFAULT NULL,
  `userPassword` varchar(100) DEFAULT NULL,
  `userEmail` varchar(100) DEFAULT NULL,
  `userLevel` varchar(100) DEFAULT NULL,
  `userStatus` int(11) DEFAULT NULL,
  `userPremiumStatus` int(11) DEFAULT NULL,
  `userCreatedDate` datetime DEFAULT NULL,
  `userCreatedUser` int(11) DEFAULT NULL,
  `userTest` int(11) DEFAULT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=172 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `userIC`, `userPassword`, `userEmail`, `userLevel`, `userStatus`, `userPremiumStatus`, `userCreatedDate`, `userCreatedUser`, `userTest`) VALUES
(1, '890910105117', '827ccb0eea8a706c4c34a16891f84e7b', 'root@gmail.com', '99', 1, 1, NULL, NULL, NULL),
(2, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'mary@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(3, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'junainah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(4, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'sahriati@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(5, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'shafie@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(6, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'rozaime@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(7, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'magdalina@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(8, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'salma@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(9, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'halbi@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(10, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'noraziera@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(11, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'jokley@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(12, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'conchita@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(13, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'adrian@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(14, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'azizan@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(15, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'hariz@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(16, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'jasinta@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(17, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'arbaya@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(18, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' hillyin@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(19, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'emily@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(20, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' missda@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(21, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'diandra@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(22, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' fred.asoon@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(23, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'zurinah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(24, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'noraini@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(25, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'audrey@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(26, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' farah_suzianah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(27, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'rosfadhilah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(28, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'alfred@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(29, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'elvysia@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(30, NULL, '27f1807b157919c8bbbfbbc0cc54f208', '', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(31, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'zuhair@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(32, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' kamilie@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(33, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'farah.izzah@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(34, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' afreeza@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(35, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'asmidah@celcom1cbc.com  ', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(36, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' frederic@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(37, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'sartini@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(38, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' stanley@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:09', 1, NULL),
(39, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'rosarnie@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(40, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'samsiah@celcom1cbc.com  ', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(41, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'sulaiman@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(42, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'azizul@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(43, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'kasmidah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(44, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'azman@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(45, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'juleah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(46, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'mardiyana@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(47, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'hamiatie@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(48, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'anni@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(49, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'farah_liyana@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(50, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'yusuf@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(51, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'adilah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(52, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'munawara@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(53, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'freddy@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(54, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'scrivencer@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(55, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'roldy@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(56, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'roslinda@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(57, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'suhana@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(58, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'elvison@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(59, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'fithzdowell@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(60, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' javy@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(61, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'farah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(62, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'reck@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(63, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'elisa@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(64, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'maiame@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(65, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'linda@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(66, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'helena@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(67, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'maslizan@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(68, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'florince@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(69, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'fakhri@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(70, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'zubinah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(71, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'marini@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(72, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'romansah@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(73, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'badriah@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(74, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' seha@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:13', 1, NULL),
(75, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'tony.mondorusun@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(76, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' mellisa@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(77, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'boby@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(78, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'hamlina@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(79, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'suriati@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(80, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'rosmawati@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(81, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'linah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(82, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'naimie@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(83, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'effle@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(84, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'salfarina@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(85, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'nazarul@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(86, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'rahayu@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(87, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'afiqah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(88, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'juziana@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(89, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'jusry@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(90, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'zamadiani@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(91, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'deargold@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(92, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'norieqram@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(93, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'norain@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(94, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'hanim@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(95, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'petronilla@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(96, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'wili@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(97, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'nordiah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(98, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'norsalasiah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(99, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'beatrice@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(100, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'marcellus@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(101, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'lebrien@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(102, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'octevia@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(103, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'zuraidah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(104, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'nedy@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(105, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'debbie@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(106, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'celyn@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(107, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'roziatul@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(108, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' nenly@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(109, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'malania@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(110, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' asmih@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(111, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'lovelone@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(112, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' farhana@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(113, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'irni@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(114, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'yassir@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:16', 1, NULL),
(115, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'oliver@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:20', 1, NULL),
(116, NULL, '27f1807b157919c8bbbfbbc0cc54f208', ' mary.jembun@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:20', 1, NULL),
(117, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'barbara@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:20', 1, NULL),
(118, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'hasyimah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:20', 1, NULL),
(119, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'sima@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:20', 1, NULL),
(120, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'david@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:20', 1, NULL),
(121, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'arnida@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(122, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'suhaimee@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(123, NULL, '827ccb0eea8a706c4c34a16891f84e7b', 'adam@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(124, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'noraliza@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(125, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'sharzakila@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(126, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'noorlida@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(127, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'sitihafiza@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(128, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'amirah@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(129, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'syakir@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(130, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'norazlan.pandi@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(131, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'nurulakmar@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(132, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'norhayati@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(133, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'anuarezman@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(134, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'atiqah@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(135, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'nurulmaliessa@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(136, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'jannah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(137, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'khirominshah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(138, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'syafiqah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(139, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'nadia@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(140, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'edham@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(141, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'mazliza@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(142, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'safiah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(143, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'raziff@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(144, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'nazrin.manap@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:31', 1, NULL),
(145, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'fauziah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:34', 1, NULL),
(146, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'sitiamirah@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:34', 1, NULL),
(147, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'rasyadan@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:39', 1, NULL),
(148, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'nabilah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:39', 1, NULL),
(149, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'aminur@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:39', 1, NULL),
(150, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'norizzati.manaf@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:43', 1, NULL),
(151, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'norhasliza@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:43', 1, NULL),
(152, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'hasanatul@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:43', 1, NULL),
(153, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'siti.halijah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:43', 1, NULL),
(154, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'nurizayanty@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:43', 1, NULL),
(155, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'fiqahyussof@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:43', 1, NULL),
(156, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'izzattie@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:43', 1, NULL),
(157, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'firdaus@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:43', 1, NULL),
(158, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'norizzati@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:43', 1, NULL),
(159, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'radhiah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:43', 1, NULL),
(160, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'azizah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:43', 1, NULL),
(161, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'shuhadah@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:43', 1, NULL),
(162, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'zakiah@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:46', 1, NULL),
(163, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'zahir@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:46', 1, NULL),
(164, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'erma@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:46', 1, NULL),
(165, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'hairil@celcom1cbc.com ', '2', 1, 0, '2014-04-24 11:07:46', 1, NULL),
(166, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'azwary@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:46', 1, NULL),
(167, NULL, '27f1807b157919c8bbbfbbc0cc54f208', 'firdus@celcom1cbc.com', '2', 1, 0, '2014-04-24 11:07:46', 1, NULL),
(168, '12345', '827ccb0eea8a706c4c34a16891f84e7b', 'shamsul@nusuara.com', '3', 1, 0, '2014-04-24 12:00:05', 1, NULL),
(169, '123456', '827ccb0eea8a706c4c34a16891f84e7b', 'fitriyanie@nusuara.com', '3', 1, 0, '2014-04-24 12:01:18', 1, NULL),
(170, '1234567', '827ccb0eea8a706c4c34a16891f84e7b', 'yusrizal@nusuara.com', '3', 1, 0, '2014-04-24 12:01:59', 1, NULL),
(171, '12341', '827ccb0eea8a706c4c34a16891f84e7b', 'sabarinus@nusuara.com', '3', 1, 0, '2014-04-24 12:02:52', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE IF NOT EXISTS `user_activity` (
  `userActivityID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `userActivityTypeCode` varchar(5) DEFAULT NULL,
  `userActivityParameter` varchar(100) DEFAULT NULL,
  `userActivityRefID` int(11) DEFAULT NULL,
  `userActivityCreatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`userActivityID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_type`
--

CREATE TABLE IF NOT EXISTS `user_activity_type` (
  `userActivityTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `userActivityTypeCode` varchar(5) DEFAULT NULL,
  `userActivityTypeMessage` varchar(255) DEFAULT NULL,
  `userActivityTypeCategory` int(11) DEFAULT NULL,
  PRIMARY KEY (`userActivityTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_premium`
--

CREATE TABLE IF NOT EXISTS `user_premium` (
  `userPremiumID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `userPremiumPayment` float DEFAULT NULL,
  `userPremiumCreatedDate` datetime DEFAULT NULL,
  `userPremiumCreatedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`userPremiumID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE IF NOT EXISTS `user_profile` (
  `userProfileID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `userProfileTitle` int(11) DEFAULT NULL,
  `userProfileFullName` varchar(255) DEFAULT NULL,
  `userProfilePhoneNo` varchar(100) DEFAULT NULL,
  `userProfileGender` int(11) DEFAULT NULL,
  `userProfilePOB` varchar(100) DEFAULT NULL,
  `userProfileMarital` int(11) DEFAULT NULL,
  `userProfileDOB` date DEFAULT NULL,
  `userProfileMobileNo` varchar(100) DEFAULT NULL,
  `userProfileMailingAddress` text,
  PRIMARY KEY (`userProfileID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=172 ;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`userProfileID`, `userID`, `userProfileTitle`, `userProfileFullName`, `userProfilePhoneNo`, `userProfileGender`, `userProfilePOB`, `userProfileMarital`, `userProfileDOB`, `userProfileMobileNo`, `userProfileMailingAddress`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, NULL, 'Mary Binti Tolong Wong ', '016-8158869 ', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, NULL, 'Junainah Juhari', '013-8969676', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, NULL, 'Sahriati Binti Yusuf', '016-5873741', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 5, NULL, 'Mohd Shafie Bin Jamri', '019-8425762', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 6, NULL, 'Mimi Liyana Aqilah', '010-9375603', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 7, NULL, 'Rozaime Binti Abd Lahap', '019-8521928', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 8, NULL, 'Salma Binti Kamis', '013-5436891', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 9, NULL, 'Mohd Halbi Bin Abd Hassan', '010-9531422', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 10, NULL, 'Noraziera Binti Mohd Puting', '014-8530575', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 11, NULL, 'Jokley Joseph', '019-8810883', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 12, NULL, 'Conchita David Ellih', '016-8357434', NULL, NULL, NULL, NULL, NULL, NULL),
(13, 13, NULL, 'Adrian Eric', '010-9557646', NULL, NULL, NULL, NULL, NULL, NULL),
(14, 14, NULL, 'Azizan Bin Mohd Daud', '014-6538170', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 15, NULL, 'Mohd Hariz Najiy Bin Omar', '013-7928859', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 16, NULL, 'Jasinta Anne Bte Titin', '016-8180755', NULL, NULL, NULL, NULL, NULL, NULL),
(17, 17, NULL, 'Arbaya Binti Angin', '013-8523047', NULL, NULL, NULL, NULL, NULL, NULL),
(18, 18, NULL, 'Hillyin Binti Jainol', ' 019-8727986', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 19, NULL, 'Emily Makaji', '014-6565404', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 20, NULL, 'Missda Binti Lariah', '019-2052875', NULL, NULL, NULL, NULL, NULL, NULL),
(21, 21, NULL, 'Diandra Sandra Dionysius Mingkong', '016-8112869 ', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 22, NULL, 'Fred Joane Asoon', ' 013-5508845', NULL, NULL, NULL, NULL, NULL, NULL),
(23, 23, NULL, 'Zurinah Binti Zulkepli', '013-5425892', NULL, NULL, NULL, NULL, NULL, NULL),
(24, 24, NULL, 'Noraini Binti Mat Amin', '013-5824482', NULL, NULL, NULL, NULL, NULL, NULL),
(25, 25, NULL, 'Audrey Diane L. Andrew', '016-8117475 ', NULL, NULL, NULL, NULL, NULL, NULL),
(26, 26, NULL, 'Farah Suzianah Asagena', ' 014-6533842', NULL, NULL, NULL, NULL, NULL, NULL),
(27, 27, NULL, 'Rosfadhilah Binti Ag Jaludin', '0112-9964756', NULL, NULL, NULL, NULL, NULL, NULL),
(28, 28, NULL, 'Alfred Bin Butili', '010-5829786', NULL, NULL, NULL, NULL, NULL, NULL),
(29, 29, NULL, 'Elvysia George', '013-8785913 ', NULL, NULL, NULL, NULL, NULL, NULL),
(30, 30, NULL, 'Vacant', ' 019-8715319', NULL, NULL, NULL, NULL, NULL, NULL),
(31, 31, NULL, 'Zuhair Calvin Ng Mohd Firdaus', '019-2302701 ', NULL, NULL, NULL, NULL, NULL, NULL),
(32, 32, NULL, 'Mohd Kamilie Bin Badru', ' 013-5476176 ', NULL, NULL, NULL, NULL, NULL, NULL),
(33, 33, NULL, 'Vacant', '019-8972563 ', NULL, NULL, NULL, NULL, NULL, NULL),
(34, 34, NULL, 'Siti Afreeza Binti Ahmad', ' 019-8934456   ', NULL, NULL, NULL, NULL, NULL, NULL),
(35, 35, NULL, 'Asmidah binti Kasim', '014-6812818 ', NULL, NULL, NULL, NULL, NULL, NULL),
(36, 36, NULL, 'Frederic Michael Obih', ' 013-3476072', NULL, NULL, NULL, NULL, NULL, NULL),
(37, 37, NULL, 'Sartini Binti Jamil', '017-8284167 ', NULL, NULL, NULL, NULL, NULL, NULL),
(38, 38, NULL, 'Stanley Stephen', ' 019-8715319   ', NULL, NULL, NULL, NULL, NULL, NULL),
(39, 39, NULL, 'Rosarnie Affrinie Binti Amit', '016-5872897 ', NULL, NULL, NULL, NULL, NULL, NULL),
(40, 40, NULL, 'Samsiah Binti Lauming', '013-8363767', NULL, NULL, NULL, NULL, NULL, NULL),
(41, 41, NULL, 'Sulaiman bin Sanusi', '010-5886707', NULL, NULL, NULL, NULL, NULL, NULL),
(42, 42, NULL, 'Azizul Bin Karamdin', '013-8886532', NULL, NULL, NULL, NULL, NULL, NULL),
(43, 43, NULL, 'Kasmidah binti Madami', '016-2958051', NULL, NULL, NULL, NULL, NULL, NULL),
(44, 44, NULL, 'Azman bin Alih', '016-8418238', NULL, NULL, NULL, NULL, NULL, NULL),
(45, 45, NULL, 'Juleah Binti Rente', '013-5500701', NULL, NULL, NULL, NULL, NULL, NULL),
(46, 46, NULL, 'Mardiyana Sanusi', '014-8662860', NULL, NULL, NULL, NULL, NULL, NULL),
(47, 47, NULL, 'Hamiatie Bt Abd Hamid', '014-6504566', NULL, NULL, NULL, NULL, NULL, NULL),
(48, 48, NULL, 'Anni Binti Lim', '019-8070131', NULL, NULL, NULL, NULL, NULL, NULL),
(49, 49, NULL, 'Farah Liyana Bte Susilo', '010-9318382', NULL, NULL, NULL, NULL, NULL, NULL),
(50, 50, NULL, 'Mohammad Yusuf Bin Miru', '013-8006958', NULL, NULL, NULL, NULL, NULL, NULL),
(51, 51, NULL, 'Noor Adilah Bt Farid', '014-8738829', NULL, NULL, NULL, NULL, NULL, NULL),
(52, 52, NULL, 'Munawara Binti Mohamad Nor', '014-8664074', NULL, NULL, NULL, NULL, NULL, NULL),
(53, 53, NULL, 'Freddy Lee', '013-5505633', NULL, NULL, NULL, NULL, NULL, NULL),
(54, 54, NULL, 'Scrivencer Andrew', '014-8564353', NULL, NULL, NULL, NULL, NULL, NULL),
(55, 55, NULL, 'Roldy Bin Rawan', '013-8704479', NULL, NULL, NULL, NULL, NULL, NULL),
(56, 56, NULL, 'Roslinda Gibak', '013-8536528', NULL, NULL, NULL, NULL, NULL, NULL),
(57, 57, NULL, 'Suhana Binti Nahar', '019-5849027', NULL, NULL, NULL, NULL, NULL, NULL),
(58, 58, NULL, 'Elvison Japari', '013-8788133', NULL, NULL, NULL, NULL, NULL, NULL),
(59, 59, NULL, 'Fithzdowell Sindin', '019-8418221 ', NULL, NULL, NULL, NULL, NULL, NULL),
(60, 60, NULL, 'Javy Jimmy', ' 014-8647729', NULL, NULL, NULL, NULL, NULL, NULL),
(61, 61, NULL, 'Farah Bt Cyril Pongod Parantis', '013-8660812', NULL, NULL, NULL, NULL, NULL, NULL),
(62, 62, NULL, 'Reck Teveter Giswa', '019-5081878', NULL, NULL, NULL, NULL, NULL, NULL),
(63, 63, NULL, 'Elisa @ Reyz Samun ', '013-8584213', NULL, NULL, NULL, NULL, NULL, NULL),
(64, 64, NULL, 'Maiame Bin Jaini', '013-8686936', NULL, NULL, NULL, NULL, NULL, NULL),
(65, 65, NULL, 'Lindawati Ejasney', '019-5867074', NULL, NULL, NULL, NULL, NULL, NULL),
(66, 66, NULL, 'Helena Kolurren', '019-5342138', NULL, NULL, NULL, NULL, NULL, NULL),
(67, 67, NULL, 'Maslizan Binti Amir Bak', '013-5566061', NULL, NULL, NULL, NULL, NULL, NULL),
(68, 68, NULL, 'Florince Minsor', '0111-4869038', NULL, NULL, NULL, NULL, NULL, NULL),
(69, 69, NULL, 'Ahmad Fakhri B. Endah', '019-8409033', NULL, NULL, NULL, NULL, NULL, NULL),
(70, 70, NULL, 'Norzubinah Hj. Perdes Nelson', '016-8428817', NULL, NULL, NULL, NULL, NULL, NULL),
(71, 71, NULL, 'Marini Laksamana ', '014-8728192', NULL, NULL, NULL, NULL, NULL, NULL),
(72, 72, NULL, 'Romansah Bin Romainor', '019-5337099', NULL, NULL, NULL, NULL, NULL, NULL),
(73, 73, NULL, 'Nur Badriah Binti Hj. Abd Malik', '013-8717173 ', NULL, NULL, NULL, NULL, NULL, NULL),
(74, 74, NULL, 'Noorseha Binti Mahadi', ' 019-5816305', NULL, NULL, NULL, NULL, NULL, NULL),
(75, 75, NULL, 'Tony S. Mondorusun @ Tony Bin Sarapil', '016-8151928 ', NULL, NULL, NULL, NULL, NULL, NULL),
(76, 76, NULL, 'Mellisa El Denuary (Maternity Leave)', ' 019-8734825', NULL, NULL, NULL, NULL, NULL, NULL),
(77, 77, NULL, 'Boby Addy Nasry Bin Mustapah', '0111-9554901', NULL, NULL, NULL, NULL, NULL, NULL),
(78, 78, NULL, 'Hamlina Binti Tahir', '014-9410569', NULL, NULL, NULL, NULL, NULL, NULL),
(79, 79, NULL, 'Suriati Binti Yusse', '019-8028758', NULL, NULL, NULL, NULL, NULL, NULL),
(80, 80, NULL, 'Rosmawati Binti Hanafiah', '016-8019500', NULL, NULL, NULL, NULL, NULL, NULL),
(81, 81, NULL, 'Linah @ Norlinah Binti Fediles', '019-8020603', NULL, NULL, NULL, NULL, NULL, NULL),
(82, 82, NULL, 'Naimie Binti Ibrahim', '011-19558977', NULL, NULL, NULL, NULL, NULL, NULL),
(83, 83, NULL, 'Effle Bin Jusim', '019-8704940', NULL, NULL, NULL, NULL, NULL, NULL),
(84, 84, NULL, 'Salfarina Binti Linus', '019-8424849', NULL, NULL, NULL, NULL, NULL, NULL),
(85, 85, NULL, 'Nazarul Aziz Bin Zali Wahab', '013-8689264', NULL, NULL, NULL, NULL, NULL, NULL),
(86, 86, NULL, 'Siti Rahayu Kerijin', '0111-9187898', NULL, NULL, NULL, NULL, NULL, NULL),
(87, 87, NULL, 'Nurul Afiqah Binti Mohd Nor Azan', '013-5550169', NULL, NULL, NULL, NULL, NULL, NULL),
(88, 88, NULL, 'Norjuziana Arsa Salleh', '014-8513881', NULL, NULL, NULL, NULL, NULL, NULL),
(89, 89, NULL, 'Jusry Bin Jaafar', '018-8770693', NULL, NULL, NULL, NULL, NULL, NULL),
(90, 90, NULL, 'Zamadiani Binti Amukat', '013-8534554', NULL, NULL, NULL, NULL, NULL, NULL),
(91, 91, NULL, 'Deargold F. Laiwon', '019-2554986', NULL, NULL, NULL, NULL, NULL, NULL),
(92, 92, NULL, 'Mohd Norieqram Bin Wasin', '019-8310792', NULL, NULL, NULL, NULL, NULL, NULL),
(93, 93, NULL, 'Nor''ain binti Salman', '012-8255409', NULL, NULL, NULL, NULL, NULL, NULL),
(94, 94, NULL, 'Noor Hanim Binti Barut Jani', '019-3470885', NULL, NULL, NULL, NULL, NULL, NULL),
(95, 95, NULL, 'Petronilla Barbara Jr binti Chrispine', '013-8902390', NULL, NULL, NULL, NULL, NULL, NULL),
(96, 96, NULL, 'Wili Groda Theodoru Tudo', '019-8698139', NULL, NULL, NULL, NULL, NULL, NULL),
(97, 97, NULL, 'Nordiah Gaat', '019-2487956', NULL, NULL, NULL, NULL, NULL, NULL),
(98, 98, NULL, 'Noor Salasiah Tugino', '019-8322143', NULL, NULL, NULL, NULL, NULL, NULL),
(99, 99, NULL, 'Beatrice Bernadus', '019-5271372', NULL, NULL, NULL, NULL, NULL, NULL),
(100, 100, NULL, 'Marcellus Fread', '019-8429605', NULL, NULL, NULL, NULL, NULL, NULL),
(101, 101, NULL, 'Lebrien bin Marium', '019-5305843', NULL, NULL, NULL, NULL, NULL, NULL),
(102, 102, NULL, 'Octevia Bint Lambiong', '013-8913526', NULL, NULL, NULL, NULL, NULL, NULL),
(103, 103, NULL, 'Siti Zuraidah binti Sulaiman', '019-8201356', NULL, NULL, NULL, NULL, NULL, NULL),
(104, 104, NULL, 'Nedy Judin Banganga', '013-8889215', NULL, NULL, NULL, NULL, NULL, NULL),
(105, 105, NULL, 'Debbie Jean Bt Pilau', '013-8699619', NULL, NULL, NULL, NULL, NULL, NULL),
(106, 106, NULL, 'Celyn Marsilla Binti Mair', '013-3096451', NULL, NULL, NULL, NULL, NULL, NULL),
(107, 107, NULL, 'Roziatul Mona Binti Untuk @ Adnan', '017-8108956 ', NULL, NULL, NULL, NULL, NULL, NULL),
(108, 108, NULL, 'Nenly Marudi', ' 014-8557190', NULL, NULL, NULL, NULL, NULL, NULL),
(109, 109, NULL, 'Malania John', '014-3519600 ', NULL, NULL, NULL, NULL, NULL, NULL),
(110, 110, NULL, 'Asmih Binti Setoh', ' 016-5873458', NULL, NULL, NULL, NULL, NULL, NULL),
(111, 111, NULL, 'Lovelone Juin', '019-5401788 ', NULL, NULL, NULL, NULL, NULL, NULL),
(112, 112, NULL, 'Farhana Khairunnisa Binti Bali', ' 019-8414322', NULL, NULL, NULL, NULL, NULL, NULL),
(113, 113, NULL, 'Irni Mawarni Bt Selamat', '014-8548254 ', NULL, NULL, NULL, NULL, NULL, NULL),
(114, 114, NULL, 'Yassir Bin Apul', ' 019-5800783    ', NULL, NULL, NULL, NULL, NULL, NULL),
(115, 115, NULL, 'Oliver Rabing Anak Layang ', '0111-9181411', NULL, NULL, NULL, NULL, NULL, NULL),
(116, 116, NULL, 'Mary Jembun (effective 12 Feb 2014)', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(117, 117, NULL, 'Barbara Anak Perry', '017-8091943', NULL, NULL, NULL, NULL, NULL, NULL),
(118, 118, NULL, 'Siti Hasyimah Binti Hassan', '013-8092501', NULL, NULL, NULL, NULL, NULL, NULL),
(119, 119, NULL, 'Sima Anak Kayu', '019-8656285', NULL, NULL, NULL, NULL, NULL, NULL),
(120, 120, NULL, 'David Padong Anak Bakar', '017-8526490', NULL, NULL, NULL, NULL, NULL, NULL),
(121, 121, NULL, 'Arnida Mohd Ali', '012-7113495', NULL, NULL, NULL, NULL, NULL, NULL),
(122, 122, NULL, 'Suhaimee Abdullah', '017-7713031', NULL, NULL, NULL, NULL, NULL, NULL),
(123, 123, NULL, 'Mohd Adam b Mahat ', '017-7469232', NULL, NULL, NULL, NULL, NULL, NULL),
(124, 124, NULL, 'Noraliza Binti Samuri', '013-2008216', NULL, NULL, NULL, NULL, NULL, NULL),
(125, 125, NULL, 'Sharzakila Binti Abdullah', '013-6501154', NULL, NULL, NULL, NULL, NULL, NULL),
(126, 126, NULL, 'Noorlida Binti Nordin ', '013-2459001', NULL, NULL, NULL, NULL, NULL, NULL),
(127, 127, NULL, 'Siti Hafiza Nadiah Binti Mat Ladzin', '014-9309767', NULL, NULL, NULL, NULL, NULL, NULL),
(128, 128, NULL, 'Nur Amirah Binti Abdul Wahid', '017-7513245', NULL, NULL, NULL, NULL, NULL, NULL),
(129, 129, NULL, 'Mohd Syakiruddin Bin Omar', '019-3023816', NULL, NULL, NULL, NULL, NULL, NULL),
(130, 130, NULL, 'Norazlan Bin Pandi', '017-7513245', NULL, NULL, NULL, NULL, NULL, NULL),
(131, 131, NULL, 'Nurul Akmar Shazali', '014-2796227', NULL, NULL, NULL, NULL, NULL, NULL),
(132, 132, NULL, 'Norhayati Ahmad', '013-2943422', NULL, NULL, NULL, NULL, NULL, NULL),
(133, 133, NULL, 'Anuarezman Sariff', '019-7773035', NULL, NULL, NULL, NULL, NULL, NULL),
(134, 134, NULL, 'Nur Atiqah Binti Aris', '019-7683662', NULL, NULL, NULL, NULL, NULL, NULL),
(135, 135, NULL, 'Nurul Maliessa Mohd Kamal', '017-7813128', NULL, NULL, NULL, NULL, NULL, NULL),
(136, 136, NULL, 'Nurul Jannah Binti Jamaluddin', '017-7333319', NULL, NULL, NULL, NULL, NULL, NULL),
(137, 137, NULL, 'Khirominshah Mafford', '017-7002405', NULL, NULL, NULL, NULL, NULL, NULL),
(138, 138, NULL, 'Nur Syafiqah binti Aini', '013-7456304', NULL, NULL, NULL, NULL, NULL, NULL),
(139, 139, NULL, 'Umi Nadia Abd Rahman', '019-6811595', NULL, NULL, NULL, NULL, NULL, NULL),
(140, 140, NULL, 'Muhammad Edham Bin Nasir', '017-6702907', NULL, NULL, NULL, NULL, NULL, NULL),
(141, 141, NULL, 'Mazliza Mohd Salleh', '019-7918190', NULL, NULL, NULL, NULL, NULL, NULL),
(142, 142, NULL, 'Safiah Khamis ', '013-7325113', NULL, NULL, NULL, NULL, NULL, NULL),
(143, 143, NULL, 'Mohamad Raziff Bin Abd Azis', '013-2564561', NULL, NULL, NULL, NULL, NULL, NULL),
(144, 144, NULL, 'Mohammad Nazrin bin Abdul Manap', '013-6875689', NULL, NULL, NULL, NULL, NULL, NULL),
(145, 145, NULL, 'Fauziah Bt Mohd Fadzil', '013-5333201', NULL, NULL, NULL, NULL, NULL, NULL),
(146, 146, NULL, 'Siti Amirah Ghazali', '016-5068798', NULL, NULL, NULL, NULL, NULL, NULL),
(147, 147, NULL, 'Mohd Rasyadan Bin Zulkifli', '013-9909440', NULL, NULL, NULL, NULL, NULL, NULL),
(148, 148, NULL, 'Nurul Nabilah Bt. Md Zainol', '012-6410220', NULL, NULL, NULL, NULL, NULL, NULL),
(149, 149, NULL, 'Mohamad Aminur Bin Hasan', '013-6902261', NULL, NULL, NULL, NULL, NULL, NULL),
(150, 150, NULL, 'Norizzati Bt Abd Manaf', '013-6437378', NULL, NULL, NULL, NULL, NULL, NULL),
(151, 151, NULL, 'Norhasliza Ruslan', '012-3896106', NULL, NULL, NULL, NULL, NULL, NULL),
(152, 152, NULL, 'Hasanatul Atiah Jaidin', '012-9950249', NULL, NULL, NULL, NULL, NULL, NULL),
(153, 153, NULL, 'Siti Halijah Binti Sahrum ', '017-6280562', NULL, NULL, NULL, NULL, NULL, NULL),
(154, 154, NULL, 'Nur Izayanty bt Nazar', '017-3590068', NULL, NULL, NULL, NULL, NULL, NULL),
(155, 155, NULL, 'Nor Shafiqah Bt Mohd Yussof', '017-3700062', NULL, NULL, NULL, NULL, NULL, NULL),
(156, 156, NULL, 'Siti Nur Izzattie Bt Noor Haslin', '019-3748683', NULL, NULL, NULL, NULL, NULL, NULL),
(157, 157, NULL, 'Muhammad Firdaus bin Jaafar', '019-3725479', NULL, NULL, NULL, NULL, NULL, NULL),
(158, 158, NULL, 'Norizzati Abd Rashid', '019-7833745', NULL, NULL, NULL, NULL, NULL, NULL),
(159, 159, NULL, 'Siti Radhiah Binti Md Isa', '018-2083682', NULL, NULL, NULL, NULL, NULL, NULL),
(160, 160, NULL, 'Nor Azizah Shamsuddin', '019-6364364', NULL, NULL, NULL, NULL, NULL, NULL),
(161, 161, NULL, 'Nur Shuhadah Binti Zolkapley', '014-3832429', NULL, NULL, NULL, NULL, NULL, NULL),
(162, 162, NULL, 'Zakiah Rejab', '017-4980802', NULL, NULL, NULL, NULL, NULL, NULL),
(163, 163, NULL, 'Muhamad Zahir Bin Musa', '012-5379793', NULL, NULL, NULL, NULL, NULL, NULL),
(164, 164, NULL, 'Nurul Erma Zulkifli ', '012-5539273', NULL, NULL, NULL, NULL, NULL, NULL),
(165, 165, NULL, 'Hairil Bin Hasan - WS', '012-5069896', NULL, NULL, NULL, NULL, NULL, NULL),
(166, 166, NULL, 'Azwary Bin Azmi', '013-5295054', NULL, NULL, NULL, NULL, NULL, NULL),
(167, 167, NULL, 'Ahmad Firdus Mat Ali', '012-4389765', NULL, NULL, NULL, NULL, NULL, NULL),
(168, 168, NULL, 'Shamsul', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(169, 169, NULL, 'Mrs Nurul Fitriyanie Abdullah', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(170, 170, NULL, 'Mr Yusrizal Hussein', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(171, 171, NULL, 'Mr Sabarinus Sekunil', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
