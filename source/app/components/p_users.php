<?php

/* Users view */

class users_page extends page{

    public $name = 'users';
    public $title = 'Users';
    
    public function content() {
        global $db;
        global $auth;
        global $default_permissions;
        global $permissions;
        
        if(isset($_GET['id'])){
            //Edit user page
            
            //Load user object
            
            ?>
            <!-- Breadcrumbs -->
            <ol class="breadcrumb">
              <li><a href="./?p=users">Users</a></li>
              <li><a href="./?p=users&id=<?= $_GET['id'] ?>">Edit <?= $_GET['id'] ?></a></li>
            </ol>
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <h3>Edit profile</h3>
                </div>
            </div>
            <?php
            
            if(isset($_POST['username'])){
                $e_user = new user($_GET['id']);
                
                //If post request
                $fullname = $_POST['fullname'];
                $email = $_POST['email'];
                $dp_url = $_POST['dp_url'];
                $bio = $_POST['bio'];

                $password = $_POST['reset_password'];

                //Update user query
                $query = "UPDATE " . prefix('user') . " SET fullname=?, email=?, dp_url=?, bio=? WHERE username=?";

                if($stmt = $db->prepare($query)){

                    $stmt->bind_param("sssss", $fullname, $email, $dp_url, $bio, $e_user->username);
                    if($stmt->execute()){
                        $stmt->close();

                        echo "<div class=\"alert alert-success\" role=\"alert\">Saved changes</div>";

                        if(strlen($password) > 7){
                            //Password meets requirements, update
                            if($e_user->change_password($password)){
                                //header('location:./auth/?p=profile&updated&goodpassword');
                                echo "<div class=\"alert alert-success\" role=\"alert\">Changed password</div>";

                            }else{
                                echo "<div class=\"alert alert-warning\" role=\"alert\">Error changing password</div>";
                            }

                        }else if(strlen($password) > 0){
                            //Password fails requirements, send error
                            //header('location:./auth/?p=profile&updated&badpassword');                   
                             echo "<div class=\"alert alert-warning\" role=\"alert\">Password must be at least 8 characters long</div>";

                        }
                    }
                    else{
                        $stmt->close();
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Error saving changes - please check email address is unique</div>";
                    }
                }else{
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Error saving changes</div>";
                }

            }
            
            //Try to display user data
            try{
                //Object for applying permissions/groups updates to
                $c_user = new user($_GET['id']);
                
                //Add permission script
                if(isset($_POST['perm_name'])){
                    $add_perm_name = $_POST['perm_name'];
                    try{
                        $c_user->add_permission($add_perm_name);
                        echo "<div class=\"alert alert-success\" role=\"alert\">Added '{$permissions[$add_perm_name]}' to user {$c_user->username}</div>";
                    } catch (Exception $ex) {
                        echo "<div class=\"alert alert-danger role=\"alert\">{$ex->getMessage()}</div>";
                    }
                }
                
                //Remove permission script
                if(isset($_GET['remove_perm'])){
                    $remove_perm_name = $_GET['remove_perm'];
                    if($c_user->username != $auth->profile()['username']){
                        try{

                            $c_user->remove_permission($remove_perm_name);
                            echo "<div class=\"alert alert-success\" role=\"alert\">Removed '{$permissions[$remove_perm_name]}' from user {$c_user->username}</div>";
                        } catch (Exception $ex) {
                            echo "<div class=\"alert alert-danger role=\"alert\">{$ex->getMessage()}</div>";
                        }
                    }else{
                        echo "<div class=\"alert alert-danger role=\"alert\">You cannot remove permissions from your own account</div>";
                    }
                }
                
                //Add group script
                if(isset($_POST['group_id'])){
                    $add_group_id = $_POST['group_id'];
                    try{
                        $c_user->add_group($add_group_id);
                        echo "<div class=\"alert alert-success\" role=\"alert\">Added user {$c_user->username} to group {$add_group_id}</div>";
                    } catch (Exception $ex) {
                        echo "<div class=\"alert alert-danger role=\"alert\">{$ex->getMessage()}</div>";
                    }
                }
                //Remove group script
                if(isset($_GET['remove_group'])){
                    $remove_group_id = $_GET['remove_group'];
                    if($c_user->username != $auth->profile()['username']){
                        try{

                            $c_user->remove_group($remove_group_id);
                            echo "<div class=\"alert alert-success\" role=\"alert\">Removed user {$c_user->username} from group </div>";
                        } catch (Exception $ex) {
                            echo "<div class=\"alert alert-danger role=\"alert\">{$ex->getMessage()}</div>";
                        }
                    }else{
                        echo "<div class=\"alert alert-danger role=\"alert\">You cannot remove yourself from a group</div>";
                    }
                }
                
                ?>
                <!-- Edit user form -->
                <form action="" method="POST" autocomplete="off" id="edit_user_form">
                    <div class="form-group">
                        <label for="user-username">Username</label>
                        <input type="text" class="form-control disabled" id="user-username" readonly placeholder="Username"  name="username" value="<?= $c_user->username ?>">
                    </div>
                    <div class="form-group">
                        <label for="user-fullname">Full name</label>
                        <input type="text" class="form-control" id="user-email" placeholder="Full name" name="fullname" value="<?= $c_user->fullname ?>">
                    </div>
                    <div class="form-group">
                        <label for="user-email">Email address</label>
                        <input type="email" class="form-control" id="user-email" placeholder="Email address" name="email" value="<?= $c_user->email ?>">
                    </div>
                    <div class="form-group">
                        <label for="user-dp">Profile picture (leave blank to use Gravatar)</label>
                        <input type="url" class="form-control" id="user-dp" placeholder="Profile picture URL"  name="dp_url" value="<?= $c_user->raw->dp_url ?>">
                    </div>
                    <div class="form-group">
                        <label for="user-bio">Bio</label>
                        <textarea class="form-control" name="bio" id="user-bio"><?= $c_user->bio ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="user-pw">Reset password</label>
                        <input type="password" class="form-control" id="user-pw" name="reset_password" placeholder="Password reset" autocomplete="off">
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <?php 
                            //Don't display button to delete user's own account
                            if($c_user->username != $auth->profile()['username']){
                        ?>
                            <button class="btn btn-danger pull-right" onclick="if(confirm('Delete user <?= $c_user->username ?>?')){ window.location.href='./?p=users&delete=<?= $c_user->username ?>';}">Delete user</button>
                            <?php }else{ ?>            
                        <button class="btn btn-default disabled pull-right">Delete user</button>
                            <?php } ?>
                        <button type="submit" class="btn btn-success pull-right" style="margin-right:4px;" onclick="document.getElementById('edit_user_form').submit();">Save changes</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <h3>Group memberships</h3>
                    </div>
                </div>
                
                <?php
                self::add_group_form($c_user);
                
                $g_list = new ajax_list($c_user->list_groups(), 'group_list');
                $g_list->display();
                ?>
                
                <!-- Permissions edit section -->
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <h3>Permissions</h3>
                    </div>
                </div>

                <!-- Individual permissions -->
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <h4>Individual permissions</h4>
                    </div>
                </div>
                <?php
                self::add_permission_form($c_user);
                
                $i_list = new ajax_list($c_user->list_individual_permissions(), 'i_perm_list');
                $i_list->display();
                ?>

                <!-- All permissions -->
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <h4>All inherited permissions</h4>
                    </div>
                </div>
                <?php            
                $all_perm_list = new ajax_list($c_user->list_permissions(), 'all_perm_list');
                $all_perm_list->display();
                
                }catch(Exception $e){

                echo "<div class=\"alert alert-danger\" role=\"alert\">Username \"{$_GET['id']}\" not recognised</div>";
            }
        }else{
            //ADD USER
            //PAGE: All Users
            
            //Add new user
            if(isset($_GET['new'])){
                
                $new_username = $_POST['username'];
                //If post request
                $new_fullname = $_POST['fullname'];
                $new_email = $_POST['email'];
                $new_dp_url = $_POST['dp_url'];
                $new_bio = $_POST['bio'];

                $new_password = $_POST['password'];
                
                //Create new user and write to object
                try{
                    $new_user = user::add_user($new_username, $new_fullname, $new_email, $new_dp_url, $new_bio, $new_password);
                    
                    if(isset($_POST['group']) && $_POST['group'] > 0 ){
                        $new_user->add_group($_POST['group']);
                    }
                    
                    echo "<div class=\"alert alert-success\" role=\"alert\">Added new user {$new_user->username}</div>";
                } catch (Exception $ex) {
                    echo "<div class=\"alert alert-danger\" role=\"alert\">{$ex->getMessage()}</div>";
                }
                
            }else if(isset($_GET['delete'])){
                //DELETE USER
                
                //User object to delete
                $d_user = new user($_GET['delete']);
                
                if($d_user->username != $auth->profile()['username']){
                
                    $safe_username = $db->escape_string($d_user->username);
                    
                    //Delete user query
                    $del_query_1 = "DELETE FROM " . prefix('user') . " WHERE username='{$safe_username}'";
                    $del_query_2 = "DELETE FROM " . prefix('user_perm') . " WHERE username='{$safe_username}'";
                    $del_query_3 = "DELETE FROM " . prefix('user_group') . " WHERE username='{$safe_username}'";
                 
                    if($db->query($del_query_1) && $db->query($del_query_2) && $db->query($del_query_3)){
                        echo "<div class=\"alert alert-success\" role=\"alert\">Deleted user {$d_user->username}</div>";
                    }else{
                         echo "<div class=\"alert alert-danger\" role=\"alert\">Error deleting user {$d_user->username}: $db->error</div>";
                    }
                }else{
                    echo "<div class=\"alert alert-danger\" role=\"alert\">You cannot delete your own account</div>";

                }
                
            }
            
?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#new_user_modal">Add user</button>
    </div>
</div>


<?php
            
            /* All user page */

            $list = new ajax_list(user::list_all(), 'user_list');
            $list->display();
            
            self::new_user_modal();
        }

    
    }
    
