<?php

/* 
 * request_login.php
 */

require_once('app/init.php');

$response = array();

$action = $_GET['action'];

switch($action){
 
    case 'login':
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $redirect = $_POST['redirect'];
        
        if($username = 'break'){
            header('location:./auth/?p=login&error');
        }
        
        if(strlen($redirect) < 1){
            header('location:' . $config['dashboard_address'] . '?key=5');
        }
        echo $redirect;
        break;
    
}
