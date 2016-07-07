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
    
    /** 
     * 
     * @param type $username_or_email - Username or email of the targeted user
     * 
     * @return user if user found, false if not
     */
    public function __construct($username_or_email) {
        global $db;
        
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
        
        $perm_query = "SELECT perm_name FROM " . prefix('user_perm') . " WHERE username=?";
        
        if($stmt2 = $db->prepare($perm_query)){
            $stmt2->bind_param('s', $this->username);

            $stmt2->execute();
            $stmt2->bind_result($perm_name);

            while($stmt2->fetch()){
                $this->permissions[] = $perm_name;
            }
        }
        
    }
    
    public function check_password($password){
        if($this->password_hash == process_password($password)){
            return true;
        }
        return false;
    }
    
    public function has_permission($perm_name){
        if(in_array($perm_name, $this->permissions)){
            return true;
        }
        return false;
    }
    
    public function add_permission($perm_name){
        //If user does not already have this permission
        if(!$this->has_permission($perm_name)){
            //Add permission
        }
    }
    
    public function remove_permission($perm_name){
        $query = "DELETE FROM " . prefix('user_perm') . " WHERE username = ? AND perm_name = ?";
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
            $clean[] = ['Permission' => $permissions[$item]];
        }
        
        return $clean;
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
}

