<?php


class group {
    
    public $group_id;
    
    public $group_name;
    
    public $description;
    
    /* String array of permissions contained in this group */
    public $permissions;
    
    /* String array of usernames belonging to this group */
    private $members;
    
    public function __construct($group_id){
       
        global $db;
        global $permissions;
        
        $query = "SELECT group_id, group_name, description FROM " . prefix('group') . " WHERE group_id=?";
        
        if($stmt = $db->prepare($query)){
            $stmt->bind_param('i',$group_id);
            $stmt->execute();

            $stmt->bind_result($id, $name, $desc);

            if($stmt->fetch()){
                $this->group_id = $id;
                $this->group_name = $name;
                $this->description = $desc;
            }else{
                throw new Exception("Group not found");
                $stmt->close();
                return false;
            }

            $stmt->close();

            $this->fetch_permissions();
            $this->fetch_members();
        }else{
            throw new Exception("Error accessing the database");
            return false;
        }
        
    }
    
    public static function new_group($group_name, $description, $permissions){
        global $db;
        
        $query = "INSERT INTO " . prefix('group') . " (group_name, description) VALUES (?,?)";
        
        if($stmt = $db->prepare($query)){
            $stmt->bind_param('ss', $group_name, $description);
            if($stmt->execute()){
                $stmt->close();
            }else{
                $stmt->close();
                throw new Exception("Error adding new user - " . $stmt->error);
                return false;
            }
        }else{
            throw new Exception($db->error);
            return false;
        }
        
        //Get new group object
        $group = new group($db->insert_id);
        
        foreach($permissions as $perm_name){
            $group->add_permission($perm_name);
        }
        
        return $group;
    }
    
    public static function delete_group($group_id){
        global $db;
        
        $safe_id = $db->real_escape_string($group_id);
        
        //Delete user query
        $del_query_1 = "DELETE FROM " . prefix('group') . " WHERE group_id = '{$safe_id}'";
        $del_query_2 = "DELETE FROM " . prefix('group_perm') . " WHERE group_id = '{$safe_id}'";
        $del_query_3 = "DELETE FROM " . prefix('user_group') . " WHERE group_id = '{$safe_id}'";

        if($db->query($del_query_1) && $db->query($del_query_2) && $db->query($del_query_3)){
            echo "<div class=\"alert alert-success\" role=\"alert\">Deleted group {$safe_id}</div>";
        }else{
             echo "<div class=\"alert alert-danger\" role=\"alert\">Error deleting group: $db->error</div>";
        }
    }    
    
    public function fetch_permissions(){
        global $db;
        global $permissions;
        
        $this->permissions = null;
        
        //Get user permissions
        $perm_query = "SELECT perm_name FROM " . prefix('group_perm') . " WHERE group_id=?";
        
        if($stmt2 = $db->prepare($perm_query)){
            $stmt2->bind_param('i', $this->group_id);

            $stmt2->execute();
            $stmt2->bind_result($perm_name);

            while($stmt2->fetch()){
                $this->permissions[] = $perm_name;
            }
        }
    }
    
    public function fetch_members(){
        
        global $db;
        global $permissions;
        
        $this->members = null;
        
        //Get user permissions
        $perm_query = "SELECT username FROM " . prefix('user_group') . " WHERE group_id=?";
        
        if($stmt2 = $db->prepare($perm_query)){
            $stmt2->bind_param('i', $this->group_id);

            $stmt2->execute();
            $stmt2->bind_result($username);

            while($stmt2->fetch()){
                $this->members[] = $username;
            }
        }
    }
    
