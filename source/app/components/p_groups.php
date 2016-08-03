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
            
            
        }else{
            //List groups
            
            $groups = group::list_all_clean();
            
            $group_list = new ajax_list($groups, "group_list");
            $group_list->display();
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
            <form method="POST" action="./?p=groups&debug&id=<?= $group->group_id ?>" class="col-lg-12 col-sm-12">
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
                    <button type="submit" class="btn btn-success pull-right">Save changes</button>
                </div>
            </form>
        </div>    
        <?php
    }
    
    public static function group_permission_options($group){
        global $permissions;
        
        foreach($permissions as $perm_name => $perm_title){
            
            if($perm_name != 'super_admin' && $perm_name != 'manage_users'){
                $checked = ($group->has_permission($perm_name))? 'checked' : '';
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
}
