-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2013 at 01:26 AM
-- Server version: 5.5.27
-- PHP Version: 5.3.27

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hexagon`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `articleId` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL,
  `tag` varchar(45) DEFAULT NULL,
  `category` int(45) NOT NULL,
  `date` date NOT NULL,
  `status` enum('In Progress','Completed') DEFAULT NULL,
  PRIMARY KEY (`articleId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `memberId` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(45) DEFAULT NULL,
  `lastName` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `phoneNo` int(11) DEFAULT NULL,
  `memberPassword` varchar(255) NOT NULL,
  PRIMARY KEY (`memberId`),
  KEY `memberId` (`memberId`),
  KEY `memberId_2` (`memberId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `memberarticle`
--

CREATE TABLE IF NOT EXISTS `memberarticle` (
  `memberId` int(11) NOT NULL,
  `articleId` int(11) NOT NULL,
  KEY `memberId` (`memberId`,`articleId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `menuId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `parentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`menuId`),
  KEY `parentId` (`parentId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `taskId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `entryDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('Completed','Needs Attention','In Progress','Screwed','Not Worth Completing') NOT NULL DEFAULT 'In Progress',
  `details` mediumtext,
  `type` tinytext,
  PRIMARY KEY (`taskId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=128 ;

-- --------------------------------------------------------

--
-- Table structure for table `taskcomment`
--

CREATE TABLE IF NOT EXISTS `taskcomment` (
  `commentId` int(11) NOT NULL AUTO_INCREMENT,
  `taskId` int(11) NOT NULL,
  `memberId` int(11) NOT NULL,
  `postedDate` datetime NOT NULL,
  `title` varchar(45) NOT NULL,
  `content` varchar(255) NOT NULL,
  `tag` varchar(45) NOT NULL,
  PRIMARY KEY (`commentId`),
  KEY `taskId` (`taskId`,`memberId`),
  KEY `memberId` (`memberId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=229 ;

-- --------------------------------------------------------

--
-- Table structure for table `work`
--

CREATE TABLE IF NOT EXISTS `work` (
  `workId` int(11) NOT NULL AUTO_INCREMENT,
  `taskId` int(11) NOT NULL,
  `memberId` int(11) NOT NULL,
  `date` date NOT NULL,
  `hours` int(11) NOT NULL,
  PRIMARY KEY (`workId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=124 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `taskcomment`
--
ALTER TABLE `taskcomment`
  ADD CONSTRAINT `taskcomment_ibfk_1` FOREIGN KEY (`taskId`) REFERENCES `task` (`taskId`),
  ADD CONSTRAINT `taskcomment_ibfk_2` FOREIGN KEY (`memberId`) REFERENCES `member` (`memberId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
