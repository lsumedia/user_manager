-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jul 03, 2016 at 04:27 PM
-- Server version: 5.6.30
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `grovestr_media_user_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `mu_group`
--

CREATE TABLE IF NOT EXISTS `mu_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer ID of group',
  `group_name` varchar(100) COLLATE latin1_general_ci NOT NULL COMMENT 'Name of group',
  `description` text COLLATE latin1_general_ci NOT NULL COMMENT 'Group description',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mu_group_perm`
--

CREATE TABLE IF NOT EXISTS `mu_group_perm` (
  `group_id` int(11) NOT NULL COMMENT 'Group corresponding to permission',
  `perm_name` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT 'Permission name corresponding to group'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mu_key`
--

CREATE TABLE IF NOT EXISTS `mu_key` (
  `key_id` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT 'Integer ID of key',
  `key_value` char(64) COLLATE latin1_general_ci NOT NULL COMMENT '64 character key',
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'Username corresponding to key',
  `last_used` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of the last time the key was used',
  PRIMARY KEY (`key_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mu_user`
--

CREATE TABLE IF NOT EXISTS `mu_user` (
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'string username',
  `fullname` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'string full name',
  `email` varchar(254) COLLATE latin1_general_ci NOT NULL COMMENT 'string email address for password reset',
  `password` char(64) COLLATE latin1_general_ci NOT NULL COMMENT 'SHA-256 hash of password',
  `bio` text COLLATE latin1_general_ci NOT NULL COMMENT 'biography',
  `dp_url` varchar(2083) COLLATE latin1_general_ci NOT NULL COMMENT 'display picture url',
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mu_user_group`
--

CREATE TABLE IF NOT EXISTS `mu_user_group` (
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'Username corresponding to group membership',
  `group_id` int(11) NOT NULL COMMENT 'Group ID corresponding to membership'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mu_user_perm`
--

CREATE TABLE IF NOT EXISTS `mu_user_perm` (
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'Username corresponding to permission',
  `perm_name` varchar(100) COLLATE latin1_general_ci NOT NULL COMMENT 'Name of permission'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
