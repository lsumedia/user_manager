
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mu_group_perm`
--

CREATE TABLE IF NOT EXISTS `mu_group_perm` (
  `group_id` int(11) NOT NULL COMMENT 'Group corresponding to permission',
  `perm_name` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT 'Permission name corresponding to group',
  `group_perm_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`group_perm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mu_key`
--

CREATE TABLE IF NOT EXISTS `mu_key` (
  `key_id` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT 'Integer ID of key',
  `key_value` char(64) COLLATE latin1_general_ci NOT NULL COMMENT '64 character key',
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'Username corresponding to key',
  `key_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_used` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp of the last time the key was used',
  `valid` tinyint(1) NOT NULL COMMENT '0: invalid 1: valid',
  `ip_address` varchar(45) COLLATE latin1_general_ci NOT NULL COMMENT 'IPv4 or v6 address',
  `last_url` varchar(2083) COLLATE latin1_general_ci DEFAULT NULL COMMENT 'URL the user last requested',
  PRIMARY KEY (`key_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

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
  PRIMARY KEY (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mu_user_group`
--

CREATE TABLE IF NOT EXISTS `mu_user_group` (
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'Username corresponding to group membership',
  `group_id` int(11) NOT NULL COMMENT 'Group ID corresponding to membership',
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`user_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mu_user_perm`
--

CREATE TABLE IF NOT EXISTS `mu_user_perm` (
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'Username corresponding to permission',
  `perm_name` varchar(100) COLLATE latin1_general_ci NOT NULL COMMENT 'Name of permission',
  `user_perm_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`user_perm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
