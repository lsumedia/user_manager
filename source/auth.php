<?php


/* Requests to this file should ONLY be made by server-side applications 
 * 
 * Please ONLY use the Authenticator class to interact with this API!
 * 
 */

require_once('app/init.php');

if(isset($_GET['get_key'])){
    /* Request access key and generate one if the request is valid */
    
    $token_hash = $_POST['token_hash'];
    
}else if(isset($_GET['check_key'])){
    /* Check if an access key is valid
    (calling this from client side is pointless */
    $key_string = $_GET['key'];
    
    $response = ['true'];
    
}else if(isset($_GET['check_perm'])){
    /* Check if the user owning the access key has a given permission */
    
    $key_string = $_POST['key_string'];
    $perm_name = $_POST['perm_name'];
    
}else if(isset($_GET['kill_key'])){
    /* Invalidate an access key (log out) */
    
    $key_string = $_POST['key'];
    
}

echo json_encode($response);


