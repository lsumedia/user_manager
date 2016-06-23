<?php

class database_test extends page{
    
    public $name = 'db_test';
    
    public function content(){
        
        global $db;
        
        echo prefix('users');
        /*
        try{
            
            $query = 'SELECT * FROM ' . PREFIX('users');    
            //$t_result = $db->query($query);
            
        }catch(Exception $e){
            
        }*/
        
    }
}
