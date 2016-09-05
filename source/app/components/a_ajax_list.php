<?php
/**
 * No idea why I called this ajax_list, it doesn't use ajax...
 */
class ajax_list{
    
    //Array of arrays containing key-value pairs with friendly names for both
    //Each entry must have same keys!
    public $objects = array();
    //Array of strings containing 
    public $headers = array();
    //DOM ID the table will have
    public $id;
    public $tags;
    public $title;
    
    public function __construct($objects, $id){
        $this->objects = $objects;
        $this->id = $id;
    }
    
    public function addObject($object){
        $this->objects[] = $object;
    }
    
    public function style($tags){
        if($tags){ $this->tags = $tags; }
    }
    
    public function title($text){
        //echo "<div class=\"listtitle\">$text</div>", PHP_EOL;
        $this->title = $text;
    }
    
    public function display(){
        echo "<!-- ajaxList $this->id -->", PHP_EOL;
        $listStyle = (count($this->objects) > 10)? 'longList' : 'shortList';
        echo "<div class='form listWrapper $listStyle'>", PHP_EOL;
        $data_id = $this->id . '_data';
        $body_id = $this->id . '_body';
        $search_id = $this->id . '_search';
        $page_number = $this->id . '_pagenumber';
        self::arrayToJson($data_id, $this->objects);
        
        $count = count($this->objects);
        echo "<div class=\"listtitle\">$this->title</div>", PHP_EOL;
        echo "<div class=\"row\">";
        if($count > 10){
            $back; $next;
            $numpages = floor(($count-1) / 10 ) + 1;
            $back_id = $this->id . '_back';
            $next_id = $this->id . '_next';
            echo "<div class=\"col-lg-9 col-sm-12\"><input onkeyup=\"list_search('$this->id','$data_id',this.value);\" placeholder='Search' type='text' id='$search_id' class='form-control' /></div>";
            $back = "<i onclick=\"list_change_page('$this->id','$data_id',0);\" id='$back_id' class='material-icons' style=\"color:#888;\">chevron_left</i>";
            $next = "<i onclick=\"list_change_page('$this->id','$data_id',1);\" id='$next_id' class='material-icons'>chevron_right</i>"; 
            echo "<div class=\"listnav col-lg-3 col-sm-12\"><p>Page <span id='$page_number'>1</span> of $numpages</p>$back$next</div>";
        }
        echo "</div>";
        echo "<table class=\"table table-striped\" id=\"$this->id\" $this->tags >",PHP_EOL;
        
        $first = $this->objects[0];
        foreach($first as $key => $value){
            if($key != "onclick" && $key != 'action'){
                $this->headers[] = $key;
            }
        }
        echo "<thead><tr>", \PHP_EOL;
        foreach($this->headers as $header){
            echo "<th>". $header . "</th>", \PHP_EOL;
        }
        echo "</tr></thead><tbody id='$body_id'>", \PHP_EOL;
        //JavaScript does this bit now
        echo "</tbody></table>";
        echo "<script>list_change_page('$this->id', '$data_id', 0);</script>", PHP_EOL;
        echo "</div>", PHP_EOL;
        echo "<!-- ajaxList ends -->", PHP_EOL;
    }
    
    public static function arrayToJson($id, $objects){
        echo "<script type='application/json' id='$id'>" . json_encode($objects) . "</script>", PHP_EOL;
    }
    
}

