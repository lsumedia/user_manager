<?php

class dashboard_page extends page{

    public $name = 'dashboard';
    public $title = 'Overview';
    
    public function content() {
       ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="./?p=sessions">Sessions</a>
            </div>
            <div class="panel-body">
                <?php
                    $sess_data = access_key::list_all_clean(20);
                    $s_list = new ajax_list($sess_data, "session_list");
                    $s_list->display();
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="./?p=users">Users</a>
            </div>
            <div class="panel-body">
                <?php
                $u_data = user::list_all();
                $u_list = new ajax_list($u_data, "u_list");
                $u_list->display(); 
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="./?p=groups">Groups</a>
            </div>
            <div class="panel-body">
                 <?php
                    $g_data = group::list_all_clean();
                    $g_list = new ajax_list($g_data, 'g_list');
                    $g_list->display();
                ?>
            </div>
        </div>
    </div>
</div>
<?php        
    }
    
    public function header_content() {
    }
}