    public function header_content() {
        if(isset($_GET['id'])){
            $this->title = "";
        }
    }
    
    public static function new_user_modal(){
        ?>
        <!-- Modal -->
<div class="modal fade" id="new_user_modal" tabindex="-1" role="dialog" aria-labelledby="new_user_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="./?p=users&new" method="POST">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="new_user_modal_label">Add user</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="user-username">Username*</label>
                    <input type="text" class="form-control" id="user-username" placeholder="Username"  name="username" value="<?= $c_user->username ?>">
                </div>
                <div class="form-group">
                    <label for="user-fullname">Full name*</label>
                    <input type="text" class="form-control" id="user-email" placeholder="Full name" name="fullname" value="<?= $c_user->fullname ?>">
                </div>
                <div class="form-group">
                    <label for="user-email">Email address*</label>
                    <input type="email" class="form-control" id="user-email" placeholder="Email address" name="email" value="<?= $c_user->email ?>">
                </div>
                <div class="form-group">
                    <label for="user-dp">Profile picture (leave blank to use Gravatar)</label>
                    <input type="url" class="form-control" id="user-dp" placeholder="Profile picture URL"  name="dp_url" value="<?= $c_user->dp_url ?>">
                </div>
                <div class="form-group">
                    <label for="user-bio">Bio</label>
                    <textarea class="form-control" name="bio" id="user-bio"><?= $c_user->bio ?></textarea>
                </div>
                <div class="form-group">
                    <label for="user-group">Add to group</label>
                    <select class="form-control" name="group" id="user-group" >
                        <?= self::select_groups() ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="user-pw">Password*</label>
                    <input type="password" class="form-control" id="user-pw" name="password" placeholder="Password">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Add new user</button>
            </div>
        </form>
    </div>
  </div>
</div>
<?php
    }
    
