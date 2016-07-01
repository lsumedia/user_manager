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
        
        $key = sha1(time());
        
        if(strlen($redirect) < 1){
            if(strlen($config['dashboard_address']) > 1){
                header('location:' . $config['dashboard_address'] . '?key=' . $key);
            }else{
                header('location:./auth/?p=nopath&key=' . $key);
            }
        }
        echo $redirect;
        break;
    
}
