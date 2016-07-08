<?php

/* Users view */

class sessions_page extends page{

    public $name = 'sessions';
    public $title = 'Active sessions';
    
    public function content() {

            $list = new ajax_list(access_key::list_all_clean(), 'user_list');
            $list->display();

    }
    
    public function header_content() {
    }
}



