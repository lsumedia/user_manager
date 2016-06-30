<?php


/* Requests to this file should ONLY be made by server-side applications */

require_once('app/init.php');

if(isset($_GET['request_login_token'])){
    /* Generate login token for requesting an authentication token */
    
    $username = $_POST['username'];
    
}else if(isset($_GET['request_access_key'])){
    /* Request access key and generate one if the request is valid */
    
    $token_id = $_POST['token_id'];
    $token_hash = $_POST['token_hash'];
    
}else if(isset($_GET['validate_access_key'])){
    /* Check if an access key is valid
    (calling this from client side is pointless */
    $key_string = $_POST['key_string'];
    
}else if(isset($_GET['check_user_permission'])){
    /* Check if the user owning the access key has a given permission */
    
    $key_string = $_POST['key_string'];
    $perm_name = $_POST['perm_name'];
    
}else if(isset($_GET['kill_access_key'])){
    /* Invalidate an access key (log out) */
    
    $key_string = $_POST['key_string'];
    
}


