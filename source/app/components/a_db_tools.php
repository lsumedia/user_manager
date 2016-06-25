<?php

class db_tools{
    
    static function add_prefix($table_name){
        global $config;
        $db_prefix = $config['database']['db_prefix'];
        return $db_prefix . '_' . $table_name;
    }
    
}

function prefix($table_name){
    return db_tools::add_prefix($table_name);
}
