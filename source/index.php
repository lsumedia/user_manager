<?php

require('app/init.php');


$page_loader = new page_loader();

if(isset($_GET['p'])){
    $page_loader->load_current_page();
}else{
    $page_loader->load_page_by_name('dashboard');
}
$page_loader->load_content();
