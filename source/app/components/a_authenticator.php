<?php

/* 
 * Authenticate Plugin
 */

class authenticator{
    
    public static $config = [
        /* Authentication server address */
        'server_address' => 'auth/ajax.php',
        
        /* Address of login page to redirect to if key fails */
        'login_page_address' => 'auth/login',
        
        /* Prefix for session variables */
        'session_prefix' => 'm_users',
    ];
    
    /*
    public function __construct() {
        
    }*/
    
    public function check_session_key(){
        
        $config = self::$config;
        
        $sess_prefix = $config['session_prefix'];
        
        
        //Set or overwrite session stored key
        if($key = get_url_key()){ 
            
            $this->set_session_key($key);
            
        }else if($key = get_post_key()){
            
            $this->set_session_key($key);
            
        }
        
        if($this->get_session_key() == false){
            /* Redirect to login page if no valid key set */
            $this->redirect_to_login();
            /* Ensure function is broken, even though script should die */
            return false;
        }
        
        
        
        
    }
    
    public function server_check_key(){
        
        $config = self::$config;
        $srv_addr = $config['server_address'];
        
        $api_url = $srv_addr . '?action=validate&key=' . $this->key();
        
    }
    
    public function server_check_permission($permission){
       
        $config = self::$config;
        $srv_addr = $config['server_address'];
        
        $api_url = $srv_addr . '?action=check_perm&key=' . $this->key() . '&perm=' . $permission;
        
    }
    
    
    public function server_kill_key(){
        
        $config = self::$config;
        $srv_addr = $config['server_address'];
        
        $api_url = $srv_addr . '?action=invalidate&key=' . $this->key();
        
    }
    
    
    public function set_session_key($key){
        $config = self::$config;
        $sess_prefix = $config['session_prefix'];
        $_SESSION[$sess_prefix . '_key'] = $key;
    }
    
    public function get_session_key(){
        $config = self::$config;
        $sess_prefix = $config['session_prefix'];
        if(isset($_SESSION[$sess_prefix . '_key'])){
            $key = $_SESSION[$sess_prefix . '_key'];
            $this->key = $key;
            return $key;
        }
        return false;
    }
    
    
    /* Get session key stored in GET variable */
    public function get_url_key(){
         if(isset($_GET['key'])){
            return $_GET['key'];
        }
        return false;
    }
    
    /* Get session key stored in POST variable */
    public function get_post_key(){
        if(isset($_POST['key'])){
            return $_POST['key'];
        }
        return false;
    }
    
    public function redirect_to_login(){
        $config = self::$config;
        header('location:' . $config['login_page_address']);
        die();
    }
    
}