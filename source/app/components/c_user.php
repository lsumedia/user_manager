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
        $this->raw->dp_url = $this->dp_url;
        
        //Group permissions
        
        //Super user permission - add all permissions to user object
        if(in_array('super_admin', $this->permissions)){
            $this->permissions = null;
            foreach($permissions as $perm => $title){
                $this->permissions[] = $perm;
            }
        }
        
        
        //Fetch gravatar
        if(strlen($this->dp_url) < 1){
            $this->get_gravatar_image();
        }
    }
    
    public function add_user($username, $fullname, $email, $dp_url, $bio, $password){
        $new_hash = process_password($password);
                
        $username_valid = !(preg_match('/\s/',$username)) && (strlen($username) > 3);
        $password_valid = (strlen($password) > 7);

        //Validation
        if($username_valid && $password_valid){

            //Update user query
            $query = "INSERT INTO " . prefix('user') . " (username,fullname,email,dp_url,bio,password) VALUES (?,?,?,?,?,?)";

            if($stmt = $db->prepare($query)){

                //Bind variables
                $stmt->bind_param("ssssss", $new_username, $new_fullname, $new_email, $new_dp_url, $new_bio, $new_hash);

                if($stmt->execute()){
                    echo "<div class=\"alert alert-success\" role=\"alert\">Added new user $new_username</div>";

                

                    $stmt->close();

                    $created_user = new user($new_username);

                    foreach($default_permissions as $perm_name){
                        $created_user->add_permission($perm_name);
                    }

                    return $created_user;
                
                    
                }else{
                    throw new Exception("Error adding new user");
                }
            }
        }else{
            if(!$username_valid){
                throw new Exception("Username invalid or taken");
            }
            if(!$password_valid){
                throw new Exception("Password must be at least 8 characters long");
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
        
        //If user does not already have this permission
        if(!$this->has_permission($perm_name)){
            //Add permission
            $query = "INSERT INTO " . prefix('user_perm') . " (username,perm_name) VALUES (?,?)";
            
            if($stmt = $db->prepare($query)){
                $stmt->bind_param("ss", $this->username, $perm_name);
                if($stmt->execute()){
                    $stmt->close();
                    return true;
                }
                $stmt->close();
            }
            return false;
        }
    }
    
    /**
     * Remove permission from user
     * 
     * true if succeeded, false if failed
     * @param type $perm_name
     * @return boolean
     */
    public function remove_permission($perm_name){
        $query = "DELETE FROM " . prefix('user_perm') . " WHERE username = ? AND perm_name = ?";
        
        if($stmt = $db->prepare($query)){
                $stmt->bind_param("ss", $this->username, $perm_name);
                if($stmt->execute()){
                    $stmt->close();
                    return true;
                }
                $stmt->close();
            }
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
    
    public function list_individual_permissions(){
        global $permissions;
        
        $raw = $this->individual_permissions();
        $clean = [];
        
        foreach($raw as $perm_name){
            $clean[] = ['Permission' => $permissions[$perm_name], 'Permission code' => $perm_name];
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
    
    /**
     * new_user
     * 
     * Static function to make a new user
     * @returns user User
     */
     public static function new_user($username, $email, $password, $fullname = null, $bio = null, $dpurl = null){
        global $db;
        global $default_permissions;
         
         
        //Hash password
        $password_hash = process_password($password);
        
        //Write new user to database
        
        //Get object for new user
        
        //Add default permissions
        
        foreach($default_permissions as $d_perm){
            //add $d_perm
        }
         
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

