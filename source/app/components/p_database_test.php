<?php

class database_test extends page{
    
    public $name = 'db_test';
    
    public function content(){
        
        global $db;
        
        echo prefix('users');
        
        $query = 'SELECT * FROM ' . prefix('users');    
        $t_result = $db->query($query);
        echo $db->error;
        
    }
}
