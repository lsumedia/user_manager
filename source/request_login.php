<?php

/* 
 * The MIT License
 */

require_once('app/init.php');

$response = array();

$action = $_GET['action'];

switch($action){
 
    case 'login':
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $response['key'] = 'Yale';
        break;
    
}

echo json_encode($response);