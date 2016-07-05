<?php

/* Users view */

class sessions_page extends page{

    public $name = 'sessions';
    public $title = 'Sessions';
    
    public function content() {

            $list = new ajax_list(user::list_all(), 'user_list');
            $list->display();

            $test_user = new user('test');
            var_dump($test_user->all_permissions());
    }
    
    public function header_content() {
    }
}



