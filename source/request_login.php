<?php

/* 
 * request_login.php
 */

/* Debugging settings */
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);


require_once('app/init.php');

$response = array();

$action = $_GET['action'];

switch($action){
 
    case 'login':
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $redirect = $_POST['redirect'];
        
        /*
        
        $hash = process_password($password);
        
        $query = "SELECT username FROM " . prefix('user') . " WHERE (username=? OR email=?) AND password=?";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param('sss',$username,$username,$hash);
        $stmt->execute();
        
        $stmt->bind_result($safe_username);
        
        if($stmt->fetch()){
            //make key
        }else{
            header('location:./auth/?p=login&error&redirect=' . $redirect);
            die();
        }
        
        $stmt->close();
         */
        
        $user = new user($username);
        
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        if($user->check_password($password) == true){
            $key = access_key::generate($user->username, $user_ip);
        }else{
            header('location:./auth/?p=login&error&redirect=' . $redirect);
            die();
        }
        
        
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
        
        /* Destroy key operation */
        if(access_key::invalidate($key) == true){
            header('location:./auth/?p=login&redirect=' . $redirect);
            die();
        }else{
            echo "Error - failed to invalidate access key. You may not have been securely logged out!";
        }
        break;
    
}
