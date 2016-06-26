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
        if($this->page == null){
            echo "No page object loaded!";
            return;
        }
        $this->page->content();
        //call_user_func(array($this->page, 'content'));
    }

    function load_header(){
        if($this->page == null){
            echo "No page object loaded!";
            return;
        }
        $this->page->header_content();
        //call_user_func(array($this->page, 'header_content'));
    }
    
    function current_page_name(){
        if($this->page == null){
            return "Error - Page not found";
        }
        return $this->page->title;
    }
}
