<?php

/* Users view */

class users_page extends page{

    public $name = 'users';
    public $title = 'Users';
    
    public function content() {

    $array = [];
    $array[] = ['Name' => 'Bob'];
    $array[] = ['Name' => 'Stephanie'];
    
    ?>
<div class="row">
    <div class="col-md-12">
        <p class="pull-left">All users<p>
        <button class="pull-right btn btn-default">New user</button>
    </div>
</div>
    <?php
    
    $list = new ajax_list($array,'userlist');
    $list->display();
    
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



