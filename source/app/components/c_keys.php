<?php

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
    
    public static function get_username($key_string){
        global $db;
        
        $query = "SELECT username FROM " . prefix('key') . " WHERE key_value=? LIMIT 1";
        
        $stmt = $db->prepare($query);
        
        $stmt->bind_param('s', $key_string);
        
        $stmt->execute();
        
        $stmt->bind_result($username);
        
        if($stmt->fetch()){
            return $username;
        }
        
        return false;
    }
    
    public static function validate($key_string, $remote_ip){
        
       global $db;
       
       $get_data_query = "SELECT username, last_used, valid, ip_address, key_id FROM " . prefix('key') . ' WHERE key_value=?';
       
       $gd_s = $db->prepare($get_data_query);
       
       $gd_s->bind_param('s', $key_string);
       
       $gd_s->execute();
       
       $gd_s->bind_result($username, $last_used, $valid, $stored_ip, $key_id);
       
       if($gd_s->fetch()){
           
           /* Reject keys set to invalid */
           if($valid == 0){
               return false;
           }
           
           /* Reject users on a different IP address from the original */
           if(strlen($stored_ip > 1) && strcmp($stored_ip, $remote_ip) != 0){
               return false;
           }
           
           /* Reject keys that have timed out */
           
           $gd_s->close();
           
           $update_ts_query = "UPDATE " . prefix('key') . " SET last_used = null WHERE key_id=$key_id";
           
           $res = $db->query($update_ts_query);
           return true;
       }
       
       /* If no matching keys found */
       return false;
        
    }
      
    /* Create new access key */
    public static function generate($username, $ip_address){
        
        global $db;
        
        $default_ip_range = "*";
        if(isset($ip_address)){
            $default_ip_range = $ip_address;
        }
        
        $secure_keystring = bin2hex(openssl_random_pseudo_bytes(32));
        
        $make_key_query = "INSERT INTO " . prefix('key') . " (key_value, username, valid, ip_address) VALUES (?,?,?,?)";
        
        $mk_stmt = $db->prepare($make_key_query);
        
        $valid = 1;
        
        $mk_stmt->bind_param('ssis', $secure_keystring, $username, $valid, $default_ip_range);
        
        try{
            
            $mk_stmt->execute();
        
        }catch(Exception $e){
            
            echo "Exception: $e <br/>Database error: " . $db->error;
        }
        
        $mk_stmt->close();
        
        return $secure_keystring;
    }
    
    
    public static function invalidate($key_string){
        global $db;
        
        $query = "UPDATE " . prefix('key') . " SET valid=0 WHERE key_value=? ";
        
        if($stmt=$db->prepare($query)){
        
            $stmt->bind_param('s', $key_string);

            $stmt->execute();

            $stmt->close();
        }else{
            echo $db->error;
            return false;
        }
        
        $check_query = "SELECT valid FROM " . prefix('key') . " WHERE key_value=?";
        
        if($chk_stmt = $db->prepare($check_query)){
            $chk_stmt->bind_param('s', $key_string);
            $chk_stmt->execute();
            $chk_stmt->bind_result($valid);
            
            $chk_stmt->fetch();
            
            $chk_stmt->close();
            
            if($valid == 1){
                echo "Key not invalidated!<br/>";
                return false;
            }else{
                return true;
            }
        }
        echo "Error - nothing happened!";
        return false;
    }
    
    /* Array of a request number of keys in raw format */
    public static function list_all_raw($limit = 50){
        global $db;
        
        $sel_query = "SELECT * FROM " . prefix('key') . "  ORDER BY key_id DESC LIMIT ?";
        
        $stmt = $db->prepare($sel_query);
        
        $stmt->bind_param('i', $limit);
        
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $clean_results = [];
        
        while($row = $result->fetch_assoc()){
            $clean_results[] = $row;
        }
        
        $stmt->close();
        
        return $clean_results;
    }
}