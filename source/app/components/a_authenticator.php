<?php

/* 
 * Authenticator Plugin
 * 
 * This class is standalone and can be placed anywhere it is needed 
 * - just make sure to adjust the configuration!
 * 
 */

/* This bit from StackOverflow, gotta be honest */
function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }

 function current_page_url() 
{ 
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; 
    $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; 
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
    return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; 
} 

class authenticator{
    
    public static $config = [
        /* Authentication server address (Link to auth.php), no trailing slash */
        'server_address' => 'http://grovestreet.me/projects/user_manager/auth.php',
        
        /* Address of login page to redirect to if key fails */
        'login_page_address' => 'auth/?p=login',
        
        /* Prefix for session variables (Should be different for multiple sites 
         * on the same server & domain */
        'session_prefix' => 'm_users',
    ];
    
    /*
    public function __construct() {
        
    }*/
    
    public function check_session_key(){
        
        $config = self::$config;
        
        $sess_prefix = $config['session_prefix'];
        
        
        //Set or overwrite session stored key
        if($key = $this->get_url_key()){ 
            
            $this->set_session_key($key);
            
        }else if($key = $this->get_post_key()){
            
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
        
        /* Send current page URL in address to ensure the user can get back here */
        $current = current_page_url();
        
        header('location:' . $config['login_page_address'] . '&redirect=' . $current);
        die();
    }
    
}