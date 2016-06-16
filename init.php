<?php

/* init.php
 * 
 * Should be included on all pages which require access to the model
 */

$dir = 'components';

$comp_includes = scandir($dir);

foreach($comp_includes as $comp_ifile){
    if(strpos($comp_ifile, '.php') != -1){
        include($dir . '/' . $comp_ifile);
    }
}
