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
        
        if($username == 'break'){
            header('location:./auth/?p=login&error&redirect=' . $redirect);
            die();
        }
        
        $key = sha1(time());
        
        if(strlen($redirect) < 1){
            if(strlen($config['dashboard_address']) > 1){
                header('location:' . $config['dashboard_address'] . '?key=' . $key);
                die();
            }else{
                header('location:./auth/?p=nopath&key=' . $key);
                die();
            }
        }
        
        $r_var = (strpos($redirect, "?") == false)? '?' : '&';

        $return_full = $redirect . $r_var . "key=" . $key;
        
        //echo $return_full;
        
        header('location:' . $return_full);
        die();
        break;
    case 'logout':
        
        $key = $_POST['key'];
        $redirect = $_POST['source'];
        
        header('location:./auth/?p=login&redirect=' . $redirect);
        die();
        break;
    
}