    public static function add_permission_form($user){
        ?>
        <div class="row">
            <form method="POST" action="./?p=users&id=<?= $user->username ?>">
                <div class="col-lg-10 col-sm-12">
                    <select class="form-control" name="perm_name" >
                        <?= self::select_permissions() ?>
                    </select>
                </div>
                <div class="col-lg-2 col-sm-12">
                    <button type="submit" class="btn btn-success form-control">Add</button>
                </div>
            </form>
        </div>
        
        <?php
    }
    
    
    public static function select_permissions(){
        global $permissions;
        
        $html = '<option value="">--</option>';
        foreach($permissions as $code => $name){
            $html .= "<option value=\"$code\">$name</option>";
        }
        return $html;
    }
    
    public static function add_group_form($user){
        ?>
        <div class="row">
            <form method="POST" action="./?p=users&id=<?= $user->username ?>">
                <div class="col-lg-10 col-sm-12">
                    <select class="form-control" name="group_id" >
                        <?= self::select_groups() ?>
                    </select>
                </div>
                <div class="col-lg-2 col-sm-12">
                    <button type="submit" class="btn btn-success form-control">Add</button>
                </div>
            </form>
        </div>
        
        <?php
    }
    
    public static function select_groups(){
        global $permissions;
        
        $html = '<option value="">--</option>';
        foreach(group::list_all_raw() as $group){
            $html .= "<option value=\"{$group['group_id']}\">{$group['group_name']}</option>";
        }
        return $html;
    }
}



