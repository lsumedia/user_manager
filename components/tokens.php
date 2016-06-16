<?php


/**
 * login_token
 * 
 * Login tokens provide a way of 
 * 
 * do we need this
 * probably not
 */
class login_token{
    
    public $id;
    
    public $token_string;
    
    
    
}


/**
 * access_key
 * 
 * Access keys are granted to give users acess to the system for a certain
 * period of time. Keys are linked to user accounts, which allows permission 
 * checking, and are tied to IP addresses so sharing a link with a key in the 
 * URL will not work for a user at a different adress. Attempting to use an
 * access key from a different IP address will invalidate the key and prompt
 * the user to log in again.
 */
class access_key{
    
    public $id;
    public $key_string;
    
    public $username;
    
    public $ip_address;
    
    public static function generate($username){
        $key = new access_key();
        $key->username = $username;
        $key->ip_address = $_SERVER['REMOTE_ADDR'];
        
    }
    
    public static function validate($key_string){
        
    }
    
    
}
