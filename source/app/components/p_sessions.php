<?php

/* Users view */

class sessions_page extends page{

    public $name = 'sessions';
    public $title = 'Active sessions';
    
    public function content() {
        
        if(isset($_GET['kick'])){
            $kick_key = $_GET['kick'];
            
            if($kick_username = access_key::get_username($kick_key)){
                if(access_key::invalidate($kick_key)){
                    echo "<div class=\"alert alert-success\" role=\"alert\">Kicked user $kick_username</div>";
                }
            }
        }

        $list = new ajax_list(access_key::list_all_clean(), 'user_list');
        $list->display();

    }
    
    public function header_content() {
    }
}



