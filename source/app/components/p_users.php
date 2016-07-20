<?php

/* Users view */

class users_page extends page{

    public $name = 'users';
    public $title = 'Users';
    
    public function content() {
        global $db;
        
        if(isset($_GET['id'])){
            //Load user object
            
            ?>
            <!-- Breadcrumbs -->
            <ol class="breadcrumb">
              <li><a href="./?p=users">Users</a></li>
              <li><a href="javascript:void();">Edit <?= $_GET['id'] ?></a></li>
            </ol>
            <?php
            
            if(isset($_POST['username'])){
                $e_user = new user($_GET['id']);
                
                //If post request
                $fullname = $_POST['fullname'];
                $email = $_POST['email'];
                $dp_url = $_POST['dp_url'];
                $bio = $_POST['bio'];

                $password = $_POST['password'];

                //Update user query
                $query = "UPDATE " . prefix('user') . " SET fullname=?, email=?, dp_url=?, bio=? WHERE username=?";

                if($stmt = $db->prepare($query)){

                    $stmt->bind_param("sssss", $fullname, $email, $dp_url, $bio, $e_user->username);
                    $stmt->execute();
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
                    

                }else{
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Error saving changes</div>";
                }

            }
            
            $c_user = new user($_GET['id']);
            
            
?>
<!-- Edit user form -->
<form action="" method="POST">
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
        <label for="user-dp">Profile picture</label>
        <input type="url" class="form-control" id="user-dp" placeholder="Profile picture URL"  name="dp_url" value="<?= $c_user->dp_url ?>">
    </div>
    <div class="form-group">
        <label for="user-bio">Biography</label>
        <textarea class="form-control" name="bio" id="user-bio"><?= $c_user->bio ?></textarea>
    </div>
    <div class="form-group">
        <label for="user-pw">Reset password</label>
        <input type="password" class="form-control" id="user-pw" name="password" placeholder="Password reset">
    </div>
    <button type="submit" class="btn btn-success align-right">Save changes</button>
</form>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <h4>All user permissions</h4>
    </div>
</div>
<?php            
            $list = new ajax_list($c_user->list_permissions(), 'perm_list');
            $list->display();
            
            
        }else{

            $list = new ajax_list(user::list_all(), 'user_list');
            $list->display();
        }
    
    ?>

<!-- Modal -->
<div class="modal fade" id="new_user_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
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



