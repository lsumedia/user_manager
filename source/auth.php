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
    (calling this from client side is pointless)
     * Action also updates access key last_used value */
    $key_string = $_GET['key'];
    $ip = $_GET['ip'];
    $source_url = $_GET['source_url'];
    
    if(access_key::validate($key_string,$ip, $source_url)){
        $response = ['valid' => true, "key" => $key_string];
    }else{
        $response = ['valid' => false, "key" => $key_string];
    }
    
}else if(isset($_GET['check_perm'])){
    /* Check if the user owning the access key has a given permission */
    
    $key_string = $_GET['key'];
    $perm_name = $_GET['perm_name'];
    
     $key_string = $_GET['key'];
    
    try{ 
        $username = access_key::get_username($key_string);
    
        $p_user = new user($username);

        $has_permission = $p_user->has_permission($perm_name);

        $response = ['username' => $username, 'perm_name' => $perm_name, 'has_permission' => $has_permission];
        
    }catch(Exception $e){
        
        $response = ['error' => "Key is invalid"];
    }
    
}else if(isset($_GET['user_profile'])){ 
    /* Return user profile data and a list of their permissions */
    
    $key_string = $_GET['key'];
    
    $username = access_key::get_username($key_string);
    
    $p_user = new user($username);
    
    $response = $p_user;
}

echo json_encode($response);


