<?php

/* init.php
 * 
 * Should be included on all pages which require access to the model
 */

require_once('app/config.php');

$dir = 'app/components';

$comp_includes = scandir($dir);

foreach($comp_includes as $comp_ifile){
    if(strpos($comp_ifile, '.php') !== false){
        //echo $dir . '/' . $comp_ifile;
        include($dir . '/' . $comp_ifile);
    }
}
