-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2014 at 10:25 AM
-- Server version: 5.6.11
-- PHP Version: 5.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `digitalgaia_iris`
--
CREATE DATABASE IF NOT EXISTS `digitalgaia_iris` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `digitalgaia_iris`;

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
  `clusterName` varchar(100) DEFAULT NULL,
  `clusterCreatedDate` datetime DEFAULT NULL,
  `clusterCreatedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`clusterID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cluster_lead`
--

INSERT INTO `cluster_lead` (`clusterLeadID`, `clusterID`, `userID`, `clusterLeadStatus`, `clusterLeadCreatedDate`, `clusterLeadCreatedUser`) VALUES
(1, 1, 167, 1, '2014-05-14 10:24:25', 1),
(2, 2, 168, 1, '2014-05-14 10:24:43', 1),
(3, 3, 169, 1, '2014-05-14 10:24:50', 1),
(4, 5, 170, 1, '2014-05-14 10:24:55', 1),
(5, 4, 168, 1, '2014-05-14 10:25:06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cluster_site`
--

CREATE TABLE IF NOT EXISTS `cluster_site` (
  `clusterSiteID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `clusterID` int(11) DEFAULT NULL,
  PRIMARY KEY (`clusterSiteID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=85 ;

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
  `contactName` varchar(100) DEFAULT NULL,
  `contactEmail` varchar(100) DEFAULT NULL,
  `contactPhoneNo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`contactID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `messageSubject` varchar(100) DEFAULT NULL,
  `messageContent` text,
  `messageCreatedDate` datetime DEFAULT NULL,
  `messageCreatedUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`messageID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
(1, 1, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:14', NULL, NULL, 1),
(2, 1, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:15', NULL, NULL, 2),
(3, 1, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:15', NULL, NULL, 3),
(4, 2, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:15', NULL, NULL, 1),
(5, 2, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:15', NULL, NULL, 2),
(6, 2, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:15', NULL, NULL, 3),
(7, 3, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:16', NULL, NULL, 1),
(8, 3, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:16', NULL, NULL, 2),
(9, 3, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:16', NULL, NULL, 3),
(10, 4, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:16', NULL, NULL, 1),
(11, 4, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:16', NULL, NULL, 2),
(12, 4, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:16', NULL, NULL, 3),
(13, 5, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:17', NULL, NULL, 1),
(14, 5, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:17', NULL, NULL, 2),
(15, 5, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:17', NULL, NULL, 3),
(16, 6, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:18', NULL, NULL, 1),
(17, 6, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:18', NULL, NULL, 2),
(18, 6, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:18', NULL, NULL, 3),
(19, 7, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:18', NULL, NULL, 1),
(20, 7, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:18', NULL, NULL, 2),
(21, 7, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:18', NULL, NULL, 3),
(22, 8, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:18', NULL, NULL, 1),
(23, 8, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:19', NULL, NULL, 2),
(24, 8, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:19', NULL, NULL, 3),
(25, 9, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:19', NULL, NULL, 1),
(26, 9, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:19', NULL, NULL, 2),
(27, 9, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:19', NULL, NULL, 3),
(28, 10, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:20', NULL, NULL, 1),
(29, 10, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:20', NULL, NULL, 2),
(30, 10, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:20', NULL, NULL, 3),
(31, 11, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:20', NULL, NULL, 1),
(32, 11, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:20', NULL, NULL, 2),
(33, 11, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:20', NULL, NULL, 3),
(34, 12, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:21', NULL, NULL, 1),
(35, 12, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:21', NULL, NULL, 2),
(36, 12, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:21', NULL, NULL, 3),
(37, 13, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:21', NULL, NULL, 1),
(38, 13, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:21', NULL, NULL, 2),
(39, 13, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:21', NULL, NULL, 3),
(40, 14, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:22', NULL, NULL, 1),
(41, 14, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:22', NULL, NULL, 2),
(42, 14, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:22', NULL, NULL, 3),
(43, 15, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:22', NULL, NULL, 1),
(44, 15, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:22', NULL, NULL, 2),
(45, 15, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:22', NULL, NULL, 3),
(46, 16, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:23', NULL, NULL, 1),
(47, 16, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:23', NULL, NULL, 2),
(48, 16, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:23', NULL, NULL, 3),
(49, 17, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:23', NULL, NULL, 1),
(50, 17, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:23', NULL, NULL, 2),
(51, 17, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:23', NULL, NULL, 3),
(52, 18, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:23', NULL, NULL, 1),
(53, 18, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:23', NULL, NULL, 2),
(54, 18, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:24', NULL, NULL, 3),
(55, 19, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:24', NULL, NULL, 1),
(56, 19, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:24', NULL, NULL, 2),
(57, 19, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:24', NULL, NULL, 3),
(58, 20, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:28', NULL, NULL, 1),
(59, 20, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:28', NULL, NULL, 2),
(60, 20, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:28', NULL, NULL, 3),
(61, 21, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:28', NULL, NULL, 1),
(62, 21, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:29', NULL, NULL, 2),
(63, 21, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:29', NULL, NULL, 3),
(64, 22, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:29', NULL, NULL, 1),
(65, 22, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:29', NULL, NULL, 2),
(66, 22, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:29', NULL, NULL, 3),
(67, 23, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:29', NULL, NULL, 1),
(68, 23, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:30', NULL, NULL, 2),
(69, 23, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:30', NULL, NULL, 3),
(70, 24, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:30', NULL, NULL, 1),
(71, 24, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:30', NULL, NULL, 2),
(72, 24, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:30', NULL, NULL, 3),
(73, 25, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:30', NULL, NULL, 1),
(74, 25, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:30', NULL, NULL, 2),
(75, 25, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:30', NULL, NULL, 3),
(76, 26, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:31', NULL, NULL, 1),
(77, 26, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:31', NULL, NULL, 2),
(78, 26, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:31', NULL, NULL, 3),
(79, 27, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:31', NULL, NULL, 1),
(80, 27, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:31', NULL, NULL, 2),
(81, 27, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:31', NULL, NULL, 3),
(82, 28, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:32', NULL, NULL, 1),
(83, 28, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:32', NULL, NULL, 2),
(84, 28, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:32', NULL, NULL, 3),
(85, 29, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:32', NULL, NULL, 1),
(86, 29, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:32', NULL, NULL, 2),
(87, 29, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:32', NULL, NULL, 3),
(88, 30, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:33', NULL, NULL, 1),
(89, 30, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:33', NULL, NULL, 2),
(90, 30, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:33', NULL, NULL, 3),
(91, 31, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:33', NULL, NULL, 1),
(92, 31, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:33', NULL, NULL, 2),
(93, 31, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:33', NULL, NULL, 3),
(94, 32, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:34', NULL, NULL, 1),
(95, 32, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:34', NULL, NULL, 2),
(96, 32, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:34', NULL, NULL, 3),
(97, 33, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:34', NULL, NULL, 1),
(98, 33, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:34', NULL, NULL, 2),
(99, 33, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:34', NULL, NULL, 3),
(100, 34, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:35', NULL, NULL, 1),
(101, 34, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:35', NULL, NULL, 2),
(102, 34, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:35', NULL, NULL, 3),
(103, 35, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:36', NULL, NULL, 1),
(104, 35, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:36', NULL, NULL, 2),
(105, 35, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:36', NULL, NULL, 3),
(106, 36, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:36', NULL, NULL, 1),
(107, 36, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:36', NULL, NULL, 2),
(108, 36, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:36', NULL, NULL, 3),
(109, 37, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:37', NULL, NULL, 1),
(110, 37, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:37', NULL, NULL, 2),
(111, 37, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:37', NULL, NULL, 3),
(112, 38, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:46', NULL, NULL, 1),
(113, 38, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:46', NULL, NULL, 2),
(114, 38, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:46', NULL, NULL, 3),
(115, 39, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:46', NULL, NULL, 1),
(116, 39, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:46', NULL, NULL, 2),
(117, 39, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:46', NULL, NULL, 3),
(118, 40, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:47', NULL, NULL, 1),
(119, 40, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:47', NULL, NULL, 2),
(120, 40, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:47', NULL, NULL, 3),
(121, 41, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:47', NULL, NULL, 1),
(122, 41, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:47', NULL, NULL, 2),
(123, 41, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:48', NULL, NULL, 3),
(124, 42, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:48', NULL, NULL, 1),
(125, 42, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:48', NULL, NULL, 2),
(126, 42, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:48', NULL, NULL, 3),
(127, 43, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:49', NULL, NULL, 1),
(128, 43, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:49', NULL, NULL, 2),
(129, 43, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:49', NULL, NULL, 3),
(130, 44, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:49', NULL, NULL, 1),
(131, 44, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:49', NULL, NULL, 2),
(132, 44, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:50', NULL, NULL, 3),
(133, 45, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:50', NULL, NULL, 1),
(134, 45, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:50', NULL, NULL, 2),
(135, 45, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:50', NULL, NULL, 3),
(136, 46, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:50', NULL, NULL, 1),
(137, 46, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:50', NULL, NULL, 2),
(138, 46, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:50', NULL, NULL, 3),
(139, 47, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:51', NULL, NULL, 1),
(140, 47, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:51', NULL, NULL, 2),
(141, 47, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:51', NULL, NULL, 3),
(142, 48, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:51', NULL, NULL, 1),
(143, 48, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:51', NULL, NULL, 2),
(144, 48, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:51', NULL, NULL, 3),
(145, 49, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:52', NULL, NULL, 1),
(146, 49, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:52', NULL, NULL, 2),
(147, 49, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:52', NULL, NULL, 3),
(148, 50, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:53', NULL, NULL, 1),
(149, 50, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:53', NULL, NULL, 2),
(150, 50, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:53', NULL, NULL, 3),
(151, 51, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:53', NULL, NULL, 1),
(152, 51, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:53', NULL, NULL, 2),
(153, 51, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:53', NULL, NULL, 3),
(154, 52, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:54', NULL, NULL, 1),
(155, 52, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:54', NULL, NULL, 2),
(156, 52, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:54', NULL, NULL, 3),
(157, 53, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:54', NULL, NULL, 1),
(158, 53, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:54', NULL, NULL, 2),
(159, 53, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:54', NULL, NULL, 3),
(160, 54, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:55', NULL, NULL, 1),
(161, 54, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:55', NULL, NULL, 2),
(162, 54, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:55', NULL, NULL, 3),
(163, 55, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:55', NULL, NULL, 1),
(164, 55, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:55', NULL, NULL, 2),
(165, 55, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:55', NULL, NULL, 3),
(166, 56, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:56', NULL, NULL, 1),
(167, 56, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:56', NULL, NULL, 2),
(168, 56, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:56', NULL, NULL, 3),
(169, 57, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:56', NULL, NULL, 1),
(170, 57, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:56', NULL, NULL, 2),
(171, 57, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:37:56', NULL, NULL, 3),
(172, 58, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:44', NULL, NULL, 1),
(173, 58, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:44', NULL, NULL, 2),
(174, 58, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:45', NULL, NULL, 3),
(175, 59, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:45', NULL, NULL, 1),
(176, 59, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:45', NULL, NULL, 2),
(177, 59, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:45', NULL, NULL, 3),
(178, 60, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:45', NULL, NULL, 1),
(179, 60, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:45', NULL, NULL, 2),
(180, 60, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:45', NULL, NULL, 3),
(181, 61, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:51', NULL, NULL, 1),
(182, 61, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:51', NULL, NULL, 2),
(183, 61, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:51', NULL, NULL, 3),
(184, 62, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:52', NULL, NULL, 1),
(185, 62, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:52', NULL, NULL, 2),
(186, 62, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:52', NULL, NULL, 3),
(187, 63, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:53', NULL, NULL, 1),
(188, 63, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:53', NULL, NULL, 2),
(189, 63, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:53', NULL, NULL, 3),
(190, 64, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:54', NULL, NULL, 1),
(191, 64, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:54', NULL, NULL, 2),
(192, 64, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:54', NULL, NULL, 3),
(193, 65, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:54', NULL, NULL, 1),
(194, 65, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:54', NULL, NULL, 2),
(195, 65, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:54', NULL, NULL, 3),
(196, 66, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:55', NULL, NULL, 1),
(197, 66, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:55', NULL, NULL, 2),
(198, 66, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:55', NULL, NULL, 3),
(199, 67, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:55', NULL, NULL, 1),
(200, 67, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:55', NULL, NULL, 2),
(201, 67, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:55', NULL, NULL, 3),
(202, 68, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:56', NULL, NULL, 1),
(203, 68, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:56', NULL, NULL, 2),
(204, 68, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:56', NULL, NULL, 3),
(205, 69, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:56', NULL, NULL, 1),
(206, 69, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:56', NULL, NULL, 2),
(207, 69, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:56', NULL, NULL, 3),
(208, 70, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:57', NULL, NULL, 1),
(209, 70, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:57', NULL, NULL, 2),
(210, 70, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:57', NULL, NULL, 3),
(211, 71, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:57', NULL, NULL, 1),
(212, 71, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:57', NULL, NULL, 2),
(213, 71, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:57', NULL, NULL, 3),
(214, 72, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:58', NULL, NULL, 1),
(215, 72, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:58', NULL, NULL, 2),
(216, 72, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:52:58', NULL, NULL, 3),
(217, 73, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:02', NULL, NULL, 1),
(218, 73, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:02', NULL, NULL, 2),
(219, 73, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:02', NULL, NULL, 3),
(220, 74, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:04', NULL, NULL, 1),
(221, 74, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:04', NULL, NULL, 2),
(222, 74, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:04', NULL, NULL, 3),
(223, 75, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:05', NULL, NULL, 1),
(224, 75, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:05', NULL, NULL, 2),
(225, 75, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:05', NULL, NULL, 3),
(226, 76, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:08', NULL, NULL, 1),
(227, 76, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:08', NULL, NULL, 2),
(228, 76, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:08', NULL, NULL, 3),
(229, 77, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:09', NULL, NULL, 1),
(230, 77, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:09', NULL, NULL, 2),
(231, 77, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:09', NULL, NULL, 3),
(232, 78, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:09', NULL, NULL, 1),
(233, 78, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:09', NULL, NULL, 2),
(234, 78, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:09', NULL, NULL, 3),
(235, 79, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:10', NULL, NULL, 1),
(236, 79, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:10', NULL, NULL, 2),
(237, 79, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:10', NULL, NULL, 3),
(238, 80, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:11', NULL, NULL, 1),
(239, 80, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:11', NULL, NULL, 2),
(240, 80, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:11', NULL, NULL, 3),
(241, 81, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:11', NULL, NULL, 1),
(242, 81, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:11', NULL, NULL, 2),
(243, 81, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:11', NULL, NULL, 3),
(244, 82, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:15', NULL, NULL, 1),
(245, 82, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:15', NULL, NULL, 2),
(246, 82, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:15', NULL, NULL, 3),
(247, 83, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:15', NULL, NULL, 1),
(248, 83, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:15', NULL, NULL, 2),
(249, 83, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:15', NULL, NULL, 3),
(250, 84, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:16', NULL, NULL, 1),
(251, 84, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:16', NULL, NULL, 2),
(252, 84, 1, '', '', 'Page ini masih baru. Sila kemaskini terlebih dahulu', 1, 1, '2014-05-14 09:53:16', NULL, NULL, 3);

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
(3, '3', 'AJK PI1M', 'ajk');

-- --------------------------------------------------------

--
-- Table structure for table `page_photo`
--

CREATE TABLE IF NOT EXISTS `page_photo` (
  `pagePhotoID` int(11) NOT NULL AUTO_INCREMENT,
  `pageID` int(11) DEFAULT NULL,
  `photoID` int(11) DEFAULT NULL,
  PRIMARY KEY (`pagePhotoID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
(1, 12, 'Kg Kopimpinan, Penampang', 'kopimpinan', 1, '2014-05-14 09:37:14', NULL, NULL),
(2, 12, 'Kg Duvanson Ketiau, Putatan', 'duvanson-ketiau', 1, '2014-05-14 09:37:15', NULL, NULL),
(3, 12, 'Kg Kuala, Papar', 'kg-kuala', 1, '2014-05-14 09:37:16', NULL, NULL),
(4, 12, 'Kg Biau, Bongawan Papar', 'biau', 1, '2014-05-14 09:37:16', NULL, NULL),
(5, 12, 'Kg Lawa Kabajang, Beaufort (AM)', 'lawa-kabajang', 1, '2014-05-14 09:37:17', NULL, NULL),
(6, 12, 'Kg Bundu, Kuala Penyu', 'bundu', 1, '2014-05-14 09:37:17', NULL, NULL),
(7, 12, 'Pekan Kuala Penyu', 'kuala-penyu', 1, '2014-05-14 09:37:18', NULL, NULL),
(8, 12, 'Pekan Menumbok', 'menumbok', 1, '2014-05-14 09:37:18', NULL, NULL),
(9, 12, 'Pekan Putera Jaya', 'putera-jaya', 1, '2014-05-14 09:37:19', NULL, NULL),
(10, 12, 'Pekan Babagon', 'babagon', 1, '2014-05-14 09:37:20', NULL, NULL),
(11, 12, 'Inobong (AM)', 'inobong', 1, '2014-05-14 09:37:20', NULL, NULL),
(12, 12, 'Kg Muhibbah, Putatan', 'kg-muhibbah', 1, '2014-05-14 09:37:21', NULL, NULL),
(13, 12, 'Pekan Kg Langkuas', 'langkuas', 1, '2014-05-14 09:37:21', NULL, NULL),
(14, 12, 'Kg Belatik', 'belatik', 1, '2014-05-14 09:37:22', NULL, NULL),
(15, 12, 'Rumah Kebajikan OKU Kimanis', 'kimanis', 1, '2014-05-14 09:37:22', NULL, NULL),
(16, 12, 'Pekan Kg. Bambangan', 'bambangan', 1, '2014-05-14 09:37:22', NULL, NULL),
(17, 12, 'Pekan Kg Lubok', 'kg-lubok', 1, '2014-05-14 09:37:23', NULL, NULL),
(18, 12, 'Kuala Mengalong', 'kuala-mengalong', 1, '2014-05-14 09:37:23', NULL, NULL),
(19, 12, 'Lubok Temiang (AM)', 'lubok-temiang', 1, '2014-05-14 09:37:24', NULL, NULL),
(20, 12, 'Kg Batu Payung, Tawau', 'batu-payung', 1, '2014-05-14 09:37:28', NULL, NULL),
(21, 12, 'Kg Airport, Kunak', 'kg-airport', 1, '2014-05-14 09:37:28', NULL, NULL),
(22, 12, 'Kg Hampilan, Kunak', 'hampilan', 1, '2014-05-14 09:37:29', NULL, NULL),
(23, 12, 'Kg Kadazan, Kunak', 'kg-kadazan', 1, '2014-05-14 09:37:29', NULL, NULL),
(24, 12, 'Kg Lormalong, Kunak', 'lormalong', 1, '2014-05-14 09:37:30', NULL, NULL),
(25, 12, 'Pekan Kunak, Kunak', 'pekan-kunak', 1, '2014-05-14 09:37:30', NULL, NULL),
(26, 12, 'Pekan Tungku, Tungku', 'pekan-tungku', 1, '2014-05-14 09:37:31', NULL, NULL),
(27, 12, 'Pekan Beluran, Beluran', 'pekan-beluran', 1, '2014-05-14 09:37:31', NULL, NULL),
(28, 12, 'Kg Kuala Sapi, Beluran', 'kuala-sapi', 1, '2014-05-14 09:37:32', NULL, NULL),
(29, 12, 'Kg Bintang Mas, Beluran', 'bintang-mas', 1, '2014-05-14 09:37:32', NULL, NULL),
(30, 12, 'Pekan Telupid, Beluran', 'telupid', 1, '2014-05-14 09:37:33', NULL, NULL),
(31, 12, 'Kg Wonod, Beluran', 'kg-wonod', 1, '2014-05-14 09:37:33', NULL, NULL),
(32, 12, 'Kg Linayukan, Tongod', 'linayukan', 1, '2014-05-14 09:37:34', NULL, NULL),
(33, 12, 'Kg Sogo Sogo, Tongod', 'sogo-sogo', 1, '2014-05-14 09:37:34', NULL, NULL),
(34, 12, 'Pekan Tongod, Tongod', 'pekan-tongod', 1, '2014-05-14 09:37:35', NULL, NULL),
(35, 12, 'Kg Balung Cocos, Tawau', 'balung-cocos', 1, '2014-05-14 09:37:36', NULL, NULL),
(36, 12, 'Layung Industrial, Lahad Datu', 'layung-industrial', 1, '2014-05-14 09:37:36', NULL, NULL),
(37, 12, 'Kg Bugaya, Semporna', 'bugaya', 1, '2014-05-14 09:37:37', NULL, NULL),
(38, 12, 'Pejabat Daerah Kudat, Kudat (AM)', 'daerah-kudat', 1, '2014-05-14 09:37:46', NULL, NULL),
(39, 12, 'Kg Tandek, Kota Marudu', 'tandek', 1, '2014-05-14 09:37:46', NULL, NULL),
(40, 12, 'Kg Sg Damit, Tuaran', 'sg-damit', 1, '2014-05-14 09:37:46', NULL, NULL),
(41, 12, 'Kg Malanggang Baru, Kiulu', 'malanggang-baru', 1, '2014-05-14 09:37:47', NULL, NULL),
(42, 12, 'Kg Mesilou, Ranau', 'mesilou', 1, '2014-05-14 09:37:48', NULL, NULL),
(43, 12, 'Kg. Desa Aman, Ranau', 'desa-aman', 1, '2014-05-14 09:37:48', NULL, NULL),
(44, 12, 'Kg Lohan, Ranau', 'lohan', 1, '2014-05-14 09:37:49', NULL, NULL),
(45, 12, 'Kg Bongkud, Ranau', 'bongkud', 1, '2014-05-14 09:37:50', NULL, NULL),
(46, 12, 'Kg Toboh, Tambunan', 'toboh', 1, '2014-05-14 09:37:50', NULL, NULL),
(47, 12, 'Kg Bingkor, Keningau', 'bingkor', 1, '2014-05-14 09:37:51', NULL, NULL),
(48, 12, 'Kg Sook, Keningau', 'sook', 1, '2014-05-14 09:37:51', NULL, NULL),
(49, 12, 'Kg Apin Apin, Keningau', 'apin-apin', 1, '2014-05-14 09:37:52', NULL, NULL),
(50, 12, 'Kg Bunsit, Keningau', 'bunsit', 1, '2014-05-14 09:37:52', NULL, NULL),
(51, 12, 'Taman Sabana, Keningau', 'taman-sabana', 1, '2014-05-14 09:37:53', NULL, NULL),
(52, 12, 'Kg Malima, Keningau', 'malima', 1, '2014-05-14 09:37:54', NULL, NULL),
(53, 12, 'Kg Sawang (AM)', 'sawang', 1, '2014-05-14 09:37:54', NULL, NULL),
(54, 12, 'Kg Suang Punggur', 'suang-punggur', 1, '2014-05-14 09:37:55', NULL, NULL),
(55, 12, 'Pekan Kg Serusop, Tuaran', 'kg-serusop', 1, '2014-05-14 09:37:55', NULL, NULL),
(56, 12, 'Kg Pamilaan', 'pamilaan', 1, '2014-05-14 09:37:56', NULL, NULL),
(57, 12, 'Pekan Rampayan Laut', 'rampayan-laut', 1, '2014-05-14 09:37:56', NULL, NULL),
(58, 13, 'Rumah Benjamin', 'rumah-benjamin', 1, '2014-05-14 09:52:44', NULL, NULL),
(59, 13, 'Kg. Nanga Tada', 'nanga-tada', 1, '2014-05-14 09:52:45', NULL, NULL),
(60, 13, 'Kg. Muhibbah', 'muhibbah-sarawak', 1, '2014-05-14 09:52:45', NULL, NULL),
(61, 1, 'Felda Kahang Barat', 'felda-kahang-barat', 1, '2014-05-14 09:52:51', 1, '2014-05-14 10:04:27'),
(62, 1, 'Felda Ayer Hitam', 'felda-ayer-hitam', 1, '2014-05-14 09:52:52', 1, '2014-05-14 09:56:13'),
(63, 1, 'Felda Kahang Timur', 'felda-kahang-timur', 1, '2014-05-14 09:52:53', 1, '2014-05-14 10:04:37'),
(64, 1, 'Felda Bukit Tongkat', 'felda-bukit-tongkat', 1, '2014-05-14 09:52:53', 1, '2014-05-14 10:03:02'),
(65, 1, 'Felda Palong Timur 1', 'felda-palong-timur-1', 1, '2014-05-14 09:52:54', 1, '2014-05-14 10:05:12'),
(66, 1, 'Felda Palong Timur 3', 'felda-palong-timur-3', 1, '2014-05-14 09:52:54', 1, '2014-05-14 10:05:22'),
(67, 1, 'Felda Bukit Permai', 'felda-bukit-permai', 1, '2014-05-14 09:52:55', 1, '2014-05-14 10:02:39'),
(68, 1, 'Felda Bukit Batu', 'felda-bukit-batu', 1, '2014-05-14 09:52:55', 1, '2014-05-14 09:56:27'),
(69, 1, 'Felda Layang-Layang', 'felda-layang-layang', 1, '2014-05-14 09:52:56', 1, '2014-05-14 10:05:04'),
(70, 1, 'Kg. Parit Hj. Idris', 'kg-parit-hj-idris', 1, '2014-05-14 09:52:57', 1, '2014-05-14 10:07:00'),
(71, 1, 'Felda Inas Utara', 'inas-utara', 1, '2014-05-14 09:52:57', NULL, NULL),
(72, 1, 'Kg. Mensudut Lama', 'kg-mensudut-lama', 1, '2014-05-14 09:52:58', 1, '2014-05-14 10:06:37'),
(73, 2, 'Felda Bukit Tangga', 'felda-bukit-tangga', 1, '2014-05-14 09:53:02', 1, '2014-05-14 10:02:55'),
(74, 14, 'PPR Pantai', 'ppr-pantai', 1, '2014-05-14 09:53:04', NULL, NULL),
(75, 14, 'PPR Kerinchi', 'ppr-kerinchi', 1, '2014-05-14 09:53:05', NULL, NULL),
(76, 5, 'Felda Kepis', 'felda-kepis', 1, '2014-05-14 09:53:08', 1, '2014-05-14 10:04:58'),
(77, 5, 'Felda Jelai 4', 'felda-jelai-4', 1, '2014-05-14 09:53:08', 1, '2014-05-14 10:03:21'),
(78, 5, 'Felda Pasir Besar', 'felda-pasir-besar', 1, '2014-05-14 09:53:09', 1, '2014-05-14 10:05:36'),
(79, 5, 'Felda Sg Kelamah', 'felda-sg-kelamah', 1, '2014-05-14 09:53:10', 1, '2014-05-14 10:05:41'),
(80, 5, 'Felda Bukit Jalor', 'felda-bukit-jalor', 1, '2014-05-14 09:53:10', 1, '2014-05-14 09:58:06'),
(81, 5, 'Felda Bukit Rokan', 'felda-bukit-rokan', 1, '2014-05-14 09:53:11', 1, '2014-05-14 10:02:46'),
(82, 9, 'Felda Chuping', 'felda-chuping', 1, '2014-05-14 09:53:15', 1, '2014-05-14 10:03:09'),
(83, 9, 'Felda Mata Air', 'mata-air', 1, '2014-05-14 09:53:15', NULL, NULL),
(84, 9, 'Felda Rimba Mas', 'rimba-mas', 1, '2014-05-14 09:53:15', NULL, NULL);

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
(61, 61, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(62, 62, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(63, 63, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(64, 64, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(65, 65, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(66, 66, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(67, 67, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(68, 68, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(69, 69, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(70, 70, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(71, 71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 72, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(73, 73, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(74, 74, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 75, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 76, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(77, 77, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(78, 78, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(79, 79, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(80, 80, '', '', '', '', '\r\n	          \r\n	          	        	        ', '', '', '', '', NULL),
(81, 81, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
(82, 82, '', '', '', '', '\r\n	          	        ', '', '', '', '', NULL),
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=168 ;

--
-- Dumping data for table `site_manager`
--

INSERT INTO `site_manager` (`siteManagerID`, `userID`, `siteID`, `siteManagerStatus`, `siteManagerCreatedDate`, `siteManagerCreatedUser`, `siteManagerUpdatedDate`, `siteManagerUpdatedUser`) VALUES
(1, 2, 1, 1, '2014-05-14 09:37:14', 1, NULL, NULL),
(2, 3, 1, 1, '2014-05-14 09:37:14', 1, NULL, NULL),
(3, 4, 2, 1, '2014-05-14 09:37:15', 1, NULL, NULL),
(4, 5, 2, 1, '2014-05-14 09:37:15', 1, NULL, NULL),
(5, 6, 3, 1, '2014-05-14 09:37:16', 1, NULL, NULL),
(6, 7, 3, 1, '2014-05-14 09:37:16', 1, NULL, NULL),
(7, 7, 4, 1, '2014-05-14 09:37:16', 1, NULL, NULL),
(8, 8, 4, 1, '2014-05-14 09:37:16', 1, NULL, NULL),
(9, 9, 5, 1, '2014-05-14 09:37:17', 1, NULL, NULL),
(10, 10, 5, 1, '2014-05-14 09:37:17', 1, NULL, NULL),
(11, 11, 6, 1, '2014-05-14 09:37:17', 1, NULL, NULL),
(12, 12, 6, 1, '2014-05-14 09:37:18', 1, NULL, NULL),
(13, 13, 7, 1, '2014-05-14 09:37:18', 1, NULL, NULL),
(14, 14, 7, 1, '2014-05-14 09:37:18', 1, NULL, NULL),
(15, 15, 8, 1, '2014-05-14 09:37:18', 1, NULL, NULL),
(16, 16, 8, 1, '2014-05-14 09:37:18', 1, NULL, NULL),
(17, 17, 9, 1, '2014-05-14 09:37:19', 1, NULL, NULL),
(18, 18, 9, 1, '2014-05-14 09:37:19', 1, NULL, NULL),
(19, 19, 10, 1, '2014-05-14 09:37:20', 1, NULL, NULL),
(20, 20, 10, 1, '2014-05-14 09:37:20', 1, NULL, NULL),
(21, 21, 11, 1, '2014-05-14 09:37:20', 1, NULL, NULL),
(22, 22, 11, 1, '2014-05-14 09:37:20', 1, NULL, NULL),
(23, 23, 12, 1, '2014-05-14 09:37:21', 1, NULL, NULL),
(24, 24, 12, 1, '2014-05-14 09:37:21', 1, NULL, NULL),
(25, 25, 13, 1, '2014-05-14 09:37:21', 1, NULL, NULL),
(26, 26, 13, 1, '2014-05-14 09:37:21', 1, NULL, NULL),
(27, 27, 14, 1, '2014-05-14 09:37:22', 1, NULL, NULL),
(28, 28, 14, 1, '2014-05-14 09:37:22', 1, NULL, NULL),
(29, 29, 15, 1, '2014-05-14 09:37:22', 1, NULL, NULL),
(30, 30, 16, 1, '2014-05-14 09:37:23', 1, NULL, NULL),
(31, 31, 16, 1, '2014-05-14 09:37:23', 1, NULL, NULL),
(32, 32, 17, 1, '2014-05-14 09:37:23', 1, NULL, NULL),
(33, 33, 17, 1, '2014-05-14 09:37:23', 1, NULL, NULL),
(34, 34, 18, 1, '2014-05-14 09:37:23', 1, NULL, NULL),
(35, 35, 18, 1, '2014-05-14 09:37:23', 1, NULL, NULL),
(36, 36, 19, 1, '2014-05-14 09:37:24', 1, NULL, NULL),
(37, 37, 19, 1, '2014-05-14 09:37:24', 1, NULL, NULL),
(38, 38, 20, 1, '2014-05-14 09:37:28', 1, NULL, NULL),
(39, 39, 20, 1, '2014-05-14 09:37:28', 1, NULL, NULL),
(40, 40, 21, 1, '2014-05-14 09:37:28', 1, NULL, NULL),
(41, 41, 21, 1, '2014-05-14 09:37:28', 1, NULL, NULL),
(42, 42, 22, 1, '2014-05-14 09:37:29', 1, NULL, NULL),
(43, 43, 22, 1, '2014-05-14 09:37:29', 1, NULL, NULL),
(44, 44, 23, 1, '2014-05-14 09:37:29', 1, NULL, NULL),
(45, 45, 23, 1, '2014-05-14 09:37:29', 1, NULL, NULL),
(46, 46, 24, 1, '2014-05-14 09:37:30', 1, NULL, NULL),
(47, 47, 24, 1, '2014-05-14 09:37:30', 1, NULL, NULL),
(48, 48, 25, 1, '2014-05-14 09:37:30', 1, NULL, NULL),
(49, 49, 25, 1, '2014-05-14 09:37:30', 1, NULL, NULL),
(50, 50, 26, 1, '2014-05-14 09:37:31', 1, NULL, NULL),
(51, 51, 26, 1, '2014-05-14 09:37:31', 1, NULL, NULL),
(52, 52, 27, 1, '2014-05-14 09:37:31', 1, NULL, NULL),
(53, 53, 27, 1, '2014-05-14 09:37:31', 1, NULL, NULL),
(54, 54, 28, 1, '2014-05-14 09:37:32', 1, NULL, NULL),
(55, 55, 28, 1, '2014-05-14 09:37:32', 1, NULL, NULL),
(56, 56, 29, 1, '2014-05-14 09:37:32', 1, NULL, NULL),
(57, 57, 29, 1, '2014-05-14 09:37:32', 1, NULL, NULL),
(58, 58, 30, 1, '2014-05-14 09:37:33', 1, NULL, NULL),
(59, 59, 30, 1, '2014-05-14 09:37:33', 1, NULL, NULL),
(60, 60, 31, 1, '2014-05-14 09:37:33', 1, NULL, NULL),
(61, 61, 31, 1, '2014-05-14 09:37:33', 1, NULL, NULL),
(62, 62, 32, 1, '2014-05-14 09:37:34', 1, NULL, NULL),
(63, 63, 32, 1, '2014-05-14 09:37:34', 1, NULL, NULL),
(64, 64, 33, 1, '2014-05-14 09:37:34', 1, NULL, NULL),
(65, 65, 33, 1, '2014-05-14 09:37:34', 1, NULL, NULL),
(66, 66, 34, 1, '2014-05-14 09:37:35', 1, NULL, NULL),
(67, 67, 34, 1, '2014-05-14 09:37:35', 1, NULL, NULL),
(68, 68, 35, 1, '2014-05-14 09:37:36', 1, NULL, NULL),
(69, 69, 35, 1, '2014-05-14 09:37:36', 1, NULL, NULL),
(70, 70, 36, 1, '2014-05-14 09:37:36', 1, NULL, NULL),
(71, 71, 36, 1, '2014-05-14 09:37:36', 1, NULL, NULL),
(72, 72, 37, 1, '2014-05-14 09:37:37', 1, NULL, NULL),
(73, 73, 37, 1, '2014-05-14 09:37:37', 1, NULL, NULL),
(74, 74, 38, 1, '2014-05-14 09:37:46', 1, NULL, NULL),
(75, 75, 38, 1, '2014-05-14 09:37:46', 1, NULL, NULL),
(76, 76, 39, 1, '2014-05-14 09:37:46', 1, NULL, NULL),
(77, 77, 39, 1, '2014-05-14 09:37:46', 1, NULL, NULL),
(78, 78, 40, 1, '2014-05-14 09:37:46', 1, NULL, NULL),
(79, 79, 40, 1, '2014-05-14 09:37:46', 1, NULL, NULL),
(80, 80, 41, 1, '2014-05-14 09:37:47', 1, NULL, NULL),
(81, 81, 41, 1, '2014-05-14 09:37:47', 1, NULL, NULL),
(82, 82, 42, 1, '2014-05-14 09:37:48', 1, NULL, NULL),
(83, 83, 42, 1, '2014-05-14 09:37:48', 1, NULL, NULL),
(84, 84, 43, 1, '2014-05-14 09:37:48', 1, NULL, NULL),
(85, 85, 43, 1, '2014-05-14 09:37:49', 1, NULL, NULL),
(86, 86, 44, 1, '2014-05-14 09:37:49', 1, NULL, NULL),
(87, 87, 44, 1, '2014-05-14 09:37:49', 1, NULL, NULL),
(88, 88, 45, 1, '2014-05-14 09:37:50', 1, NULL, NULL),
(89, 89, 45, 1, '2014-05-14 09:37:50', 1, NULL, NULL),
(90, 90, 46, 1, '2014-05-14 09:37:50', 1, NULL, NULL),
(91, 91, 46, 1, '2014-05-14 09:37:50', 1, NULL, NULL),
(92, 92, 47, 1, '2014-05-14 09:37:51', 1, NULL, NULL),
(93, 93, 47, 1, '2014-05-14 09:37:51', 1, NULL, NULL),
(94, 94, 48, 1, '2014-05-14 09:37:51', 1, NULL, NULL),
(95, 95, 48, 1, '2014-05-14 09:37:51', 1, NULL, NULL),
(96, 96, 49, 1, '2014-05-14 09:37:52', 1, NULL, NULL),
(97, 97, 49, 1, '2014-05-14 09:37:52', 1, NULL, NULL),
(98, 98, 50, 1, '2014-05-14 09:37:52', 1, NULL, NULL),
(99, 99, 50, 1, '2014-05-14 09:37:53', 1, NULL, NULL),
(100, 100, 51, 1, '2014-05-14 09:37:53', 1, NULL, NULL),
(101, 101, 51, 1, '2014-05-14 09:37:53', 1, NULL, NULL),
(102, 102, 52, 1, '2014-05-14 09:37:54', 1, NULL, NULL),
(103, 103, 52, 1, '2014-05-14 09:37:54', 1, NULL, NULL),
(104, 104, 53, 1, '2014-05-14 09:37:54', 1, NULL, NULL),
(105, 105, 53, 1, '2014-05-14 09:37:54', 1, NULL, NULL),
(106, 106, 54, 1, '2014-05-14 09:37:55', 1, NULL, NULL),
(107, 107, 54, 1, '2014-05-14 09:37:55', 1, NULL, NULL),
(108, 108, 55, 1, '2014-05-14 09:37:55', 1, NULL, NULL),
(109, 109, 55, 1, '2014-05-14 09:37:55', 1, NULL, NULL),
(110, 110, 56, 1, '2014-05-14 09:37:56', 1, NULL, NULL),
(111, 111, 56, 1, '2014-05-14 09:37:56', 1, NULL, NULL),
(112, 112, 57, 1, '2014-05-14 09:37:56', 1, NULL, NULL),
(113, 113, 57, 1, '2014-05-14 09:37:56', 1, NULL, NULL),
(114, 114, 58, 1, '2014-05-14 09:52:44', 1, NULL, NULL),
(115, 115, 58, 1, '2014-05-14 09:52:44', 1, NULL, NULL),
(116, 116, 59, 1, '2014-05-14 09:52:45', 1, NULL, NULL),
(117, 117, 59, 1, '2014-05-14 09:52:45', 1, NULL, NULL),
(118, 118, 60, 1, '2014-05-14 09:52:45', 1, NULL, NULL),
(119, 119, 60, 1, '2014-05-14 09:52:45', 1, NULL, NULL),
(120, 120, 61, 1, '2014-05-14 09:52:51', 1, NULL, NULL),
(121, 121, 61, 1, '2014-05-14 09:52:51', 1, NULL, NULL),
(122, 122, 62, 1, '2014-05-14 09:52:52', 1, NULL, NULL),
(123, 123, 62, 1, '2014-05-14 09:52:52', 1, NULL, NULL),
(124, 124, 63, 1, '2014-05-14 09:52:53', 1, NULL, NULL),
(125, 125, 63, 1, '2014-05-14 09:52:53', 1, NULL, NULL),
(126, 126, 64, 1, '2014-05-14 09:52:53', 1, NULL, NULL),
(127, 127, 64, 1, '2014-05-14 09:52:53', 1, NULL, NULL),
(128, 128, 65, 1, '2014-05-14 09:52:54', 1, NULL, NULL),
(129, 129, 65, 1, '2014-05-14 09:52:54', 1, NULL, NULL),
(130, 130, 66, 1, '2014-05-14 09:52:54', 1, NULL, NULL),
(131, 131, 66, 1, '2014-05-14 09:52:54', 1, NULL, NULL),
(132, 132, 67, 1, '2014-05-14 09:52:55', 1, NULL, NULL),
(133, 133, 67, 1, '2014-05-14 09:52:55', 1, NULL, NULL),
(134, 134, 68, 1, '2014-05-14 09:52:55', 1, NULL, NULL),
(135, 135, 68, 1, '2014-05-14 09:52:55', 1, NULL, NULL),
(136, 136, 69, 1, '2014-05-14 09:52:56', 1, NULL, NULL),
(137, 137, 69, 1, '2014-05-14 09:52:56', 1, NULL, NULL),
(138, 138, 70, 1, '2014-05-14 09:52:57', 1, NULL, NULL),
(139, 139, 70, 1, '2014-05-14 09:52:57', 1, NULL, NULL),
(140, 140, 71, 1, '2014-05-14 09:52:57', 1, NULL, NULL),
(141, 141, 71, 1, '2014-05-14 09:52:57', 1, NULL, NULL),
(142, 142, 72, 1, '2014-05-14 09:52:58', 1, NULL, NULL),
(143, 143, 72, 1, '2014-05-14 09:52:58', 1, NULL, NULL),
(144, 144, 73, 1, '2014-05-14 09:53:02', 1, NULL, NULL),
(145, 145, 73, 1, '2014-05-14 09:53:02', 1, NULL, NULL),
(146, 146, 74, 1, '2014-05-14 09:53:04', 1, NULL, NULL),
(147, 147, 74, 1, '2014-05-14 09:53:04', 1, NULL, NULL),
(148, 148, 75, 1, '2014-05-14 09:53:05', 1, NULL, NULL),
(149, 148, 75, 1, '2014-05-14 09:53:05', 1, NULL, NULL),
(150, 149, 76, 1, '2014-05-14 09:53:08', 1, NULL, NULL),
(151, 150, 76, 1, '2014-05-14 09:53:08', 1, NULL, NULL),
(152, 151, 77, 1, '2014-05-14 09:53:09', 1, NULL, NULL),
(153, 152, 77, 1, '2014-05-14 09:53:09', 1, NULL, NULL),
(154, 153, 78, 1, '2014-05-14 09:53:09', 1, NULL, NULL),
(155, 154, 78, 1, '2014-05-14 09:53:09', 1, NULL, NULL),
(156, 155, 79, 1, '2014-05-14 09:53:10', 1, NULL, NULL),
(157, 156, 79, 1, '2014-05-14 09:53:10', 1, NULL, NULL),
(158, 157, 80, 1, '2014-05-14 09:53:10', 1, NULL, NULL),
(159, 158, 80, 1, '2014-05-14 09:53:10', 1, NULL, NULL),
(160, 159, 81, 1, '2014-05-14 09:53:11', 1, NULL, NULL),
(161, 160, 81, 1, '2014-05-14 09:53:11', 1, NULL, NULL),
(162, 161, 82, 1, '2014-05-14 09:53:15', 1, NULL, NULL),
(163, 162, 82, 1, '2014-05-14 09:53:15', 1, NULL, NULL),
(164, 163, 83, 1, '2014-05-14 09:53:15', 1, NULL, NULL),
(165, 164, 83, 1, '2014-05-14 09:53:15', 1, NULL, NULL),
(166, 165, 84, 1, '2014-05-14 09:53:15', 1, NULL, NULL),
(167, 166, 84, 1, '2014-05-14 09:53:15', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `site_member`
--

CREATE TABLE IF NOT EXISTS `site_member` (
  `siteMemberID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  PRIMARY KEY (`siteMemberID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `siteMessageCategory` int(11) DEFAULT NULL,
  `siteMessageCreatedUser` int(11) DEFAULT NULL,
  `siteMessageStatus` int(11) DEFAULT NULL,
  PRIMARY KEY (`siteMessageID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_request`
--

CREATE TABLE IF NOT EXISTS `site_request` (
  `siteRequestID` int(11) NOT NULL AUTO_INCREMENT,
  `siteRequestType` int(11) DEFAULT NULL,
  `siteID` int(11) DEFAULT NULL,
  `siteRequestRefID` int(11) DEFAULT NULL,
  `siteRequestData` text,
  `siteRequestStatus` int(11) DEFAULT NULL,
  `siteRequestCreatedDate` datetime DEFAULT NULL,
  `siteRequestCreatedUser` int(11) DEFAULT NULL,
  `siteRequestUpdatedDate` datetime DEFAULT NULL,
  `siteRequestUpdatedUser` int(11) DEFAULT NULL,
  `siteRequestApprovalRead` int(11) DEFAULT NULL,
  PRIMARY KEY (`siteRequestID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_slider`
--

CREATE TABLE IF NOT EXISTS `site_slider` (
  `siteSliderID` int(11) NOT NULL AUTO_INCREMENT,
  `siteID` int(11) DEFAULT NULL,
  `siteSliderType` int(11) DEFAULT NULL,
  `siteSliderName` varchar(100) DEFAULT NULL,
  `siteSliderImage` varchar(100) DEFAULT NULL,
  `siteSliderStatus` int(11) DEFAULT NULL,
  `siteSliderCreatedDate` datetime DEFAULT NULL,
  `siteSliderCreatedUser` int(11) DEFAULT NULL,
  `siteSliderLink` varchar(100) DEFAULT NULL,
  `siteSliderTarget` int(11) DEFAULT NULL,
  PRIMARY KEY (`siteSliderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
-- Table structure for table `token`
--

CREATE TABLE IF NOT EXISTS `token` (
  `tokenID` int(11) NOT NULL AUTO_INCREMENT,
  `tokenType` int(11) DEFAULT NULL,
  `tokenName` varchar(100) DEFAULT NULL,
  `tokenStatus` int(11) DEFAULT NULL,
  `tokenCreatedDate` datetime DEFAULT NULL,
  `tokenUpdatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`tokenID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `token_resetpass`
--

CREATE TABLE IF NOT EXISTS `token_resetpass` (
  `tokenResetpassID` int(11) NOT NULL AUTO_INCREMENT,
  `tokenID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `tokenResetpassStatus` int(11) DEFAULT NULL,
  PRIMARY KEY (`tokenResetpassID`)
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
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=171 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `userIC`, `userPassword`, `userEmail`, `userLevel`, `userStatus`, `userPremiumStatus`, `userCreatedDate`, `userCreatedUser`) VALUES
(1, '890910105117', '6b41291438d8afc0dfffacdc9b460845', 'root@gmail.com', '99', 1, 1, NULL, NULL),
(2, NULL, 'daa57b48840eb2156dca97c43c985371', 'mary@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:14', 1),
(3, NULL, 'daa57b48840eb2156dca97c43c985371', 'junainah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:14', 1),
(4, NULL, 'daa57b48840eb2156dca97c43c985371', 'sahriati@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:15', 1),
(5, NULL, 'daa57b48840eb2156dca97c43c985371', 'shafie@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:15', 1),
(6, NULL, 'daa57b48840eb2156dca97c43c985371', 'rozaime@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:15', 1),
(7, NULL, 'daa57b48840eb2156dca97c43c985371', 'magdalina@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:16', 1),
(8, NULL, 'daa57b48840eb2156dca97c43c985371', 'salma@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:16', 1),
(9, NULL, 'daa57b48840eb2156dca97c43c985371', 'halbi@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:17', 1),
(10, NULL, 'daa57b48840eb2156dca97c43c985371', 'noraziera@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:17', 1),
(11, NULL, 'daa57b48840eb2156dca97c43c985371', 'jokley@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:17', 1),
(12, NULL, 'daa57b48840eb2156dca97c43c985371', 'conchita@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:17', 1),
(13, NULL, 'daa57b48840eb2156dca97c43c985371', 'adrian@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:18', 1),
(14, NULL, 'daa57b48840eb2156dca97c43c985371', 'azizan@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:18', 1),
(15, NULL, 'daa57b48840eb2156dca97c43c985371', 'hariz@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:18', 1),
(16, NULL, 'daa57b48840eb2156dca97c43c985371', 'jasinta@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:18', 1),
(17, NULL, 'daa57b48840eb2156dca97c43c985371', 'arbaya@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:19', 1),
(18, NULL, 'daa57b48840eb2156dca97c43c985371', ' hillyin@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:19', 1),
(19, NULL, 'daa57b48840eb2156dca97c43c985371', 'emily@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:19', 1),
(20, NULL, 'daa57b48840eb2156dca97c43c985371', ' missda@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:19', 1),
(21, NULL, 'daa57b48840eb2156dca97c43c985371', 'diandra@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:20', 1),
(22, NULL, 'daa57b48840eb2156dca97c43c985371', ' fred.asoon@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:20', 1),
(23, NULL, 'daa57b48840eb2156dca97c43c985371', 'zurinah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:20', 1),
(24, NULL, 'daa57b48840eb2156dca97c43c985371', 'noraini@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:21', 1),
(25, NULL, 'daa57b48840eb2156dca97c43c985371', 'audrey@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:21', 1),
(26, NULL, 'daa57b48840eb2156dca97c43c985371', ' farah_suzianah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:21', 1),
(27, NULL, 'daa57b48840eb2156dca97c43c985371', 'rosfadhilah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:22', 1),
(28, NULL, 'daa57b48840eb2156dca97c43c985371', 'alfred@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:22', 1),
(29, NULL, 'daa57b48840eb2156dca97c43c985371', 'elvysia@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:22', 1),
(30, NULL, 'daa57b48840eb2156dca97c43c985371', 'zuhair@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:22', 1),
(31, NULL, 'daa57b48840eb2156dca97c43c985371', ' kamilie@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:22', 1),
(32, NULL, 'daa57b48840eb2156dca97c43c985371', 'farah.izzah@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:23', 1),
(33, NULL, 'daa57b48840eb2156dca97c43c985371', ' afreeza@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:23', 1),
(34, NULL, 'daa57b48840eb2156dca97c43c985371', 'asmidah@celcom1cbc.com  ', '2', 1, 0, '2014-05-14 09:37:23', 1),
(35, NULL, 'daa57b48840eb2156dca97c43c985371', ' frederic@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:23', 1),
(36, NULL, 'daa57b48840eb2156dca97c43c985371', 'sartini@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:24', 1),
(37, NULL, 'daa57b48840eb2156dca97c43c985371', ' stanley@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:24', 1),
(38, NULL, 'daa57b48840eb2156dca97c43c985371', 'rosarnie@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:28', 1),
(39, NULL, 'daa57b48840eb2156dca97c43c985371', 'samsiah@celcom1cbc.com  ', '2', 1, 0, '2014-05-14 09:37:28', 1),
(40, NULL, 'daa57b48840eb2156dca97c43c985371', 'sulaiman@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:28', 1),
(41, NULL, 'daa57b48840eb2156dca97c43c985371', 'azizul@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:28', 1),
(42, NULL, 'daa57b48840eb2156dca97c43c985371', 'kasmidah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:29', 1),
(43, NULL, 'daa57b48840eb2156dca97c43c985371', 'azman@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:29', 1),
(44, NULL, 'daa57b48840eb2156dca97c43c985371', 'juleah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:29', 1),
(45, NULL, 'daa57b48840eb2156dca97c43c985371', 'mardiyana@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:29', 1),
(46, NULL, 'daa57b48840eb2156dca97c43c985371', 'hamiatie@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:30', 1),
(47, NULL, 'daa57b48840eb2156dca97c43c985371', 'anni@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:30', 1),
(48, NULL, 'daa57b48840eb2156dca97c43c985371', 'farah_liyana@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:30', 1),
(49, NULL, 'daa57b48840eb2156dca97c43c985371', 'yusuf@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:30', 1),
(50, NULL, 'daa57b48840eb2156dca97c43c985371', 'adilah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:31', 1),
(51, NULL, 'daa57b48840eb2156dca97c43c985371', 'munawara@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:31', 1),
(52, NULL, 'daa57b48840eb2156dca97c43c985371', 'freddy@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:31', 1),
(53, NULL, 'daa57b48840eb2156dca97c43c985371', 'scrivencer@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:31', 1),
(54, NULL, 'daa57b48840eb2156dca97c43c985371', 'roldy@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:31', 1),
(55, NULL, 'daa57b48840eb2156dca97c43c985371', 'roslinda@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:32', 1),
(56, NULL, 'daa57b48840eb2156dca97c43c985371', 'suhana@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:32', 1),
(57, NULL, 'daa57b48840eb2156dca97c43c985371', 'elvison@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:32', 1),
(58, NULL, 'daa57b48840eb2156dca97c43c985371', 'fithzdowell@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:32', 1),
(59, NULL, 'daa57b48840eb2156dca97c43c985371', ' javy@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:33', 1),
(60, NULL, 'daa57b48840eb2156dca97c43c985371', 'farah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:33', 1),
(61, NULL, 'daa57b48840eb2156dca97c43c985371', 'reck@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:33', 1),
(62, NULL, 'daa57b48840eb2156dca97c43c985371', 'elisa@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:33', 1),
(63, NULL, 'daa57b48840eb2156dca97c43c985371', 'maiame@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:34', 1),
(64, NULL, 'daa57b48840eb2156dca97c43c985371', 'linda@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:34', 1),
(65, NULL, 'daa57b48840eb2156dca97c43c985371', 'helena@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:34', 1),
(66, NULL, 'daa57b48840eb2156dca97c43c985371', 'maslizan@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:35', 1),
(67, NULL, 'daa57b48840eb2156dca97c43c985371', 'florince@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:35', 1),
(68, NULL, 'daa57b48840eb2156dca97c43c985371', 'fakhri@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:36', 1),
(69, NULL, 'daa57b48840eb2156dca97c43c985371', 'zubinah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:36', 1),
(70, NULL, 'daa57b48840eb2156dca97c43c985371', 'marini@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:36', 1),
(71, NULL, 'daa57b48840eb2156dca97c43c985371', 'romansah@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:36', 1),
(72, NULL, 'daa57b48840eb2156dca97c43c985371', 'badriah@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:36', 1),
(73, NULL, 'daa57b48840eb2156dca97c43c985371', ' seha@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:37', 1),
(74, NULL, 'daa57b48840eb2156dca97c43c985371', 'tony.mondorusun@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:45', 1),
(75, NULL, 'daa57b48840eb2156dca97c43c985371', ' mellisa@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:45', 1),
(76, NULL, 'daa57b48840eb2156dca97c43c985371', 'boby@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:46', 1),
(77, NULL, 'daa57b48840eb2156dca97c43c985371', 'hamlina@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:46', 1),
(78, NULL, 'daa57b48840eb2156dca97c43c985371', 'suriati@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:46', 1),
(79, NULL, 'daa57b48840eb2156dca97c43c985371', 'rosmawati@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:46', 1),
(80, NULL, 'daa57b48840eb2156dca97c43c985371', 'linah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:47', 1),
(81, NULL, 'daa57b48840eb2156dca97c43c985371', 'naimie@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:47', 1),
(82, NULL, 'daa57b48840eb2156dca97c43c985371', 'effle@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:48', 1),
(83, NULL, 'daa57b48840eb2156dca97c43c985371', 'salfarina@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:48', 1),
(84, NULL, 'daa57b48840eb2156dca97c43c985371', 'nazarul@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:48', 1),
(85, NULL, 'daa57b48840eb2156dca97c43c985371', 'rahayu@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:48', 1),
(86, NULL, 'daa57b48840eb2156dca97c43c985371', 'afiqah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:49', 1),
(87, NULL, 'daa57b48840eb2156dca97c43c985371', 'juziana@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:49', 1),
(88, NULL, 'daa57b48840eb2156dca97c43c985371', 'jusry@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:50', 1),
(89, NULL, 'daa57b48840eb2156dca97c43c985371', 'zamadiani@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:50', 1),
(90, NULL, 'daa57b48840eb2156dca97c43c985371', 'deargold@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:50', 1),
(91, NULL, 'daa57b48840eb2156dca97c43c985371', 'norieqram@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:50', 1),
(92, NULL, 'daa57b48840eb2156dca97c43c985371', 'norain@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:51', 1),
(93, NULL, 'daa57b48840eb2156dca97c43c985371', 'hanim@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:51', 1),
(94, NULL, 'daa57b48840eb2156dca97c43c985371', 'petronilla@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:51', 1),
(95, NULL, 'daa57b48840eb2156dca97c43c985371', 'wili@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:51', 1),
(96, NULL, 'daa57b48840eb2156dca97c43c985371', 'nordiah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:52', 1),
(97, NULL, 'daa57b48840eb2156dca97c43c985371', 'norsalasiah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:52', 1),
(98, NULL, 'daa57b48840eb2156dca97c43c985371', 'beatrice@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:52', 1),
(99, NULL, 'daa57b48840eb2156dca97c43c985371', 'marcellus@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:52', 1),
(100, NULL, 'daa57b48840eb2156dca97c43c985371', 'lebrien@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:53', 1),
(101, NULL, 'daa57b48840eb2156dca97c43c985371', 'octevia@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:53', 1),
(102, NULL, 'daa57b48840eb2156dca97c43c985371', 'zuraidah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:53', 1),
(103, NULL, 'daa57b48840eb2156dca97c43c985371', 'nedy@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:54', 1),
(104, NULL, 'daa57b48840eb2156dca97c43c985371', 'debbie@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:54', 1),
(105, NULL, 'daa57b48840eb2156dca97c43c985371', 'celyn@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:54', 1),
(106, NULL, 'daa57b48840eb2156dca97c43c985371', 'roziatul@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:54', 1),
(107, NULL, 'daa57b48840eb2156dca97c43c985371', ' nenly@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:54', 1),
(108, NULL, 'daa57b48840eb2156dca97c43c985371', 'malania@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:55', 1),
(109, NULL, 'daa57b48840eb2156dca97c43c985371', ' asmih@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:55', 1),
(110, NULL, 'daa57b48840eb2156dca97c43c985371', 'lovelone@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:37:55', 1),
(111, NULL, 'daa57b48840eb2156dca97c43c985371', ' farhana@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:56', 1),
(112, NULL, 'daa57b48840eb2156dca97c43c985371', 'irni@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:56', 1),
(113, NULL, 'daa57b48840eb2156dca97c43c985371', 'yassir@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:37:56', 1),
(114, NULL, 'daa57b48840eb2156dca97c43c985371', 'oliver@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:44', 1),
(115, NULL, 'daa57b48840eb2156dca97c43c985371', ' mary.jembun@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:44', 1),
(116, NULL, 'daa57b48840eb2156dca97c43c985371', 'barbara@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:45', 1),
(117, NULL, 'daa57b48840eb2156dca97c43c985371', 'hasyimah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:45', 1),
(118, NULL, 'daa57b48840eb2156dca97c43c985371', 'sima@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:45', 1),
(119, NULL, 'daa57b48840eb2156dca97c43c985371', 'david@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:45', 1),
(120, NULL, 'daa57b48840eb2156dca97c43c985371', 'arnida@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:51', 1),
(121, NULL, 'daa57b48840eb2156dca97c43c985371', 'suhaimee@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:51', 1),
(122, NULL, 'daa57b48840eb2156dca97c43c985371', 'adam@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:52', 1),
(123, NULL, 'daa57b48840eb2156dca97c43c985371', 'noraliza@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:52:52', 1),
(124, NULL, 'daa57b48840eb2156dca97c43c985371', 'sharzakila@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:52:52', 1),
(125, NULL, 'daa57b48840eb2156dca97c43c985371', 'noorlida@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:53', 1),
(126, NULL, 'daa57b48840eb2156dca97c43c985371', 'sitihafiza@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:53', 1),
(127, NULL, 'daa57b48840eb2156dca97c43c985371', 'amirah@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:52:53', 1),
(128, NULL, 'daa57b48840eb2156dca97c43c985371', 'syakir@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:54', 1),
(129, NULL, 'daa57b48840eb2156dca97c43c985371', 'norazlan.pandi@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:54', 1),
(130, NULL, 'daa57b48840eb2156dca97c43c985371', 'nurulakmar@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:54', 1),
(131, NULL, 'daa57b48840eb2156dca97c43c985371', 'norhayati@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:54', 1),
(132, NULL, 'daa57b48840eb2156dca97c43c985371', 'anuarezman@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:55', 1),
(133, NULL, 'daa57b48840eb2156dca97c43c985371', 'atiqah@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:52:55', 1),
(134, NULL, 'daa57b48840eb2156dca97c43c985371', 'nurulmaliessa@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:55', 1),
(135, NULL, 'daa57b48840eb2156dca97c43c985371', 'jannah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:55', 1),
(136, NULL, 'daa57b48840eb2156dca97c43c985371', 'khirominshah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:56', 1),
(137, NULL, 'daa57b48840eb2156dca97c43c985371', 'syafiqah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:56', 1),
(138, NULL, 'daa57b48840eb2156dca97c43c985371', 'nadia@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:56', 1),
(139, NULL, 'daa57b48840eb2156dca97c43c985371', 'edham@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:57', 1),
(140, NULL, 'daa57b48840eb2156dca97c43c985371', 'mazliza@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:57', 1),
(141, NULL, 'daa57b48840eb2156dca97c43c985371', 'safiah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:57', 1),
(142, NULL, 'daa57b48840eb2156dca97c43c985371', 'raziff@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:58', 1),
(143, NULL, 'daa57b48840eb2156dca97c43c985371', 'nazrin.manap@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:52:58', 1),
(144, NULL, 'daa57b48840eb2156dca97c43c985371', 'fauziah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:01', 1),
(145, NULL, 'daa57b48840eb2156dca97c43c985371', 'sitiamirah@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:53:01', 1),
(146, NULL, 'daa57b48840eb2156dca97c43c985371', 'rasyadan@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:04', 1),
(147, NULL, 'daa57b48840eb2156dca97c43c985371', 'nabilah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:04', 1),
(148, NULL, 'daa57b48840eb2156dca97c43c985371', 'aminur@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:05', 1),
(149, NULL, 'daa57b48840eb2156dca97c43c985371', 'norizzati.manaf@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:08', 1),
(150, NULL, 'daa57b48840eb2156dca97c43c985371', 'norhasliza@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:08', 1),
(151, NULL, 'daa57b48840eb2156dca97c43c985371', 'hasanatul@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:08', 1),
(152, NULL, 'daa57b48840eb2156dca97c43c985371', 'siti.halijah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:08', 1),
(153, NULL, 'daa57b48840eb2156dca97c43c985371', 'nurizayanty@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:09', 1),
(154, NULL, 'daa57b48840eb2156dca97c43c985371', 'fiqahyussof@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:09', 1),
(155, NULL, 'daa57b48840eb2156dca97c43c985371', 'izzattie@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:53:09', 1),
(156, NULL, 'daa57b48840eb2156dca97c43c985371', 'firdaus@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:10', 1),
(157, NULL, 'daa57b48840eb2156dca97c43c985371', 'norizzati@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:10', 1),
(158, NULL, 'daa57b48840eb2156dca97c43c985371', 'radhiah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:10', 1),
(159, NULL, 'daa57b48840eb2156dca97c43c985371', 'azizah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:11', 1),
(160, NULL, 'daa57b48840eb2156dca97c43c985371', 'shuhadah@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:11', 1),
(161, NULL, 'daa57b48840eb2156dca97c43c985371', 'zakiah@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:53:14', 1),
(162, NULL, 'daa57b48840eb2156dca97c43c985371', 'zahir@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:15', 1),
(163, NULL, 'daa57b48840eb2156dca97c43c985371', 'erma@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:15', 1),
(164, NULL, 'daa57b48840eb2156dca97c43c985371', 'hairil@celcom1cbc.com ', '2', 1, 0, '2014-05-14 09:53:15', 1),
(165, NULL, 'daa57b48840eb2156dca97c43c985371', 'azwary@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:15', 1),
(166, NULL, 'daa57b48840eb2156dca97c43c985371', 'firdus@celcom1cbc.com', '2', 1, 0, '2014-05-14 09:53:15', 1),
(167, '12345', 'daa57b48840eb2156dca97c43c985371', 'fitriyanie@nusuara.com', '3', 1, 0, '2014-05-14 10:22:59', 1),
(168, '123456', 'daa57b48840eb2156dca97c43c985371', 'yusrizal@nusuara.com', '3', 1, 0, '2014-05-14 10:23:32', 1),
(169, '12345667', 'daa57b48840eb2156dca97c43c985371', 'sabarinus@nusuara.com', '3', 1, 0, '2014-05-14 10:23:48', 1),
(170, '1234566789', 'daa57b48840eb2156dca97c43c985371', 'shamsul@nusuara.com', '3', 1, 0, '2014-05-14 10:24:15', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=171 ;

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
(30, 30, NULL, 'Zuhair Calvin Ng Mohd Firdaus', '019-2302701 ', NULL, NULL, NULL, NULL, NULL, NULL),
(31, 31, NULL, 'Mohd Kamilie Bin Badru', ' 013-5476176 ', NULL, NULL, NULL, NULL, NULL, NULL),
(32, 32, NULL, 'Vacant', '019-8972563 ', NULL, NULL, NULL, NULL, NULL, NULL),
(33, 33, NULL, 'Siti Afreeza Binti Ahmad', ' 019-8934456   ', NULL, NULL, NULL, NULL, NULL, NULL),
(34, 34, NULL, 'Asmidah binti Kasim', '014-6812818 ', NULL, NULL, NULL, NULL, NULL, NULL),
(35, 35, NULL, 'Frederic Michael Obih', ' 013-3476072', NULL, NULL, NULL, NULL, NULL, NULL),
(36, 36, NULL, 'Sartini Binti Jamil', '017-8284167 ', NULL, NULL, NULL, NULL, NULL, NULL),
(37, 37, NULL, 'Stanley Stephen', ' 019-8715319   ', NULL, NULL, NULL, NULL, NULL, NULL),
(38, 38, NULL, 'Rosarnie Affrinie Binti Amit', '016-5872897 ', NULL, NULL, NULL, NULL, NULL, NULL),
(39, 39, NULL, 'Samsiah Binti Lauming', '013-8363767', NULL, NULL, NULL, NULL, NULL, NULL),
(40, 40, NULL, 'Sulaiman bin Sanusi', '010-5886707', NULL, NULL, NULL, NULL, NULL, NULL),
(41, 41, NULL, 'Azizul Bin Karamdin', '013-8886532', NULL, NULL, NULL, NULL, NULL, NULL),
(42, 42, NULL, 'Kasmidah binti Madami', '016-2958051', NULL, NULL, NULL, NULL, NULL, NULL),
(43, 43, NULL, 'Azman bin Alih', '016-8418238', NULL, NULL, NULL, NULL, NULL, NULL),
(44, 44, NULL, 'Juleah Binti Rente', '013-5500701', NULL, NULL, NULL, NULL, NULL, NULL),
(45, 45, NULL, 'Mardiyana Sanusi', '014-8662860', NULL, NULL, NULL, NULL, NULL, NULL),
(46, 46, NULL, 'Hamiatie Bt Abd Hamid', '014-6504566', NULL, NULL, NULL, NULL, NULL, NULL),
(47, 47, NULL, 'Anni Binti Lim', '019-8070131', NULL, NULL, NULL, NULL, NULL, NULL),
(48, 48, NULL, 'Farah Liyana Bte Susilo', '010-9318382', NULL, NULL, NULL, NULL, NULL, NULL),
(49, 49, NULL, 'Mohammad Yusuf Bin Miru', '013-8006958', NULL, NULL, NULL, NULL, NULL, NULL),
(50, 50, NULL, 'Noor Adilah Bt Farid', '014-8738829', NULL, NULL, NULL, NULL, NULL, NULL),
(51, 51, NULL, 'Munawara Binti Mohamad Nor', '014-8664074', NULL, NULL, NULL, NULL, NULL, NULL),
(52, 52, NULL, 'Freddy Lee', '013-5505633', NULL, NULL, NULL, NULL, NULL, NULL),
(53, 53, NULL, 'Scrivencer Andrew', '014-8564353', NULL, NULL, NULL, NULL, NULL, NULL),
(54, 54, NULL, 'Roldy Bin Rawan', '013-8704479', NULL, NULL, NULL, NULL, NULL, NULL),
(55, 55, NULL, 'Roslinda Gibak', '013-8536528', NULL, NULL, NULL, NULL, NULL, NULL),
(56, 56, NULL, 'Suhana Binti Nahar', '019-5849027', NULL, NULL, NULL, NULL, NULL, NULL),
(57, 57, NULL, 'Elvison Japari', '013-8788133', NULL, NULL, NULL, NULL, NULL, NULL),
(58, 58, NULL, 'Fithzdowell Sindin', '019-8418221 ', NULL, NULL, NULL, NULL, NULL, NULL),
(59, 59, NULL, 'Javy Jimmy', ' 014-8647729', NULL, NULL, NULL, NULL, NULL, NULL),
(60, 60, NULL, 'Farah Bt Cyril Pongod Parantis', '013-8660812', NULL, NULL, NULL, NULL, NULL, NULL),
(61, 61, NULL, 'Reck Teveter Giswa', '019-5081878', NULL, NULL, NULL, NULL, NULL, NULL),
(62, 62, NULL, 'Elisa @ Reyz Samun ', '013-8584213', NULL, NULL, NULL, NULL, NULL, NULL),
(63, 63, NULL, 'Maiame Bin Jaini', '013-8686936', NULL, NULL, NULL, NULL, NULL, NULL),
(64, 64, NULL, 'Lindawati Ejasney', '019-5867074', NULL, NULL, NULL, NULL, NULL, NULL),
(65, 65, NULL, 'Helena Kolurren', '019-5342138', NULL, NULL, NULL, NULL, NULL, NULL),
(66, 66, NULL, 'Maslizan Binti Amir Bak', '013-5566061', NULL, NULL, NULL, NULL, NULL, NULL),
(67, 67, NULL, 'Florince Minsor', '0111-4869038', NULL, NULL, NULL, NULL, NULL, NULL),
(68, 68, NULL, 'Ahmad Fakhri B. Endah', '019-8409033', NULL, NULL, NULL, NULL, NULL, NULL),
(69, 69, NULL, 'Norzubinah Hj. Perdes Nelson', '016-8428817', NULL, NULL, NULL, NULL, NULL, NULL),
(70, 70, NULL, 'Marini Laksamana ', '014-8728192', NULL, NULL, NULL, NULL, NULL, NULL),
(71, 71, NULL, 'Romansah Bin Romainor', '019-5337099', NULL, NULL, NULL, NULL, NULL, NULL),
(72, 72, NULL, 'Nur Badriah Binti Hj. Abd Malik', '013-8717173 ', NULL, NULL, NULL, NULL, NULL, NULL),
(73, 73, NULL, 'Noorseha Binti Mahadi', ' 019-5816305', NULL, NULL, NULL, NULL, NULL, NULL),
(74, 74, NULL, 'Tony S. Mondorusun @ Tony Bin Sarapil', '016-8151928 ', NULL, NULL, NULL, NULL, NULL, NULL),
(75, 75, NULL, 'Mellisa El Denuary (Maternity Leave)', ' 019-8734825', NULL, NULL, NULL, NULL, NULL, NULL),
(76, 76, NULL, 'Boby Addy Nasry Bin Mustapah', '0111-9554901', NULL, NULL, NULL, NULL, NULL, NULL),
(77, 77, NULL, 'Hamlina Binti Tahir', '014-9410569', NULL, NULL, NULL, NULL, NULL, NULL),
(78, 78, NULL, 'Suriati Binti Yusse', '019-8028758', NULL, NULL, NULL, NULL, NULL, NULL),
(79, 79, NULL, 'Rosmawati Binti Hanafiah', '016-8019500', NULL, NULL, NULL, NULL, NULL, NULL),
(80, 80, NULL, 'Linah @ Norlinah Binti Fediles', '019-8020603', NULL, NULL, NULL, NULL, NULL, NULL),
(81, 81, NULL, 'Naimie Binti Ibrahim', '011-19558977', NULL, NULL, NULL, NULL, NULL, NULL),
(82, 82, NULL, 'Effle Bin Jusim', '019-8704940', NULL, NULL, NULL, NULL, NULL, NULL),
(83, 83, NULL, 'Salfarina Binti Linus', '019-8424849', NULL, NULL, NULL, NULL, NULL, NULL),
(84, 84, NULL, 'Nazarul Aziz Bin Zali Wahab', '013-8689264', NULL, NULL, NULL, NULL, NULL, NULL),
(85, 85, NULL, 'Siti Rahayu Kerijin', '0111-9187898', NULL, NULL, NULL, NULL, NULL, NULL),
(86, 86, NULL, 'Nurul Afiqah Binti Mohd Nor Azan', '013-5550169', NULL, NULL, NULL, NULL, NULL, NULL),
(87, 87, NULL, 'Norjuziana Arsa Salleh', '014-8513881', NULL, NULL, NULL, NULL, NULL, NULL),
(88, 88, NULL, 'Jusry Bin Jaafar', '018-8770693', NULL, NULL, NULL, NULL, NULL, NULL),
(89, 89, NULL, 'Zamadiani Binti Amukat', '013-8534554', NULL, NULL, NULL, NULL, NULL, NULL),
(90, 90, NULL, 'Deargold F. Laiwon', '019-2554986', NULL, NULL, NULL, NULL, NULL, NULL),
(91, 91, NULL, 'Mohd Norieqram Bin Wasin', '019-8310792', NULL, NULL, NULL, NULL, NULL, NULL),
(92, 92, NULL, 'Nor''ain binti Salman', '012-8255409', NULL, NULL, NULL, NULL, NULL, NULL),
(93, 93, NULL, 'Noor Hanim Binti Barut Jani', '019-3470885', NULL, NULL, NULL, NULL, NULL, NULL),
(94, 94, NULL, 'Petronilla Barbara Jr binti Chrispine', '013-8902390', NULL, NULL, NULL, NULL, NULL, NULL),
(95, 95, NULL, 'Wili Groda Theodoru Tudo', '019-8698139', NULL, NULL, NULL, NULL, NULL, NULL),
(96, 96, NULL, 'Nordiah Gaat', '019-2487956', NULL, NULL, NULL, NULL, NULL, NULL),
(97, 97, NULL, 'Noor Salasiah Tugino', '019-8322143', NULL, NULL, NULL, NULL, NULL, NULL),
(98, 98, NULL, 'Beatrice Bernadus', '019-5271372', NULL, NULL, NULL, NULL, NULL, NULL),
(99, 99, NULL, 'Marcellus Fread', '019-8429605', NULL, NULL, NULL, NULL, NULL, NULL),
(100, 100, NULL, 'Lebrien bin Marium', '019-5305843', NULL, NULL, NULL, NULL, NULL, NULL),
(101, 101, NULL, 'Octevia Bint Lambiong', '013-8913526', NULL, NULL, NULL, NULL, NULL, NULL),
(102, 102, NULL, 'Siti Zuraidah binti Sulaiman', '019-8201356', NULL, NULL, NULL, NULL, NULL, NULL),
(103, 103, NULL, 'Nedy Judin Banganga', '013-8889215', NULL, NULL, NULL, NULL, NULL, NULL),
(104, 104, NULL, 'Debbie Jean Bt Pilau', '013-8699619', NULL, NULL, NULL, NULL, NULL, NULL),
(105, 105, NULL, 'Celyn Marsilla Binti Mair', '013-3096451', NULL, NULL, NULL, NULL, NULL, NULL),
(106, 106, NULL, 'Roziatul Mona Binti Untuk @ Adnan', '017-8108956 ', NULL, NULL, NULL, NULL, NULL, NULL),
(107, 107, NULL, 'Nenly Marudi', ' 014-8557190', NULL, NULL, NULL, NULL, NULL, NULL),
(108, 108, NULL, 'Malania John', '014-3519600 ', NULL, NULL, NULL, NULL, NULL, NULL),
(109, 109, NULL, 'Asmih Binti Setoh', ' 016-5873458', NULL, NULL, NULL, NULL, NULL, NULL),
(110, 110, NULL, 'Lovelone Juin', '019-5401788 ', NULL, NULL, NULL, NULL, NULL, NULL),
(111, 111, NULL, 'Farhana Khairunnisa Binti Bali', ' 019-8414322', NULL, NULL, NULL, NULL, NULL, NULL),
(112, 112, NULL, 'Irni Mawarni Bt Selamat', '014-8548254 ', NULL, NULL, NULL, NULL, NULL, NULL),
(113, 113, NULL, 'Yassir Bin Apul', ' 019-5800783    ', NULL, NULL, NULL, NULL, NULL, NULL),
(114, 114, NULL, 'Oliver Rabing Anak Layang ', '0111-9181411', NULL, NULL, NULL, NULL, NULL, NULL),
(115, 115, NULL, 'Mary Jembun (effective 12 Feb 2014)', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(116, 116, NULL, 'Barbara Anak Perry', '017-8091943', NULL, NULL, NULL, NULL, NULL, NULL),
(117, 117, NULL, 'Siti Hasyimah Binti Hassan', '013-8092501', NULL, NULL, NULL, NULL, NULL, NULL),
(118, 118, NULL, 'Sima Anak Kayu', '019-8656285', NULL, NULL, NULL, NULL, NULL, NULL),
(119, 119, NULL, 'David Padong Anak Bakar', '017-8526490', NULL, NULL, NULL, NULL, NULL, NULL),
(120, 120, NULL, 'Arnida Mohd Ali', '012-7113495', NULL, NULL, NULL, NULL, NULL, NULL),
(121, 121, NULL, 'Suhaimee Abdullah', '017-7713031', NULL, NULL, NULL, NULL, NULL, NULL),
(122, 122, NULL, 'Mohd Adam b Mahat ', '017-7469232', NULL, NULL, NULL, NULL, NULL, NULL),
(123, 123, NULL, 'Noraliza Binti Samuri', '013-2008216', NULL, NULL, NULL, NULL, NULL, NULL),
(124, 124, NULL, 'Sharzakila Binti Abdullah', '013-6501154', NULL, NULL, NULL, NULL, NULL, NULL),
(125, 125, NULL, 'Noorlida Binti Nordin ', '013-2459001', NULL, NULL, NULL, NULL, NULL, NULL),
(126, 126, NULL, 'Siti Hafiza Nadiah Binti Mat Ladzin', '014-9309767', NULL, NULL, NULL, NULL, NULL, NULL),
(127, 127, NULL, 'Nur Amirah Binti Abdul Wahid', '017-7513245', NULL, NULL, NULL, NULL, NULL, NULL),
(128, 128, NULL, 'Mohd Syakiruddin Bin Omar', '019-3023816', NULL, NULL, NULL, NULL, NULL, NULL),
(129, 129, NULL, 'Norazlan Bin Pandi', '017-7513245', NULL, NULL, NULL, NULL, NULL, NULL),
(130, 130, NULL, 'Nurul Akmar Shazali', '014-2796227', NULL, NULL, NULL, NULL, NULL, NULL),
(131, 131, NULL, 'Norhayati Ahmad', '013-2943422', NULL, NULL, NULL, NULL, NULL, NULL),
(132, 132, NULL, 'Anuarezman Sariff', '019-7773035', NULL, NULL, NULL, NULL, NULL, NULL),
(133, 133, NULL, 'Nur Atiqah Binti Aris', '019-7683662', NULL, NULL, NULL, NULL, NULL, NULL),
(134, 134, NULL, 'Nurul Maliessa Mohd Kamal', '017-7813128', NULL, NULL, NULL, NULL, NULL, NULL),
(135, 135, NULL, 'Nurul Jannah Binti Jamaluddin', '017-7333319', NULL, NULL, NULL, NULL, NULL, NULL),
(136, 136, NULL, 'Khirominshah Mafford', '017-7002405', NULL, NULL, NULL, NULL, NULL, NULL),
(137, 137, NULL, 'Nur Syafiqah binti Aini', '013-7456304', NULL, NULL, NULL, NULL, NULL, NULL),
(138, 138, NULL, 'Umi Nadia Abd Rahman', '019-6811595', NULL, NULL, NULL, NULL, NULL, NULL),
(139, 139, NULL, 'Muhammad Edham Bin Nasir', '017-6702907', NULL, NULL, NULL, NULL, NULL, NULL),
(140, 140, NULL, 'Mazliza Mohd Salleh', '019-7918190', NULL, NULL, NULL, NULL, NULL, NULL),
(141, 141, NULL, 'Safiah Khamis ', '013-7325113', NULL, NULL, NULL, NULL, NULL, NULL),
(142, 142, NULL, 'Mohamad Raziff Bin Abd Azis', '013-2564561', NULL, NULL, NULL, NULL, NULL, NULL),
(143, 143, NULL, 'Mohammad Nazrin bin Abdul Manap', '013-6875689', NULL, NULL, NULL, NULL, NULL, NULL),
(144, 144, NULL, 'Fauziah Bt Mohd Fadzil', '013-5333201', NULL, NULL, NULL, NULL, NULL, NULL),
(145, 145, NULL, 'Siti Amirah Ghazali', '016-5068798', NULL, NULL, NULL, NULL, NULL, NULL),
(146, 146, NULL, 'Mohd Rasyadan Bin Zulkifli', '013-9909440', NULL, NULL, NULL, NULL, NULL, NULL),
(147, 147, NULL, 'Nurul Nabilah Bt. Md Zainol', '012-6410220', NULL, NULL, NULL, NULL, NULL, NULL),
(148, 148, NULL, 'Mohamad Aminur Bin Hasan', '013-6902261', NULL, NULL, NULL, NULL, NULL, NULL),
(149, 149, NULL, 'Norizzati Bt Abd Manaf', '013-6437378', NULL, NULL, NULL, NULL, NULL, NULL),
(150, 150, NULL, 'Norhasliza Ruslan', '012-3896106', NULL, NULL, NULL, NULL, NULL, NULL),
(151, 151, NULL, 'Hasanatul Atiah Jaidin', '012-9950249', NULL, NULL, NULL, NULL, NULL, NULL),
(152, 152, NULL, 'Siti Halijah Binti Sahrum ', '017-6280562', NULL, NULL, NULL, NULL, NULL, NULL),
(153, 153, NULL, 'Nur Izayanty bt Nazar', '017-3590068', NULL, NULL, NULL, NULL, NULL, NULL),
(154, 154, NULL, 'Nor Shafiqah Bt Mohd Yussof', '017-3700062', NULL, NULL, NULL, NULL, NULL, NULL),
(155, 155, NULL, 'Siti Nur Izzattie Bt Noor Haslin', '019-3748683', NULL, NULL, NULL, NULL, NULL, NULL),
(156, 156, NULL, 'Muhammad Firdaus bin Jaafar', '019-3725479', NULL, NULL, NULL, NULL, NULL, NULL),
(157, 157, NULL, 'Norizzati Abd Rashid', '019-7833745', NULL, NULL, NULL, NULL, NULL, NULL),
(158, 158, NULL, 'Siti Radhiah Binti Md Isa', '018-2083682', NULL, NULL, NULL, NULL, NULL, NULL),
(159, 159, NULL, 'Nor Azizah Shamsuddin', '019-6364364', NULL, NULL, NULL, NULL, NULL, NULL),
(160, 160, NULL, 'Nur Shuhadah Binti Zolkapley', '014-3832429', NULL, NULL, NULL, NULL, NULL, NULL),
(161, 161, NULL, 'Zakiah Rejab', '017-4980802', NULL, NULL, NULL, NULL, NULL, NULL),
(162, 162, NULL, 'Muhamad Zahir Bin Musa', '012-5379793', NULL, NULL, NULL, NULL, NULL, NULL),
(163, 163, NULL, 'Nurul Erma Zulkifli ', '012-5539273', NULL, NULL, NULL, NULL, NULL, NULL),
(164, 164, NULL, 'Hairil Bin Hasan - WS', '012-5069896', NULL, NULL, NULL, NULL, NULL, NULL),
(165, 165, NULL, 'Azwary Bin Azmi', '013-5295054', NULL, NULL, NULL, NULL, NULL, NULL),
(166, 166, NULL, 'Ahmad Firdus Mat Ali', '012-4389765', NULL, NULL, NULL, NULL, NULL, NULL),
(167, 167, NULL, 'Mrs Nurul Fitriyanie Abdullah', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(168, 168, NULL, 'Mr Yusrizal Hussein', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(169, 169, NULL, 'Mr Sabarinus Sekunil', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(170, 170, NULL, 'Shamsul bin Jantan', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
