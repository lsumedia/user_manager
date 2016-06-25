<?php

/* init.php
 * 
 * Should be included on all pages which require access to the model and database
 */


//Load configuration

require_once('app/config.php');

//Load database connection

$db_settings = $config['database'];
$db = new mysqli($db_settings['db_host'], $db_settings['db_user'], $db_settings['db_pass'], $db_settings['db_name']);

if($db->connect_error){
    echo 'Initialisation error: ' . $db->connect_error;
}

$dir = 'app/components';

$comp_includes = scandir($dir);

foreach($comp_includes as $comp_ifile){
    if(strpos($comp_ifile, '.php') !== false){
        //echo $dir . '/' . $comp_ifile;
        include($dir . '/' . $comp_ifile);
    }
}
