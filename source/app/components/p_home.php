<?php

class dashboard_page extends page{

    public $name = 'dashboard';
    public $title = 'Dashboard';
    
    public function content() {
        echo "Hello world!";
        
    }
    
    public function header_content() {
    }
}
