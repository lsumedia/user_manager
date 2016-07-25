<?php

/* 
 * The MIT License
 *
 * Copyright 2016 LSU Media.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/* Users model */


function process_password($password){
    return hash('SHA256', $password);
}

class user{
    
    public $username;
    
    public $fullname;
    
    public $email;
   
    public $bio;
    
    public $dp_url;
    
    private $password_hash;
    
    public $permissions;
    
    public $group_ids;
    
    public $groups;
    
    public $raw;
    
    /** 
     * 
     * @param type $username_or_email - Username or email of the targeted user
     * 
     * @return user if user found, false if not
     */
    public function __construct($username_or_email) {
        global $db;
        global $permissions;
        
        $query = "SELECT username, fullname, email, bio, dp_url, password FROM " . prefix('user') . " WHERE (username=? OR email=?)";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss',$username_or_email, $username_or_email);
        $stmt->execute();
        
        $stmt->bind_result($un, $fn, $em, $bio, $dp, $pw);
        
        if($stmt->fetch()){
            $this->username = $un;
            $this->fullname = $fn;
            $this->email = $em;
            $this->bio = $bio;
            $this->dp_url = $dp;
            $this->password_hash = $pw;
        }else{
            throw new Exception("User not found");
        }
        
        $stmt->close();
        
        $this->fetch_permissions();
        
        $this->raw->dp_url = $this->dp_url;
        
        //Fetch gravatar
        if(strlen($this->dp_url) < 1){
            $this->get_gravatar_image();
        }
    }
    
    /**
     * Fetch permissions data from database
     * 
     * @global type $db
     */
    private function fetch_permissions(){
        global $db;
        global $permissions;
        
        $this->permissions = null;
        
        //Get user permissions
        $perm_query = "SELECT perm_name FROM " . prefix('user_perm') . " WHERE username=?";
        
        if($stmt2 = $db->prepare($perm_query)){
            $stmt2->bind_param('s', $this->username);

            $stmt2->execute();
            $stmt2->bind_result($perm_name);

            while($stmt2->fetch()){
                $this->permissions[] = $perm_name;
            }
        }
        
        $this->raw->permissions = $this->permissions;
        
        $stmt2->close();
        
        //Group permissions
        try{
            $this->fetch_groups();

            foreach($this->group_ids as $one_group_id){

                $one_group = new group($one_group_id);
                
                $group_perms = $one_group->get_permissions();
                
                foreach($group_perms as $one_perm){
                    if(!in_array($one_perm, $this->permissions)){
                        $this->permissions[] = $one_perm;
                    }
                }
            }
        } catch (Exception $ex) {

        }
        
        //Super user permission - add all permissions to user object
        if(in_array('super_admin', $this->permissions)){
            $this->permissions = null;
            foreach($permissions as $perm => $title){
                $this->permissions[] = $perm;
            }
        }
    }
    
    public function fetch_groups(){
        
        global $db;
        global $permissions;
        
        $this->groups = null;
        
        //Get user permissions
        $perm_query = "SELECT group_id FROM " . prefix('user_group') . " WHERE username=?";
        
        if($stmt2 = $db->prepare($perm_query)){
            $stmt2->bind_param('s', $this->username);

            $stmt2->execute();
            $stmt2->bind_result($group_id);

            while($stmt2->fetch()){
                $this->group_ids[] = $group_id;
            }
        }
        
        $stmt2->close();
    }
    
    public static function add_user($username, $fullname, $email, $dp_url, $bio, $password){
        
        global $db;
        global $permissions;
        global $default_permissions;
        global $auth;
        
        //Hash password
        $hash = process_password($password);
                
        //Validation variables
        $username_valid = !(preg_match('/\s/',$username)) && (strlen($username) > 3);
        $password_valid = (strlen($password) > 7);
        $email_valid = (filter_var($email, FILTER_VALIDATE_EMAIL) !== false);

        //Validation
        if($username_valid && $password_valid && $email_valid){

            //Update user query
            $query = "INSERT INTO " . prefix('user') . " (username,fullname,email,dp_url,bio,password) VALUES (?,?,?,?,?,?)";

            if($stmt = $db->prepare($query)){

                //Bind variables
                $stmt->bind_param("ssssss", $username, $fullname, $email, $dp_url, $bio, $hash);

                if($stmt->execute()){
                    
                    $stmt->close();

                    $created_user = new user($username);

                    foreach($default_permissions as $perm_name){
                        try{
                            $created_user->add_permission($perm_name);
                        }catch(Exception $e){
                            throw new Exception("Some default permissions were not valid. Please check your config file.");
                        }
                    }

                    return $created_user;
                
                    
                }else{
                    throw new Exception("Error adding new user: " . $stmt->error);
                }
            }
        }else{
            if(!$username_valid){
                throw new Exception("Username invalid or taken");
            }
            if(!$password_valid){
                throw new Exception("Password must be at least 8 characters long");
            }
            if(!$email_valid){
                throw new Exception("Please enter a valid email address");
            }
        }
        return false;
       
    }
    
    public function check_password($password){
        if($this->password_hash == process_password($password)){
            return true;
        }
        return false;
    }
    
