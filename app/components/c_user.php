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


function process_password($password, $salt){
    return hash('SHA256', $password . $salt);
}

class user{
    
    public $username;
    
    public $fullname;
    
    public $email;
   
    private $password_hash;
    
    private $password_salt;
    
    private $permissions;
    
    public function check_password($password){
        if($this->password_hash === process_password($password, $this->salt)){
            return true;
        }
        return false;
    }
    
    public static function get_user($username_or_email){
        
    }
    
    public function has_permission($perm_name){
        
    }
    
    public function add_permission($perm_name){
        //If user does not already have this permission
        if(!$this->has_permission($perm_name)){
            //Add permission
        }
    }
    
    public function remove_permission($perm_name){
        
    }
}
