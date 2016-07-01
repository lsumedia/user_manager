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
        'server_address' => 'http://grovestreet.me/projects/user_manager/source/auth.php',
        
        /* Address of login page to redirect to if key fails */
        'login_page_address' => 'http://grovestreet.me/projects/user_manager/source/auth/?p=login',
        
        /* Address of logout page (for logout button) */
        'logout_page_address' => 'http://grovestreet.me/projects/user_manager/source/auth/?p=logout',
        
        
        /* Prefix for session variables (Should be different for multiple sites 
         * on the same server & domain */
        'session_prefix' => 'm_users',
    ];
    
    
    public function __construct() {
        session_start();
    }
    
    public function check_login(){
        
        $config = self::$config;
        
        $sess_prefix = $config['session_prefix'];
        
        
        //Set or overwrite session stored key
        if($key = $this->get_url_key()){ 
            
            $this->set_session_key($key);
            
        }else if($key = $this->get_post_key()){
            
            $this->set_session_key($key);
            
        }
        
        if($this->get_session_key() == false || $this->get_session_key() != 5){
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
    
    /**
     * server_check_permission - liase with the server to see if the 
     * user has a given permission
     * 
     * @param string $permission - permission name to check for 
     */
    public function server_check_permission($permission){
       
        $config = self::$config;
        $srv_addr = $config['server_address'];
        
        $api_url = $srv_addr . '?action=check_perm&key=' . $this->key() . '&perm=' . $permission;
        
    }
    
    /**
     * server_kill_key - liase with the server to invalidate the user's key
     * (logout operation)
     */
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
    
    /**
     * status_bug
     * 
     * Displays a small notice on the page which displays current login information
     * and a sign out button
     */
    public function status_bug(){
        $config = self::$config;
        $sess_prefix = $config['session_prefix'];
        ?>
<style>
    #<?= $sess_prefix ?>_bug{
        background-color:white;
        width:300px;
        height:65px;
        position:fixed;
        bottom:50px;
        right:50px;
        box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
    }
</style>
<div id="<?= $sess_prefix ?>_bug" class="<?= $sess_prefix ?>_bug">
    <?= $this->key ?>
    <p><a href="<?= $config['logout_page_address'] . '&key=' . $this->key ?>">Logout</a></p>
</div>        
<?php
        
    }
    
}