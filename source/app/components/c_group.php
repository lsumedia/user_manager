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
            }

            $stmt->close();

            $this->fetch_permissions();
            $this->fetch_members();
        }else{
            throw new Exception("Error accessing the database");
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
    
    
    public function get_permissions(){
        return $this->permissions;
    }
    
    public function get_members(){
        return $this->members;
    }
}
