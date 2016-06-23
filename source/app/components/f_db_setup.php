<?php

class db_model{
    
    
    public function configure(){
        //users
    
        $user_query = "CREATE TABLE IF NOT EXISTS " . prefix("user");
    
        //user permission
    
        //groups
    
        //group permission
        
        //add default user
        
    }

    /**
     * check_configuration
     * 
     * Check if the database is correctly configured. Should be run at some point
     * on load and notify the user if the configuration is wrong
     */
    public function check_configuration(){
        
        $user_query = "SHOW TABLES LIKE " . prefix('user');
        
        
        
        $user_perm_query = "SHOW TABLES LIKE " . prefix('user_permission');
        
        
        
        $group_query = "SHOW TABLES LIKE " . prefix('group');
        
        
        
        $group_perm_query = "SHOW TABLES LIKE " . prefix('group_permission');
        
        
    }
    
}

