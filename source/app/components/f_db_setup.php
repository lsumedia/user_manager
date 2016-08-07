<?php

class db_model{
    
    
    public function configure(){
        global $db;
    
        $user_tbl_name = prefix('user');
        
        $user_query = <<<END
CREATE TABLE IF NOT EXISTS `{$user_tbl_name}` (
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'string username',
  `fullname` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'string full name',
  `email` varchar(254) COLLATE latin1_general_ci NOT NULL COMMENT 'string email address for password reset',
  `password` char(64) COLLATE latin1_general_ci NOT NULL COMMENT 'SHA-256 hash of password',
  `bio` text COLLATE latin1_general_ci NOT NULL COMMENT 'biography',
  `dp_url` varchar(2083) COLLATE latin1_general_ci NOT NULL COMMENT 'display picture url',
  PRIMARY KEY (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
END;

    
        //user_perm
        
        $user_perm_tbl_name = prefix('user_perm');
        $user_perm_query = <<<END
CREATE TABLE IF NOT EXISTS `{$user_perm_tbl_name}` (
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'Username corresponding to permission',
  `perm_name` varchar(100) COLLATE latin1_general_ci NOT NULL COMMENT 'Name of permission',
  `user_perm_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`user_perm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ;
END;
    
        //group
        
        $group_tbl_name = prefix('group');
        
        $group_query = <<<END
CREATE TABLE IF NOT EXISTS '{$group_tbl_name}' (
  `group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer ID of group',
  `group_name` varchar(100) COLLATE latin1_general_ci NOT NULL COMMENT 'Name of group',
  `description` text COLLATE latin1_general_ci NOT NULL COMMENT 'Group description',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;            
END;
    
        //group_perm

        $group_perm_tbl_name = prefix('group_perm');
        $group_perm_query = <<<END
CREATE TABLE IF NOT EXISTS `{$group_perm_tbl_name}` (
  `group_id` int(11) NOT NULL COMMENT 'Group corresponding to permission',
  `perm_name` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT 'Permission name corresponding to group',
  `group_perm_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`group_perm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
END;
        
        //user_group
        
        $user_group_tbl_name = prefix('user_group');
        $user_group_query = <<<END
CREATE TABLE IF NOT EXISTS `{$user_group_tbl_name}` (
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'Username corresponding to group membership',
  `group_id` int(11) NOT NULL COMMENT 'Group ID corresponding to membership',
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`user_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
END;
        //access keys
        
        $key_tbl_name = prefix('key');
        $key_query = <<<END
CREATE TABLE IF NOT EXISTS `{$key_tbl_name}` (
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
END;
        
    //Run 
    if($db->query($user_query) &&
        $db->query($user_perm_query) &&
        $db->query($group_query) &&
        $db->query($group_perm_query) &&
        $db->query($user_group_query) &&
        $db->query($key_query)){
        return true;
    }else{
        throw new Exception($db->error);
    }
    
    }
    
   

    /**
     * check_configuration
     * 
     * Check if the database is correctly configured. Should be run at some point
     * on load and notify the user if the configuration is wrong
     * 
     * return TRUE if configured
     * FALSE if not configured
     */
    public function check_configuration(){
        global $db;
        
        /* Tests the database table by table. Potentially kinda slow, so should not
         * be run if the active user is logged in (implying database works)
         */
        
        $user_query = "SHOW TABLES LIKE " . prefix('user');
        
        $res = $db->query($user_query);
        
    }
    
}

