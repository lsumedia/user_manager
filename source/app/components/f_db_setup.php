<?php

class db_model{
    
    
    public function configure(){
        //users
    
        $user_query = "CREATE TABLE IF NOT EXISTS " . prefix("user");
    
        //user permission
    
        //groups
    
        //group permission
        
        //access keys
        
    }

    /**
     * check_configuration
     * 
     * Check if the database is correctly configured. Should be run at some point
     * on load and notify the user if the configuration is wrong
     */
    public function check_configuration(){
        
        /* Tests the database table by table. Potentially kinda slow, so should not
         * be run if the active user is logged in.
         */
        
        $user_query = "SHOW TABLES LIKE " . prefix('user');
        
        
        
        $user_perm_query = "SHOW TABLES LIKE " . prefix('user_permission');
        
        
        
        $group_query = "SHOW TABLES LIKE " . prefix('group');
        
        
        
        $group_perm_query = "SHOW TABLES LIKE " . prefix('group_permission');
        
        
        $access_key_query = "SHOW TABLES LIKE " . prefix('access_key');
        
    }
    
}

