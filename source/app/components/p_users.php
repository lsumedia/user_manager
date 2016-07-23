<?php

/* Users view */

class users_page extends page{

    public $name = 'users';
    public $title = 'Users';
    
    public function content() {
        global $db;
        global $auth;
        
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
                
                $c_user = new user($_GET['id']);
            
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
                        <label for="user-bio">Biography</label>
                        <textarea class="form-control" name="bio" id="user-bio"><?= $c_user->bio ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="user-pw">Reset password</label>
                        <input type="password" class="form-control" id="user-pw" name="reset_password" placeholder="Password reset" autocomplete="off">
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <button type="submit" class="btn btn-success" onclick="document.getElementById('edit_user_form').submit();">Save changes</button>
                        <?php 
                            //Don't display button to delete user's own account
                            if($c_user->username != $auth->profile()['username']){
                        ?>
                            <button class="btn btn-danger" onclick="if(confirm('Delete user <?= $c_user->username ?>?')){ window.location.href='./?p=users&delete=<?= $c_user->username ?>';}">Delete user</button>
                            <?php }else{ ?>            
                        <button class="btn btn-default disabled">Delete user</button>
                            <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <h3>Group memberships</h3>
                    </div>
                </div>

                <!-- Permissions edit section -->
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <h3>Permissions</h3>
                    </div>
                </div>

                <!-- Individual permissions -->
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <h4>User individual permissions</h4>
                    </div>
                </div>
                <?php
                $i_list = new ajax_list($c_user->list_individual_permissions(), 'i_perm_list');
                $i_list->display();
                ?>

                <!-- All permissions -->
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <h4>All user/group permissions</h4>
                    </div>
                </div>
                <?php            
                $all_perm_list = new ajax_list($c_user->list_permissions(), 'all_perm_list');
                $all_perm_list->display();

                }catch(Exception $e){

                echo "<div class=\"alert alert-danger\" role=\"alert\">Username \"{$_GET['id']}\" not recognised</div>";
            }
        }else{
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
                
                
                $new_hash = process_password($new_password);
                
                $username_valid = !(preg_match('/\s/',$new_username)) && (strlen($new_username) > 3);
                $password_valid = (strlen($new_password) > 7);
                
                //Validation
                if($username_valid && $password_valid){

                    //Update user query
                    $query = "INSERT INTO " . prefix('user') . " (username,fullname,email,dp_url,bio,password) VALUES (?,?,?,?,?,?)";

                    if($stmt = $db->prepare($query)){

                        //Bind variables
                        $stmt->bind_param("ssssss", $new_username, $new_fullname, $new_email, $new_dp_url, $new_bio, $new_hash);

                        if($stmt->execute()){
                            echo "<div class=\"alert alert-success\" role=\"alert\">Added new user $new_username</div>";

                        }else{
                            echo "<div class=\"alert alert-danger\" role=\"alert\">Error adding new user</div>";

                        }

                        $stmt->close();
                    }
                }else{
                    if(!$username_valid){
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Username invalid or taken</div>";
                    }
                    if(!$password_valid){
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Password must be at least 8 characters long</div>";
                    }
                }
                
            }else if(isset($_GET['delete'])){
                
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
        }
    
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
                    <label for="user-bio">Biography</label>
                    <textarea class="form-control" name="bio" id="user-bio"><?= $c_user->bio ?></textarea>
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
    
    public function header_content() {
        if(isset($_GET['id'])){
            $this->title = "";
        }
    }
}



