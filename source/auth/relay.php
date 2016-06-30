<?php

/* 
 * Licensed under MIT licence, yada yada
 * 
 * relay.php for authentication from a server 
 * Clients can talk to this file - should be used for the login application
 * 
 */

/* Location of the auth.php file relative to parent directory
 * (unless this folder has been moved, it should be
 * at ../auth.php */

$authphp = '/auth.php';

$url_arr = explode('/', "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]");
array_pop($url_arr);
array_pop($url_arr);
$server_path = implode($url_arr,'/');
$server_path .= $authphp;

$action = $_GET['action'];

switch($action){
 
    case 'login':
        
        $response = ['key' => 'Yale'];
        break;
    
}

//echo json_encode($response);
echo $server_path;


