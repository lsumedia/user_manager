<?php

/* Users view */

class users_page extends page{

    public $name = 'users';
    public $title = 'Users';
    
    public function content() {

        if(isset($_GET['id'])){
            
            $c_user = new user($_GET['id']);
            
            $list = new ajax_list($c_user->list_permissions(), 'perm_list');
            $list->display();
            
            
        }else{

            $list = new ajax_list(user::list_all(), 'user_list');
            $list->display();

            $test_user = new user('test');
            var_dump($test_user->all_permissions());
        }
    
    }
    
    public function header_content() {
    }
}



