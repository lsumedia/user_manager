<?php

/* Users view */

class users_page extends page{

    public $name = 'users';
    public $title = 'Users';
    
    public function content() {

    $array = [];
    $array[] = ['Name' => 'Bob'];
    $array[] = ['Name' => 'Stephanie'];
    
    $list = new ajax_list($array,'userlist');
    $list->display();
    
    }
    
    public function header_content() {
    }
}



