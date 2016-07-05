<?php

/* 
 * Authenticator Plugin
 * 
 * This class is standalone and can be placed anywhere it is needed 
 * - just make sure to adjust the configuration!
 * 
 */

class authenticator{
    
    public static $config = [
        /* Authentication server root folder with trailing slash */
        'server_root' => 'http://grovestreet.me/projects/user_manager/source/',
        
        /* Prefix for session variables (Should be different for multiple sites 
         * on the same server & domain */
        'session_prefix' => 'm_users',
        
        /* (Optional) If set, users will be redirected to this address after login
        instead of the original requested page. Should be null if not used. */
        'custom_redirect' => null
    ];
    
    private function config(){
        $config = self::$config;
        $config['server_address'] = $config['server_root'] . 'auth.php';
        $config['login_page_address'] = $config['server_root'] . 'auth/?p=login';
        $config['logout_page_address'] = $config['server_root'] . 'auth/?p=logout';
        return $config;
    }
    
    
    public function __construct() {
        session_start();
    }
    
    public function check_login(){
        
        $config = $this->config();
        
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
        
        /* If session key invalid, not registered or timed out */
        if($this->server_check_key() == false ){
            $this->clear_session_key();
            $this->redirect_to_login();
            return false;
        }
        
        
        
    }
   
    public function server_check_key(){
        
        $srv_addr = $this->config()['server_address'];
        
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        $api_url = $srv_addr . "?check_key&key=" . $this->key . "&ip=" . $user_ip;
        
        $data = json_decode(file_get_contents($api_url),true);
        
        if($data['valid'] == true && $data['key'] == $this->key){
            return true;
        }else{
            var_dump($data);
            return false;
        }
        
    }
    
    /**
     * server_check_permission - liase with the server to see if the 
     * user has a given permission
     * 
     * @param string $permission - permission name to check for 
     */
    public function server_check_permission($permission){
       
        $config = $this->config();
        $srv_addr = $config['server_address'];
        
        $api_url = $srv_addr . '?action=check_perm&key=' . $this->key() . '&perm=' . $permission;
        
        
        
    }
    
    /**
     * server_kill_key - liase with the server to invalidate the user's key
     * (logout operation)
     */
    public function server_kill_key(){
        
        $config = $this->config();
        $srv_addr = $config['server_address'];
        
        $api_url = $srv_addr . '?action=invalidate&key=' . $this->key();
        
    }
    
    
    public function set_session_key($key){
        $config = $this->config();
        $sess_prefix = $config['session_prefix'];
        $_SESSION[$sess_prefix . '_key'] = $key;
    }
    
    public function get_session_key(){
        $config = $this->config();
        $sess_prefix = $config['session_prefix'];
        if(isset($_SESSION[$sess_prefix . '_key'])){
            $key = $_SESSION[$sess_prefix . '_key'];
            $this->key = $key;
            return $key;
        }
        return false;
    }
    
    public function clear_session_key(){
        $config = $this->config();
        $sess_prefix = $config['session_prefix'];
        $_SESSION[$sess_prefix . '_key'] = null;
        unset($_SESSION[$sess_prefix . '_key']);
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
    
    public function redirect_url(){
        if(isset(self::$config['custom_redirect'])){
            return self::$config['custom_redirect'];
        }else{
            return current_page_url();
        }
    }
    
    public function redirect_to_login(){
        $config = $this->config();
        
        /* Send current page URL in address to ensure the user can get back here */
        $current = rawurlencode($this->redirect_url());
        
        header('location:' . $config['login_page_address'] . '&redirect=' . $current);
        die();
    }
    
    
    public function logout_url(){
        return $this->config()['logout_page_address'] . '&key=' . $this->key . '&source=' . urlencode($this->redirect_url());
    }
    
    /**
     * status_bug
     * 
     * Displays a small notice on the page which displays current login information
     * and a sign out button
     */
    public function status_bug(){
        $config = $this->config();
        $sess_prefix = $config['session_prefix'];
        ?>
<style>
    .authenticator_logout_main{
        background-color:white;
        width:300px;
        height:65px;
        overflow:hidden;
        position:fixed;
        bottom:50px;
        right:50px;
        box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
    }
</style>
<div class="authenticator_logout_main" id="<?= $sess_prefix ?>_bug">
    <p><a href="<?= $this->logout_url() ?>">Logout</a></p>
    <p><?= $this->key ?></p>
</div>        
<?php
        
    }
    
}

/* This bit from StackOverflow, gotta be honest */
function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }

 function current_page_url() 
{ 
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; 
    $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; 
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
    return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; 
} 