    public function list_members_clean(){
        global $db;
        
        $query = "SELECT u.username, u.fullname, u.email FROM " . prefix('user_group') . " AS ug,  " . prefix('user') . " AS u WHERE ug.group_id=? AND ug.username=u.username";
        if($stmt2 = $db->prepare($query)){
            $stmt2->bind_param('i', $this->group_id);

            $stmt2->execute();
            $stmt2->bind_result($username, $fullname, $email);

            $clean = [];
            
            while($stmt2->fetch()){
                $clean[] = ['Username' => $username, "Full name" => $fullname, "Email" => $email, "action" => "./?p=users&id={$username}"];
            }
            
            return $clean;
        }
        throw new Exception($db->error);
        return false;
    }
    
    
    public function get_permissions(){
        if($this->permissions == null){
            return [];
        }
        return $this->permissions;
    }
    
    public function get_members(){
        if($this->members == null){
            return [];
        }
        return $this->members;
    }
    
    public function has_permission($perm_name){
        if(in_array($perm_name, $this->permissions)){
            return true;
        }
        return false;
    }
    
    public function add_permission($perm_name){
        global $db;
        global $permissions;
        
        //If group does not already have this permission
        if(!$this->has_permission($perm_name) && array_key_exists($perm_name, $permissions)){
            //Add permission
            $query = "INSERT INTO " . prefix('group_perm') . " (group_id,perm_name) VALUES (?,?)";
            
            if($stmt = $db->prepare($query)){
                $stmt->bind_param("is", $this->group_id, $perm_name);
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
        throw new Exception("Group already has permission or permission \"{$perm_name}\" is not valid");
        return false;
    }
    
    public function remove_permission($perm_name){
        global $db;
        
        $query = "DELETE FROM " . prefix('group_perm') . " WHERE group_id = ? AND perm_name = ?";
        
        if($stmt = $db->prepare($query)){
            $stmt->bind_param("is", $this->group_id, $perm_name);
            if($stmt->execute()){
               
                if($stmt->affected_rows > 0){
                    $stmt->close();
                    $this->fetch_permissions();
                    return true;
                }else{
                    throw new Exception("Permission \"$perm_name\" not removed from $this->group_id - unknown error");
                }
            }
            $stmt->close();
        }
        throw new Exception("Failed to remove permission $perm_name");
        return false;
    }
    
    public function update_info($group_name, $description){
        global $db;
        
        $query = "UPDATE " . prefix('group') . " SET group_name = ?, description = ? WHERE group_id = ?";
        
        if($stmt = $db->prepare($query)){
            
            $stmt->bind_param('ssi', $group_name, $description, $this->group_id);
            
            if($stmt->execute()){
                $this->group_name = $group_name;
                $this->description = $description;
                $stmt->close();
            }else{
                $stmt->close();
                throw new Exception("Updating group info failed - " . $stmt->error);
            }
        }else{
            throw new Exception("Error - " . $db->error);
        }
    }
    
    public static function list_all_raw(){
        global $db;
        $query = "SELECT group_id, group_name, description FROM " . prefix('group');
        
        $results = [];
        
        if($stmt = $db->prepare($query)){
            $stmt->execute();

            $stmt->bind_result($id, $name, $desc);
            
            
            while($stmt->fetch()){
                $results[] = ['group_id' => $id, 'group_name' => $name, 'description' => $desc];
            }

            $stmt->close();
            
        }else{
            throw new Exception("Error accessing the database");
        }
        
        return $results;
    }
    
    public static function list_all_objects(){
        $raw = self::list_all_raw();
        
        $objs = [];
        
        foreach($raw as $one_raw){
            $objs[] = new group($one_raw['group_id']);
        }
        
        return $objs;
    }
    
    public static function list_all_clean(){
        $raw = self::list_all_objects();
        $clean = [];
        
        foreach($raw as $one){
            $p_imp = implode($one->permissions,', ');
            $p_string = (strlen($p_imp) > 50)? substr($p_imp, 0, 50) . '...' : $p_imp; 
            $clean[] = [
                'Group name' => $one->group_name, 
                'Group description' => $one->description, 
                'Permissions' => $p_string,
                'action' => './?p=groups&id=' . $one->group_id
                ];
        }
        
        return $clean;
    }
}
