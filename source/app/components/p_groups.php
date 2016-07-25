<?php


class group_page extends page{

    public $name = 'groups';
    public $title = 'Groups';
    
    public function content() {
        
        $group = new group(1);
        
        var_dump($group);
        
    }
    
    public function header_content() {
    }
}
