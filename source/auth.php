<?php


/* Requests to this file should ONLY be made by server-side applications 
 * 
 * Please ONLY use the Authenticator class to interact with this API!
 * 
 */

/* Debugging settings *//*
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
*/

require_once('app/init.php');

if(isset($_GET['check_key'])){
    /* Check if an access key is valid
    (calling this from client side is pointless */
    $key_string = $_GET['key'];
    $ip = $_GET['ip'];
    
    if(access_key::validate($key_string,$ip)){
        $response = ['valid' => true, "key" => $key_string];
    }else{
        $response = ['valid' => false, "key" => $key_string];
    }
    
}else if(isset($_GET['check_perm'])){
    /* Check if the user owning the access key has a given permission */
    
    $key_string = $_GET['key_string'];
    $perm_name = $_GET['perm_name'];
    
}else if(isset($_GET['user_profile'])){
    
    $key_string = $_GET['key'];
    
    $username = access_key::get_username($key_string);
    
    $p_user = new user($username);
    
    $response = $p_user;
}

echo json_encode($response);


