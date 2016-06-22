<?php

class page {
    
    public $name;
    public $title;
    
    public function content(){}
    
    public function header_content(){}
}

class page_loader{
    
    /* Class representing the page to pick */
    private $page;
    
    public function load_current_page(){
        
       $this->load_page_by_name($_GET['p']);
        
    }
    
    public function load_page_by_name($name){
        foreach(get_declared_classes() as $class){
            if(is_subclass_of($class, 'page')){
                $obj = new $class();
                if($obj->name == $name){
                    $this->page = $obj;
                }
            }
        }
    }

    function load_content(){
        try{
            $this->page->content();
        }catch(Exception $e){
            echo "Error loading page: $e";
        }
        //call_user_func(array($this->page, 'content'));
    }

    function load_header(){
        $this->page->header_content();
        //call_user_func(array($this->page, 'header_content'));
    }
}