    public function change_password($newpassword){
        global $db;
        
        $hash = process_password($newpassword);
        
        $query = "UPDATE " . prefix('user') . " SET password=? WHERE username=?";
        if($stmt = $db->prepare($query)){
            $stmt->bind_param("ss", $hash, $this->username);
            if($stmt->execute()){
                $stmt->close();
                return true;
            }
        }
        $stmt->close();
        return false;
    }
    
    public function has_permission($perm_name){
        if(in_array($perm_name, $this->permissions)){
            return true;
        }
        return false;
    }
    
    /** Remove permission from users
     * 
     * true if succeeded, false if failed
     * @global type $db
     * @param type $perm_name
     * @return boolean
     */
    public function add_permission($perm_name){
        global $db;
        global $permissions;
        
        //If user does not already have this permission
        if(!$this->has_permission($perm_name) && array_key_exists($perm_name, $permissions)){
            //Add permission
            $query = "INSERT INTO " . prefix('user_perm') . " (username,perm_name) VALUES (?,?)";
            
            if($stmt = $db->prepare($query)){
                $stmt->bind_param("ss", $this->username, $perm_name);
                if($stmt->execute()){
                    $stmt->close();
                    $this->fetch_permissions();
                    return true;
                }else{
                    throw new Exception($stmt->error);
                }
                $stmt->close();
            }else{
                throw new Exception($db->error);
            }
        }
        throw new Exception("User already has permission or permission \"{$perm_name}\" is not valid");
        return false;
    }
    
    /**
     * Remove permission from user
     * 
     * true if succeeded, false if failed
     * @param type $perm_name
     * @return boolean
     */
    public function remove_permission($perm_name){
        global $db;
        
        $query = "DELETE FROM " . prefix('user_perm') . " WHERE username = ? AND perm_name = ?";
        
        if($stmt = $db->prepare($query)){
            $stmt->bind_param("ss", $this->username, $perm_name);
            if($stmt->execute()){
               
                if($stmt->affected_rows > 0){
                    $stmt->close();
                    return true;
                }else{
                    throw new Exception("Permission \"$perm_name\" not removed from $this->username - unknown error");
                }
            }
            $stmt->close();
        }
        throw new Exception("Failed to remove permission $perm_name");
        return false;
    }
    
    public function all_permissions(){
        return $this->permissions;
    }
    
    /**
     * list_permissions
     * 
     * Returns list of permissions for use in an ajax_list
     */
    public function list_permissions(){
        global $permissions;
        
        $list = $this->permissions;
        
        $clean = [];
        
        foreach($list as $item){
            $clean[] = ['Permission' => $permissions[$item], "Permission code" => $item];
        }
        
        return $clean;
    }
    
    public function individual_permissions(){
        //Initialise database object
        global $db;
        
        //Array to store results
        $ind_perms = [];
        
        $query = "SELECT perm_name FROM " . prefix('user_perm') . " WHERE username=?";
        if($stmt = $db->prepare($query)){
            $stmt->bind_param('s', $this->username);
            $stmt->execute();
            
            $stmt->bind_result($perm_name);
            while($stmt->fetch()){
                $ind_perms[] = $perm_name;
            }
        }
        
        return $ind_perms;
        
    }
    
    /**ajaxList compatible dataset for displaying/editing
     * user individual permissions
     * 
     * @global type $permissions
     * @return type
     */
    public function list_individual_permissions(){
        global $permissions;
        
        $raw = $this->individual_permissions();
        $clean = [];
        
        foreach($raw as $perm_name){
            $clean[] = [
                'Permission' => $permissions[$perm_name], 
                'Permission code' => $perm_name, 
                '' => "<a href=\"./?p=users&id={$this->username}&remove_perm={$perm_name}\"><button class=\"btn btn-danger pull-right\">Remove</button></a>"
                ];
        }
        
        return $clean;
    }
    
    
    public function get_gravatar_image(){
        $this->dp_url = self::calculate_gravatar_url($this->email);
    }
    
    public static function calculate_gravatar_url($email){
        $hash = md5(strtolower(trim($email)));
        
        return "https://www.gravatar.com/avatar/{$hash}";
    }
    
    public static function list_all(){
        global $db;
        
        $query = "SELECT * FROM " . prefix('user');
        
        $clean = [];
        
        $result = $db->query($query);
        
        while($row = $result->fetch_assoc()){
            $clean[] = ['Username' => $row['username'], 'Full name' => $row['fullname'] , 'Email Address' => $row['email'], 'action' => './?p=users&id=' . $row['username']];
        }
        return $clean;
    }
    
    /**
     * Return array of all users' public information (excluding passwords & emails)
     */
    public static function list_public_raw(){
        global $db;
        
        $query = "SELECT username, fullname, bio, email, dp_url FROM " . prefix('user');
        
        $res = [];
        
        $result = $db->query($query);
        
        while($row = $result->fetch_assoc()){
            if(strlen($row['dp_url']) < 1){
                $row['dp_url'] = self::calculate_gravatar_url($row['email']);
            }
            
            //Obscure email
            unset($row['email']);
            
            $res[] = $row;
        }
        return $res;
    }
    
    public function get_data(){
        $data = [];
        foreach($this as $key => $value){
            $data[$key] = $value;
        }
        return $data;
    }
}

