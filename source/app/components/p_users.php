<?php

/* Users view */

class users_page extends page{

    public $name = 'users';
    public $title = 'Users';
    
    public function content() {

        if(isset($_GET['id'])){
            
            $c_user = new user($_GET['id']);
            
?>
<div class="row">
    <div class="col-lg-12">
        <h3>Editing user</h3>
    </div>
</div>
<div>
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <input name="username" class="form-control disabled" value="<?= $c_user->username ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <input name="fullname" class="form-control" value="<?= $c_user->fullname ?>" />
        </div>
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
    }
}



