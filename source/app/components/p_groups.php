<?php


class group_page extends page{

    public $name = 'groups';
    public $title = 'Groups';
    
    public function content() {
        
        if(isset($_GET['id'])){
            //Edit group page
            
            $group_id = $_GET['id'];
            
            $e_group = new group($group_id);
            
            ?>
            <!-- Breadcrumbs -->
            <ol class="breadcrumb">
              <li><a href="./?p=groups">Groups</a></li>
              <li><a href="./?p=groups&id=<?= $group_id ?>">Edit <?= $e_group->group_name ?></a></li>
            </ol>
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <h3>Edit group</h3>
                </div>
            </div>
            <?php
            
            if(isset($_POST['group_name'])){
                try{
                    self::group_update_script($e_group);
                    echo "<div class=\"alert alert-success\" role=\"alert\">Updated {$e_group->group_name}</div>";

                }catch(Exception $e){
                    echo "<div class=\"alert alert-danger\" role=\"alert\">{$e->getMessage()}</div>";

                }
                
            }
            
            self::edit_group_form($e_group);
            
            try{
                
                $member_list = new ajax_list($e_group->list_members_clean(), "member_list");
                $member_list->display("Members");
                
            }catch(Exception $e){
                
                echo "<div class=\"alert alert-danger\" role=\"alert\">{$e->getMessage()}</div>";
            
                
            }
            
            
        }else{
            
            if(isset($_GET['new'])){
                self::group_add_script();
            }
            
            if(isset($_GET['delete'])){
                $del_id = $_GET['delete'];
                group::delete_group($del_id);
            }
            
            ?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <button class="btn btn-success pull-right" data-toggle="modal" data-target="#new_group_modal">Add group</button>
    </div>
</div>
            <?php
            //List groups
            
            $groups = group::list_all_clean();
            
            $group_list = new ajax_list($groups, "group_list");
            $group_list->display();
            
            self::new_group_modal();
        }
        
        
    }
    
    public function header_content() {
         if(isset($_GET['id'])){
            $this->title = "";
        }
    }
    
    public static function edit_group_form($group){
        ?>
        <div class="row">
            <form method="POST" action="./?p=groups&id=<?= $group->group_id ?>" class="col-lg-12 col-sm-12">
                <div class="form-group">
                    <label for="group_name">Group name</label>
                    <input type="text" class="form-control" name="group_name" value="<?= $group->group_name ?>" id="group_name"/>
                </div>
                <div class="form-group">
                    <label for="group_dec">Group description</label>
                    <input type="text" class="form-control" name="description" value="<?= $group->description ?>" id="group_desc"/>
                </div>
                <div class="form-group">
                    <p>Group permissions</p>
                </div>
                <?php self::group_permission_options($group) ?>
                <div class="form-group">                    
                    <button 
                        type="button" 
                        class="btn btn-danger pull-right" 
                        onclick="if(confirm('Delete group <?= $group->group_id ?>?')){ window.location.href='./?p=groups&delete=<?= $group->group_id ?>';}">
                        Delete group
                    </button>
                    <button type="submit" class="btn btn-success pull-right" style="margin-right:4px">Save changes</button>
                </div>
            </form>
        </div>    
        <?php
    }
    
    public static function group_permission_options($group){
        global $permissions;
        
        foreach($permissions as $perm_name => $perm_title){
            
            if($perm_name != 'super_admin' && $perm_name != 'manage_users'){
                if($group == null){
                    $checked = '';
                }else{
                    $checked = ($group->has_permission($perm_name))? 'checked' : '';
                }
                ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="group_<?= $perm_name ?>" name="permission[]" value="<?= $perm_name ?>" <?= $checked ?>/><?= $perm_title ?>
                    </label>
                </div>
                <?php
            }
        }
        
    }
    
    public static function group_update_script($group){
        global $db;
        
        
        $group_id = $_GET['id'];
        $group_name = $_POST['group_name'];
        $description = $_POST['description'];
        $permissions = $_POST['permission'];
       
        
        $remove_permissions = array_diff($group->get_permissions(), $permissions);
        $add_permissions = array_diff($permissions, $group->get_permissions());
        
        foreach($remove_permissions as $remove_one_perm){
            $group->remove_permission($remove_one_perm);
        }
        
        foreach($add_permissions as $add_one_perm){
            $group->add_permission($add_one_perm);
        }     
        
        //Update name & description
        $group->update_info($group_name, $description);

    }
    
    public static function group_add_script(){
        
        $group_name = $_POST['group_name'];
        $description = $_POST['description'];
        $permissions = $_POST['permission'];
        
        
        
        try{
            
            //Validate group name length
            if(strlen($group_name) < 3){
                throw new Exception("Please enter a group name at least 3 characters long");
            }
            
            $n_group = group::new_group($group_name, $description, $permissions);
            echo "<div class=\"alert alert-success\" role=\"alert\">Added new group {$n_group->group_name}</div>";

        }catch(Exception $e){
            echo "<div class=\"alert alert-danger\" role=\"alert\">{$e->getMessage()}</div>";
        }
        
    }
    
        public static function new_group_modal(){
        ?>
        <!-- Modal -->
<div class="modal fade" id="new_group_modal" tabindex="-1" role="dialog" aria-labelledby="new_group_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="./?p=groups&new" method="POST">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="new_group_modal_label">Add group</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="group_name">Group name</label>
                    <input type="text" class="form-control" id="group_name" placeholder="Group name"  name="group_name" value="">
                </div>
                <div class="form-group">
                    <label for="group_description">Group description</label>
                    <input type="text" class="form-control" id="group_description" placeholder="Group description" name="description" value="">
                </div>
                <div class="form-group">
                    <?php self::group_permission_options(null) ?>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success">Add new group</button>
            </div>
        </form>
    </div>
  </div>
</div>
<?php
    }

}
